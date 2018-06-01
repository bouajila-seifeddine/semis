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
require_once(dirname(__FILE__).'/version.php');

abstract class MigrationVersion {
  private $tables;
  private $sorted;
  private $previous;
  private $changes = array();

  public function __construct($version, $factory, $previous=null) {
    $this->version = $version;
    $this->factory = $factory;
    $this->conn = $factory->getConnection();
    $this->tables = array();
    $this->sorted = array();
    $this->registerTables();
    $this->sortTables();
    $this->previous = $previous;
  }

  public function install() {
    foreach ($this->sorted as $alias) {
      if (! $this->createTable($alias))
        return false;
    }
    foreach ($this->getData() as $alias => $data) {
      $table = $this->getTable($alias);
      $this->conn->insert($table, $data);
    }
    return true;
  }

  public function uninstall() {
    $tables = $this->sorted;
    $tables = array_reverse($tables);
    foreach ($tables as $alias) {
      $this->dropTable($alias);
    }
    return true;
  }

  public function getChanges() {
    return $this->changes;
  }

  public function migrate() {
    if ($this->previous) {
      $this->changes = array();
      $oldDefs = $this->previous->getTables();
      $oldTables = array_keys($oldDefs);
      $currTables = array_keys($this->tables);
      $newTables = array_diff($currTables, $oldTables);
      foreach ($newTables as $alias) {
        if (! $this->createTable($alias))
          return false;
      }
      $missingTables = array_diff($oldTables, $currTables);
      foreach ($missingTables as $table) {
        if (! $this->dropTable($table))
          return false;
      }
      $same = array_intersect($oldTables, $currTables);
      foreach ($same as $table) {
        if (! $this->migrateTable($table, $oldDefs[$table], $this->tables[$table]))
          return false;
      }
      return $this->migrateData($this->factory, $this->conn);
    }
    return true;
  }

  private function createTable($alias) {
    $def = $this->tables[$alias];
    $fkeys = array();
    foreach ($def['fkeys'] as $a => $f) {
      $f['alias'] = $a;
      $fkeys[$this->tables[$a]['table']] = $f;
    }
    $this->changes[] = "create table $alias";
    return $this->conn->createTable($def['table'], $def['columns'], $def['keys'], $fkeys, $def['ukeys']);
  }

  protected function dropTable($alias) {
    $table = $this->getTable($alias);
    $this->dropConstrainsToTable($table);
    $this->changes[] = "drop table $alias";
    return $this->conn->dropTable($table);
  }

  private function migrateTable($alias, $old, $new) {
    if ($old['columns'] != $new['columns']) {
      $oldNames = array_keys($old['columns']);
      $newNames = array_keys($new['columns']);
      $newColumns = array_diff($newNames, $oldNames);
      foreach ($newColumns as $name) {
        if (! $this->createColumn($alias, $name, $new['columns'][$name]))
          return false;
      }
      $missingColumns = array_diff($oldNames, $newNames);
      foreach ($missingColumns as $name) {
        if (! $this->dropColumn($alias, $name))
          return false;
      }
      $same = array_intersect($oldNames, $newNames);
      foreach ($same as $name) {
        $oldDef = $old['columns'][$name];
        $newDef = $new['columns'][$name];
        if ($oldDef != $newDef) {
          if (! $this->migrateColumn($alias, $name, $newDef))
            return false;
        }
      }
    }
    return true;
  }

  protected function migrateColumn($tableAlias, $name, $def) {
    $table = $this->getTable($tableAlias);
    if ($this->columnExists($table, $name)) {
      $this->changes[] = "modify column $table.$name";
      return $this->conn->alterColumn($table, $name, $def);
    }
    return true;
  }

  protected function createColumn($tableAlias, $name, $def) {
    $table = $this->getTable($tableAlias);
    if (! $this->columnExists($table, $name)) {
      $this->changes[] = "create column $table.$name";
      return $this->conn->createColumn($table, $name, $def);
    }
    return true;
  }

  protected function dropColumn($tableAlias, $name) {
    $table = $this->getTable($tableAlias);
    if ($this->columnExists($table, $name)) {
      $this->changes[] = "drop column $table.$name";
      return $this->conn->removeColumn($table, $name);
    }
    return true;
  }

  protected function tableExists($table) {
    $q = "SELECT * FROM information_schema.TABLES WHERE table_schema=database() AND table_name = '$table'";
    $res = $this->conn->query($q);
    if ($res && $res->fetch()) {
      return true;
    }
    return false;
  }

  protected function columnExists($table, $column) {
    $q = "SELECT * FROM information_schema.COLUMNS WHERE table_schema = database() AND table_name ='$table' AND column_name = '$column'";
    $res = $this->conn->query($q);
    if ($res && $res->fetch()) {
      return true;
    }
    return false;
  }

  protected function dropConstrainsToTable($target) {
    $s = array();
    $constraints = "SELECT table_name, constraint_name FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE constraint_schema=database() AND referenced_table_name = '$target'";
    $res = $this->conn->query($constraints);
    while ($row = $res->fetch()) {
      $table = $row['table_name'];
      $key = $row['constraint_name'];
      $s[] = "ALTER TABLE $table DROP FOREIGN KEY $key;";
    }
    foreach ($s as $e) {
      $this->conn->execute($e);
    }
  }

  protected function registerTable($alias, $columns, $keys, $fkeys=array(), $ukeys=array()) {
    if (isset($this->tables[$alias]))
      throw new \Exception("Table already registered: $alias");
    $table = $this->getTable($alias);
    foreach ($fkeys as $a=>$def) {
      $this->getTable($a);
    }
    $this->tables[$alias] = array(
      'table' => $table,
      'columns' => $columns,
      'keys' => $keys,
      'fkeys' => $fkeys,
      'ukeys' => $ukeys
    );
  }

  private function sortTables() {
    $resolved = array();
    $tables = array_keys($this->tables);
    sort($tables);
    while (! empty($tables)) {
      $key = $this->findItem($tables, $resolved);
      if (is_null($key)) {
        throw new \Error('Circular depenencies');
      }
      $resolved[] = $tables[$key];
      unset($tables[$key]);
    }
    $this->sorted = $resolved;
  }

  private function findItem($tables, $resolved) {
    foreach ($tables as $key=>$table) {
      if ($this->isResolved($table, $resolved)) {
        return $key;
      }
    }
    return null;
  }

  protected function insert($alias, $data) {
    $table = $this->getTable($alias);
    $this->conn->insert($table, $data);
    return true;
  }

  protected function update($alias, $data, $where) {
    $table = $this->getTable($alias);
    $this->conn->update($table, $data, $where);
    return true;
  }

  private function isResolved($table, $resolved) {
    $fkeys = array_keys($this->tables[$table]['fkeys']);
    foreach ($fkeys as $fkey) {
      if (! in_array($fkey, $resolved))
        return false;
    }
    return true;
  }

  public function __toString() {
    return $this->version;
  }

  public function getTables() {
    return $this->tables;
  }

  public function migrateData($factory, $conn) {
    return true;
  }

  protected function getTable($alias) {
    return $this->factory->getServiceTable($alias);
  }

  protected function getConnection() {
    return $this->conn;
  }

  abstract protected function registerTables();
  abstract protected function getData();
}
