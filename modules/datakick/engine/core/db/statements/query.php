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

class Query extends DBBase implements Statement {
  private $counter = 1;
  private $dictionary;
  private $fields = array();
  private $sorts = array();
  private $conditions = array();
  private $components = array();
  private $limit = null;
  private $calcRows = false;
  private $distinct = false;
  private $opened = true;

  public function __construct(Escape $escape, Dictionary $dictionary) {
    parent::__construct($escape);
    $this->dictionary = $dictionary;
  }

  public function isValid() {
    return !empty($this->fields);
  }

  public function describe() {
    return array(
      'components' => $this->components,
      'fields' => $this->fields,
      'sorts' => $this->sorts
    );
  }

  public function execute(Factory $factory, Context $context) {
    $this->close();
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

  public function getSQL(Context $context) {
    $this->close();
    $ret = "SELECT";
    if ($this->limit && $this->calcRows) {
      $ret .= " SQL_CALC_FOUND_ROWS ";
    }
    $ret .= ($this->distinct) ? " DISTINCT\n" : "\n";
    $ret .= $this->getSQLFields($context, $this->fields, "  ");
    $cnt = count($this->components);
    if ($cnt > 0) {
      $ret .= "\nFROM\n";
      $first = true;
      foreach ($this->components as $component) {
        if (! $first) {
          $joinType = isset($component['joinType']) ? $component['joinType'] : 'INNER';
          $ret .= " $joinType JOIN\n";
        }
        $ret .= $this->getSQLComponent($context, $component);
        $first = false;
      }
    }
    $this->conditions = $this->processConditions($this->conditions);
    if (count($this->conditions) > 0) {
      $ret .= "\nWHERE ";
      $ret .= join("\n  AND ", $this->conditions);
    }
    if (count($this->sorts) > 0) {
      $ret .= "\nORDER BY ";
      $ret .= join(", ", $this->sorts);
    }
    if ($this->limit)  {
      $ret .= "\n" . $this->limit;
    }
    return $ret;
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

  public function addSort($field, $asc=true) {
    $this->assertOpened();
    if (! isset($this->fields[$field])) {
      throw new \Exception("Can't add sort: field $field not found");
    }
    $dir = $asc ? '' : ' DESC';
    array_push($this->sorts, "${field}${dir}");
  }

  private function assertOpened() {
    if (! $this->opened) {
      throw new \Exception("Can't modify closed query");
    }
  }

  private function close() {
    if ($this->opened) {
      foreach ($this->components as $tAlias => &$comp) {
        if (isset($comp['link'])) {
          $def = $comp['link'];
          $link = $this->getLink($def);
          $sAlias = isset($def['alias']) ? $def['alias'] : 'source';
          $joinType = isset($def['joinType']) ? $def['joinType'] : 'INNER';
          if ($link->isHABTM()) {
            $conditions = array();
            $sf = $link->getSourceFields();
            $tf = $link->getJoinSourceFields();
            for ($i=0; $i<count($sf); $i++) {
              $left = $this->exposeComponentField($sAlias, $sf[$i]);
              $right = $this->getSQLAlias($tAlias, true).'.habtm_'.$tf[$i];
              array_push($conditions, "$left = $right");
            }
            $comp['join'] = $conditions;
          } else {
            $comp['join'] = $this->getLinkConditions($link, $sAlias, $tAlias);
          }
          $comp['joinType'] = $joinType;
        }
      }
      $this->opened = false;
    }
  }


  private function joinConditions($cond, $name, $alias) {
    $matches = array();
    $pattern = "/<$name:([a-zA-Z0-9-_]+)>/";
    preg_match_all($pattern, $cond, $matches, PREG_PATTERN_ORDER);
    $orig = $matches[0];
    $names = $matches[1];
    for ($i = 0; $i<count($orig); $i++) {
      $param = $orig[$i];
      $name = $names[$i];
      $fld = $this->exposeComponentField($alias, $name);
      $cond = str_replace($param, $fld, $cond);
    }
    return $cond;
  }

  private function joinConditionsAliasOnly($cond, $name, $alias) {
    $matches = array();
    $pattern = "/<$name:([a-zA-Z0-9-_]+)>/";
    preg_match_all($pattern, $cond, $matches, PREG_PATTERN_ORDER);
    $orig = $matches[0];
    $names = $matches[1];
    for ($i = 0; $i<count($orig); $i++) {
      $param = $orig[$i];
      $name = $names[$i];
      $cond = str_replace($param, "$alias.$name", $cond);
    }
    return $cond;
  }

  public function setLimit($limit, $offset=null) {
    $this->assertOpened();
    $limit = (int)($limit);
    $offset = $offset ? (int)($offset) : 0;
    $this->limit = "LIMIT $limit OFFSET $offset";
  }

  public function setDistinct($distinct) {
    $this->assertOpened();
    $this->distinct = $distinct;
  }

  private function getTempTables() {
    $tempTables = array();
    foreach ($this->components as $component) {
      $fields = $component['fields'];
      $tables = $component['tables'];
      $requiredTables = $this->collectRequiredTables($fields, $tables);
      foreach ($tables as $alias => $table) {
        if (isset($table['temporary']) && in_array($alias, $requiredTables)) {
          array_push($tempTables, $table['temporary']);
        }
      }
    }
    return array_unique($tempTables);
  }

  public function getSQLComponent(Context $context, $component, $addAlias=true) {
    $tables = $component['tables'];
    $alias = $component['alias'];
    $fields = $component['fields'];
    $requiredTables = array_unique(array_merge($component['require'], $this->collectRequiredTables($fields, $tables)));
    $ret = "  (\n";
    $ret .= "    SELECT\n";
    $ret .= $this->getSQLFields($context, $fields, "      ");
    $ret .= "\n    FROM";
    $ret .= $this->getSQLTables($context, $tables, $requiredTables, "\n      ");
    if (isset($component['conditions'])) {
      $conditions = $this->parametrizeArray($context, $component['conditions']);
      $conditions = $this->processConditions($conditions);
      if (count($conditions) > 0) {
        $ret .= "\n    WHERE ";
        $ret .= join("\n  AND ", $conditions);
      }
    }
    if ($addAlias) {
      $ret .= "\n  ) AS ".$this->getSQLAlias($alias, true);
      if (isset($component['join'])) {
        $conditions = $this->processConditions($component['join']);
        $ret .= " ON (" . join(" AND ", $conditions) . ") ";
      }
    } else {
      $ret .= "\n )";
    }
    return $ret;
  }

  public function getLink($link) {
    if (isset($link['using'])) {
      $alias = $link['alias'];
      if (!isset($this->components[$alias])) {
        throw new \Exception("Link target collection not exposed: $alias");
      }
      $collection = $this->components[$alias]['collection'];
      return $this->dictionary->getLink($collection, $link['using']);
    } else {
      if (is_array($link)) {
        return new Link('adhocLink', $link, null, $this->dictionary);
      }
      return $link;
    }
  }

  public function getLinkConditions($link, $alias, $tAlias) {
    $conditions = array();
    $cnt = $link->getJoinCount();
    if ($cnt > 1) {
      $or = array();
      for ($i=0; $i<$cnt; $i++) {
        $c = $this->getSingleLinkCondition($link->getSourceFields($i), $link->getTargetFields($i), $alias, $tAlias);
        if (count($c)) {
          array_push($or, "(".implode($c, ' AND ') . ")");
        }
      }
      if (count($or)) {
        $orCondition = "(".implode($or, " OR ").")";
        array_push($conditions, $orCondition);
      }
    } else {
      $conditions = $this->getSingleLinkCondition($link->getSourceFields(), $link->getTargetFields(), $alias, $tAlias);
    }
    if ($link->hasConditions()) {
      foreach ($link->getConditions() as $cond) {
        $cond = $this->joinConditions($cond, 'source', $alias);
        $cond = $this->joinConditions($cond, 'target', $tAlias);
        array_push($conditions, $cond);
      }
    }
    return $conditions;
  }

  private function getSingleLinkCondition($sourceFields, $targetFields, $sAlias, $tAlias) {
    $conditions = array();
    for ($i=0; $i<count($sourceFields); $i++) {
      $left = $this->exposeComponentField($sAlias, $sourceFields[$i]);
      $right = $this->exposeComponentField($tAlias, $targetFields[$i]);
      array_push($conditions, "$left = $right");
    }
    return $conditions;
  }

  public function exposeCollection($collection, $alias, $link=null) {
    $this->assertOpened();
    if (!isset($this->components[$alias])) {
      $def = $this->dictionary->getCollection($collection);
      $linkDef = $link ? $this->getLink($link) : null;
      $conditions = array();
      $tables = array();
      foreach ($def->getTables() as $tAlias => $table) {
        if (isset($table['conditions'])) {
          $conditions = array_merge($conditions, $table['conditions']);
        }
        $tables[$tAlias] = $table;
      }

      $this->components[$alias] = array(
        'alias' => $alias,
        'collection' => $collection,
        'tables' => $tables,
        'link' => $link,
        'fields' => array()
      );

      if ($linkDef && $linkDef->isHABTM()) {
        $targetFields = $linkDef->getTargetFields();
        $joinTargetFields = $linkDef->getJoinTargetFields();
        $joinSourceFields = $linkDef->getJoinSourceFields();
        $conds = array();
        $targetF = array();
        $joinF = array();
        for ($i = 0; $i<count($targetFields); $i++) {
          $f = $targetFields[$i];
          $j = $joinTargetFields[$i];
          $e = $this->dictionary->getField($collection, $f);
          array_push($targetF, $e);
          array_push($conds, "habtm.$j = " . $e->getSQL());
        }
        foreach ($joinSourceFields as $s) {
          $key = "habtm_$s";
          $joinF[$key] = new Field($key, array(
            'description' => 'habtm join field',
            'type' => 'string',
            'sql' => "habtm.$s",
            'require' => array('habtm')
          ), $def);
        }
        $req = $this->collectRequiredTables($targetF, $tables);
        $habtm = array(
          'table' => $linkDef->getJoinTable(),
          'require' => $req,
          'join' => array(
            'type' => 'INNER',
            'conditions' => $conds
          )
        );
        $this->components[$alias]['tables']['habtm'] = $habtm;
        $this->components[$alias]['fields'] = $joinF;
      }
      if ($linkDef && $linkDef->hasJoinConditions()) {
        $joinConds = array();
        foreach ($linkDef->getJoinConditions() as $cond) {
          array_push($joinConds, $this->joinConditionsAliasOnly($cond, 'join', 'habtm'));
        }
        $conditions = array_merge($conditions, $joinConds);
      }
      if ($link && $def->hasJoinConditions()) {
        $conditions = array_merge($conditions, $def->getJoinConditions());
      } else if ($def->hasConditions()) {
        $conditions = array_merge($conditions, $def->getConditions());
      }

      if (count($conditions) > 0) {
        list ($ret, $req) = self::parametrizeFields($conditions, $def);
        $this->components[$alias]['require'] = $req;
        $this->components[$alias]['conditions'] = $ret;
      } else {
        $this->components[$alias]['require'] = array();
      }
    }
  }

  public function exposeKeyFields($alias) {
    $this->assertOpened();
    $collection = $this->getCollectionByAlias($alias);
    $def = $this->dictionary->getCollection($collection);
    $keys = array();
    foreach($def->getKeys() as $key) {
      $expr = $this->exposeField($alias, $key);
      array_push($keys, $expr);
    }
    return $keys;
  }

  public function addSorts($array) {
    $this->assertOpened();
    foreach($array as $expr) {
      $this->addSort($expr);
    }
  }

  public function addCondition($cond) {
    $this->assertOpened();
    array_push($this->conditions, $cond);
  }

  public function addComponentCondition($alias, $cond) {
    $this->assertOpened();
    if (!isset($this->components[$alias])) {
      throw new \Exception("Collection not exposed: $alias");
    }
    $component = $this->components[$alias];
    if (! isset($component['conditions'])) {
      $this->components[$alias]['conditions'] = array($cond);
    } else {
      array_push($this->components[$alias]['conditions'], $cond);
    }
  }

  public function getCollectionByAlias($alias) {
    if (!isset($this->components[$alias])) {
      throw new \Exception("Collection not exposed: $alias");
    }
    return $this->components[$alias]['collection'];
  }

  public function exposeComponentField($colAlias, $field) {
    $this->assertOpened();
    $hasComponent = isset($this->components[$colAlias]);
    if ($hasComponent) {
      if (! isset($this->components[$colAlias]['fields'][$field])) {
        $component = $this->components[$colAlias];
        $collection = $component['collection'];
        if (! $this->dictionary->hasField($collection, $field)) {
          throw new \Exception("Collection '$collection' does not contains field '$field'");
        }
        $fld = $this->dictionary->getField($collection, $field);
        $fields=$component['fields'];
        $this->components[$colAlias]['fields'][$field] = $fld;
      }
      $fld = $this->components[$colAlias]['fields'][$field];
      $base =  $this->getSQLAlias($colAlias, true) . "." . $this->getSQLAlias($field);
      $sql = $fld->getSQL();
      if (is_array($sql)) {
        $ret = array();
        foreach($sql as $key => $f) {
          $ret[$key] = $base . '_' . $key;
        }
        return $ret;
      }
      return $base;
    } else {
      throw new \Exception("Component '$colAlias' is not exposed [$colAlias.$field]");
    }
  }

  public function exposeField($colAlias, $field) {
    $this->assertOpened();
    $alias = $this->getSQLAlias($colAlias . '_' . $field);
    if (isset($this->fields[$alias]))
      return $alias;
    $this->fields[$alias] = array(
      'sql' => $this->exposeComponentField($colAlias, $field)
    );
    return $alias;
  }

  public function exposeExpression($expr) {
    $this->assertOpened();
    foreach ($this->fields as $alias => $fld) {
      if ($fld['sql'] === $expr) {
        return $alias;
      }
    }
    $alias = "e" . $this->counter++;
    $this->fields[$alias] = array(
      'sql' => $expr
    );
    return $alias;
  }

  private function getComponentExternalAlias($fields, $field) {
    $alias = $field;
    $counter = 0;
    while (isset($fields[$alias])) {
      $counter++;
      $alias = $field . $counter;
    }
    return $alias;
  }

  public function hasLimit() {
    return !!$this->limit;
  }

  public function calcRows($doCalc=true) {
    $this->assertOpened();
    $this->calcRows = $doCalc;
  }

  public function hasCalcRows() {
    return $this->calcRows;
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
