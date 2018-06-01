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

class ReplaceRegFunction extends Func {
    public function __construct() {
        parent::__construct('replaceReg', 'string', array(
            'names' => array('subject', 'pattern', 'replacement'),
            'types' => array('string', 'string', 'string')
        ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $subject = $args[0];
        $pattern = $args[1];
        $replacement = $args[2];
        if (substr($pattern, 0, 1) != '/') {
          $pattern = "/$pattern/";
        }
        return preg_replace($pattern, $replacement, $subject);
    }

    public function jsEvaluate() {
        return "return subject ? subject.replace(new RegExp(pattern, 'g'), replacement) : subject;";
    }
}
