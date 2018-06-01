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

class TaskControl extends \Exception {
  private $status;
  private $error;

  public function __construct($status, $error=null) {
    parent::__construct("Task control: $status");
    $this->status = $status;
    $this->error = $error;
  }

  public function impactStatus() {
    return $this->status != 'hibernate';
  }

  public function getTaskStatus() {
    return $this->status;
  }

  public function getTaskError() {
    return $this->error;
  }

  public static function hibernate() {
    throw new TaskControl('hibernate');
  }

  public static function kill() {
    throw new TaskControl('killed', "Task has been killed");
  }

}
