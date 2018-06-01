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

class TaskParametersService extends Service {

  public function __construct() {
    parent::__construct('task-parameters');
  }

  public function process($factory, $request) {
    $taskDef = $this->getArrayParameter('task');
    $task = $factory->getTasks()->get($taskDef);
    return self::describe($task);
  }

  public static function describe($task) {
    return array(
      'type' => $task->getType(),
      'typeName' => $task->getTypeName(),
      'name' => $task->getName(),
      'recordType' => $task->getRecordType(),
      'recordTypeName' => $task->getRecordTypeName(),
      'recordId' => $task->getRecordId(),
      'requiredParameters' => $task->getRequiredParameters(),
      'userParameters' => $task->getUserParameters()
    );
  }
}
