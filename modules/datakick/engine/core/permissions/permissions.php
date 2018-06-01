<?php
/**
* NOTICE OF LICENSE
*
*   This file is property of Petr Hucik. You may NOT redistribute the code in any way
*   See license.txt for the complete license agreement
*
* @author    Petr Hucik
* @website   https://www.getdatakick.com
* @copyright Petr Hucik <petr@getdatakick.com>
* @license   see license.txt
* @version   2.1.3
*/
namespace Datakick;

class Permissions {
  private $factory;
  private $userId;
  private $roleId;
  private $isAdmin;
  private $perms;
  private $rw = false;

  public function __construct($userId, $roleId, $isAdmin) {
    $this->userId = $userId;
    $this->roleId = $roleId;
    $this->isAdmin = $isAdmin;
    $this->perms = array();
  }

  public function setFactory($factory) {
    $this->factory = $factory;
    $this->rw = !$factory->trialEnded();
    $this->load($factory);
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getRoleId() {
    return $this->roleId;
  }

  public function isAdmin() {
    return $this->isAdmin;
  }

  public function checkAdmin() {
    if (! $this->isAdmin()) {
      throw new PermissionError('admin');
    }
  }

  public function checkView($collection) {
    $col = $this->getCollection($collection);
    if (! $this->canView($col)) {
      throw new PermissionError('view', $col->getName());
    }
  }

  public function checkCreate($collection) {
    $col = $this->getCollection($collection);
    if (! $this->canCreate($col)) {
      throw new PermissionError('create', $col->getName());
    }
  }

  public function checkEdit($collection) {
    $col = $this->getCollection($collection);
    if (! $this->canEdit($col)) {
      throw new PermissionError('edit', $col->getName());
    }
  }

  public function checkDelete($collection) {
    $col = $this->getCollection($collection);
    if (! $this->canDelete($col)) {
      throw new PermissionError('delete', $col->getName());
    }
  }

  private function getCollection($collection) {
    if (! $this->factory) {
      throw new \Exception("Permissions: Factory not set yet");
    }
    return $this->factory->getDictionary()->getCollection($collection);
  }

  public function canView(Collection $collection) {
    return $this->checkPerm($collection, 'view');
  }

  public function canCreate(Collection $collection) {
    return $this->rw && $this->canView($collection) && $this->checkPerm($collection, 'create');
  }

  public function canEdit(Collection $collection) {
    return $this->rw && $this->canView($collection) &&$this->checkPerm($collection, 'edit');
  }

  public function canDelete(Collection $collection) {
    return $this->rw && $this->canView($collection) &&$this->checkPerm($collection, 'delete');
  }

  private function checkPerm(Collection $collection, $type) {
    // 1. admins can do everything
    if ($this->isAdmin()) {
      return true;
    }

    // 2. permission in collections have precedence
    if ($collection->hasFixedPermission($type)) {
      return $collection->getFixedPermission($type);
    }

    // 3. system collections
    if ($collection->getCategory() === 'system') {
      if ($this->handlesPermission($collection)) {
        return $this->checkPermission($collection, $type);
      }
      return $this->datakickPermission($type);
    }

    // 4. platform permissions
    return $this->checkPermission($collection, $type);
  }

  /**
   * returns true, if datakick module handles this module on it's own
   */
  public final function handlesPermission($collection) {
    $id = is_string($collection) ? $collection : $collection->getId();
    return isset($this->perms[$id]);
  }

  public function hasPermission(Collection $collection, $type) {
    if ($this->isAdmin())
      return true;
    $perm = $collection->getId();
    return isset($this->perms[$perm][$type]) && $this->perms[$perm][$type];
  }

  public function datakickPermission($type) {
    return $this->isAdmin() || $this->hasPermission('datakick', $type);
  }

  public function checkPermission(Collection $collection, $type) {
    return $this->datakickPermission($type) && $this->hasPermission($collection, $type);
  }

  protected function load(Factory $factory) {
    if (! $this->isAdmin()) {
      $conn = $factory->getConnection();
      $userPermissions = $factory->getServiceTable('user-permissions');
      $rolePermissions = $factory->getServiceTable('role-permissions');
      $roleId = $this->getRoleId();
      $userId = $this->getUserId();
      $sql = "SELECT 0 AS prio, `permission` AS p, `view` AS v, `edit` AS e, `create` as c, `delete` AS d FROM $rolePermissions WHERE role_id = $roleId";
      $sql .= "\nUNION";
      $sql .= "\nSELECT 1, `permission`, `view`, `edit`, `create`, `delete` FROM $userPermissions WHERE user_id = $userId";
      $sql .= "\nORDER BY p, prio";
      $res = $conn->query($sql);
      while ($row = $res->fetch()) {
        $this->perms[$row['p']] = array(
          'view' => (int)$row['v'],
          'create' => (int)$row['c'],
          'edit' => (int)$row['e'],
          'delete' => (int)$row['d']
        );
      }
    }
  }

  public function getPermissions() {
    $dict = $this->factory->getDictionary();
    $ret = array(
      'datakick' => array(
        'handles' => $this->handlesPermission('datakick'),
        'view' => $this->datakickPermission('view'),
        'create' => $this->datakickPermission('create'),
        'edit' => $this->datakickPermission('edit'),
        'delete' => $this->datakickPermission('delete'),
      )
    );
    foreach ($dict->getCollections() as $col) {
      $id = $col->getId();
      $ret[$id] = array(
        'handles' => $this->handlesPermission($col),
        'view' => $this->canView($col),
        'create' => $this->canCreate($col),
        'edit' => $this->canEdit($col),
        'delete' => $this->canDelete($col)
      );
      $fixedPermissions = $col->getFixedPermissions();
      if ($fixedPermissions) {
        $ret[$id]['fixed'] = array_keys($fixedPermissions);
      }
    }
    return $ret;
  }

}
