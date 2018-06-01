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

class RandomFunction extends Func {
    public function __construct() {
      parent::__construct('random', 'number', array(
        'names' => array(),
        'types' => array()
      ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return rand() / getrandmax();
    }

    public function isDeterministic() {
        return false;
    }

    public function jsEvaluate() {
      return "return Math.random()";
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        return "RAND()";
    }
}
