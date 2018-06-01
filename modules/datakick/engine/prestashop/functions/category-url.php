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

class CategoryUrlFunction extends Func {
    public function __construct() {
      parent::__construct('categoryUrl', 'string', array(
        'names' => array('categoryId'),
        'types' => array('number')
      ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        return self::getUrl(\Context::getContext()->link, $args[0], $context->getValue('language'), $context->getValue('shop'));
    }

    public static function getUrl($link, $categoryId, $langId, $shopId) {
        return  $link->getCategoryLink($categoryId, null, $langId, null, $shopId);
    }

    public function jsEvaluate() {
      return 'return "http://url";';
    }

    public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
      return new CategoryUrlExtractor($childExtractors[0], $context);
    }
}


class CategoryUrlExtractor extends Extractor {
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
        $categoryId = $this->extractor->getValue($resultset);
        if (is_null($categoryId))
          return null;
        if (! isset($this->cache[$categoryId])) {
            $this->cache[$categoryId] = CategoryUrlFunction::getUrl($this->link, $categoryId, $this->language, $this->shop);
        }
        return $this->cache[$categoryId];
    }
}
