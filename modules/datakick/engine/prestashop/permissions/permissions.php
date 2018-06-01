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

class PrestashopPermissions extends Permissions {
  private $tabPerms;

  public function __construct($userId, $roleId) {
    $userId = (int)$userId;
    $roleId = (int)$roleId;
    parent::__construct($userId, $roleId, $roleId === _PS_ADMIN_PROFILE_);
  }

  protected function load(Factory $factory) {
    parent::load($factory);
    if (! $this->isAdmin()) {
      $perms = \Profile::getProfileAccesses($this->getRoleId());
      foreach($perms as $perm) {
        $className = $perm['class_name'];
        if ($className) {
          $p = array(
            'view' => (bool)$perm['view'],
            'create' => (bool)$perm['add'],
            'edit' => (bool)$perm['edit'],
            'delete' => (bool)$perm['delete'],
          );
          $this->tabPerms[$className] = $p;
        }
      }
    }
  }

  public function datakickPermission($type) {
    if ($this->isAdmin())
      return true;
    if ($this->handlesPermission('datakick')) {
      return parent::datakickPermission($type);
    }
    $tab = 'AdminDatakickFull';
    return isset($this->tabPerms[$tab]) && $this->tabPerms[$tab][$type];
  }

  public function checkPermission(Collection $collection, $type) {
    if ($this->isAdmin())
      return true;
    if ($this->handlesPermission($collection)) {
      return parent::checkPermission($collection, $type);
    }
    if ($collection->hasPlatformField('psTab')) {
      return $this->tabPerm($collection->getPlatformField('psTab'), $type);
    }
    // if collection comes from third-party module than it's always accessible
    if ($collection->hasPlatformField('psModule')) {
      return true;
    }
    return parent::checkPermission($collection, $type);
  }

  private function tabPerm($tab, $type) {
    return $this->datakickPermission($type) && isset($this->tabPerms[$tab]) && $this->tabPerms[$tab][$type];
  }

  public function getTabPermissions() {
    return $this->tabPerms;
  }
}
