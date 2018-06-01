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

class SubstringFunction extends Func {
    public function __construct() {
        parent::__construct('substring', 'string', array(
            'names' => array('str', 'start', 'length'),
            'types' => array('string', 'number', 'number')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $str = $args[0];
        $start = $args[1];
        $length = $args[2];
        return mb_substr($str, $start, $length);
    }

    public function jsEvaluate() {
        return 'return str ? str.substring(start, start+length) : str;';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $str = $args[0];
        $start = $args[1];
        $length = $args[2];
        return "SUBSTRING($str, $start+1, $length)";
    }
}
