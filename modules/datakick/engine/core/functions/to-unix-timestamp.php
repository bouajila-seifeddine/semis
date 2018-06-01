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

class ToUnixTimestampFunction extends Func {
    public function __construct() {
        parent::__construct('toUnixTimestamp', 'number', array(
            'names' => array('date'),
            'types' => array('datetime')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
      $date = $args[0];
      return $date->getTimestamp();
    }

    public function jsEvaluate() {
        return 'return 0;';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        return "UNIX_TIMESTAMP({$args[0]})";
    }
}
