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

class SetOptionService extends Service {

  public function __construct() {
    parent::__construct('set-option');
  }

  public function process($factory, $request) {
    $value = $this->getParameter('value');
    $keys = array(
      'name' => $this->getParameter('name'),
      'user_id' => $factory->getUser()->getId()
    );

    if ($this->getParameterWithDefault('global', false)) {
      $factory->getUser()->getPermissions()->checkAdmin();
      $keys['user_id'] = -1;
    }

    $table = $factory->getServiceTable('options');
    $conn = $factory->getConnection();

    if (is_null($value) || $value === '') {
      $conn->delete($table, $keys);
    } else {
      // try to convert type to make sure it's valid
      $type = $this->getParameter('type');
      $value = Types::serialize($type, Types::convertValue($type, $value, false));

      $values = array(
        'type' => $type,
        'value' => $value
      );
      return $conn->insertUpdate($table, $keys, $values);
    }

  }
}
