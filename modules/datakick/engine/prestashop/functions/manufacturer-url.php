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

class ManufacturerUrlFunction extends Func {
    public function __construct() {
      parent::__construct('manufacturerUrl', 'string', array(
        'names' => array('manufacturerId'),
        'types' => array('number')
      ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return self::getUrl(\Context::getContext()->link, $args[0], $context->getValue('language'), $context->getValue('shop'));
    }

    public static function getUrl($link, $manufacturerId, $langId, $shopId) {
        return  $link->getManufacturerLink($manufacturerId, null, $langId, $shopId);
    }

    public function jsEvaluate() {
      return 'return "http://url";';
    }

    public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
      return new ManufacturerUrlExtractor($childExtractors[0], $context);
    }
}


class ManufacturerUrlExtractor extends Extractor {
    private $extractor;
    private $language;
    private $shop;
    private $cache;

    public function __construct($extractor, Context $context) {
        $this->extractor = $extractor;
        $this->link = \Context::getContext()->link;
        $this->language = $context->getValue('language');
        $this->shop = $context->getValue('shop');
        $this->cache = array();
    }

    public function getValue($resultset) {
        $manufacturerId = $this->extractor->getValue($resultset);
        if (is_null($manufacturerId))
          return null;
        if (! isset($this->cache[$manufacturerId])) {
            $this->cache[$manufacturerId] = ManufacturerUrlFunction::getUrl($this->link, $manufacturerId, $this->language, $this->shop);
        }
        return $this->cache[$manufacturerId];
    }
}
