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

class AssetDeleteVisitor implements Visitor {
  private $factory;

  public function __construct(Factory $factory) {
    $this->factory = $factory;
  }

  public function visit($info) {
    @unlink($info['path']);
  }

  public function after() {
    $factory = $this->factory;
    $table = $factory->getServiceTable('assets');
    $factory->getConnection()->execute("DELETE FROM $table");
  }
}
