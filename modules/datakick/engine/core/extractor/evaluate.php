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

class EvaluateExtractor extends Extractor {
    private $func;
    private $argsExtractors;
    private $argsTypes;
    private $context;

    public function __construct($func, $extractors, $argsTypes, Context $context) {
        $this->func = $func;
        $this->argsExtractors = $extractors;
        $this->argsTypes = $argsTypes;
        $this->context = $context;
    }

    public function getValue($resultset) {
        $args = array();
        foreach($this->argsExtractors as $extractor) {
          array_push($args, $extractor->getValue($resultset));
        }
        return $this->func->evaluate($args, $this->argsTypes, $this->context);
    }
}
