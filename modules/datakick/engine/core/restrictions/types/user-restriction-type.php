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

class UserRestrictionType implements RestrictionType {
  public function __construct($user) {
    $this->user = $user;
  }

  public function fields() {
    return array('user', 'public');
  }

  public function getDefaultReadLevel() {
    return "user,public";
  }

  public function getDefaultWriteLevel() {
    return "user";
  }

  public function getIcon() {
    return "user";
  }

  public function getName() {
    return "User restriction";
  }

  public function getDescription() {
    return "Restrict access to records based on logged-in user id";
  }

  public function getLevels() {
    return array(
      'user' => "Is owned by user",
      'user,public' => "Record is public or owned by user",
    );
  }

  public function create($level) {
    $levels = explode(',', $level);
    $restriction = new EqualsRestriction('user', $this->user->getId());
    if (in_array('public', $levels)) {
      $restriction = new OrRestriction($restriction, new IdentityRestriction('public'));
    }
    return $restriction;
  }
}
