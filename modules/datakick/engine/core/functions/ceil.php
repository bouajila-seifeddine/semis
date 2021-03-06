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

class CeilFunction extends Func {
    public function __construct() {
        parent::__construct('ceil', 'number', array(
            'names' => array('value'),
            'types' => array('number')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $value = $args[0];
        if (is_null($value))
          return $value;
        return ceil($value);
    }

    public function jsEvaluate() {
        return 'return Math.ceil(value);';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $value = $args[0];
        return "CEIL($value)";
    }
  }
