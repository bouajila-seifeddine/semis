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

class Update extends DBBase implements Statement {
    private $collection;
    private $conditions;
    private $updateFields;
    private $expressionFields;
    private $table;

    public function __construct(Escape $escape, Collection $collection, $table) {
      parent::__construct($escape);
      $this->collection = $collection;
      $this->table = $table;
      $this->conditions = array($this->exposeComponentField($collection->getId(), 'canWrite'));
      $this->updateFields = array();
      $this->expressionFields = array();
    }

    public function getCollectionByAlias($alias) {
      return $this->collection->getId();
    }

    public function exposeComponentField($colAlias, $field) {
      $fld = $this->collection->getField($field);
      $this->expressionFields[] = $fld;
      return $fld->getSQL();
    }

    public function getSQL(Context $context) {
      $col = $this->collection;
      $table = $this->getSQLTables($context, $col->getTables(), $this->getTables(), "\n  ");
      $sql = "UPDATE $table\n";
      $sql .= "SET ";
      $fields = array();
      foreach ($this->updateFields as $field => $updateSql) {
        $fields[] = "`{$this->table}`.`$field` = $updateSql";
      }
      $sql .= implode($this->parametrizeArray($context, $fields), ",\n    ");
      $sql .= "\n";
      $conditions = $this->getConditions($context);
      if ($conditions) {
        $sql .= "WHERE ";
        $sql .= join("\n  AND ", $conditions);
      }
      return $sql;
    }

    public function addUpdateField($field, $updateSql) {
      $this->updateFields[$field] = $updateSql;
    }

    public function addCondition($cond) {
      $this->conditions[] = $cond;
    }

    public function execute(Factory $factory, Context $context) {
      $tableName = $this->collection->getTable($this->table)['table'];
      $conn = $factory->getConnection();
      $conn->execute($this->getSQL($context));
      return array(
        $tableName => array(
          'update' => $conn->numRows()
        )
      );
    }

    private function getConditions(Context $context) {
      $cond = $this->conditions;
      $col = $this->collection;
      foreach ($this->getTables() as $table) {
        $t = $col->getTable($table);
        if (isset($t['conditions'])) {
          $cond = array_merge($cond, $t['conditions']);
        }
      }
      return $this->processConditions($this->parametrizeArray($context, $cond));
    }

    private function getTables() {
      $allTables = $this->collection->getTables();
      $tables = $this->collectRequiredTables($this->expressionFields, $allTables);
      array_push($tables, $this->table);
      $tables = array_merge($tables, $this->getTableDependencies($this->table, $allTables));
      $tables = array_unique($tables);
      return $tables;
    }
}
