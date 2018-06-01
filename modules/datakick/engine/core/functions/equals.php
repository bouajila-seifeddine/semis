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

class EqualsFunction extends Logical {
  public function __construct() {
    parent::__construct('equals', '===', '=');
  }

  public function doEvaluate($left, $right) {
    return $left == $right;
  }
}
