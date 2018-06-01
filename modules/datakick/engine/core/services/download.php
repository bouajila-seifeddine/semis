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

class DownloadService extends Service {

  public function __construct() {
    parent::__construct('download');
  }

  public function process($factory, $request) {
    try {
      $executionId = null;
      if (isset($_GET['execution-id']))
        $executionId = (int)$_GET['execution-id'];
      if (! $executionId)
        throw new UserError("Execution ID not provided");

      $ret = $factory->getTasks()->loadDeferred($executionId);
      $task = $ret['task'];
      $parameters = $ret['parameters'];

      $context = $factory->getContext('adhoc', $executionId);
      $context->setUserParameters($task->getUserParameters());
      $context->setValues($parameters);
      $context->setParameter('executionId', $executionId);

      $task->execute($context, new Progress(true, $task), $executionId);
    } catch (\Exception $e) {
      if (! headers_sent()) {
        header('Content-Disposition: attachment; filename="error.json"');
      }
      throw $e;
    }

    return Service::OUTPUT_HANDLED;
  }
}
