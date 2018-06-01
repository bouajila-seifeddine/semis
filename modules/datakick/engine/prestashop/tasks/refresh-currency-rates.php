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

class PrestashopRefreshCurrencyRatesTask extends Task {

  public function __construct(Factory $factory, $identity) {
    parent::__construct($factory, $identity);
  }

  public function doExecute(Context $context, Progress $progress, $executionId, $resumeState) {
    $progress->start('Refresh Currency Rates');
    $status = true;
    $shopIds = $this->getShopIDs($context);
    $i = 0;
    foreach ($shopIds as $shopId) {
      $i++;
      \Shop::setContext(\Shop::CONTEXT_SHOP, (int)$shopId);
      \Currency::refreshCurrencies();
      $progress->setProgress(count($shopIds), $i);
    }
    $progress->end();
    return array(
      'success' => true,
      'shops' => $shopIds
    );
  }

  public function getRequiredParameters() {
    return array_keys($this->getUserParameters());
  }

  public function getUserParameters() {
    $factory = $this->getFactory();
    $enums = $factory->getEnums();
    $shops = $enums['shops'];
    $default = -1;
    $label = "Select shop(s)";
    if (count($shops) > 1) {
      $shops[-1] = '-- All shops --';
    } else {
      $label = "Select shop";
      $shopKeys = array_keys($shops);
      $default = $shopKeys[0];
    }

    return array(
      'task::shopId' => array(
        'type' => 'number',
        'description' => $label,
        'values' => $shops,
        'default' => $default
      )
    );
  }

  private function getShopIDs($context) {
    $shopId = $context->getValue('task::shopId');
    if ($shopId == -1)
      return \Shop::getCompleteListOfShopsID();
    return array($shopId);
  }

}
