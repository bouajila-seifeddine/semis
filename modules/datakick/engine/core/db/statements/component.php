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

class Component extends DBBase implements Statement {
  private $collection;
  private $fields = array();
  private $requiredTables = array();
  private $conditions = array();
  private $limit = null;
  private $distinct = false;

  public function __construct(Escape $escape, Collection $collection) {
    parent::__construct($escape);
    $this->collection = $collection;
  }

  public function isValid() {
    return !empty($this->fields);
  }

  public function execute(Factory $factory, Context $context) {
    $tempTables = $factory->getTempTables();
    $temp = array();
    foreach($this->getTempTables() as $name) {
      $tempTable = $tempTables->get($name);
      $tempTable->populate($this, $context);
      array_push($temp, $tempTable);
    }
    $success = false;
    $exception = null;
    try {
      $success = $factory->getConnection()->query($this->getSQL($context));
    } catch (\Exception $e) {
      $exception = $e;
    }
    foreach ($temp as $tempTable) {
      $tempTable->destroy($this, $context);
    }
    if ($exception) {
      throw $exception;
    }
    return $success;
  }

  public function getCollectionByAlias($alias) {
    return $this->collection->getId();
  }

  public function exposeComponentField($colAlias, $field) {
    return $this->exposeField($field);
  }

  public function getSQLFields(Context $context, $fields, $prefix) {
    $ret = array();
    foreach($fields as $key=>$def) {
      $expression = is_array($def) ? $def['sql'] : $def->getSQL();
      if (is_array($expression)) {
        foreach($this->parametrizeArray($context, $expression) as $k => $e) {
          $alias = $this->getSQLAlias($key.'_'.$k, true);
          $ret[] = "$prefix$e AS $alias";
        }
      } else {
        $e = $this->parametrizeExpression($context, $expression);
        $alias = $this->getSQLAlias($key, true);
        $ret[] = "$prefix$e AS $alias";
      }
    };

    return join(",\n", $ret);
  }

  public function setLimit($limit, $offset=null) {
    $limit = (int)($limit);
    $offset = $offset ? (int)($offset) : 0;
    $this->limit = "LIMIT $limit OFFSET $offset";
  }

  public function setDistinct($distinct) {
    $this->distinct = $distinct;
  }

  private function getTempTables() {
    $tempTables = array();
    $fields = $this->fields;
    $tables = $this->collection->getTables();
    $requiredTables = $this->collectRequiredTables($fields, $tables);
    foreach ($tables as $alias => $table) {
      if (isset($table['temporary']) && in_array($alias, $requiredTables)) {
        array_push($tempTables, $table['temporary']);
      }
    }
    return array_unique($tempTables);
  }

  public function getSQL(Context $context) {
    $fields = $this->fields;
    $tables = $this->collection->getTables();
    $requiredTables = array_unique(array_merge($this->requiredTables, $this->collectRequiredTables($fields, $tables)));
    $distinct = $this->distinct ? " DISTINCT" : "";
    $ret = "SELECT$distinct";
    $ret .= $this->getSQLFields($context, $fields, " ");
    $ret .= " FROM";
    $ret .= $this->getSQLTables($context, $tables, $requiredTables);
    if ($this->conditions) {
      $conditions = $this->parametrizeArray($context, $this->conditions);
      $conditions = $this->processConditions($conditions);
      if (count($conditions) > 0) {
        $ret .= " WHERE ";
        $ret .= join(" AND ", $conditions);
      }
    }
    return $ret;
  }


  public function setConditions($conditions) {
    if ($conditions) {
      list ($ret, $req) = self::parametrizeFields($conditions, $this->collection);
      $this->requiredTables = $req;
      $this->conditions = $ret;
    }
  }

  public function addCondition($cond, $requiredTables=null) {
    $this->addConditionRaw($cond);
    if ($requiredTables) {
      $this->requiredTables = array_unique(array_merge($this->requiredTables, $requiredTables));
    }
  }

  // don't use this one unless you know 100% that condition is based on tables already in component
  public function addConditionRaw($cond) {
    array_push($this->conditions, $cond);
  }

  public function exposeField($field) {
    if (! isset($this->fields[$field])) {
      $fld = $this->collection->getField($field);
      $this->fields[$field] = $fld;
    }
    $fld = $this->fields[$field];
    $base = $this->getSQLAlias($field);
    $sql = $fld->getSQL();
    if (is_array($sql)) {
      $ret = array();
      foreach($sql as $key => $f) {
        $ret[$key] = $base . '_' . $key;
      }
      return $ret;
    }
    return $base;
  }

  public function exposePrimaryFields() {
    $ret = array();
    foreach ($this->collection->getKeys() as $key) {
      $ret[$key] = $this->exposeField($key);
    }
    return $ret;
  }

  public function hasLimit() {
    return !!$this->limit;
  }

  public static function parametrizeFields($input, Collection $collection) {
    $ret = array();
    $require = array();
    foreach ($input as $sql) {
      list ($sql, $req) = self::parametrizeField($sql, $collection);
      $ret[] = $sql;
      $require = array_merge($require, $req);
    }
    return array($ret, array_unique($require));
  }

  public static function parametrizeField($entry, Collection $collection) {
    $matches = array();
    $pattern = '/<field:([a-zA-Z0-9-_]+)>/';
    preg_match_all($pattern, $entry, $matches, PREG_PATTERN_ORDER);
    $orig = $matches[0];
    $names = $matches[1];
    $require = array();
    for ($i = 0; $i<count($orig); $i++) {
      $field = $orig[$i];
      $name = $names[$i];
      $fld = $collection->getField($name);
      $sql = $fld->getSQL();
      $require = array_merge($require, $fld->getRequiredTables());
      $entry = str_replace($field, $sql, $entry);
    }
    return array($entry, $require);
  }
}
