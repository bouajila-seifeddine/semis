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
require_once(DATAKICK_CORE.'tasks/mass-update.php');

class MassUpdateTaskFactory extends TaskFactory {
  public function createTask(Factory $factory, Array $identity) {
    $rec = $identity['record'];
    $definition = $this->getObject($rec, 'parsed');
    if ($definition) {
      $userParameters = $this->getObject($definition, 'userParameters');
      $requiredParameters = $this->getObject($rec, 'requiredParameters');
      return new MassUpdateTask($identity, $factory, $definition, $requiredParameters, $userParameters);
    }
    throw new UserError('Failed to process Mass Update record');
  }

  public function getTaskName() {
    return 'Mass update';
  }

  public function getActionName() {
    return 'Execute mass update';
  }

  public function getSupportedRecordTypes() {
    return array('massUpdates');
  }

  public function getCategory() {
    return 'modification';
  }

  public function getIcon() {
    return 'edit';
  }
}
