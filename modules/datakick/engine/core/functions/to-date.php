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

class ToDateFunction extends Func {
    public function __construct() {
        parent::__construct('toDate', 'datetime', array(
            'names' => array('format', 'str'),
            'types' => array('string', 'string')
        ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $format = $args[0];
        $str = $args[1];
        $ret = \DateTime::createFromFormat($format, $str);
        return $ret ? $ret : $context->getValue('timestamp');
    }

    public function jsEvaluate() {
        return 'return new Date(str)';
    }
  }
