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

class RoundFunction extends Func {
    public function __construct() {
        parent::__construct('round', 'number', array(
            'names' => array('value', 'precision'),
            'types' => array('number', 'number')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $value = $args[0];
        if (is_null($value))
          return $value;
        $precision = $args[1];
        if (is_null($precision))
          $precision = 2;
        return round($value, $precision);
    }

    public function jsEvaluate() {
        return 'return runtime.round(value, precision);';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $value = $args[0];
        $precision = $args[1];
        return "ROUND($value, $precision)";
    }
  }
