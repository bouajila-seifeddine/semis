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

abstract class Task {
    private $factory;
    private $type;
    private $result;
    private $executionId;
    private $start;
    private $origDuration = 0;
    private $duration;
    private $error;
    private $fullError;
    private $typeName;
    private $recordType;
    private $recordId;
    private $handlesResponse;
    private $handledResponse;

    public function __construct($factory, $identity) {
      $this->factory = $factory;
      $this->type = $identity['type'];
      $this->typeName = $identity['typeName'];
      $this->recordType = $identity['recordType'];
      $this->recordId = $identity['recordId'];
      $this->record = $identity['record'];
      $this->handlesResponse = $identity['handlesResponse'];
      $this->handledResponse = false;
    }

    public function getFactory() {
      return $this->factory;
    }

    public function getType() {
      return $this->type;
    }

    public function getName() {
      if (isset($this->record['name']))
        return $this->record['name'];
      return $this->getTypeName();
    }

    public function getFilename($ext) {
      $name = strtolower($this->getName());
      $name = preg_replace('/[^a-z0-9_]+/', '_', $name);
      return ltrim(preg_replace('/__*/', '_', $name), '_') . ".$ext";
    }

    public function getTypeName() {
      return $this->typeName;
    }

    public function getRecordType() {
      return $this->recordType;
    }

    public function getRecordTypeName() {
      $recordType = $this->recordType;
      if ($recordType) {
        return $this->factory->getDictionary()->getCollection($recordType)->getName();
      }
      return null;
    }

    public function getRecordId() {
      return $this->recordId;
    }

    public function getStatus($full=false, $includeFull=false) {
      $data = array(
        'task' => $this->type,
        'status' => $this->status,
        'duration' => $this->duration,
        'error' => $this->error
      );
      if ($this->result) {
        $data['result'] = $this->result;
      }
      if ($full && $this->fullError) {
        $data['error'] = $this->fullError;
      }
      if ($includeFull) {
        $data['fullError'] = $this->fullError;
      }
      return $data;
    }

    public function getRequiredParameters() {
      return array();
    }

    public function getUserParameters() {
      return array();
    }

    public function prepare(Context $context, $status, $executionId) {
      $this->status = $status;
      $this->executionId = $executionId;
      $this->start = microtime(true);
      $this->updateExecution();
      $this->logParameters($context, $executionId);
    }

    public function execute(Context $context, Progress $progress, $id=null) {
      $shutdown = $this->factory->getShutdownHandler();
      $shutdown->push($this);
      $orig = set_error_handler(array($this, 'errorHandler'));
      $this->source = $context->getValue('executionSource');
      $cleanup = true;
      $this->start = microtime(true);
      try {
        $resumeState = null;
        if (! $id) {
          $this->executionId = $context->getValue('executionId');
          $this->prepare($context, "running", $this->executionId);
        } else {
          $this->executionId = $id;
          $this->status = "running";
          $this->loadExisting($id);
          $resumeState = $this->result;
          $this->updateExecution();
        }
        if ($this->factory->trialEnded()) {
          throw new UserError("Datakick trial period ended");
        }
        $this->handledResponse = true;
        $this->status = "running";
        $this->result = $this->doExecute($context, $progress, $this->executionId, $resumeState);
        $error = $this->getErrorFromResult($this->result);
        if ($error) {
          $this->status = 'failed';
          $this->error = $error;
          $this->fullError = $error;
        } else {
          $this->status = 'success';
        }
      } catch (TaskControl $e) {
        if ($e->impactStatus()) {
          $this->setError($e, true);
          $error = $e->getTaskError();
          if ($error) {
            $this->error = $error;
          }
          $this->status = $e->getTaskStatus();
        } else {
          $cleanup = false;
        }
      } catch (UserError $e) {
        $this->setError($e, true);
        $this->status = 'failed';
      } catch(\Exception $e) {
        $this->status = 'failed';
        $this->setError($e, false);
      }
      if ($cleanup) {
        $this->updateExecution();
        $this->cleanup($this->executionId);
        $this->trackExecution();
      }
      set_error_handler($orig);
      $shutdown->pop($this);
      return $this->status === 'success';
    }

    private function trackExecution() {
      try {
        $si = $this->factory->getSiteInfo();
        $this->factory->callApi('execution', array(
          'id' => $si['id'],
          'source' => $this->source,
          'task' => array(
            'name' => $this->getName(),
            'type' => $this->type,
            'recordType' => $this->recordType,
            'recordId' => $this->recordId,
          ),
          'status' => $this->status,
          'duration' => $this->duration,
          'error' => $this->fullError ? $this->fullError : null,
          'result' => $this->result ? $this->transformResultForTracking($this->result) : null
        ));
      } catch(\Exception $ignored) {
        // ignored
      }
    }

