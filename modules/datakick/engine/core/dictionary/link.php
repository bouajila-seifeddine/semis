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

class Link {
  private $dictionary;
  private $id;
  private $type;
  private $joinType;
  private $description;
  private $source;
  private $target;
  private $conditions;
  private $create;
  private $delete;
  private $joins = array();
  private $generateReverse;
  private $callbacks = array();

  // habtm
  private $joinTable;
  private $joinFields;
  private $joinConditions;

  public function __construct($id, $definition, $source, Dictionary $dictionary) {
    $this->dictionary = $dictionary;
    $this->id = $id;
    $this->description = $definition['description'];
    $this->source = $source;

    $this->type = $this->getProperty($definition, 'type');
    $this->target = $this->getProperty($definition, 'collection');
    $this->create = $this->getProperty($definition, 'create', false);
    $this->delete = $this->getProperty($definition, 'delete', false);
    $this->joinType = $this->getProperty($definition, 'joinType', self::getDefaultJoinType($this->type));
    $this->conditions = $this->getProperty($definition, 'conditions', false);
    if (isset($definition['joins'])) {
      $this->joins = $definition['joins'];
    } else {
      $this->joins[] = array(
        'sourceFields' => $this->getProperty($definition, 'sourceFields'),
        'targetFields' => $this->getProperty($definition, 'targetFields')
      );
    }
    if ($this->isHABTM()) {
      $this->joinTable = $this->getProperty($definition, 'joinTable');
      $this->joinFields = $this->getProperty($definition, 'joinFields');
      $this->joinConditions = $this->getProperty($definition, 'joinConditions', false);
    }
    $this->generateReverse = $this->getProperty($definition, 'generateReverse', false);
    if (isset($definition['callbacks'])) {
      $this->callbacks = $definition['callbacks'];
    }
  }

  public function getId() {
    return $this->id;
  }

  public function getType() {
    return $this->type;
  }

  public function isHABTM() {
    return $this->type == 'HABTM';
  }

  public function getTargetId() {
    $col = $this->target;
    if (is_array($col) && isset($col['role'])) {
      $id = $this->dictionary->getCollectionIdWithRole($col['role']);
      if ($id) {
        $this->target = $id;
      }
    }
    return $this->target;
  }

  public function getTarget() {
    return $this->dictionary->getCollection($this->getTargetId());
  }

  public function getSourceId() {
    return $this->source ? $this->source->getId() : 'adhoc';
  }

  public function getSource() {
    return $this->source;
  }

  public function getName() {
    return $this->description;
  }

  public function getJoins() {
    return $this->joins;
  }

  public function getJoinCount() {
    return count($this->joins);
  }

  public function hasConditions() {
    return $this->conditions !== false;
  }

  public function getConditions() {
    return $this->hasConditions() ? $this->conditions : array();
  }

  public function canCreate() {
    return $this->create !== false;
  }

  public function canDelete() {
    return $this->delete === true;
  }

  public function canDissoc() {
    return $this->delete === 'dissoc';
  }

  public function getDefaultCreateValues() {
    if ($this->canCreate()) {
      if (is_array($this->create)) {
        return $this->create;
      }
    }
    return array();
  }

  public function hasExtraField($field) {
    return $this->isHABTM() && isset($this->joinFields['extra'][$field]);
  }

  public function getExtraField($field) {
    return new JoinField($field, $this->joinFields['extra'][$field]);
  }

  public function getReverseJoins() {
    $reverse = array();
    foreach ($this->joins as $join) {
      $reverse[] = array(
        'sourceFields' => $join['targetFields'],
        'targetFields' => $join['sourceFields']
      );
    }
    return $reverse;
  }

  public function getTargetFields($joinId = 0) {
    if (count($this->joins) < $joinId+1) {
      throw new UserError('Target fields not found');
    }
    return $this->joins[$joinId]['targetFields'];
  }

  public function getSourceFields($joinId = 0) {
    if (count($this->joins) < $joinId+1) {
      throw new UserError('Source fields not found');
    }
    return $this->joins[$joinId]['sourceFields'];
  }

  public function getJoinTable() {
    return $this->joinTable;
  }

  public function hasJoinConditions() {
    return !!$this->joinConditions;
  }

  public function getJoinConditions() {
    return $this->hasJoinConditions() ? $this->joinConditions : array();
  }

  public function getJoinTargetFields() {
    return $this->joinFields['targetFields'];
  }

  public function getJoinSourceFields($joinId = 0) {
    return $this->joinFields['sourceFields'];
  }

  public function shouldGenerateReverse() {
    return !!$this->generateReverse;
  }

  public function getReverseDefinition() {
    return $this->generateReverse;
  }

  public function hasCallback($name) {
    return isset($this->callbacks[$name]);
  }

  public function getCallback($name) {
    if (isset($this->callbacks[$name])) {
      return $this->callbacks[$name];
    }
    throw new \Exception("Callback $name not found in link " . $this->getFullId());
  }

  public function triggerCallback($name, $params) {
    if ($this->hasCallback($name)) {
      $cb = $this->getCallback($name);
      return call_user_func_array($cb, $params);
    }
    return false;
  }

  public function toJS() {
    $ret = array(
      'collection' => $this->getTargetId(),
      'type' => $this->type,
      'description' => $this->description,
      'joinType' => $this->joinType,
      'canCreate' => $this->canCreate(),
      'sourceFields' => $this->getSourceFields(),
      'targetFields' => $this->getTargetFields()
    );
    return $ret;
  }

  public function getFullId() {
    return $this->getSourceId(). ':' . $this->getId();
  }

  private function getProperty($obj, $property, $default=null) {
    if (! array_key_exists($property, $obj)) {
      if (is_null($default)) {
        throw new UserError("Link {$this->getFullId()}: missing $property: " . print_r($obj, true));
      } else {
        return $default;
      }
    }
    return $obj[$property];
  }

  private static function getDefaultJoinType($type) {
    if ($type === 'BELONGS_TO') {
      return 'INNER';
    }
    return 'LEFT';
  }

}
