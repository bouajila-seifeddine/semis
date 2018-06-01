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

class CoalesceFunction extends Func {

    public function __construct() {
      parent::__construct('coalesce', parent::VARIABLE, parent::VARIADIC, true);
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
      return $parameterTypes[count($parameterTypes) - 1];
    }

    public function jsGetType() {
      return 'return parameterTypes[parameterTypes.length - 1]';
    }

    public function validateParameters($args) {
      $len = count($args);
      if ($len > 0) {
        $type = $args[0];
        for ($i=1; $i<$len; $i++) {
          $t = $args[$i];
          if ($type !== $t) {
            throw new \Exception("Parameter validation failed for 'coalesce': different types: $type != $t");
          }
        }
        return true;
      }
      throw new \Exception("Parameter validation failed for 'coalesce': zero parameters");
    }

    public function jsValidateParameters() {
      return <<< EOD
var len = parameterTypes.length;
if (len > 0) {
  var baseType = parameterTypes[0];
  for (var i=1; i<len; i++) {
    var t = parameterTypes[i];
    if (baseType !== t)
      return false;
  }
  return true;
}
return false;
EOD;
    }

    public function evaluate($args, $argsTypes, Context $context) {
      $isString = Types::isString($argsTypes[0]);
      foreach ($args as $a) {
        if (! is_null($a)) {
          if ($isString) {
            if ($a != '')
              return $a;
          } else {
            return $a;
          }
        }
      }
      return null;
    }

    public function jsEvaluate() {
      return <<<EOE
var args = Array.prototype.slice.call(arguments);
for (var i=0; i<args.length; i++) {
  if (args[i])
    return args[i];
}
return null;
EOE;
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
      if (Types::isString($type)) {
        $args = array_map(function($a) {
          return "NULLIF($a, '')";
        }, $args);
      }
      if (Types::isCurrency($type)) {
        return array(
          'value' => self::mapValues($args),
          'currency' => self::mapCurrencies($args),
        );
      }
      return self::getSQL($args);
    }

    private static function getSQL($args) {
      return "COALESCE(".implode($args, ', ').")";
    }

    private static function mapValues($args) {
      return self::getSQL(array_map(function($a) {
        return $a['value'];
      }, $args));
    }

    private static function mapCurrencies($args) {
      return self::getSQL(array_map(function($a) {
        $value = $a['value'];
        $currency = $a['currency'];
        return "IF($value IS NULL, NULL, $currency)";
      }, $args));
    }

}
