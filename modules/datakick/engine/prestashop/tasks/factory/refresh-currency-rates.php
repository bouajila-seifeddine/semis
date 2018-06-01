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
require_once(DATAKICK_PRESTASHOP.'tasks/refresh-currency-rates.php');

class PrestashopRefreshCurrencyRatesTaskFactory extends TaskFactory {
  public function createTask(Factory $factory, Array $identity) {
    return new PrestashopRefreshCurrencyRatesTask($factory, $identity);
  }

  public function getTaskName() {
    return 'Currency Rates';
  }

  public function getIcon() {
    return 'currency';
  }

  public function getCategory() {
    return 'platform';
  }

  public function getActionName() {
    return 'Refresh currency rates';
  }

  public function getSupportedRecordTypes() {
    return array();
  }

}
