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

class SavePlaceService extends Service {

  public function __construct() {
    parent::__construct('save-place');
  }

  public function process($factory, $request) {
    $id = $this->getIdParameter();
    $table = $factory->getServiceTable('place');
    $connection = $factory->getConnection();
    $data = array(
      'name' => $this->getParameter('name'),
      'type' => $this->getParameter('type')
    );
    $config = $this->getArrayParameter('config');
    if (! $id) {
      $factory->getUser()->getPermissions()->checkCreate('places');
      $id = $connection->insert($table, $data);
    } else {
      $factory->getUser()->getPermissions()->checkEdit('places');
      $connection->update($table, $data, array( 'id' => $id ));
      $this->deleteData($factory, $id);
    }
    $this->insertData($factory, $id, $config);
    return $id;
  }

  public function insertData($factory, $id, $data) {
    if (count($data) > 0) {
      $connection = $factory->getConnection();
      $dataTable = $factory->getServiceTable('place-config');
      $values = array();
      foreach ($data as $name => $value) {
        array_push($values, array(
          'place_id' => $id,
          'name' => $name,
          'value' => $value
        ));
      }
      $connection->insert($dataTable, $values);
    }
  }

  public function deleteData($factory, $id) {
    $connection = $factory->getConnection();
    $dataTable = $factory->getServiceTable('place-config');
    $connection->delete($dataTable, array('place_id' => $id));
  }

}
