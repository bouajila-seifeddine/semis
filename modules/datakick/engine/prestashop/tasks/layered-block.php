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

class PrestashopLayeredBlockTask extends Task {

  public function __construct(Factory $factory, $identity) {
    parent::__construct($factory, $identity);
  }

  public function doExecute(Context $context, Progress $progress, $executionId, $resumeState) {
    $status = false;
    if (\Module::isInstalled('blocklayered')) {
      $progress->start('Rebuild Layered Navigation Block indexes');
      $mode = $context->getValue('task::mode');
      $blockLayered = \Module::getInstanceByName('blocklayered');
      $status = array();
      $progress->setProgress(3, 0);
      $status['prices'] = $this->indexPrices($mode, $progress);
      $progress->setProgress(3, 1);
      $status['attributes'] = (bool)$blockLayered->indexAttribute();
      $progress->setProgress(3, 2);
      $status['urls'] = (bool)$blockLayered->indexUrl();
      $progress->setProgress(3, 3);
      $progress->end();
    }
    return $status;
  }

  private function indexPrices($mode, $progress) {
    $progress->start('Index price');
    $counter = 0;
    $loop = true;
    $ret = false;
    do {
      $status;
      if ($mode === 'full') {
        $status = \BlockLayered::fullPricesIndexProcess($counter, true, true);
      } else {
        $status = \BlockLayered::pricesIndexProcess($counter, true);
      }
      $status = json_decode($status, true);
      if (isset($status['cursor']) && isset($status['count'])) {
        $count = (int)$status['count'];
        $counter = (int)$status['cursor'];
        $total = $mode === 'full' ? $count + $counter : $count;
        $progress->setProgress($total, $counter);
      } else {
        $ret = (isset($status['result']) && $status['result'] == 'ok');
        $loop = false;
      }
    } while ($loop);
    $progress->end();
    return $ret;
  }

  private function indexUrls($progress) {
  }

  public function getRequiredParameters() {
    return array_keys($this->getUserParameters());
  }

  public function getUserParameters() {
    return array(
      'task::mode' => array(
        'type' => 'string',
        'description' => 'Indexation mode',
        'values' => array(
          'full' => 'Rebuild entire index',
          'increment' => 'Add missing prices'
        ),
        'default' => 'full'
      )
    );
  }

}
