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

class CleanFunction extends Func {

    public function __construct() {
        parent::__construct('clean', 'string', array(
            'names' => array('a'),
            'types' => array('string')
        ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return trim(html_entity_decode(strip_tags($args[0])));
    }

    public function jsEvaluate() {
        return 'return a;';
    }
}
