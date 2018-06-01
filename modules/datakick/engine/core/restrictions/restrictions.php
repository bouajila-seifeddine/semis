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
require_once(dirname(__FILE__).'/types/restriction-type.php');
require_once(dirname(__FILE__).'/types/user-restriction-type.php');
require_once(dirname(__FILE__).'/restriction.php');
require_once(dirname(__FILE__).'/restrictions/allow.php');
require_once(dirname(__FILE__).'/restrictions/deny.php');
require_once(dirname(__FILE__).'/restrictions/identity.php');
require_once(dirname(__FILE__).'/restrictions/equals.php');
require_once(dirname(__FILE__).'/restrictions/or.php');
require_once(dirname(__FILE__).'/restrictions/in.php');

class Restrictions {
  private $factory = null;
  private $restrictions = null;
  private $permissions;

  public function __construct(Permissions $permissions) {
    $this->permissions = $permissions;
  }

  public function get($type, $mode) {
    $this->load();
    if (isset($this->restrictions[$type][$mode])) {
      return $this->restrictions[$type][$mode];
    }
    return AllowRestriction::instance();
  }

  public function getCondition($type, Array $fields) {
    if ($this->validateFields($type, $fields)) {
      return $this->get($type, 'read')->getCondition($fields);
    } else {
      return Restriction::DENY;
    }
  }

  public function getWriteCondition($type, Array $fields) {
    if ($this->validateFields($type, $fields)) {
      return $this->get($type, 'write')->getCondition($fields);
    } else {
      return Restriction::DENY;
    }
  }

  public function validateFields($type, $fields) {
    $types = $this->factory->getRestrictionTypes();
    if (! $types->has($type)) {
      throw new \Exception("Restriction type not found: $type");
    }
    $t = $types->get($type);
    $requires = $t->fields();
    $provided = array_keys($fields);
    if ($requires != $provided) {
      return false;
    }
    return true;
  }

  private function load() {
    if (! is_null($this->restrictions))
      return;

    $this->restrictions = array();

    if ($this->permissions->isAdmin())
      return;

    $factory = $this->factory;
    $conn = $factory->getConnection();

    $settings = array();
    $userRestrictions = $factory->getServiceTable('user-restrictions');
    $roleRestrictions = $factory->getServiceTable('role-restrictions');
    $roleId = $this->permissions->getRoleId();
    $userId = $this->permissions->getUserId();

    $sql = "SELECT 0 AS prio, `restriction` AS id, `read` AS r, `write` as w FROM $roleRestrictions WHERE role_id = $roleId";
    $sql .= "\nUNION";
    $sql .= "\nSELECT 1, `restriction`, `read`, `write` FROM $userRestrictions WHERE user_id = $userId";
    $sql .= "\nORDER BY id, prio";
    $res = $conn->query($sql);
    while ($row = $res->fetch()) {
      $settings[$row['id']] = array(
        'read' => $row['r'],
        'write' => $row['w'],
      );
    }

    $types = $factory->getRestrictionTypes();
    foreach ($types->getKeys() as $type) {
      $restrictionFactory = $types->get($type);
      $readLevel = $this->getLevel($settings, $types, $type, 'read');
      $realRead = $this->getRealLevel($readLevel, $restrictionFactory->getDefaultReadLevel());
      $writeLevel = $this->getLevel($settings, $types, $type, 'write');
      $realWrite = $this->getRealLevel($writeLevel, $restrictionFactory->getDefaultWriteLevel());
      $this->restrictions[$type] = array(
        'readLevel' => $readLevel,
        'read' => $restrictionFactory->create($realRead),
        'writeLevel' => $writeLevel,
        'write' => $restrictionFactory->create($realWrite),
      );
    }
  }

  private function getLevel($settings, $types, $type, $mode) {
    $restrictionFactory = $types->get($type);
    if (isset($settings[$type][$mode])) {
      $level = $settings[$type][$mode];
      $levels = $restrictionFactory->getLevels();
      if (isset($levels[$level])) {
        return $level;
      }
    }
    return null;
  }

  private function getRealLevel($level, $defaultLevel) {
    return is_null($level) ? $defaultLevel : $level;
  }

  private function getDefaultLevel($restrictionFactory, $mode) {
    if ($mode == 'read')
      return $restrictionFactory->getDefaultReadLevel();
    return  $restrictionFactory->getDefaultWriteLevel();
  }

  public function setFactory($factory) {
    $this->factory = $factory;
  }

  public function getCollectionForRestriction($type) {
    $dictionary = $this->factory->getDictionary();
    $affected = array();
    foreach ($dictionary->getCollections() as $collection) {
      if ($collection->isRestricted($type))
        $affected[] = $collection->getId();
    }
    return $affected;
  }

  public function getRestrictions() {
    $this->load();
    $types = $this->factory->getRestrictionTypes();
    $retTypes = array();
    $retSettings = array();
    foreach ($this->restrictions as $key => $restriction) {
      $type = $types->get($key);
      $retTypes[$key] = array(
        'icon' => $type->getIcon(),
        'name' => $type->getName(),
        'description' => $type->getDescription(),
        'restricts' => $this->getCollectionForRestriction($key),
        'levels' => $type->getLevels(),
        'defaultRead' => $type->getDefaultReadLevel(),
        'defaultWrite' => $type->getDefaultWriteLevel(),
      );
      if ($restriction['readLevel'] || $restriction['writeLevel']) {
        $retSettings[$key] = array(
          'read' => $restriction['readLevel'],
          'write' => $restriction['writeLevel'],
        );
      }
    }
    return array(
      'types' => $retTypes,
      'levels' => $retSettings
    );
  }
}
