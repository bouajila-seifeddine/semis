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

class StartsWithFunction extends Func {
    public function __construct() {
      parent::__construct('startsWith', 'boolean', array(
        'names' => array('haystack', 'needle'),
        'types' => array('string', 'string')
      ), true);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $haystack = $args[0];
        $needle = $args[1];

        if (! $haystack)
          return !$needle;

        if (! $needle)
          return true;

        $haystack = strtolower($haystack);
        $needle = strtolower($needle);
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    public function jsEvaluate() {
      return "return haystack && needle && haystack.toLowerCase().startsWith(needle.toLowerCase);";
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $haystack = $args[0];
        $needle = $args[1];
        $needleExpr = "CONCAT(REPLACE(REPLACE($needle,'%','\%'),'_','\_'), '%')";
        return "($haystack LIKE $needleExpr)";
    }
}
