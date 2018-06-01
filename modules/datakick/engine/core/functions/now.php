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

class NowFunction extends Func {
    public function __construct() {
      parent::__construct('now', 'datetime', array(
        'names' => array(),
        'types' => array()
      ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return $context->getValue('timestamp');
    }

    public function jsEvaluate() {
      return "return runtime.getParameterValue('timestamp')";
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $val = $context->getValue('timestamp');
        return $query->encodeLiteral($val, $type);
    }
}
