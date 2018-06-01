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
require_once(dirname(__FILE__).'/task-parameters.php');

class ExecuteService extends Service {

  public function __construct() {
    parent::__construct('execute');
  }

  public function process($factory, $request) {
    $taskDef = $this->getArrayParameter('task');
    $task = $factory->getTasks()->get($taskDef);
    $parameters = $this->getArrayParameter('parameters');
    $context = $factory->getContext('adhoc');
    $context->setUserParameters($task->getUserParameters());
    $context->setValues($parameters);

    //return $task->execute($context, new Progress(true, $task));

    $executionId = $context->getValue('executionId');
    if ($task->handlesResponse()) {
      $task->prepare($context, "deferred", $executionId);
      return array(
        'executionId' => $executionId,
        'task' => TaskParametersService::describe($task),
        'status' => 'deferred'
      );
    }

    $response = array(
      'error' => false,
      'data' => array(
        'executionId' => $executionId,
        'task' => TaskParametersService::describe($task),
        'status' => 'running'
      )
    );

    // close connection and continue on backend only
    ignore_user_abort(true);
    ob_start();
    echo json_encode($response);
    header('Connection: close');
    header('Content-Length: '.ob_get_length());
    ob_end_flush();
    flush();

    if (function_exists('fastcgi_finish_request'))
      fastcgi_finish_request();


    $task->execute($context, new Progress(true, $task));
    return Service::OUTPUT_HANDLED;
  }

}
