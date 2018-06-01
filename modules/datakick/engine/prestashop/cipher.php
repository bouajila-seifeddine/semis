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

class PrestashopCipher implements Cipher {
  private $method;

  public function __construct() {
    if (! \Configuration::get('PS_CIPHER_ALGORITHM') || !defined('_RIJNDAEL_KEY_')) {
      $this->method = new \Blowfish(_COOKIE_KEY_, _COOKIE_IV_);
    } else {
      $this->method = new \Rijndael(_RIJNDAEL_KEY_, _RIJNDAEL_IV_);
    }
  }

  public function encrypt($plain) {
    return $this->method->encrypt($plain);
  }

  public function decrypt($ciphered) {
    return $this->method->decrypt($ciphered);
  }
}
