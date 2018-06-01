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

class NullCheckTransformation implements ImportTransformer {

  public function transform($value, $inputType) {
    if (is_null($value)) {
      throw new UserError("empty value is not allowed");
    }
    return $value;
  }

  public function getOutputType($inputType) {
    return $inputType;
  }
}
