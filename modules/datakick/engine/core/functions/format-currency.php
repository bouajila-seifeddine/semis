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

class FormatCurrencyFunction extends Func {
    private $count = 1;
    public function __construct($utils) {
        parent::__construct('formatCurrency', 'string', array(
            'names' => array('currency'),
            'types' => array('currency')
        ), true);
        $this->utils = $utils;
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return $this->utils->formatCurrency($args[0]);
    }

    public function jsEvaluate() {
        return 'return runtime.formatCurrency(currency)';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $arg = $args[0];
        $value = $arg['value'];

        $currency = $arg['currency'];
        $cnt = $this->count++;
        $alias = 'currency_'.$cnt;
        $query->exposeCollection('currencies', $alias, array(
            'type' => 'HAS_ONE',
            'description'=> 'Adhoc link to currencies',
            'collection' => 'currencies',
            'joinType' => 'LEFT',
            'sourceFields' => array(),
            'targetFields' => array(),
            'conditions' => array(
                '<target:id> = ' . $currency
            )
        ));
        $code = $query->exposeComponentField($alias, 'code');
        return "CONCAT(ROUND($value, 2), ' ', $code)";
    }
}
