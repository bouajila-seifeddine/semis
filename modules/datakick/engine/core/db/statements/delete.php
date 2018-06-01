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

class Delete extends DBBase implements Statement {
  private $sql;
  private $tableName;

  public function __construct(Escape $escape, $tableName, $conditions=array()) {
    parent::__construct($escape);
    $this->$tableName = $tableName;
    $this->sql = $this->getStatementBuilder()->getDeleteSql($tableName, $conditions);
  }


  public function getSQL(Context $context) {
    return $this->sql;
  }

  public function execute(Factory $factory, Context $context) {
    $sql = $this->getSQL($context);
    $conn = $factory->getConnection();
    $conn->execute($sql);
    $ret = array(
      $this->tableName => array(
        'deleted' => $conn->numRows()
      )
    );
    return $ret;
  }
}
