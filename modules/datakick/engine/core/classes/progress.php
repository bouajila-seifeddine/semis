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

class Progress {

  // save progress every second
  const SAVE_INTERVAL = 1;
  const CHECK_INTERVAL = 1;

  private $webRequest;
  private $task;

  // set_time_limit state
  private $initTime;
  private $maxExecTime = 30;
  private $refreshTime = 20;
  private $shouldHibernate = false;

  // track progress state
  private $lastSaved;

  // track last kill check
  private $lastChecked;

  public function  __construct($webRequest=true, $task=null) {
    $this->webRequest = $webRequest;
    $this->task = $task;
    if ($webRequest) {
      $maxExecTime = @ini_get('max_execution_time');
      if (! is_numeric($maxExecTime)) {
        $this->maxExecTime = $maxExecTime;
        $this->refreshTime = max(5, $maxExecTime-10);
      }
      $this->initTime = microtime(true);
      @set_time_limit($this->maxExecTime);
    }
    if ($task) {
      $this->lastSaved = microtime(true);
      $this->lastChecked = microtime(true);
    }
  }

  public function start($name) {
    $this->trackTaskProgress();
  }

  public function end() {
    $this->trackTaskProgress();
  }

  public function setProgress($totalItems, $done, $taskState=null) {
    $task = $this->task;
    $this->trackTaskProgress();

    // extend session timeout on time shortage
    if ($this->timeShortage()) {
      $this->initTime = microtime(true);
      @set_time_limit($this->maxExecTime);

      // if this is resumable task, we will terminate its execution on next $taskState event
      if ($task && $task->isResumable()) {
        $this->shouldHibernate = true;
      }
    }

    // $taskState event - task progress to next resumable state. This is a joint point
    // for resumable functionality
    if ($task && !is_null($taskState)) {
      $task->updateResult($taskState);
      $this->lastSaved = microtime(true);
      // hibernate only when $taskState change events
      if ($this->shouldHibernate) {
        $task->hibernate();
      }
    }

  }

  private function timeShortage() {
    if ($this->webRequest) {
      $diff = microtime(true) - $this->initTime;
      return ($diff >= $this->refreshTime);
    }
    return false;
  }

  private function trackTaskProgress() {
    if ($this->task) {
      $task = $this->task;
      $now = microtime(true);

      // check if this task still runs
      $diff = $now - $this->lastChecked;
      if ($diff >= self::CHECK_INTERVAL) {
        $task->checkIfRunning();
        $this->lastChecked = microtime(true);
      }

      // update task last_updated
      $diff = $now - $this->lastSaved;
      if ($diff >= self::SAVE_INTERVAL) {
        $task->updateExecution();
        $this->lastSaved = microtime(true);
      }
    }
  }
}