    private function logParameters($context, $executionId) {
      $params = array();
      foreach ($this->getRequiredParameters() as $param) {
        if ($context->getProvideMethod($param) == 'set') {
          array_push($params, array(
            'execution_id' => $executionId,
            'name' => $param,
            'value' => $context->getValue($param)
          ));
        }
      }

      if (count($params) > 0) {
        $connection = $this->factory->getConnection();
        $dataTable = $this->factory->getServiceTable('execution-parameters');
        $connection->insert($dataTable, $params);
      }
    }

    public function updateExecution() {
      if ($this->status !== 'killed') {
        $this->checkIfRunning();
      }
      $table = $this->factory->getServiceTable('executions');
      $connection = $this->factory->getConnection();
      $saveRecord = ($this->recordId === -1) || ($this->status === 'deferred');
      $this->duration = (microtime(true) - $this->start) + $this->origDuration;
      return $connection->update($table, array(
        'name' => $this->getName(),
        'task_type' => $this->type,
        'record_type' => $this->recordType,
        'record_id' => $this->recordId,
        'record' => $saveRecord ? json_encode($this->record) : null,
        'status' => $this->status,
        'duration' => $this->duration,
        'error' => $this->error ? $this->error : null,
        'full_error' => $this->fullError ? $this->fullError : null,
        'result' => $this->getResult(),
        'user_id' => $this->factory->getUser()->getId(),
        'last_updated' => array(
          'value' => 'NOW()',
          'literal' => false
        )
      ), array(
        'id' => $this->executionId
      ));
    }

    public function updateResult($result) {
      $this->result = $result;
      $this->updateExecution();
    }

    public function hibernate() {
      $this->status = 'paused';
      $this->updateExecution();
      $url = $this->factory->getResumeUrl($this->executionId);
      $this->factory->fetch($url)->execute();
      TaskControl::hibernate();
    }

    public function checkIfRunning() {
      if ($this->isKilled()) {
        TaskControl::kill();
      }
    }

    private function isKilled() {
      $data = $this->factory->getRecord('executions')->load($this->executionId, array('status'));
      return $data['status'] == 'killed';
    }

    private function getResult() {
      if ($this->result) {
        return json_encode($this->result);
      }
      return null;
    }

    private function setError(\Exception $e, $userError) {
      $this->status = 'failed';
      $this->fullError = $e->__toString();
      $this->error = $userError ? $e->getMessage() : "There as been an error";
    }

    public function getTaskInfo($context, $executionId) {
      $ret = array(
        'taskName' => $this->getName(),
        'taskType' => $this->type,
        'recordType' => $this->recordType,
        'recordTypeName' => $this->getRecordTypeName(),
        'recordId' => $this->recordId,
        'status' => $this->status,
        'duration' => $this->duration,
        'executionId' => $executionId,
      );
      foreach ($this->factory->getSystemParameters() as $key => $param) {
        if (! isset($ret[$key])) {
          $val = $context->getValue($key);
          if (Types::isDateTime($param['type'])) {
            $val = $val->format('Y-m-d H:i:s');
          } else {
            $val = (string)$val;
          }
          if (isset($param['values'])) {
            $ret[$key . 'Value'] = $param['values'][$val];
          }
          $ret[$key] = $val;
        }
      }
      return $ret;
    }

    /**
     * returns true, if task writes directly to std output
     */
    public function handlesResponse() {
      return $this->handlesResponse;
    }

    public function handledResponse() {
      return $this->handlesResponse && $this->handledResponse;
    }

    public function isResumable() {
      return false;
    }

    public function cleanup($executionId) {
      // no-op
    }

    // PHP error handler to be used for detecting fatal errors
    public function errorHandler($errno, $errstr, $file, $lineNo) {
      if ($errno == E_USER_ERROR) {
        $this->status = 'failed';
        $this->fullError = $errstr . " in " . $file . " on line " . $lineNo;
        $this->error = 'There has been an error';
        $this->updateExecution();
        $this->trackExecution();
      }
      return false;
    }

    public function onShutdown() {
      $error = error_get_last();
      if ($error && $error['type'] === E_ERROR) {
        return $this->errorHandler(E_USER_ERROR, $error['message'], $error['file'], $error['line']);
      }
      $this->fullError = 'Task has been interrupted';
      $this->error = 'Task has been interrupted';
      $this->status = 'failed';
      $this->updateExecution();
      $this->trackExecution();
    }

    private function loadExisting($id) {
      $data = $this->factory->getRecord('executions')->load($id, array('duration', 'result'));
      if ($data['result']) {
        $this->result = json_decode($data['result'], true);
      }
      if (! is_null($data['duration'])) {
        $this->origDuration = (float)$data['duration'];
        $this->duration = $this->origDuration;
      }
    }

    protected function transformResultForTracking($result) {
      return $result;
    }

    protected function getErrorFromResult($result) {
      return null;
    }

    public abstract function doExecute(Context $context, Progress $progress, $executionId, $resumeState);
}
