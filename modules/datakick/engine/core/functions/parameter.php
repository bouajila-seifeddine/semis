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

class ParameterFunction extends Func {
    public function __construct() {
      parent::__construct('parameter', parent::VARIABLE, array(
        'names' => array('type', 'name'),
        'types' => array('string', 'string')
      ), true);
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
        $type = $parameters[0];
        $id = $parameters[1];
        if (gettype($type) != 'string' || gettype($id) != 'string') {
            throw new \Exception("Parameters must be literals");
        }
        $expected = $context->getType($id);
        if ($type != $expected) {
            throw new \Exception("Parameter type not match ($type != $expected)");
        }
        return $type;
    }

    public function isHidden() {
        return true;
    }

    public function jsGetType() {
      return 'return parameters[0]';
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return $context->getValue($args[1]);
    }

    public function jsEvaluate() {
      return 'return runtime.getParameterValue(name)';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $val = $context->getValue($args[1]);
        return $query->encodeLiteral($val, $type);
    }
}
