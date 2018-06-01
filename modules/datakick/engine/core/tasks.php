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
require_once(dirname(__FILE__).'/registry.php');
require_once(dirname(__FILE__).'/tasks/task-control.php');
require_once(dirname(__FILE__).'/tasks/task.php');
require_once(dirname(__FILE__).'/tasks/factory/task-factory.php');
require_once(dirname(__FILE__).'/tasks/factory/xml-feed-factory.php');
require_once(dirname(__FILE__).'/tasks/factory/xml-file-factory.php');
require_once(dirname(__FILE__).'/tasks/factory/csv-file-factory.php');
require_once(dirname(__FILE__).'/tasks/factory/csv-feed-factory.php');
require_once(dirname(__FILE__).'/tasks/factory/mass-update-factory.php');
require_once(dirname(__FILE__).'/tasks/factory/import-factory.php');

class Tasks {
  private $factory;
  private $taskFactories;

  public function __construct($factory) {
    $this->factory = $factory;
    $this->taskFactories = new Registry('TaskFactory');
    $this->taskFactories->register('xml-feed', new XmlFeedTaskFactory());
    $this->taskFactories->register('xml-file', new XmlFileTaskFactory());
    $this->taskFactories->register('csv-feed', new CsvFeedTaskFactory());
    $this->taskFactories->register('csv-file', new CsvFileTaskFactory());
    $this->taskFactories->register('mass-update', new MassUpdateTaskFactory());
    $this->taskFactories->register('import', new ImportTaskFactory());
    $factory->includePlatformTasks($this->taskFactories);
    $this->ensureNames();
  }

  public function get($taskDef) {
    if (! isset($taskDef['taskType']))
      throw new UserError("Task type not defined");
    $taskType = $taskDef['taskType'];
    $recordType = isset($taskDef['recordType']) ? $taskDef['recordType'] : null;
    $recordId = isset($taskDef['recordId']) ? $taskDef['recordId'] : null;
    $record = isset($taskDef['record']) ? $taskDef['record'] : null;
    $taskFactory = $this->taskFactories->get($taskType);
    if (! is_null($recordType) && $recordType != '') {
      if (is_null($record) && $recordId !== -1) {
        $record = $this->factory->getRecord($recordType)->load($recordId, Record::ALL_FIELDS);
      } else {
        if (is_null($record)) {
          throw new UserError("Either recordId or record must be provided");
        }
      }
    }
    return $taskFactory->createTask($this->factory, array(
      'type' => $taskType,
      'typeName' => $this->getTaskName($taskType),
      'recordType' => $recordType,
      'recordId' => $recordId,
      'record' => $record,
      'handlesResponse' => $taskFactory->handlesResponse()
    ));
  }

  public function loadDeferred($executionId, $expectedStatus='deferred') {
    $data = $this->factory->getRecord('executions')->load(
      $executionId,
      array('taskType', 'recordType', 'recordId', 'status', 'record'),
      array('parameters' => array('name', 'value'))
    );
    if ($data['status'] != $expectedStatus)
      throw new UserError("Task is not $expectedStatus");
    if (! is_null($data['record'])) {
      $data['record'] = json_decode($data['record'], true);
    }
    $parameters = array();
    foreach($data['parameters'] as $param) {
      $parameters[$param['name']] = $param['value'];
    }
    return array(
      'task' => $this->get($data),
      'parameters' => $parameters
    );
  }

  public function getTaskName($type) {
    if ($this->taskFactories->has($type)) {
      return $this->taskFactories->get($type)->getTaskName();
    }
    return $type;
  }

  public function getNames() {
    $definitions = array();
    foreach ($this->taskFactories->getKeys() as $key) {
      $task = $this->taskFactories->get($key);
      $definitions[$key] = array(
        'category' => $task->getCategory(),
        'type' => $key,
        'name' => $task->getTaskName(),
        'icon' => $task->getIcon(),
        'actionName' => $task->getActionName(),
        'recordTypes' => $task->getSupportedRecordTypes(),
        'alwaysConfirm' => $task->shouldAlwaysConfirm(),
        'handlesResponse' => $task->handlesResponse()
      );
    }
    return $definitions;
  }

  private function ensureNames() {
    $taskNames = array();
    $table = $this->factory->getServiceTable('task-type');
    $conn = $this->factory->getConnection();
    $res = $conn->query("SELECT type, name FROM $table");
    while ($row = $res->fetch()) {
      $taskNames[$row['type']] = $row['name'];
    }
    $insert = array();
    foreach ($this->taskFactories->getKeys() as $key) {
      $name = $this->getTaskName($key);
      if (! isset($taskNames[$key])) {
        array_push($insert, array(
          'type' => $key,
          'name' => $name
        ));
      }
    }
    if (count($insert) > 0) {
      $conn->insert($table, $insert);
    }
  }
}
