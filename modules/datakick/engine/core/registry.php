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

class Registry {
    private $name;
    private $reg = array();

    public function __construct($name) {
        $this->name = $name;
    }

    public function register($name, $obj) {
        $this->reg[$name] = $obj;
    }

    public function has($name) {
      return isset($this->reg[$name]);
    }

    public function getKeys() {
      return array_keys($this->reg);
    }

    public function get($name) {
        if (! isset($this->reg[$name])) {
            $registryName = $this->name;
            throw new \Exception("$registryName: $name not found");
        }
        return $this->reg[$name];
    }
}
