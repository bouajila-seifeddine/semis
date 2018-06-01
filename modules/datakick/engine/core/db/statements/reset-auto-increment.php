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

class ResetAutoIncrement implements Statement {
  private $collection;

  public function __construct(Collection $collection) {
    $this->collection = $collection;
  }

  public function getSQL(Context $context) {
    $ret = "BEGIN\n";
    $ret .="  <@inc> = " . $this->getMaxIdSql() . "\n";
    $ret .="  ".$this->getAlterTableSql('@inc') . "\n";
    $ret .= "END";
    return $ret;
  }

  private function getMaxIdSql() {
    $tables = $this->collection->getTables();
    $ptAlias = $this->collection->getPrimaryTable();
    $primaryTable = $tables[$ptAlias]['table'];
    $key;
    foreach ($this->collection->getKeyFields() as $k) {
      $mapping = $k->getMapping();
      $key = $mapping[$ptAlias];
    }
    return "SELECT IFNULL(MAX($key), 0)+1 from $primaryTable";
  }

  private function getAlterTableSql($value) {
    $tables = $this->collection->getTables();
    $ptAlias = $this->collection->getPrimaryTable();
    $primaryTable = $tables[$ptAlias]['table'];
    return "ALTER TABLE $primaryTable AUTO_INCREMENT = $value";
  }

  public function execute(Factory $factory, Context $context) {
    $conn = $factory->getConnection();
    $value = $conn->singleSelect($this->getMaxIdSql());
    $conn->execute($this->getAlterTableSql($value));
  }
}
