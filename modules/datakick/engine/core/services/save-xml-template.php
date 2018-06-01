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

class SaveXmlTemplateService extends Service {

  public function __construct() {
    parent::__construct('save-xml-template');
  }

  public function process($factory, $request) {
    $id = $this->getIdParameter();
    $name = $this->getParameter('name');
    $template = $this->getArrayParameter('template');
    $parsed = $this->getArrayParameter('parsed');
    $userParameters = $this->getArrayParameter('userParameters');
    $requiredParameters = $this->getArrayParameter('requiredParameters');
    $table = $factory->getServiceTable('xml-templates');
    $connection = $factory->getConnection();
    $data = array(
      'name' => $name,
      'template' => json_encode($template),
      'parsed' => json_encode($parsed),
      'user_parameters' => json_encode($userParameters),
      'required_parameters' => json_encode($requiredParameters),
      'image' => $this->getParameter('image', false),
      'icon' => $this->getParameter('icon', false),
      'description' => $this->getParameter('description', false),
      'public' => $this->getParameterWithDefault('public', true)
    );
    if (! $id) {
      $factory->getUser()->getPermissions()->checkCreate('xmlTemplates');
      $data['user_id'] = $factory->getUser()->getId();
      $id = $connection->insert($table, $data);
    } else {
      $factory->getUser()->getPermissions()->checkEdit('xmlTemplates');
      $key = "id = $id";
      $connection->update($table, $data, $key);
    }
    return $id;
  }
}
