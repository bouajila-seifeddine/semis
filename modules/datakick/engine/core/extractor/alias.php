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

class AliasExtractor extends Extractor {
  private $alias;
  private $type;

  public function __construct($alias, $type) {
    $this->alias = $alias;
    $this->type = $type;
  }

  public function getAlias() {
    return $this->alias;
  }

  public function parseDate($val, $arrayType) {
    if ($arrayType) {
      $ret = new \DateTime();
      $ret->setTimestamp($val);
      return $ret;
    }
    return new \DateTime($val);
  }

  public function getCurrency($valueAlias, $currencyAlias, $resultset) {
    $value = isset($valueAlias) ? $resultset[$valueAlias] : null;
    $currencyId = isset($currencyAlias) ? $resultset[$currencyAlias] : null;

    if (! is_null($currencyId)) {
      $currencyId = (int)$currencyId;
    }
    if (! is_null($value)) {
      $value = (float)$value;
    }
    return new Currency($currencyId, $value);
  }

  public function convertArrayValues($type, $values) {
    if (Types::isString($type))
      return $values;
    $ret = array();
    foreach ($values as $val) {
      array_push($ret, $this->toType($type, $val, true));
    }
    return $ret;
  }

  private function toType($type, $val, $arrayType=false) {
    switch ($type) {
      case 'boolean':
        return boolval($val);
      case 'number':
        return $val + 0;
      case 'datetime':
        return $this->parseDate($val, $arrayType);
    }
    return $val;
  }

  public function getValue($resultset) {
    if ($this->type === 'currency') {
      return $this->getCurrency($this->alias['value'], $this->alias['currency'], $resultset);
    }

    $val = $resultset[$this->alias];
    if (is_null($val)) {
      return null;
    }

    if (Types::isArray($this->type)) {
      return $this->convertArrayValues(Types::getArrayType($this->type), explode(chr(1), $val));
    }
    return $this->toType($this->type, $val);
  }
}
