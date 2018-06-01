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

class SaveImportDefinitionService extends Service {

  public function __construct() {
    parent::__construct('save-import-definition');
  }

  public function process($factory, $request) {
    $id = $this->getIdParameter();
    $name = $this->getParameter('name');
    $definition = $this->getArrayParameter('definition');
    $parsed = $this->getArrayParameter('parsed');
    $table = $factory->getServiceTable('import-definition');
    $connection = $factory->getConnection();
    $data = array(
      'name' => $name,
      'definition' => json_encode($definition),
      'parsed' => json_encode($parsed),
      'image' => $this->getParameter('image', false),
      'icon' => $this->getParameter('icon', false),
      'description' => $this->getParameter('description', false),
      'public' => $this->getParameterWithDefault('public', true)
    );
    if (! $id) {
      $factory->getUser()->getPermissions()->checkCreate('importDefinitions');
      $data['user_id'] = $factory->getUser()->getId();
      $id = $connection->insert($table, $data);
    } else {
      $factory->getUser()->getPermissions()->checkEdit('importDefinitions');
      $key = "id = $id";
      $connection->update($table, $data, $key);
    }
    return $id;
  }
}
