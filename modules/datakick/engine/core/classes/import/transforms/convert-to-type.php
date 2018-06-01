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

class ConvertToType extends TypeCheckTransformation implements ImportTransformer {
  private $type;
  private $allowNull;

  public function __construct($type, $allowNull) {
    parent::__construct('string');
    $this->type = $type;
    $this->allowNull = $allowNull;
  }

  public function transform($value, $inputType) {
    parent::transform($value, $inputType);
    return Types::convertValue($this->type, $value, $this->allowNull);
  }

  public function getOutputType($inputType) {
    return $this->type;
  }
}
