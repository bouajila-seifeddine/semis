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

// returns all but first element
class TailFunction extends Func {

    public function __construct($length) {
      parent::__construct('tail', 'array[any]', parent::VARIADIC, true);
      $this->length = $length;
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
      return $parameterTypes[0];
    }

    public function jsGetType() {
      return 'return parameterTypes[0]';
    }

    public function validateParameters($args) {
      $len = count($args);
      if ($len>0 && $len<=2) {
        if (! Types::isArray($args[0]))
            throw new \Exception("Parameter validation failed for 'tail': first parameter must be an array, " . $args[0] . ' given');
        if ($len === 2 && !Types::isNumber($args[1]))
            throw new \Exception("Parameter validation failed for 'tail': second optional parameter must be a number, " . $args[1] . ' given');
        return;
      }
      throw new \Exception("Parameter validation failed for 'tail': parameter count: [ " . implode(', ', $args) . ' ]');
    }

    public function jsValidateParameters() {
      return <<< EOD
var len = parameterTypes.length;
if (len > 0 && len <= 2) {
  if (parameterTypes[0].indexOf("array[") === 0) {
    return (len === 1 || parameterTypes[1] === "number");
  }
}
return false;
EOD;
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $len = count($args);
        $arr = $args[0];
        $num = $len > 1 ? $args[1] : 1;
        return array_slice($arr, $num);
    }

    public function jsEvaluate() {
      return <<<EOE
var args = Array.prototype.slice.call(arguments);
var arr = args[0];
var num = args[1] || 1;
return arr.splice(num);
EOE;
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $arr = $args[0];
        $num = 1;
        if (count($args) === 2) {
            $num = $args[1];
        }
        $len = $this->length->getSqlExpression(array($arr), 'number', array($type), $query, $context);
        return "IF($num < $len, SUBSTRING_INDEX($arr, CHAR(1), $num - $len), '')";
    }
}
