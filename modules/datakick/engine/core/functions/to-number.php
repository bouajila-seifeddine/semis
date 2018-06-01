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

class ToNumberFunction extends Func {
    public function __construct() {
        parent::__construct('toNumber', 'number', array(
            'names' => array('a'),
            'types' => array('any')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $arg = $args[0];
        $type = $argsTypes[0];
        if (! is_null($arg)) {
          if (Types::isCurrency($type)) {
            return $arg->getValue();
          }
          return floatval($arg);
        }
        return 0;
    }

    public function jsEvaluate() {
        return 'return a';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $arg = $args[0];
        $type = $argTypes[0];
        if ($type === 'currency') {
            return $arg['value'];
        }
        return "CAST($arg AS DECIMAL(10,6))";
    }

    public function validateParameters($parameterTypes) {
        parent::validateParameters($parameterTypes);
        $type = $parameterTypes[0];
        if ($type !== 'string' && $type !== 'currency') {
            $this->validationFailed("string or currency", $type);
        }
    }

    public function jsValidateParameters() {
        return "return parameterTypes.length === 1 && (parameterTypes[0] === 'string' || parameterTypes[0] === 'currency')";
    }

  }
