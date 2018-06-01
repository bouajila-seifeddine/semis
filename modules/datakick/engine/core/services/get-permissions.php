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

class GetPermissionsService extends Service {

  public function __construct() {
    parent::__construct('get-permissions');
  }

  public function process($factory, $request) {
    // only admin can change permissions
    $factory->getUser()->getPermissions()->checkAdmin();
    $dictionary = $factory->getDictionary();

    $role = $this->getParameter('role', false);
    $user = -1;
    if (is_null($role)) {
      $role = -1;
      $user = $this->getParameter('user', false);
      if (is_null($user)) {
        throw new UserError("Either 'role' or 'user' parameter must be specified");
      }
    }
    $user = (int)$user;
    $role = (int)$role;

    $perm = $factory->getPermissions($user, $role);

    if ($perm->isAdmin())
      return array('admin' => true);
    return array('admin' => false, 'permissions' => $perm->getPermissions());
  }
}
