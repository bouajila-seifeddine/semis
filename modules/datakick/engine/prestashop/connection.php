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

class PrestashopConnection extends Connection {
  private $conn;

  public function __construct($factory) {
    parent::__construct($factory);
    $this->conn = \DB::getInstance();
  }

  public function doExecute($sql) {
    return $this->conn->execute($sql);
  }

  public function doQuery($sql) {
    return $this->conn->query($sql, array(), true);
  }

  public function getLastError() {
    return $this->conn->getMsgError();
  }

  public function getLastInsertId() {
    return $this->conn->Insert_ID();
  }

  public function escape($string) {
    return $this->conn->_escape($string);
  }

  public function numRows() {
    return $this->conn->numRows();
  }

  public function getVersion() {
    return $this->conn->getVersion();
  }

  public function getDefaultEngine() {
    return _MYSQL_ENGINE_;
  }

}
