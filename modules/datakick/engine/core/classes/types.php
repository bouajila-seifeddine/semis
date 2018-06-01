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

class Types  {
  static $formatCurrency;

  public static function setFormatCurrency($formatCurrency) {
    self::$formatCurrency = $formatCurrency;
  }

  public static function parseCurrency($string) {
    if (self::$formatCurrency) {
      return self::$formatCurrency->parseCurrency($string);
    }
    return null;
  }

  public static function isAny($type) {
    return $type === 'any';
  }

  public static function isArray($type) {
    return strpos($type, "array[") === 0;
  }

  public static function getArrayType($type) {
    return substr(substr($type, 0, strlen($type)-1), 6);
  }

  public static function isString($type) {
    return $type === "string";
  }

  public static function isNumber($type) {
    return $type === "number";
  }

  public static function isBoolean($type) {
    return $type === "boolean";
  }

  public static function isDateTime($type) {
    return $type === "datetime";
  }

  public static function isCurrency($type) {
    return $type === "currency";
  }

  public static function isNumerical($type) {
    return self::isCurrency($type) || self::isNumber($type);
  }

  public static function isNumericalJS($type) {
    if (! $type)
    return "false";
    return "(($type) == 'currency' || ($type) == 'number')";
  }

  public static function getValue($type, $value) {
    if (self::isCurrency($type)) {
      return $value->getValue();
    }
    return $value;
  }

  public static function getValueSql($type, $value) {
    if (self::isCurrency($type)) {
      return $value['value'];
    }
    return $value;
  }

  public static function isKnownType($type) {
    return (
      self::isString($type) ||
      self::isNumber($type) ||
      self::isBoolean($type) ||
      self::isDateTime($type) ||
      self::isCurrency($type) ||
      (self::isArray($type) && self::isKnownType(self::getArrayType($type)))
    );
  }

  public static function truthyValues() {
    return array('true', 'yes', 'on', '1');
  }

  public static function isTruthyValue($val) {
    $str = strtolower($val);
    return in_array($str, self::truthyValues());
  }

  public static function falsyValues() {
    return array('false', 'no', 'off', '0');
  }

  public static function isFalsyValue($val) {
    $str = strtolower($val);
    return in_array($str, self::falsyValues());
  }

  public static function convertValue($type, $value, $allowNull=true, $throw=true) {
    if (! self::isKnownType($type)) {
      throw new UserError("Unknown type: $type");
    }

    if (is_null($value)) {
      if ($allowNull) {
        return $value;
      }
      if ($throw) {
        throw new UserError("Can't convert NULL to $type");
      }
      return null;
    }

    if (self::isArray($type)) {
      return $value;
    }

    if (self::isString($type)) {
      if (is_string($value))
        return $value;
      return (string)$value;
    }

    if (self::isNumber($type)) {
      if (is_float($value) || is_int($value))
        return $value;
      if (is_numeric($value)) {
        $float = floatval($value);
        $int = intval($value);
        return (round($float) == $float) ? $int : $float;
      }
    }

    if (self::isCurrency($type)) {
      if (gettype($value) === 'string') {
        $arr = explode(':', $value);
        if (count($arr) == 2) {
          $val = self::convertValue('number', $arr[0], false, false);
          $id = self::convertValue('number', $arr[1], false, false);
          if ($val && $id) {
            return new Currency($id, $val);
          }
        } else if (is_numeric($value)) {
          $val = self::convertValue('number', $value);
          return new Currency(1, $val);
        } else {
          $currency = self::parseCurrency($value);
          if ($currency) {
            return $currency;
          }
        }
      }
      if (is_array($value) && isset($value['currency']) && isset($value['value'])) {
        $val = self::convertValue('number', $value['value']);
        return new Currency((int)$value['currency'], $val);
      }
      if (is_a('\Datakick\Currency', $value)) {
        return $value;
      }
    }

    if (self::isBoolean($type)) {
      if (gettype($value) === 'string') {
        if (self::isTruthyValue($value)) {
          return true;
        }
        if (self::isFalsyValue($value)) {
          return false;
        }
        if (is_numeric($value)) {
          return boolval($value);
        }
      } else {
        return boolval($value);
      }
    }

    if (self::isDateTime($type)) {
      if (is_a($value, 'DateTime'))
        return $value;
      if (is_string($value))
        $value = strtotime($value);
      if (is_int($value)) {
        $ret = new \DateTime();
        $ret->setTimestamp($value);
        return $ret;
      }
    }

    if ($throw) {
      throw new UserError("Can't convert '$value' to $type");
    }
    return null;
  }

  public static function serialize($type, $value) {
    if (self::isDateTime($type)) {
      return (string)$value->getTimestamp();
    }
    if (self::isCurrency($type)) {
      return $value->getValue() . ':' .$value->getCurrencyId();
    }
    return (string)$value;
  }

  public static function toString($type, $value) {
    if ($value) {
      if (self::isDateTime($type)) {
        return $value->format('Y-m-d');
      }
      if (self::isCurrency($type)) {
        return $value->getValue();
      }
      return (string)$value;
    }
    return "";
  }

  public static function jsonValue($type, $value) {
    if (is_null($value)) {
      return $value;
    }
    if (self::isDateTime($type)) {
      if ($value->getTimestamp() < 0) {
        return null;
      }
      return $value->format('c');
    }
    if (self::isCurrency($type)) {
      return array(
        'currency' => $value->getCurrencyId(),
        'value' => $value->getValue()
      );
    }
    return $value;
  }
}
