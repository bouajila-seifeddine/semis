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

class DeleteRecordService extends Service {

  public function __construct() {
    parent::__construct('delete-record');
  }

  public function process($factory, $request) {
    $type = $this->getParameter('type');
    $perm = $factory->getUser()->getPermissions();
    $perm->checkDelete($type);

    $keys = $this->getArrayParameter('key');
    $connection = $factory->getConnection();
    $recordType = $factory->getRecord($type);

    $rec = $recordType->load($keys, array('canWrite'));

    // check write restriction
    if (! $rec['canWrite']) {
      throw new PermissionError("delete", $factory->getDictionary()->getCollection($type)->getName());
    }

    if (in_array($type, array('lists', 'xmlTemplates', 'massUpdates'))) {
      $this->deleteTasks($factory, $type, $keys[0]);
    }
    if ($type === 'customFields') {
      return $factory->getCustomization()->deleteCustomField((int)$keys[0]);
    }
    return $recordType->delete($keys);
  }

  private function deleteTasks($factory, $recordType, $recordId) {
    $connection = $factory->getConnection();
    $endpoints = $factory->getServiceTable('endpoint');
    $schedules = $factory->getServiceTable('schedule');
    $recordType = $connection->escape($recordType);
    $recordId = (int)$recordId;
    $connection->execute("DELETE FROM $endpoints WHERE record_type='$recordType' AND record_id=$recordId");
    $connection->execute("DELETE FROM $schedules WHERE record_type='$recordType' AND record_id=$recordId");
  }
}
