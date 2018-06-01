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

class ShutdownHandler {
  private $tasks = array();

  public function __construct() {
    register_shutdown_function(array($this, "onShutdown"));
  }

  public function onShutdown() {
    foreach ($this->tasks as $task) {
      $task->onShutdown();
    }
  }

  public function push(Task $task) {
    array_push($this->tasks, $task);
  }

  public function pop() {
    array_pop($this->tasks);
  }
}
