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

class UUID {

  public static function v4() {
    $data = self::getRandomData();
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return strtoupper(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
  }

  private static function getRandomData() {
    if (function_exists('random_bytes'))
      return random_bytes(16);
    if (function_exists('openssl_random_pseudo_bytes'))
      return openssl_random_pseudo_bytes(16);
    return substr(hex2bin(sha1(uniqid('rd'.rand(), true))), 0, 16);
  }

}
