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

class SaveCustomFieldService extends Service {

  public function __construct() {
    parent::__construct('save-custom-field');
  }

  public function process($factory, $request) {
    $perm = $factory->getUser()->getPermissions();
    $id = $this->getIdParameter();
    $fieldset = $this->getParameter('fieldset');
    $name = $this->getParameter('name');
    $type = $this->getParameter('type');
    $alias = $this->getParameter('alias');
    $subtype = $this->getParameter('subtype', false);
    $collection = $this->getParameter('collection');
    $cust = $factory->getCustomization();
    if ($id) {
      $perm->checkEdit('customFields');
      return $cust->updateCustomField($id, $collection, $alias, $type, $subtype, $name, $fieldset);
    } else {
      $perm->checkCreate('customFields');
      return $cust->createCustomField($collection, $alias, $type, $subtype, $name, $fieldset);
    }
  }
}
