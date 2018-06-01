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

abstract class Arithmetic extends Func {
    public function __construct($type) {
      parent::__construct($type, parent::VARIABLE, array(
        'names' => array('left', 'right'),
        'types' => array('any', 'any')
      ), true);
    }

    public function validateParameters($parameterTypes) {
        parent::validateParameters($parameterTypes);
        foreach($parameterTypes as $type) {
            if ($type !== 'number' && $type !== 'currency') {
                $this->validationFailed("number or currency", $type);
            }
        }
    }

    public function jsValidateParameters() {
        return "return (parameterTypes[0] === 'number' || parameterTypes[0] === 'currency') && (parameterTypes[1] === 'number' || parameterTypes[1] === 'currency')";
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
      return $parameterTypes[0];
    }

    public function jsGetType() {
      return 'return parameterTypes[0]';
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $left = $args[0];
        $leftCurrency = $argsTypes[0] === 'currency';

        $right = $args[1];
        $rightCurrency = $argsTypes[1] === 'currency';

        if ($leftCurrency) {
            if ($rightCurrency) {
                $currencyId = $left->resolveCurrencyId($right);
                return new Currency($currencyId, $this->doEvaluate($left->getValue(), $right->getValue()));
            } else {
                $currencyId = $left->getCurrencyId();
                return new Currency($currencyId, $this->doEvaluate($left->getValue(), $right));
            }
        } else if ($rightCurrency) {
            return $this->doEvaluate($left, $right->getValue());
        } else {
            return $this->doEvaluate($left, $right);
        }
    }

    public function jsEvaluate() {
      return $this->doJsEvaluate();
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $left = $args[0];
        $leftCurrency = $argTypes[0] === 'currency';

        $right = $args[1];
        $rightCurrency = $argTypes[1] === 'currency';

        if ($leftCurrency) {
            $lc = $left['currency'];
            if ($rightCurrency) {
                $rc = $right['currency'];
                return array(
                    'value' => $this->doGetSqlExpression($left['value'], $right['value']),
                    'currency' => "( CASE $lc WHEN $rc THEN $lc ELSE -1 END )"
                );
            } else {
                return array(
                    'value' => $this->doGetSqlExpression($left['value'], $right),
                    'currency' => $lc
                );
            }
        } else if ($rightCurrency) {
            return $this->doGetSqlExpression($left, $right['value']);
        } else {
            return $this->doGetSqlExpression($left, $right);
        }
    }

    abstract function doEvaluate($left, $right);
    abstract function doJsEvaluate();
    abstract function doGetSqlExpression($left, $right);
}
