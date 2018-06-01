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

class DateDiffFunction extends Func {
    public function __construct() {
        parent::__construct('dateDiff', 'number', array(
            'names' => array('date1', 'date2'),
            'types' => array('datetime', 'datetime')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
      $date1 = $args[0];
      $date2 = $args[1];
      $interval = $date2->diff($date1);
      return $interval->invert ? -1 * $interval->days : $interval->days;
    }

    public function jsEvaluate() {
        return 'return 0;';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        return "DATEDIFF({$args[0]}, {$args[1]})";
    }
}
