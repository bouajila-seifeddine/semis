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

// returns first element
class LastFunction extends Func {

    public function __construct() {
        parent::__construct('last', parent::VARIABLE, array(
            'names' => array('a'),
            'types' => array('array[any]')
        ), true);
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
        return Types::getArrayType($parameterTypes[0]);
    }

    public function jsGetType() {
        return 'var x = parameterTypes[0]; return x.substr(0, x.length-1).substr(6)';
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return end($args[0]);
    }

    public function jsEvaluate() {
        return 'return a[a.length - 1]';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $arr = $args[0];
        return "SUBSTRING_INDEX($arr, CHAR(1), -1)";
    }
}
