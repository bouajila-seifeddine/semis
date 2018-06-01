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

class SplitFunction extends Func {
    public function __construct() {
        parent::__construct('split', 'array[string]', array(
            'names' => array('str', 'delimiter'),
            'types' => array('string', 'string')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $str = $args[0];
        $delimiter = $args[1];
        if (! $delimiter)
          $delimiter = ' ';
        return explode($delimiter, $str);
    }

    public function jsEvaluate() {
        return 'return str ? str.split(delimiter) : [];';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $str = $args[0];
        $delimiter = $args[1];
        if (! $delimiter) {
          $delimiter = ' ';
        }
        return "REPLACE($str, $delimiter, CHAR(1))";
    }
}
