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

class SetConfigurationService extends Service {

  public function __construct() {
    parent::__construct('set-configuration');
  }

  public function process($factory, $request) {
    $name = $this->getParameter('name');
    $value = $this->getParameter('value', false);

    if ($name) {
      $config = $factory->getPersistentConfig();
      if (is_null($value)) {
        $config->remove($name);
      } else {
        $config->set($name, $value);
      }
    }
  }
}
