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

class User {
  private $id;
  private $name;
  private $email;
  private $language;
  private $permissions;
  private $restrictions;

  /**
   * returns special user with permission to read schedule information only
   */
  public static function systemUser() {
    return new User(-1, "System user", "", "", new Permissions(-1, -1, true));
  }

  public function __construct($id, $name, $email, $language, Permissions $permissions) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->language = $language;
    $this->permissions = $permissions;
    $this->restrictions = new Restrictions($permissions);
  }

  public function getId() {
    return $this->id;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getName() {
    return $this->name;
  }

  public function getPermissions() {
    return $this->permissions;
  }

  public function getRestrictions() {
    return $this->restrictions;
  }

  public function getLanguage() {
    return $this->language;
  }

}
