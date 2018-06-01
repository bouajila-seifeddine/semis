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

class TypeCheckTransformation implements ImportTransformer {
  private $type;

  public function __construct($type) {
    $this->type = $type;
  }

  public function transform($value, $inputType) {
    if ($this->type != $inputType) {
      throw new UserError("input type must be {$this->type}, $inputType given");
    }
    return $value;
  }

  public function getOutputType($inputType) {
    return $inputType;
  }
}
