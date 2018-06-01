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

abstract class Logical extends Func {
  private $jsOperator;
  private $sqlOperator;
  private $argType;

  public function __construct($name, $jsOperator, $sqlOperator=null, $type='any') {
    parent::__construct($name, 'boolean', array(
      'names' => array('left', 'right'),
      'types' => array($type, $type)
    ), true);
    $this->argType = $type;
    $this->jsOperator = $jsOperator;
    $this->sqlOperator = $sqlOperator === null ? $jsOperator : $sqlOperator;
  }

  public function validateParameters($args) {
    parent::validateParameters($args);
    $left = $args[0];
    $right = $args[1];
    if (Types::isNumerical($left) && Types::isNumerical($right))
      return;

    if ($left !== $right) {
      $params = implode(', ', $args);
      throw new \Exception("Parameter validation failed for '{$this->getName()}': $params should be of the same type");
    }
  }

  public function jsValidateParameters() {
    $l = Types::isNumericalJS('l');
    $r = Types::isNumericalJS('r');
    return "
      if (parameterTypes.length !== 2)
        return false;
      var l=parameterTypes[0];
      var r=parameterTypes[1];
      if ($l && $r)
        return true;
      return l == r;
    ";
  }

  public function jsEvaluate() {
    return "return left {$this->jsOperator} right";
  }

  public abstract function doEvaluate($left, $right);

  public function evaluate($args, $argsTypes, Context $context) {
    $left = Types::getValue($argsTypes[0], $args[0]);
    $right = Types::getValue($argsTypes[1], $args[1]);
    $ret = $this->doEvaluate($left, $right);
    if ($ret) {
      if (Types::isCurrency($argsTypes[0]) && Types::isCurrency($argsTypes[1])) {
        return $args[0]->getCurrencyId() == $args[1]->getCurrencyId();
      }
    }
    return $ret;
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    $left = Types::getValueSql($argTypes[0], $args[0]);
    $right = Types::getValueSql($argTypes[1], $args[1]);

    $add = '';
    if (Types::isCurrency($argTypes[0]) && Types::isCurrency($argTypes[1])) {
      $leftId = $args[0]['currency'];
      $rightId = $args[1]['currency'];
      $add = "AND $leftId = $rightId ";
    }

    $oper = $this->sqlOperator;
    return "( $left $oper $right $add)";

  }
}
