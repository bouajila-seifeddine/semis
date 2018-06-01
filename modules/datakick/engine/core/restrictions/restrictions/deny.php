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

class DenyRestriction implements Restriction {
  static $singleton = null;

  private function __construct() {
  }

  public static function instance() {
    if (! self::$singleton) {
      self::$singleton = new DenyRestriction();
    }
    return self::$singleton;
  }

  // restrict everything
  public function getCondition(Array $fields) {
    return Restriction::DENY;
  }

}
