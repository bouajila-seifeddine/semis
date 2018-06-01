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

class TrimFunction extends Func {
    public function __construct() {
        parent::__construct('trim', 'string', array(
            'names' => array('str'),
            'types' => array('string')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return trim($args[0]);
    }

    public function jsEvaluate() {
        return "return str ? str.trim() : str;";
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $str = $args[0];
        return "TRIM($str)";
    }
}
