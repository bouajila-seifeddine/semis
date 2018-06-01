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

class EnableCronService extends Service {

  public function __construct() {
    parent::__construct('enable-cron');
  }

  public function process($factory, $request) {
    $factory->getUser()->getPermissions()->checkEdit('crons');
    $cron = $this->getParameter('cron');
    $enabled = !!$this->getParameter('enabled');

    $table = $factory->getServiceTable('cron-type');
    $conn = $factory->getConnection();
    $conn->update($table, array('active' => $enabled), array('cron' => $cron));
    return $enabled;
  }
}
