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

class PrestashopUser extends User {
  private $employee;
  public function __construct($employee) {
    if (is_null($employee) || is_null($employee->id)) {
      throw new UserError("Employee not exists - can't create user");
    }
    $perm = new PrestashopPermissions($employee->id, $employee->id_profile);
    $name = $employee->firstname . ' ' . $employee->lastname;
    $this->employee = $employee;
    $lang = new \Language($employee->id_lang);
    parent::__construct($employee->id, $name, $employee->email, $lang->iso_code, $perm);
  }

  public function getEmployee() {
    return $this->employee;
  }
}
