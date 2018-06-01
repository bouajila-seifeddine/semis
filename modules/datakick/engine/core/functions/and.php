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

class AndFunction extends Logical {
  public function __construct() {
    parent::__construct('and', '&&');
  }

  public function doEvaluate($left, $right) {
    return $left && $right;
  }

  public function partialReduce($expression, $args, $argsTypes, Context $context) {
    if (! is_null($args[0])) {
      // short circuit
      return $args[0] ? $expression['args'][1] : false;
    }
    if (! is_null($args[1])) {
      // short circuit
      return $args[1] ? $expression['args'][0] : false;
    }
    return $expression;
  }
}
