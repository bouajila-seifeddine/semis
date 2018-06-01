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

class JoinField {
  private $id;
  private $type;
  private $description;
  private $sqlField;

  public function __construct($id, $definition) {
    $this->id = $id;
    $this->description = $definition['description'];
    $this->type = $definition['type'];
    $this->sqlField = $definition['sqlField'];
  }

  public function getType() {
    return $this->type;
  }

  public function getName() {
    return $this->description;
  }

  public function getParameters() {
    return array();
  }

  public function getId() {
    return $this->id;
  }

  public function getSqlField() {
    return $this->sqlField;
  }
}
