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

class EnumTransformation extends TypeCheckTransformation implements ImportTransformer {
  private $values;
  private $label;
  private $throw;

  public function __construct($type, $values, $throw) {
    parent::__construct($type);
    $this->values = $values;
    $this->throw = $throw;
    $cnt = count($values);
    if (! $cnt) {
      throw new \Exception("Invariant: empty values");
    }

    if ($throw) {
      if ($cnt === 1) {
        $this->label = "'{$values[0]}'";
      } else {
        $keys = array_keys($values);
        $last = "'{$keys[$cnt - 1]}'";
        array_pop($keys);
        $this->label = "'" . implode("', '", $keys) . "'" . " or $last";
      }
    }
  }

  public function transform($value, $inputType) {
    parent::transform($value, $inputType);
    if (array_key_exists($value, $this->values)) {
      return $value;
    }
    if ($this->throw) {
      throw new UserError("invalid value '$value', expected {$this->label}");
    }
    return null;
  }

  public function getOutputType($inputType) {
    return $inputType;
  }
}
