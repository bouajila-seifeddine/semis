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

class SavePermissionsService extends Service {

  public function __construct() {
    parent::__construct('save-permissions');
  }

  public function process($factory, $request) {
    // only admin can change permissions
    $factory->getUser()->getPermissions()->checkAdmin();
    $dictionary = $factory->getDictionary();

    $keyColumn = 'role_id';
    $table = $factory->getServiceTable('role-permissions');
    $key = $this->getParameter('role', false);
    if (is_null($key)) {
      $keyColumn = 'user_id';
      $table = $factory->getServiceTable('user-permissions');
      $key = $this->getParameter('user', false);
      if (is_null($key)) {
        throw new UserError("Either 'role' or 'user' parameter must be specified");
      }
    }
    $key = (int)Types::convertValue('number', $key);

    $permissions = $this->getArrayParameter('permissions');
    $data = array();
    foreach ($permissions as $permission => $capabilities) {
      if ($permission != 'datakick' && !$dictionary->hasCollection($permission)) {
        throw new UserError("Unknown permission '$permission'");
      }
      $entry = array(
        $keyColumn => $key,
        'permission' => $permission,
        'view' => false,
        'edit' => false,
        'create' => false,
        'delete' => false,
      );
      foreach ($capabilities as $cap) {
        $this->verifyCapability($cap);
        $entry[$cap] = true;
      }
      $data[] = $entry;
    }

    $conn = $factory->getConnection();
    $conn->delete($table, array($keyColumn => $key));
    $conn->insert($table, $data);
    return true;
  }

  private function verifyCapability($cap) {
    if (! in_array($cap, array("view", "edit", "create", "delete"))) {
      throw new UserError("Invalid permission capability: '$cap'");
    }
  }
}
