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
if (! defined('DATAKICK_CORE')) {
  define('DATAKICK_CORE', dirname(__FILE__).'/core/');
  define('DATAKICK_PRESTASHOP', dirname(__FILE__).'/prestashop/');

  require_once(DATAKICK_PRESTASHOP . 'factory.php');
  require_once(DATAKICK_CORE . 'request-handler.php');
}
