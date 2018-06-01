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

class Scheduler {
  private $factory;
  public function __construct($factory) {
    $this->factory = $factory;
  }

  public function saveSchedule($scheduleId, $name, $task, $parameters, $startAt, $frequency, $active=true, $userId=null) {
    $factory = $this->factory;
    $conn = $factory->getConnection();
    $table = $factory->getServiceTable('schedule');
    $data = array(
      'name' => $name,
      'task_type' => $task['taskType'],
      'record_type' => isset($task['recordType']) ? $task['recordType'] : null,
      'record_id' => isset($task['recordId']) ? $task['recordId'] : null,
      'frequency' => $frequency,
      'next' => $startAt->format("Y-m-d H:i:s"),
      'active' => (bool)$active
    );

    $perm = $factory->getUser()->getPermissions();
    if (! $scheduleId) {
      $perm->checkCreate('schedules');
      $data['user_id'] = $factory->getUser()->getId();
      $scheduleId = $conn->insert($table, $data);
    } else {
      $perm->checkEdit('schedules');
      if (! is_null($userId)) {
        $perm->checkAdmin();
        $data['user_id'] = (int)$userId;
      }
      $conn->update($table, $data, array('id' => $scheduleId));
      $this->deleteData($scheduleId);
    }
    $this->insertData($scheduleId, $parameters);
    return $scheduleId;
  }

  private function insertData($id, $data) {
    if (count($data) > 0) {
      $connection = $this->factory->getConnection();
      $dataTable = $this->factory->getServiceTable('schedule-parameter');
      $values = array();
      foreach ($data as $name => $value) {
        array_push($values, array(
          'schedule_id' => $id,
          'name' => $name,
          'value' => $value
        ));
      }
      $connection->insert($dataTable, $values);
    }
  }

  public function deleteData($id) {
    $connection = $this->factory->getConnection();
    $dataTable = $this->factory->getServiceTable('schedule-parameter');
    $connection->delete($dataTable, array('schedule_id' => $id));
  }

  public function deleteSchedule($scheduleId) {
    $this->factory->getUser()->getPermissions()->checkDelete('schedules');
    $this->factory->getRecord("schedules")->delete($scheduleId);
  }

  public function cronEvent($cronName, $webCron) {
    if (! $this->factory->trialEnded()) {
      $id = $cronName . '-' . time();
      $allowed = $this->markCron($cronName);
      if ($allowed) {
        // use `for` cycle to avoid (possible) neverending loop
        // we will handle max 1000 tasks per single cron execution -- that should be
        // more than enough
        for ($i=0; $i<1000; $i++) {
          $exception = null;
          $task = null;
          try {
            $task = $this->grabTask($id);
            if (! $task) {
              return $this->deactiveCurrentTask($cronName);
            }
            $userId = (int)$task['userId'];
            $this->factory->substituteUser($userId);
            $context = $this->factory->getContext('schedule', $task['id']);
            $exception = null;
            $this->executeTask($context, $task, $webCron);
          } catch (\Exception $e) {}
          if ($task) {
            $this->releaseTask($task, $context);
          } else {
            $this->deactiveCurrentTask($id);
          }
        }
        throw new UserError("too many tasks");
      }
    }
  }

  private function deactiveCurrentTask($id) {
    $conn = $this->factory->getConnection();
    $table = $this->factory->getServiceTable('schedule');
    $sql = "UPDATE $table SET processing = NULL, active=0 WHERE processing = '$id'";
    $conn->execute($sql);
  }

  private function grabTask($id) {
    $conn = $this->factory->getConnection();
    $table = $this->factory->getServiceTable('schedule');
    $now = date("Y-m-d H:i:s");
    $sql = "UPDATE $table SET processing = '$id' WHERE processing IS NULL AND active=1 AND next <= '$now' ORDER BY next LIMIT 1";
    $conn->execute($sql);
    $schedules = $this->factory->getRecord("schedules");
    $schedule = $schedules->loadBy(
      array('processing' => $id, 'active' => true),
      array('id', 'typeId', 'recordType', 'recordId', 'next', 'frequency', 'userId'),
      array('parameters' => array('name', 'value')),
      false
    );
    return $schedule;
  }

  private function releaseTask($task, $context) {
    $conn = $this->factory->getConnection();
    $table = $this->factory->getServiceTable('schedule');
    $minDate = new \DateTime();
    $minDate->modify('1 minute');
    $next = self::getNextDate($task['next'], $task['frequency'], $minDate)->format("Y-m-d H:i:s");
    $now = $context->getValue('timestamp')->format('Y-m-d H:i:s');
    $executionId = $context->getValue('executionId');
    $sql = "UPDATE $table SET processing = NULL, next='$next', last='$now', last_execution_id=$executionId WHERE id=".$task['id'];
    $conn->execute($sql);
  }

  private function executeTask($context, $schedule, $webCron) {
    $taskDef = array(
      'taskType' => $schedule['typeId'],
      'recordType' => $schedule['recordType'],
      'recordId' => $schedule['recordId']
    );
    $parameters = array();
    foreach($schedule['parameters'] as $par) {
      $parameters[$par['name']] = $par['value'];
    }
    $task = $this->factory->getTasks()->get($taskDef);
    $context->setUserParameters($task->getUserParameters());
    $context->setValues($parameters);
    $progress = new Progress($webCron, $task);
    $task->execute($context, $progress);
  }

  private function markCron($cronName) {
    $conn = $this->factory->getConnection();
    $table = $this->factory->getServiceTable('cron-type');
    $cron = $conn->escape($cronName);
    $res = $conn->query("SELECT * FROM $table WHERE cron='$cron'");
    $ret = $res->fetch();
    if ($ret == false) {
      $conn->insert($table, array('cron' => $cronName, 'active' => 0, 'history' =>time()));
      return false;
    }
    $history = $ret['history'];
    $ts = time();
    $split = array();
    if ($history) {
      $split = explode(',', $history);
    }
    array_push($split, $ts);
    $history = implode(',', array_slice($split, -10));
    $conn->update($table, array(
      'history' => $history,
      'last' => date("Y-m-d H:i:s", $ts)
    ), array('cron' => $cronName));
    return $ret['active'];
  }

  private function isCronAllowed($cronName) {
    return true;
  }

  public static function getNextDate($lastDate, $freq, $minNextDate) {
    if (! in_array($freq, array('hour', 'day', 'week', 'month', 'year'))) {
      throw new UserError("Unknown frequency: $freq");
    }
    $ret = $lastDate->modify('1 '.$freq);
    return $ret >= $minNextDate ? $ret : self::getNextDate($ret, $freq, $minNextDate);
  }

}
