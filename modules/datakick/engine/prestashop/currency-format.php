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

class PrestashopCurrencyFormat {
  private $currencies;
  private $byCode;
  private $bySymbol;

  private $codeRegexp;

  public function __construct($currencies) {
    $this->currencies = $currencies;
    $this->byCode = array();
    $this->bySymbol = array();
    $codeRegexp = '';
    foreach ($currencies as $key => $currency) {
      if (isset($currency['code'])) {
        $this->byCode[$currency['code']] = $key;
      }
      if (isset($currency['symbol'])) {
        $this->bySymbol[$currency['symbol']] = $key;
      }
    }
    $this->codeRegexp = "/^\s*(\d*\.?\d+)\s*(".implode('|', array_keys($this->byCode)) . ")\s*$/";
  }

  public function formatCurrency(Currency $currency) {
    $value = $currency->getValue();
    $currencyId = $currency->getCurrencyId();
    if (isset($this->currencies[$currencyId])) {
      $currency = $this->currencies[$currencyId];
      return round($value, 2) . ' ' . $currency['code'];
    }
    return round($value, 2);
  }

  public function parseCurrency($string) {
    $matches = array();
    if (preg_match($this->codeRegexp, $string, $matches)) {
      $value = Types::convertValue('number', $matches[1]);
      $currencyId = $this->byCode[$matches[2]];
      return new Currency($currencyId, $value);
    }
  }

}
