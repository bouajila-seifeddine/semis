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
require_once(DATAKICK_CORE.'tasks/import.php');

class ImportTaskFactory extends TaskFactory {
  public function createTask(Factory $factory, Array $identity) {
    return new ImportTask($identity, $factory);
  }

  public function getTaskName() {
    return 'Import data';
  }

  public function getActionName() {
    return 'Import data';
  }

  public function getSupportedRecordTypes() {
    return array('importDatasources');
  }

  public function getCategory() {
    return 'import';
  }

  public function getIcon() {
    return 'upload';
  }
}
