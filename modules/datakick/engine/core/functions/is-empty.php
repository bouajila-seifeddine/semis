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

class IsEmptyFunction extends Func {

  public function __construct() {
    parent::__construct('isEmpty', 'boolean', array(
      'names' => array('item'),
      'types' => array('any')
    ), true);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $arg = $args[0];
    if (is_null($arg))
      return true;
    $type = $argsTypes[0];
    if (Types::isString($type)) {
      return $arg == '';
    } else if (Types::isArray($type)) {
      return count($arg) == 0;
    }
    return false;
  }

  public function jsEvaluate() {
    return 'return !!item';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    $item = $args[0];
    $type = $argTypes[0];
    $check = "$item IS NULL";
    if (Types::isString($type)) {
      $check .= " OR $item = ''";
    } else if (Types::isArray($type)) {
      $check .= " OR CHAR_LENGTH($item) = 0" ;
    }
    return "($check)";
  }
}
