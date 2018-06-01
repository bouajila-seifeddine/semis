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

class LengthFunction extends Func {

    public function __construct() {
      parent::__construct('length', 'number', parent::VARIADIC, true);
    }

    public function validateParameters($args) {
      $len = count($args);
      if ($len === 1) {
        if (!Types::isArray($args[0]) && !Types::isString($args[0]))
            throw new \Exception("Parameter validation failed for 'length': parameter must be an array or string, " . $args[0] . ' given');
      } else {
          throw new \Exception("Parameter validation failed for 'length': parameter count: [ " . implode(', ', $args) . ' ]');
      }
    }

    public function jsValidateParameters() {
      return <<< EOD
var len = parameterTypes.length;
var p = parameterTypes[0];
return len == 1 && (p === "string" || p.indexOf("array[") === 0);
EOD;
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $a = $args[0];
        if (Types::isString($argsTypes[0]))
            return strlen($a);
        return count($a);
    }

    public function jsEvaluate() {
      return <<<EOE
var args = Array.prototype.slice.call(arguments);
var a = args[0];
return a.length;
EOE;
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $a = $args[0];
        if (Types::isString($argTypes[0]))
            return "LENGTH($a)";
        return "IF(CHAR_LENGTH($a) = 0, 0, (1 + CHAR_LENGTH(REPLACE($a, CHAR(1), '--')) - CHAR_LENGTH($a)))";
    }
}
