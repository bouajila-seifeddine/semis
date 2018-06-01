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

abstract class SchemaLoader {
  private $dictionary;
  private $factory;

  public function __construct($dictionary, Factory $factory) {
    $this->dictionary = $dictionary;
    $this->factory = $factory;
  }

  public function getFactory() {
    return $this->factory;
  }

  protected function loadSchema($loader) {
    $loader->register($this->dictionary, $this->factory);
  }

  protected function registerCollection($collection) {
    $this->dictionary->registerCollection($collection);
  }

  public abstract function load();
}
