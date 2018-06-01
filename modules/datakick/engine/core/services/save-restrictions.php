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

class SaveRestrictionsService extends Service {

  public function __construct() {
    parent::__construct('save-restrictions');
  }

  public function process($factory, $request) {
    // only admin can change permissions
    $factory->getUser()->getPermissions()->checkAdmin();
    $dictionary = $factory->getDictionary();

    $keyColumn = 'role_id';
    $table = $factory->getServiceTable('role-restrictions');
    $key = $this->getParameter('role', false);
    if (is_null($key)) {
      $keyColumn = 'user_id';
      $table = $factory->getServiceTable('user-restrictions');
      $key = $this->getParameter('user', false);
      if (is_null($key)) {
        throw new UserError("Either 'role' or 'user' parameter must be specified");
      }
    }
    $key = (int)Types::convertValue('number', $key);

    $restrictions = $this->getArrayParameter('restrictions');
    $data = array();
    $types = $factory->getRestrictionTypes();
    foreach ($restrictions as $restriction => $capabilities) {
      if (! $types->has($restriction)) {
        throw new UserError("Unknown restriction type '$restriction'");
      }
      $type = $types->get($restriction);
      $levels = $type->getLevels();
      $read = $this->getLevel($restriction, $levels, $capabilities, 'read');
      $write = $this->getLevel($restriction, $levels, $capabilities, 'write');
      $data[] = array(
        $keyColumn => $key,
        'restriction' => $restriction,
        'read' => $read,
        'write' => $write
      );
    }

    $conn = $factory->getConnection();
    $conn->delete($table, array($keyColumn => $key));
    $conn->insert($table, $data);
    return true;
  }

  private function getLevel($restriction, $levels, $caps, $type) {
    $level = isset($caps[$type]) ? $caps[$type] : '';
    if (! isset($levels[$level])) {
      throw new UserError("Restriction '$restriction': invalid $type level '$level'");
    }
    return $level;
  }

}
