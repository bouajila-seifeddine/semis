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

class ToCurrencyFunction extends Func {
  public function __construct() {
    parent::__construct('toCurrency', 'currency', array(
      'names' => array('amount', 'currencyId'),
      'types' => array('number', 'number')
    ), true);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    return new Currency($args[1], $args[0]);
  }

  public function jsEvaluate() {
    return 'return amount';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    $amount = $args[0];
    $currency = $args[1];
    return array(
      'value' => $amount,
      'currency' => $currency
    );
  }
}
