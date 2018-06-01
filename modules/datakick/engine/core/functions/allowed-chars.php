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

class AllowedCharsFunction extends Func {

    public function __construct() {
        parent::__construct('allowedChars', 'string', array(
            'names' => array('str', 'regex', 'replacement'),
            'types' => array('string', 'string', 'string')
        ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $str = $args[0];
        if (is_null($str))
          return '';
        $regex = "/[^" . $args[1] . "]/";
        $replacement = $args[2];
        return preg_replace($regex, $replacement, $str);
    }

    public function jsEvaluate() {
        return 'return a;';
    }
}
