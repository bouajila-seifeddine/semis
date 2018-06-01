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

class ReplaceFunction extends Func {
    public function __construct() {
        parent::__construct('replace', 'string', array(
            'names' => array('subject', 'search', 'replacement'),
            'types' => array('string', 'string', 'string')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $subject = $args[0];
        $search = $args[1];
        $replacement = $args[2];
        return str_replace($search, $replacement, $subject);
    }

    public function jsEvaluate() {
        return "return subject ? subject.replace(new RegExp(search, 'g'), replacement) : subject;";
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $subject = $args[0];
        $search = $args[1];
        $replacement = $args[2];
        return "REPLACE($subject, $search, $replacement)";
    }
}
