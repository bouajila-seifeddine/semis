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

class SaveScheduleService extends Service {

  public function __construct() {
    parent::__construct('save-schedule');
  }

  public function process($factory, $request) {
    $id = $this->getIdParameter();
    $taskDef = $this->getArrayParameter('task');
    $name = $this->getParameter('name');
    $active = $this->getParameterWithDefault('active', true);
    $frequency = $this->getParameter('frequency');
    $next = $this->getDateParameter('next');
    $parameters = $this->getArrayParameter('parameters');
    $userId = $this->getParameter('userId', false);
    return $factory->getScheduler()->saveSchedule($id, $name, $taskDef, $parameters, $next, $frequency, $active, $userId);
  }

}
