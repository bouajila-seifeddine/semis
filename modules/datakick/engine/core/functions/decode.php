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

class DecodeFunction extends Func {

    public function __construct() {
      parent::__construct('decode', parent::VARIABLE, parent::VARIADIC, true);
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
      return $parameterTypes[count($parameterTypes) - 1];
    }

    public function jsGetType() {
      return 'return parameterTypes[parameterTypes.length - 1]';
    }

    public function validateParameters($args) {
      $len = count($args);
      if ($len >= 4 && ($len % 2 === 0)) {
        $baseType = $args[0];
        $retType = $args[$len - 1];
        $options = (int)(($len - 2) / 2);
        for ($i=0; $i<$options; $i++) {
          $optionVal = $args[1 + $i*2];
          $optionRet = $args[2 + $i*2];
          if ($baseType !== $optionVal)
            throw new \Exception("Parameter validation failed for 'decode': option type: $baseType != $optionVal");
          if ($retType !== $optionRet)
            throw new \Exception("Parameter validation failed for 'decode': return type: $retType != $optionRet");
        }
        return true;
      }
      throw new \Exception("Parameter validation failed for 'decode': parameter count: [ " . implode(', ', $args) . ' ]');
    }

    public function jsValidateParameters() {
      return <<< EOD
var len = parameterTypes.length;
if (len >= 4 && (len % 2 === 0)) {
  var baseType = parameterTypes[0];
  var retType = parameterTypes[len - 1];
  var options = ((len - 2) / 2);
  for (var i=0; i<options; i++) {
    var optionVal = parameterTypes[1 + i*2];
    var optionRet = parameterTypes[2 + i*2];
    if (baseType !== optionVal)
      return false;
    if (retType !== optionRet)
      return false;
  }
  return true;
}
return false;
EOD;
    }

    public function evaluate($args, $argsTypes, Context $context) {
      $val = $args[0];
      $last = count($args)-1;
      $options = (int)($last / 2);
      for ($i=0; $i<$options; $i++) {
        $optionVal = $args[1 + $i*2];
        if ($val === $optionVal)
          return $args[2 + $i*2];
      }
      return $args[$last];
    }

    public function jsEvaluate() {
      return <<<EOE
var args = Array.prototype.slice.call(arguments);
var val = args[0];
var options = (args.length - 2) / 2;
for (var i=0; i<options; i++) {
  var optionVal = args[1 + i*2];
  if (val === optionVal)
    return args[2 + i*2];
}
return args[args.length - 2];
EOE;
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
      $ret = "( CASE {$args[0]} ";
      $last = count($args) - 1;
      $options = (int)($last / 2);
      for ($i=0; $i<$options; $i++) {
        $optionVal = $args[1 + $i*2];
        $optionRet = $args[2 + $i*2];
        $ret .= "WHEN $optionVal THEN $optionRet ";
      }
      $ret .= "ELSE {$args[$last]} END )";
      return $ret;
    }
}
