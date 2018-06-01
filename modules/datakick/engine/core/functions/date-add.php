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

class DateAddFunction extends Func {
    public function __construct() {
        parent::__construct('dateAdd', 'datetime', array(
            'names' => array('datetime', 'type', 'increment'),
            'types' => array('datetime', 'string', 'number')
        ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
      $datetime = $args[0];
      $type = $args[1];
      $increment = $args[2];

      if ($type === 'hour' || $type === 'minute' || $type === 'second' || $type === 'day' || $type === 'month' || $type === 'year') {
        $modify = ($increment > 0 ? '+' : '') . $increment . ' ' . $type;
        $d = clone $datetime;
        return $d->modify($modify);
      }
      return $datetime;
    }

    public function jsEvaluate() {
        return 'return datetime';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
      $datetime = $args[0];
      $type = $args[1];
      $increment = $args[2];
      if (is_string($type)) {
        $type = str_replace($type, "'", "");
      } else {
        $type = 'day';
      }

      if ($type === 'hour' || $type === 'minute' || $type === 'second' || $type === 'day' || $type === 'month' || $type === 'year') {
        $type = strtoupper($type);
      } else {
        $type = 'DAY';
      }

      return "DATE_ADD($datetime, INTERVAL $increment $type)";
    }
}
