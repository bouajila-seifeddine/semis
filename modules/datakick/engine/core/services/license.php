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

class LicenseService extends Service {

  public function __construct() {
    parent::__construct('license');
  }

  public function process($factory, $request) {
    // only admins can change licenses
    $factory->getUser()->getPermissions()->checkAdmin();
    return $factory->getPersistentConfig()->set('licenseKey', $this->getParameter('license', $request));
  }
}
