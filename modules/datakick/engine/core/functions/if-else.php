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

class IfElseFunction extends Func {

    public function __construct() {
      parent::__construct('if', parent::VARIABLE, array(
        'names' => array('cond', 't', 'f'),
        'types' => array('boolean', 'any', 'any')
      ), true);
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
      if (count($parameterTypes) === 3)
        return $parameterTypes[2];
      return null;
    }

    public function jsGetType() {
      return 'return parameterTypes[2]';
    }

    public function validateParameters($args) {
      parent::validateParameters($args);
      if ($args[1] !== $args[2]) {
        $params = implode(', ', $args);
        throw new \Exception("Parameter validation failed for '{$this->getName()}': truth and falsy branches should have the same type");
      }
    }

    public function jsValidateParameters() {
      return <<< EOD
        return parameterTypes.length === 3
          && parameterTypes[0] === 'boolean'
          && parameterTypes[1] === parameterTypes[2];
EOD;
    }

    public function evaluate($args, $argsTypes, Context $context) {
      return $args[0] ? $args[1] : $args[2];
    }

    public function partialReduce($expression, $args, $argsTypes, Context $context) {
      $cond = $args[0];
      if (! is_null($cond)) {
        return $args[0] ? $expression['args'][1] : $expression['args'][2];
      }
      return $expression;
    }

    public function jsEvaluate() {
      return "return cond ? t : f";
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        if (Types::isCurrency($argTypes[1])) {
          return array(
            'value' => "IF({$args[0]}, {$args[1]['value']}, {$args[2]['value']})",
            'currency' => "IF({$args[0]}, {$args[1]['currency']}, {$args[2]['currency']})",
          );
        }
        return "IF({$args[0]}, {$args[1]}, {$args[2]})";
    }
}
