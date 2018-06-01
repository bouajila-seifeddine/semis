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

class Batch implements Statement {
  private $statements;

  public function __construct() {
    $this->statements = array();
  }

  public function addStatement(Statement $statement) {
    $this->statements[] = $statement;
  }

  public function getSQL(Context $context) {
    $sql = array();
    foreach ($this->statements as $stm) {
      $sql[] = $stm->getSQL($context);
    }
    return implode("\n", $sql);
  }

  public function execute(Factory $factory, Context $context) {
    foreach ($this->statements as $stm) {
      $stm->execute($factory, $context);
    }
  }
}
