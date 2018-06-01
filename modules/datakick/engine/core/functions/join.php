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

class JoinFunction extends Func {
    public function __construct() {
        parent::__construct('join', 'string', array(
            'names' => array('arr', 'delimiter'),
            'types' => array('array[any]', 'string')
        ), true);
    }

    public function validateParameters($parameterTypes) {
        $type = $parameterTypes[0];
        if (! Types::isArray($type))
            throw new \Exception("Join: validate parameters failed: $parameterTypes is not an array");
        if (! Types::isString($parameterTypes[1]))
        throw new \Exception("Join: validate parameters failed: delimiter is not string");
    }

    public function jsValidateParameters() {
        return 'var p=parameterTypes[0]; return p.indexOf("array[") === 0 && parameterTypes[1] === "string";';
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $type = $argsTypes[0];
        $arr = $args[0];
        $delimiter = $args[1];
        return implode($delimiter, $arr);
    }

    public function jsEvaluate() {
        return 'return arr.join(delimiter)';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $arr = $args[0];
        $delimiter = $args[1];
        return "REPLACE($arr, CHAR(1), $delimiter)";
    }
}
