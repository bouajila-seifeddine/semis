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

class DefaultsTo implements ImportTransformer {
  private $defaultValue;

  public function __construct($defaultValue) {
    $this->defaultValue = $defaultValue;
  }

  public function transform($value, $inputType) {
    if (is_null($value) || (is_string($value) && trim($value) == '')) {
      return $this->defaultValue;
    }
    return $value;
  }

  public function getOutputType($inputType) {
    return $inputType;
  }
}
