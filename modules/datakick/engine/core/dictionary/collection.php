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

class Collection {
  private $id;
  private $singular;
  private $name;
  private $display;
  private $role;
  private $category = 'common';
  private $priority;
  private $usageTable;
  private $parameters = array();
  private $listDefinition;

  private $key;
  private $tables = array();
  private $requiredTables = array();
  private $conditions = array();
  private $joinConditions = array();
  private $fields = array();
  private $expressions = array();
  private $links = array();
  private $restrictions = array();
  private $permissions = array();
  private $callbacks = array();
  private $platform = array();
  private $deleteTables = array();
  private $deleteConditions = array();

  private $supportsCreate = false;
  private $supportsDelete = false;
  private $canCreate = false;
  private $canEdit = false;
  private $canDelete = false;

  public function __construct($dictionary, $definition, $platformFields=array()) {
    $this->dictionary = $dictionary;
    $this->id = $definition['id'];
    $this->singular = $definition['singular'];
    $this->display = $definition['display'];
    $this->name = $definition['description'];
    if (isset($definition['role'])) {
      $this->role = $definition['role'];
    }
    if (isset($definition['usage'])) {
      $this->usageTable = $definition['usage'];
    }
    if (isset($definition['list'])) {
      $this->listDefinition = $definition['list'];
    }
    if (isset($definition['category'])) {
      $this->category = $definition['category'];
    }
    if (isset($definition['priority'])) {
      $this->priority = $definition['priority'];
    }
    if (isset($definition['create'])) {
      $this->supportsCreate = $definition['create'];
    }
    if (isset($definition['delete'])) {
      $del = $definition['delete'];
      if (is_array($del)) {
        $this->supportsDelete = !!$del['value'];
        if (isset($del['extraTables'])) {
          $this->deleteTables = $del['extraTables'];
        }
        if (isset($del['conditions'])) {
          $this->deleteConditions = $del['conditions'];
        }
      } else {
        $this->supportsDelete = !!$del;
      }
    }
    $this->key = $definition['key'];
    $this->parameters = $this->mapList($definition, 'parameters');
    $this->tables = $this->mapList($definition, 'tables');
    $this->conditions = $this->mapList($definition, 'conditions');
    $this->joinConditions = $this->mapList($definition, 'joinConditions');
    $this->fields = $this->mapList($definition, 'fields');
    $this->expressions = $this->mapList($definition, 'expressions');
    $this->links = $this->mapList($definition, 'links');
    $this->restrictions = $this->mapList($definition, 'restrictions');
    $this->permissions = $this->mapList($definition, 'permissions');
    if (isset($definition['callbacks'])) {
      $this->callbacks = $definition['callbacks'];
    }
    foreach ($platformFields as $field) {
      if (isset($definition[$field])) {
        $this->platform[$field] = $definition[$field];
      }
    }
  }

  public function getId() {
    return $this->id;
  }

  public function getSingularId() {
    return $this->singular;
  }

  public function getSingularName() {
    return ucwords(Utils::decamelize($this->singular, ' '));
  }

  public function getName() {
    return $this->name;
  }

  public function getCategory() {
    return $this->category;
  }

  public function getPriority() {
    return $this->priority;
  }

  public function getUsageTable() {
    return $this->usageTable;
  }

  public function hasRole() {
    return isset($this->role);
  }

  public function getRole() {
    return $this->role;
  }


  /**
   * Primary key
   */
  public function getKeys() {
    return $this->key;
  }

  public function getKeyFields() {
    return array_map(array($this, 'getField'), $this->key);
  }

  public function getFirstKeyField() {
    return $this->getKeyFields()[0];
  }

  /**
   * Display field
   */
  public function getDisplayField() {
    return $this->display;
  }

  public function hasListDefinition() {
    return isset($this->listDefinition);
  }

  public function getListDefinition() {
    return $this->listDefinition;
  }

  /**
   * Platform specific fields
   */
  public function hasPlatformField($id) {
    return isset($this->platform[$id]);
  }

  public function getPlatformField($id) {
    if ($this->hasPlatformField($id)) {
      return $this->platform[$id];
    }
    throw new UserError("Collection " . $this->getId() . ": platform specific field `$id` not found");
  }

  /**
   * Parameters
   */
  public function getParameters() {
    return $this->parameters;
  }

  /**
   * Tables
   */
  public function getTables() {
    return $this->tables;
  }

  public function getTable($key) {
    if ($this->hasTable($key)) {
      return $this->tables[$key];
    }
    throw new \Exception("Collection " . $this->getId() . ": table `$key` not found");
  }

  public function hasTable($key) {
    return isset($this->tables[$key]);
  }

  public function addTable($id, $def) {
    $this->tables[$id] = $this->map($def, $id, 'tables');
  }

  public function addTables($arr) {
    foreach ($arr as $id => $def) {
      $this->addTable($id, $def);
    }
  }

