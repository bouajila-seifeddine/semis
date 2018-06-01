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
require_once(DATAKICK_CORE.'tasks/xml-file.php');

class XmlFileTaskFactory extends TaskFactory {

  public function createTask(Factory $factory, Array $identity) {
    $rec = $identity['record'];

    $template = $this->getObject($rec, 'parsed');
    $userParameters = array_merge(array(
      'task::placeId' => $this->placeParameter($factory),
      'task::outputPath' => $this->outputPathParameter()
    ), $this->getObject($rec, 'userParameters'));
    $requiredParameters = array_merge(array(
      'task::placeId',
      'task::outputPath'
    ), $this->getObject($rec, 'requiredParameters'));


    return new XmlFileTask($identity, $factory, $template, $requiredParameters, $userParameters);
  }

  public function getTaskName() {
    return 'XML file';
  }

  public function getIcon() {
    return 'run';
  }

  public function getActionName() {
    return 'Generate XML file';
  }

  public function getSupportedRecordTypes() {
    return array('xmlTemplates');
  }

  public function getCategory() {
    return 'export';
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
      'default' => "{normalized}.xml"
    );
  }
}
