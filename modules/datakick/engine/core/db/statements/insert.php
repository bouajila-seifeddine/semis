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

class Insert extends DBBase implements Statement {
  private $sql;
  private $id;
  private $tableName;

  public function __construct(Escape $escape, $tableName, $table, $values, $getId=false) {
    parent::__construct($escape);
    $this->getId = $getId;
    $this->tableName = $tableName;
    $ignore = isset($table['unique']);
    $this->sql = $this->getStatementBuilder()->getInsertSql($tableName, $values, $ignore);
  }

  public function getPrimaryKey() {
    return $this->id;
  }

  public function getSQL(Context $context) {
    $sql = $this->sql;
    $dep = $this->getParent();
    if ($dep) {
      $sql = str_replace('<pk>', $dep->id, $sql);
    }
    return $this->parametrizeExpression($context, $sql) . ';';
  }

  public function execute(Factory $factory, Context $context) {
    $tableName = $this->tableName;
    $sql = $this->getSQL($context);

    $conn = $factory->getConnection();
    $conn->execute($sql);
    $ret = array(
      $tableName => array(
        'insert' => $conn->numRows()
      )
    );

    if ($this->getId) {
      $this->id = $conn->getLastInsertId();
      $ret[$tableName]['id'] = $this->id;
    }

    return $ret;
  }

  private function getTableName() {
    return $this->collection->getTable($this->table)['table'];
  }
}
