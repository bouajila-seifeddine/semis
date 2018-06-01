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

class Chain implements ImportTransformer {
  public function __construct($chain) {
    $this->chain = $chain;
  }

  public function transform($value, $inputType) {
    $ret = $value;
    foreach ($this->chain as $transformer) {
      $ret = $transformer->transform($ret, $inputType);
      $inputType = $transformer->getOutputType($inputType);
    }
    return $ret;
  }

  public function getOutputType($inputType) {
    $ret = $inputType;
    foreach ($this->chain as $transformer) {
      $ret = $transformer->getOutputType($ret);
    }
    return $ret;
  }

}
