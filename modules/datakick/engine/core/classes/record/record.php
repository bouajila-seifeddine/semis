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

class Record {

  const ALL_FIELDS = '*';
  const PRIMARY_KEY = 'PKS';

  public function __construct($type, Context $context, $factory) {
    $this->type = $type;
    $this->factory = $factory;
    $this->context = $context;
  }

  public function load($keys, $fields=array(), Array $association=array()) {
    if ($keys && !is_array($keys)) {
      $keys = array($keys);
    }
    return $this->loadBy($this->getKeyCondition($keys), $fields, $association);
  }

  public function loadFirst($fields=array(), Array $association=array(), $throw=false) {
    return $this->loadBy(array(), $fields, $association, $throw);
  }

  public function delete($keys) {
    if ($keys && !is_array($keys)) {
      $keys = array($keys);
    }
    $keysDef = $this->getKeyDefinition(true);
    $alias = null;
    foreach ($keysDef as $key) {
      if (count($key->getRequiredTables()) != 1) {
        throw new \Exception("Can't delete record: multialias key field");
      }
      $keyTable = $key->getRequiredTables()[0];
      if ($alias) {
        if ($alias != $keyTable) {
          throw new \Exception("Can't delete record: multialias keys");
        }
      } else {
        $alias = $keyTable;
      }
    }
    if (! $alias) {
      throw new \Exception("Can't delete record: table not found");
    }

    $def = $this->getTable($alias);
    $connection = $this->factory->getConnection();
    return $connection->delete($def['table'], $this->getKeyCondition($keys));
  }

  public function existsKey($keys) {
    if (!is_array($keys)) {
      $keys = array($keys);
    }
    return $this->exists($this->getKeyCondition($keys));
  }

  public function exists(Array $conditions) {
    $ret = $this->getQuery($conditions, $this->getKeyDefinition(false));
    $query = $ret['query'];
    $query->setLimit(1);
    $res = $query->execute($this->factory, $this->context);
    $ret = $res->fetch();
    return !!$ret;
  }

  public function loadBy(Array $conditions, $fields=array(), Array $association=array(), $throw=true) {
    $type = $this->type;
    $factory = $this->factory;
    $dictionary = $factory->getDictionary();
    $collection = $dictionary->getCollection($type);
    if ($fields === self::ALL_FIELDS) {
      $fields = array_keys($collection->getFields());
    } else if ($fields === self::PRIMARY_KEY) {
      $fields = $collection->getKeys();
    }

    $ret = $this->getQuery($conditions, $fields);
    $query = $ret['query'];
    $extractors = $ret['extractors'];

    $assoc = array();
    foreach ($association as $a=>$fields) {
      $link = $collection->getLink($a);
      $assoc[$a] = array(
        'cond' => $this->exposeKeysToLink($link, $query, $dictionary),
        'collection' => $link->getTargetId(),
        'fields' => $fields
      );
    }
    $query->setLimit(1);

    $res = $query->execute($this->factory, $this->context);
    $row = $res->fetch();
    if (! $row) {
      if ($throw) {
        $name = $this->getCollection()->getSingularName();
        $conds = $this->printCondition($conditions);
        throw new UserError("$name $conds not found");
      }
      return false;
    }
    $values = array();

    foreach ($extractors as $fld => $extractor) {
      $values[$fld] = $extractor->getValue($row);
    }

    // retrieve associations
    foreach ($assoc as $name => $def) {
      $cond = array_map(function($extractor) use ($row) {
        return $extractor->getValue($row);
      }, $def['cond']);
      $values[$name] = $this->getAssociations($def['collection'], $cond, $def['fields']);
    }
    return $values;
  }

  public function loadRecords($conditions, $fields) {
    $ret = $this->getQuery($conditions, $fields);
    $query = $ret['query'];
    $extractors = $ret['extractors'];
    $res = $query->execute($this->factory, $this->context);
    $ret = array();
    while ($row = $res->fetch()) {
      $values = array();
      foreach ($extractors as $fld => $extractor) {
        $values[$fld] = $extractor->getValue($row);
      }
      array_push($ret, $values);
    }
    return $ret;
  }

  private function printCondition($cond) {
    $col = $this->getCollection();
    if (count($cond) == 1) {
      $keys = array_keys($cond);
      $pkeys = $col->getKeys();
      if ($keys == $pkeys) {
        $key = $keys[0];
        $value = $cond[$key];
        return "with $key $value";
      }
    }
    $ret = array();
    foreach($cond as $key=>$value) {
      $ret[] = "$key=$value";
    }
    $ret = implode($ret, ', ');
    return "[$ret]";
  }

  private function getAssociations($type, $conditions, $fields) {
    $assocRecord = $this->factory->getRecord($type);
    return $assocRecord->loadRecords($conditions, $fields);
  }

  private function getQuery($conditions, $fields) {
    $type = $this->type;
    $factory = $this->factory;
    $dictionary = $factory->getDictionary();

    $query = $factory->getQuery();

    if (count($fields) === 0) {
      $fields = $this->getKeyDefinition();
      array_push($fields, $dictionary->getDisplayField($type));
    }

    $extractors = array();
    $query->exposeCollection($type, 'rec');
    $query->exposeKeyFields('rec');
    foreach($fields as $fld) {
      $fieldType = $dictionary->getFieldType($type, $fld);
      $alias = $query->exposeField('rec', $fld);
      if (Types::isCurrency($fieldType)) {
        $extractors[$fld] = new AliasExtractor(array(
          'value' => $alias.'_value',
          'currency' => $alias.'_currency'
        ), $fieldType);
      } else {
        $extractors[$fld] = new AliasExtractor($alias, $fieldType);
      }
    }

    foreach ($conditions as $field => $value) {
      $def = $dictionary->getField($type, $field);
      $value = $query->encodeLiteral($value, $def->getType());
      $query->exposeComponentField('rec', $field);
      $query->addComponentCondition('rec', $def->getSql() . " = $value");
    }

    return array(
      'query' => $query,
      'extractors' => $extractors
    );
  }

  private function getKeyDefinition($full=false) {
    $collection = $this->getCollection();
    return $full ? $collection->getKeyFields() : $collection->getKeys();
  }

  private function getField($id) {
    return $this->factory->getDictionary()->getField($this->type, $id);
  }

  private function getTable($alias) {
    return $this->factory->getDictionary()->getTable($this->type, $alias);
  }

  private function getKeyCondition($keys) {
    $keysDef = $this->getKeyDefinition();
    if (count($keys) != count($keysDef)) {
      throw new \Exception('Invalid keys');
    }

    $conditions = array();
    for ($i=0; $i<count($keys); $i++) {
      $value = $keys[$i];
      $name = $keysDef[$i];
      $conditions[$name] = $value;
    }

    return $conditions;
  }

  private function exposeKeysToLink($link, $query, $dictionary) {
    $assocKeys = array();
    $dictionary = $this->factory->getDictionary();
    $sourceFields = $link->getSourceFields();
    $targetFields = $link->getTargetFields();
    for ($i=0; $i<count($sourceFields); $i++) {
      $key = $sourceFields[$i];
      $field = $dictionary->getField($this->type, $key);
      $alias = $query->exposeField('rec', $key);
      $targetField = $targetFields[$i];
      $assocKeys[$targetField] = new AliasExtractor($alias, $field->getType());
    }
    return $assocKeys;
  }

  private function getCollection() {
    $factory = $this->factory;
    $dictionary = $factory->getDictionary();
    return $dictionary->getCollection($this->type);
  }
}
