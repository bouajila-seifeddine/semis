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
if (php_sapi_name() == 'cli') {
  // cli execution - real cron, yey
  $_SERVER['REQUEST_METHOD'] = 'POST';
  require_once(dirname(__FILE__).'/../../config/config.inc.php');
  require_once(dirname(__FILE__).'/../../init.php');
  require_once(dirname(__FILE__).'/engine/engine.php');
  $factory = Datakick\PrestashopFactory::withContext(Context::getContext(), Datakick\User::systemUser());
  $factory->getScheduler()->cronEvent('cron', false);
} else {
  // webcron execution
  require_once(dirname(__FILE__).'/../../config/config.inc.php');
  require_once(dirname(__FILE__).'/../../init.php');
  require_once(dirname(__FILE__).'/engine/engine.php');
  $factory = Datakick\PrestashopFactory::withContext(Context::getContext(), Datakick\User::systemUser());

  $config = $factory->getPersistentConfig();
  if (Tools::getValue('token') != $config->get('webcronToken')) {
    die('Invalid Token');
  }

  // close connection and continue on backend only
  ignore_user_abort(true);
  ob_start();
  echo 'datakick webcron';
  header('Connection: close');
  header('Content-Length: '.ob_get_length());
  ob_end_flush();
  flush();

  if (function_exists('fastcgi_finish_request'))
    fastcgi_finish_request();

  // process request
  $factory->getScheduler()->cronEvent('webcron', true);
}
