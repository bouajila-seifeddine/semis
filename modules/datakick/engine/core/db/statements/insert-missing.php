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

class InsertMissing extends DBBase implements Statement {
  private $collection;
  private $table;
  private $conditions = array();
  private $fields = array();

  public function __construct(Escape $escape, Collection $collection, $table) {
    parent::__construct($escape);
    $this->collection = $collection;
    $this->table = $table;
  }

  public function getCollectionByAlias($alias) {
    return $this->collection->getId();
  }

  public function exposeComponentField($colAlias, $field) {
    $fld = $this->collection->getField($field);
    $this->fields[] = $fld;
    return $fld->getSQL();
  }

  public function addCondition($cond) {
    $this->conditions[] = $cond;
  }

  public function getSQL(Context $context) {
    $tableDef = $this->collection->getTable($this->table);
    if (! isset($tableDef['create'])) {
      throw new \Exception("Table {$this->table} can't be initialized");
    }

    $table = $tableDef['table'];

    $keys = array_keys($tableDef['create']);
    $expressions = array_values($tableDef['create']);
    foreach ($expressions as &$expr) {
      if ($expr == '<pk>') {
        $expr = $this->collection->getKeyFields()[0]->getSQL();
      }
      $matches = array();
      $pattern = '/<field:([a-zA-Z0-9-_]+)>/';
      preg_match_all($pattern, $expr, $matches, PREG_PATTERN_ORDER);
      $orig = $matches[0];
      $names = $matches[1];
      for ($i = 0; $i<count($orig); $i++) {
        $field = $orig[$i];
        $name = $names[$i];
        $fld = $this->collection->getField($name);
        $sql = $fld->getSQL();
        $expr = str_replace($field, $sql, $expr);
      }
    }

    $fields = "`" . implode("`, `", $keys) . "`";
    $selectFields = implode(", ", $this->parametrizeArray($context, $expressions));


    $sql = "INSERT INTO `$table`($fields)";

    $tables = $this->getSQLTables($context, $this->collection->getTables(), $this->getTables($context), "\n  ");
    $sql .= "\nSELECT $selectFields FROM$tables";
    $sql .= "\nWHERE ";
    $sql .= join("\n  AND ", $this->getConditions($context, $keys));

    return $sql;
  }

  public function execute(Factory $factory, Context $context) {
    $tableName = $this->collection->getTable($this->table)['table'];
    $conn = $factory->getConnection();
    $conn->execute($this->getSQL($context));
    return array(
      $tableName => array(
        'insert-missing' => $conn->numRows()
      )
    );
  }

  private function getTables(Context $context) {
    $allTables = $this->collection->getTables();
    $tables = $this->collectRequiredTables($this->fields, $allTables);
    array_push($tables, $this->table);
    $tables = array_merge($tables, $this->getTableDependencies($this->table, $allTables));
    $tables = array_unique($tables);
    return $tables;
  }

  private function getConditions(Context $context, $keys) {
    $cond = $this->conditions;
    $col = $this->collection;
    foreach ($this->getTables($context) as $table) {
      $t = $col->getTable($table);
      if (isset($t['conditions'])) {
        $cond = array_merge($cond, $t['conditions']);
      }
    }
    foreach ($keys as $key) {
      $cond[] = "{$this->table}.$key IS NULL";
    }
    return $this->parametrizeArray($context, $cond);
  }

}
