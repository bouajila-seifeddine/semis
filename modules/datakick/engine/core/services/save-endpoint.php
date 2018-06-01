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

class SaveEndpointService extends Service {

  public function __construct() {
    parent::__construct('save-endpoint');
  }

  public function process($factory, $request) {
    $id = $this->getIdParameter();
    $taskDef = $this->getArrayParameter('task');
    $endpoint = $this->getParameter('endpoint');
    $name = $this->getParameter('name');
    $parameters = $this->getArrayParameter('parameters');
    $active = $this->getParameterWithDefault('active', true);

    $table = $factory->getServiceTable('endpoint');
    $connection = $factory->getConnection();

    $data = array(
      'task_type' => $taskDef['taskType'],
      'record_type' => isset($taskDef['recordType']) ? $taskDef['recordType'] : null,
      'record_id' => isset($taskDef['recordId']) ? $taskDef['recordId'] : null,
      'name' => $name,
      'endpoint' => $endpoint,
      'active' => (bool)$active
    );

    $perm = $factory->getUser()->getPermissions();
    if (! $id) {
      $perm->checkCreate('endpoints');
      $data['endpoint'] = $this->getEndpoint($factory, $endpoint);
      $data['user_id'] = $factory->getUser()->getId();
      $id = $connection->insert($table, $data);
    } else {
      $perm->checkEdit('endpoints');
      $userId = $this->getParameter('userId', false);
      if (! is_null($userId)) {
        $perm->checkAdmin();
        $data['user_id'] = (int)$userId;
      }
      $connection->update($table, $data, array('id' => $id));
      $this->deleteData($factory, $id);
    }
    $this->insertData($factory, $id, $parameters);
    return $id;
  }

  public function getEndpoint($factory, $start) {
    $endpoint = $start;
    $endpoints = $factory->getRecord('endpoints');
    for ($i=1; $i<99; $i++) {
      if (! $endpoints->exists(array('endpoint' => $endpoint))) {
        return $endpoint;
      }
      $endpoint = $start . '-' .$i;
    }
    throw new \Exception("Failed to generate endpoint");
  }

  public function insertData($factory, $id, $data) {
    if (count($data) > 0) {
      $connection = $factory->getConnection();
      $dataTable = $factory->getServiceTable('endpoint-parameter');
      $values = array();
      foreach ($data as $name => $def) {
        $value = isset($def['value']) ? $def['value'] : null;
        $param = isset($def['param']) ? $def['param'] : null;
        array_push($values, array(
          'endpoint_id' => $id,
          'name' => $name,
          'param' => $param,
          'value' => $value
        ));
      }
      $connection->insert($dataTable, $values);
    }
  }

  public function deleteData($factory, $id) {
    $connection = $factory->getConnection();
    $dataTable = $factory->getServiceTable('endpoint-parameter');
    $connection->delete($dataTable, array('endpoint_id' => $id));
  }
}
