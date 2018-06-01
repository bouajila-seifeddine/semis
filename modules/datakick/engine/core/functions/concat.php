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

class ConcatFunction extends Func {
    public function __construct() {
      parent::__construct('concat', 'string', array(
        'names' => array('left', 'right'),
        'types' => array('string', 'string')
      ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return $args[0] . $args[1];
    }

    public function jsEvaluate() {
      return 'return left + right';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        return "CONCAT({$args[0]}, {$args[1]})";
    }
}
