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
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/engine/engine.php');
$factory = Datakick\PrestashopFactory::withContext(Context::getContext(), Datakick\User::systemUser());

$config = $factory->getPersistentConfig();
if (Tools::getValue('token') != $config->get('webcronToken')) {
  die('Invalid Token');
}

$executionId = (int)Tools::getValue('execution-id');
if (! $executionId) {
  die('Execution ID not provided');
}

$task = null;
try {
  // load execution
  $data = $factory->getRecord('executions')->load($executionId, array('status', 'userId', 'source'));
  if ($data['status'] != 'paused') {
    die("Execution $executionId is not paused");
  }

  // substitute user
  $userId = (int)$data['userId'];
  $factory->substituteUser($userId);

  // load task
  $ret = $factory->getTasks()->loadDeferred($executionId, 'paused');
  $task = $ret['task'];
  $parameters = $ret['parameters'];

  $context = $factory->getContext($data['source'], $executionId);
  $context->setUserParameters($task->getUserParameters());
  $context->setValues($parameters);
  $context->setParameter('executionId', $executionId);
} catch (Exception $e) {
  die($e->getMessage());
}

if (! $task) {
  die("Unexpected error");
}

// close connection and continue on backend only
ignore_user_abort(true);
ob_start();
echo "Task $executionId resumed";
header('Connection: close');
header('Content-Length: '.ob_get_length());
ob_end_flush();
flush();

if (function_exists('fastcgi_finish_request'))
  fastcgi_finish_request();

// process task
$task->execute($context, new Datakick\Progress(true, $task), $executionId);
