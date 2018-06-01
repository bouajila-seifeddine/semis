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

class ConstantExtractor extends Extractor {
    private $value;

    public function __construct($value) {
      $this->value = $value;
    }

    public function getValue($resultset) {
        return $this->value;
    }
}
