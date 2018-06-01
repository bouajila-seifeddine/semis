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
require_once(dirname(__FILE__).'/registry.php');
require_once(dirname(__FILE__).'/places/place.php');
require_once(dirname(__FILE__).'/places/local.php');
require_once(dirname(__FILE__).'/places/ftp.php');
require_once(dirname(__FILE__).'/places/email.php');

class Places {

  public function __construct($factory) {
    $this->factory = $factory;
  }

  public function getPlace($placeId, $placeType, $name, $config) {
    $factory = $this->factory;
    if ($placeType === 'local') {
      return new LocalPlace($placeId, $name, $config, $factory);
    }
    if ($placeType === 'ftp') {
      return new FtpPlace($placeId, $name, $config, $factory);
    }
    if ($placeType === 'email') {
      return new EmailPlace($placeId, $name, $config, $factory);
    }
    throw new \Exception("Unknown place type: $placeType");
  }

  public function load($placeId, $config=array()) {
    $data = $this->factory->getRecord("places")->load($placeId, array('type', 'name'), array(
      'config' => array('name', 'value'),
    ));
    foreach($data['config'] as $d) {
      $config[$d['name']] = $d['value'];
    }
    return $this->getPlace($placeId, $data['type'], $data['name'], $config);
  }
}
