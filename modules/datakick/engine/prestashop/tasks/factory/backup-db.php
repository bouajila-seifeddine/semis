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
require_once(DATAKICK_PRESTASHOP.'tasks/backup-db.php');

class PrestashopBackupDbFactory extends TaskFactory {
  public function createTask(Factory $factory, Array $identity) {
    return new PrestashopBackupDbTask($factory, $identity);
  }

  public function getTaskName() {
    return 'Backup Database';
  }

  public function getIcon() {
    return 'backup';
  }

  public function getCategory() {
    return 'platform';
  }

  public function getActionName() {
    return 'Create database backup';
  }

  public function getSupportedRecordTypes() {
    return array();
  }

}
