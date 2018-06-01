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

class ConvertCurrencyFunction extends Func {
  public function __construct() {
    parent::__construct('convertCurrency', 'currency', array(
      'names' => array('from', 'targetCurrency'),
      'types' => array('currency', 'number')
    ), true);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $curr = $args[0];
    $fromCurrency = $curr->getCurrencyId();
    $targetCurrency = (int)$args[1];
    $value = $curr->getValue();
    if ($fromCurrency == $targetCurrency) {
      return $curr;
    }
    $from = new \Currency($fromCurrency);
    $to = new \Currency($targetCurrency);
    $newValue = \Tools::convertPriceFull($value, $from, $to);
    return new Currency($targetCurrency, $newValue);
  }

  public function jsEvaluate() {
    return 'return false;';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    $curr = $args[0];
    $value = $curr['value'];
    $from = $curr['currency'];
    $to = $args[1];
    return $this->getSQL($value, $from, $to, $context->getValue('defaultCurrency'));
  }

  private function getSQL($value, $from, $to, $defaultCurrency) {
    $table = _DB_PREFIX_ . 'currency';
    $toBase = "COALESCE((SELECT conversion_rate FROM $table WHERE id_currency = $from), 1)";
    $toTarget = "(SELECT conversion_rate FROM $table WHERE id_currency = $to)";
    return array(
      'value' => "(($value / $toBase) * $toTarget)",
      'currency' => $to
    );
  }
}
