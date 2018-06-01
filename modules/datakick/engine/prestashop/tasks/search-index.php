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

class PrestashopSearchIndexTask extends Task {

  public function __construct(Factory $factory, $identity) {
    parent::__construct($factory, $identity);
  }

  public function doExecute(Context $context, Progress $progress, $executionId, $resumeState) {
    $progress->start('Index Products');
    @ini_set('max_execution_time', 7200);
    @set_time_limit(7200);
    $shopId = $context->getValue('task::shopId');
    $shop = \Context::getContext()->shop;
    if ($shopId == -1) {
      $shop->setContext(\Shop::CONTEXT_ALL);
    } else {
      $shop->setContext(\Shop::CONTEXT_SHOP, (int)$shopId);
    }
    $mode = $context->getValue('task::mode');
    $status = \Search::indexation($mode === 'full');
    $progress->end();
    return array(
      'success' => $status,
      'mode' => $mode,
      'shop' => $shopId
    );
  }

  public function getRequiredParameters() {
    return array_keys($this->getUserParameters());
  }

  public function getUserParameters() {
    $factory = $this->getFactory();
    $enums = $factory->getEnums();
    $shops = $enums['shops'];
    $hidden = count($shops) <= 1;
    $shops[-1] = '-- All shops --';

    return array(
      'task::shopId' => array(
        'type' => 'number',
        'description' => "Select shop(s)",
        'values' => $shops,
        'default' => -1,
        'hidden' => $hidden,
        'order' => 1
      ),
      'task::mode' => array(
        'type' => 'string',
        'description' => 'Indexation mode',
        'values' => array(
          'full' => 'Rebuild entire index',
          'increment' => 'Add missing products'
        ),
        'default' => 'full',
        'order' => 2
      )
    );
  }

}
