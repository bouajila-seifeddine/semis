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

class FormatDateFunction extends Func {
    public function __construct() {
        parent::__construct('formatDate', 'string', array(
            'names' => array('format', 'datetime'),
            'types' => array('string', 'datetime')
        ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        if (! is_null($args[1])) {
          return date($args[0], $args[1]->format('U'));
        }
        return null;
    }

    public function jsEvaluate() {
        return 'return runtime.formatDate(format, datetime)';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        // TODO implement format date in sql
        return "DATE_FORMAT({$args[1]}, {$args[0]})";
    }
}
