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
require_once(DATAKICK_CORE.'tasks/xml-feed.php');

class XmlFeedTaskFactory extends TaskFactory {

  public function createTask(Factory $factory, Array $identity) {
    $rec = $identity['record'];

    $template = $this->getObject($rec, 'parsed');
    $userParameters = $this->getObject($rec, 'userParameters');
    $requiredParameters = $this->getObject($rec, 'requiredParameters');

    $output = new XmlOutputStream(fopen('php://output', 'w'));
    return new XmlFeedTask($identity, $factory, $template, $requiredParameters, $userParameters, $output);
  }

  public function getTaskName() {
    return 'XML feed';
  }

  public function getIcon() {
    return 'download';
  }

  public function getActionName() {
    return 'Download XML file';
  }

  public function handlesResponse() {
    return true;
  }

  public function getSupportedRecordTypes() {
    return array('xmlTemplates');
  }

  public function shouldAlwaysConfirm() {
    return false;
  }

  public function getCategory() {
    return 'export';
  }

}