  public function getPrimaryTable() {
    foreach ($this->tables as $id => $def) {
      if (! isset($def['join'])) {
        return $id;
      }
    }
    throw new \Exception("Invariant: collection {$this->getId()} doesn't have primary table");
  }

  public function getCoreTables() {
    $primary = array();
    foreach ($this->tables as $id => $def) {
      if (! isset($def['join'])) {
        $primary[] = $id;
      } else if (isset($def['primary'])) {
        $primary[] = $id;
      }
    }
    return $primary;
  }

  /**
   * Conditions
   */
  public function getConditions() {
    return $this->conditions;
  }

  public function hasConditions() {
    return count($this->conditions) > 0;
  }

  public function addCondition($def) {
    $this->conditions[] = $this->map($def, null, 'conditions');
  }

  /**
   * joinConditions
   */
  public function hasJoinConditions() {
    return count($this->joinConditions) > 0;
  }

  public function getJoinConditions() {
    return $this->joinConditions;
  }

  /**
   * Fields
   */
  public function getFields() {
    return $this->fields;
  }

  public function getField($key) {
    if ($this->hasField($key)) {
      return $this->fields[$key];
    }
    throw new UserError("Collection " . $this->getId() . ": field `$key` not found");
  }

  public function hasField($key) {
    return isset($this->fields[$key]);
  }

  public function addField($id, $def) {
    $this->fields[$id] = $this->mapField($def, $id);
  }

  public function addFields($arr) {
    foreach ($arr as $id => $def) {
      $this->addField($id, $def);
    }
  }

  public function removeField($key) {
    unset($this->fields[$key]);
  }

  /**
   * Expressions
   */
  public function getExpressions() {
    return $this->expressions;
  }


  /**
   * Links
   */
  public function getLinks() {
    return $this->links;
  }

  public function hasLink($key) {
    return isset($this->links[$key]);
  }

  public function getLink($key) {
    if ($this->hasLink($key)) {
      return $this->links[$key];
    }
    throw new UserError("Collection " . $this->getId() . ": link `$key` not found");
  }

  public function addLink($key, $def) {
    $this->links[$key] = $this->map($def, $key, 'links');
  }

  public function removeLink($key) {
    unset($this->links[$key]);
  }

  /**
   * Restrictions
   */
  public function hasRestrictions() {
    return count($this->restrictions) > 0;
  }

  public function isRestricted($type) {
    return isset($this->restrictions[$type]);
  }

  public function getRestrictions() {
    return $this->restrictions;
  }

  /**
   * Permissions
   */
  public function getFixedPermissions() {
    return $this->permissions;
  }

  public function hasFixedPermission($type) {
    return isset($this->permissions[$type]);
  }

  public function getFixedPermission($type) {
    return $this->permissions[$type];
  }

  public function canCreate() {
    if ($this->supportsCreate) {
      return $this->canCreate;
    }
    return false;
  }

  public function setCanCreate($can) {
    $this->canCreate = $can;
  }

  public function canDelete() {
    if ($this->supportsDelete) {
      return $this->supportsDelete;
    }
    return false;
  }

  public function setCanDelete($can) {
    $this->canDelete = $can;
  }

  public function canEdit() {
    return $this->canEdit;
  }

  public function setCanEdit($can) {
    $this->canEdit = $can;
  }


  /**
   * Callbacks
   */
  public function hasCallback($name) {
    return isset($this->callbacks[$name]);
  }

  public function getCallback($name) {
    return $this->callbacks[$name];
  }

  public function triggerCallback($name, $params) {
    if ($this->hasCallback($name)) {
      $cb = $this->getCallback($name);
      return call_user_func_array($cb, $params);
    }
    return false;
  }

  public function getDeleteTables() {
    return $this->deleteTables;
  }

  public function addDeleteTable($table, $fkeys) {
    $this->deleteTables[] = array(
      'table' => $table,
      'fkeys' => $fkeys
    );
  }

  public function getDeleteConditions() {
    return $this->deleteConditions;
  }

  public function extend($type, $key, $value) {
    if (in_array($type, array('fields', 'links', 'tables', 'expressions', 'parameters'))) {
      $this->{$type}[$key] = $this->map($value, $key, $type);
    } else {
      throw new \Exception("Invalid extension $type - $key");
    }
  }


  private function mapList($definition, $type) {
    if (isset($definition[$type])) {
      $callable = $this->getMapper($type);
      $list = $definition[$type];
      if ($callable) {
        foreach ($list as $key => &$value) {
          $value = call_user_func_array($callable, array($value, $key));
        }
      }
      return $list;
    }
    return array();
  }

  private function map($def, $id, $type) {
    $mapper = $this->getMapper($type);
    if ($mapper) {
      return $mapper($def, $id);
    }
    return $def;
  }

  private function getMapper($type) {
    switch ($type) {
      case 'fields': return array($this, 'mapField');
      case 'links': return array($this, 'mapLink');
    }
  }

  private function mapField($def, $id) {
    return new Field($id, $def, $this);
  }

  private function mapLink($def, $id) {
    return new Link($id, $def, $this, $this->dictionary);
  }

}
