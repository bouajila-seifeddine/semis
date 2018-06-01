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
require_once(DATAKICK_CORE.'tasks/csv-file.php');

class CsvFileTaskFactory extends TaskFactory {

  public function createTask(Factory $factory, Array $identity) {
    $rec = $identity['record'];
    $template = $this->getObject($rec, 'parsed');
    $userParameters = isset($template['userParameters']) ? $template['userParameters'] : array();
    $userParameters = array_merge(array(
      'task::exportColumnNames' => $this->exportColumnNamesParameter(),
      'task::separator' => $this->separatorParameter(),
      'task::placeId' => $this->placeParameter($factory),
      'task::outputPath' => $this->outputPathParameter()
    ), $userParameters);
    $requiredParameters = array_merge(array(
      'task::exportColumnNames',
      'task::separator',
      'task::placeId',
      'task::outputPath'
    ), $this->getObject($rec, 'requiredParameters'));
    return new CsvFileTask($identity, $factory, $template, $requiredParameters, $userParameters);
  }

  public function getTaskName() {
    return 'CSV file';
  }

  public function getActionName() {
    return 'Generate CSV file';
  }

  public function getSupportedRecordTypes() {
    return array('lists');
  }

  public function getCategory() {
    return 'export';
  }

  public function getIcon() {
    return 'run';
  }

  private function placeParameter($factory) {
    $ret = array(
      'type' => 'number',
      'description' => "Destination",
      'selectRecord' => 'places'
    );
    $def = $factory->getRecord('places')->loadFirst();
    if ($def)
      $ret['default'] = $def['id'];
    return $ret;
  }

  private function outputPathParameter() {
    return array(
      'type' => 'string',
      'description' => "Output file",
      'default' => "{normalized}.csv"
    );
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
      'default' => 'comma'
    );
  }
}
