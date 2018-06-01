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

class GetCurrencyIdFunction extends Func {
  public function __construct() {
    parent::__construct('getCurrencyId', 'number', array(
      'names' => array('currency'),
      'types' => array('currency')
    ), true);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    return $args[0]->getCurrencyId();
  }

  public function jsEvaluate() {
    return 'return 1';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    $arg = $args[0];
    return $arg['currency'];
  }
}
