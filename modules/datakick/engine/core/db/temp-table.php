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

abstract class TemporaryTable {
  public function __construct($name, $dbName) {
    $this->name = $name;
    $this->dbName = $dbName;
  }

  public function getName() {
    return $this->name;
  }

  public function getDbName() {
    return $this->dbName;
  }

  public function populate(Connection $connection, Context $context) {
    $connection->execute($this->getCreateTableSql());
    $res = $connection->query("SELECT 1 AS 'x' FROM {$this->getDbName()} LIMIT 1");
    if (! $res->fetch()) {
      $dbName = $this->getDbName();
      $populateSql = $this->getPopulateSql($connection, $context);
      if ($populateSql) {
        return $connection->execute("INSERT INTO $dbName $populateSql");
      }

      $data = $this->getPopulateData($connection, $context);
      if (! is_array($data)) {
        throw new \Exception('Subclass must override either getPopulateSql or getPopulateData method');
      }
      return $connection->insert($dbName, $data);
    }
  }

  public function destroy($connection, $context) {
  }

  private function getCreateTableSql() {
    $dbName = $this->getDbName();
    $engine = $this->getEngine();
    $charset = $this->getCharset();
    $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS $dbName (\n  ";
    $sql .= implode(",\n  ", $this->getFields());
    $sql .= ",\n  PRIMARY KEY (" . implode(', ', $this->getPrimaryKey()) . ")\n";
    $sql .= ") ENGINE=$engine DEFAULT CHARSET=$charset;";
    return $sql;
  }

  public abstract function getFields();

  public abstract function getPrimaryKey();

  public function getPopulateSql(Connection $connection, Context $context) {
    return null;
  }
  public function getPopulateData(Connection $connection, Context $context) {
    return null;
  }
  public function getEngine() {
    return 'MYISAM';
  }
  public function getCharset() {
    return 'utf8';
  }
}
