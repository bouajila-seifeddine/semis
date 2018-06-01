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

class InRestriction implements Restriction {
  private $fieldName;
  private $values;

  public function __construct($fieldName, Array $values) {
    $this->fieldName = $fieldName;
    $this->values = $values;
  }

  public function getCondition(Array $fields) {
    $field = $fields[$this->fieldName];
    if ($this->values) {
      $list = implode(', ', $this->values);
      return "$field IN ($list)";
    } else {
      return Restriction::DENY;
    }
  }

}
