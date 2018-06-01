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

abstract class TaskFactory {
  abstract function createTask(Factory $factory, Array $identity);

  abstract function getTaskName();

  abstract function getIcon();

  abstract function getCategory();

  public function getActionName() {
    return 'Execute '.$this->getTaskName();
  }

  public function getSupportedRecordTypes() {
    return array();
  }

  public function shouldAlwaysConfirm() {
    return true;
  }

  public function handlesResponse() {
    return false;
  }

  protected function getObject($rec, $name) {
    if (isset($rec[$name])) {
      $value = $rec[$name];
      if (is_string($value))
        return json_decode($value, true);
      return $value;
    }
    return array();
  }
}
