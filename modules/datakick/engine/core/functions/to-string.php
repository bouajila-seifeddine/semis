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

class ToStringFunction extends Func {
  public function __construct($join, $formatCurrency) {
    parent::__construct('toString', 'string', array(
      'names' => array('a'),
      'types' => array('any')
    ), true);
    $this->join = $join;
    $this->formatCurrency = $formatCurrency;
  }

  public function supportSql() {
    return true;
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $type = $argsTypes[0];
    $arg = $args[0];
    if ($type === 'string') {
      return $arg;
    }
    if ($type === 'datetime') {
      return $arg->format('Y-m-d H:i:s');
    }
    if ($type === 'currency') {
      return $this->formatCurrency->evaluate(array($arg), array('currency'), $context);
    }
    if (Types::isArray($type)) {
      return $this->join->evaluate(array($arg, ' : '), array($type, 'string'), $context);
    }
    return "{$arg}";
  }

  public function jsEvaluate() {
    return 'return a.toString()';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    $t = $argTypes[0];
    $arg = $args[0];
    if ($t === 'string' || $t === 'number' || $t === 'boolean') {
      return $arg;
    }

    if ($t === 'currency') {
      return $this->formatCurrency->getSqlExpression($args, $type, $argTypes, $query, $context);
    }

    if (Types::isDateTime($t)) {
      return "DATE_FORMAT($arg, '%Y-%m-%d %H:%i:%s')";
    }

    if (Types::isArray($t)) {
      return $this->join->getSqlExpression(array($arg, "' : '"), $type, $argTypes, $query, $context);
    }

    throw new \Exception("Can't cast '$t' to string");
  }
}
