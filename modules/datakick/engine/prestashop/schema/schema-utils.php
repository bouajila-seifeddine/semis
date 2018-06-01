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
namespace Datakick\Schema\Prestashop;

class SchemaUtils {
  public static function normalizeDate($date) {
    return "IF($date < '1900-01-01', NULL, $date)";
  }

  public static function isMultiShop() {
    return \Shop::isFeatureActive();
  }
}
