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

class PrestashopConfiguration implements Configuration {

  public function get($key) {
    $ret = \Configuration::getGlobalValue(self::getKey($key));
    if ($ret === false)
      return null;
    return $ret;
  }

  public function set($key, $value) {
    return \Configuration::updateGlobalValue(self::getKey($key), $value);
  }

  public function remove($key) {
    return \Configuration::deleteByName(self::getKey($key));
  }

  private static function getKey($key) {
    return 'DATAKICK_' . strtoupper(Utils::decamelize($key));
  }

}
