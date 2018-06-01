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
require_once(DATAKICK_CORE.'tasks/csv-feed.php');

class CsvFeedTaskFactory extends TaskFactory {

  public function createTask(Factory $factory, Array $identity) {
    $rec = $identity['record'];
    $definition = $this->getObject($rec, 'parsed');
    $userParameters = isset($definition['userParameters']) ? $definition['userParameters'] : array();
    $userParameters = array_merge(array(
      'task::exportColumnNames' => $this->exportColumnNamesParameter(),
      'task::separator' => $this->separatorParameter(),
    ), $userParameters);
    $requiredParameters = array_merge(array(
      'task::exportColumnNames',
      'task::separator',
    ), $this->getObject($rec, 'requiredParameters'));
    return new CsvFeedTask($identity, $factory, $definition, $requiredParameters, $userParameters);
  }

  public function getTaskName() {
    return 'CSV feed';
  }

  public function getIcon() {
    return 'download';
  }

  public function handlesResponse() {
    return true;
  }

  public function getActionName() {
    return 'Download CSV file';
  }

  public function getSupportedRecordTypes() {
    return array('lists');
  }

  public function shouldAlwaysConfirm() {
    return false;
  }

  public function getCategory() {
    return 'export';
  }

  private function exportColumnNamesParameter() {
    return array(
      'type' => 'boolean',
      'description' => "Export column names",
      'default' => true
    );
  }

  private function separatorParameter() {
    return array(
      'type' => 'string',
      'description' => "Columns separator",
      'default' => 'comma',
      'values' => array(
        'comma' => "Comma",
        'semicolon' => 'Semicolon',
        'space' => 'Space',
        'tab' => 'Tabulator'
      )
    );
  }
}
