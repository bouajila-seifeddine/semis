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

class ProductUrlFunction extends Func {
  public function __construct() {
    parent::__construct('productUrl', 'string', array(
      'names' => array('productId', 'combinationId'),
      'types' => array('number', 'number')
    ), false);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $productId = (int)$args[0];
    $combinationId = null;
    if (count($args) == 2) {
      $combinationId = (int)$args[1];
    }
    return self::getUrl(\Context::getContext()->link, $productId, $combinationId, $context->getValue('language'), $context->getValue('shop'));
  }

  public static function getUrl($link, $productId, $combinationId, $langId, $shopId) {
    $ipa = is_null($combinationId) ? 0 : $combinationId;
    return $link->getProductLink($productId, null, null, null, $langId, $shopId, $ipa, false, false, !!$ipa);
  }

  public function validateParameters($args) {
    $cnt = count($args);
    $test = $args;
    if ($cnt == 1) {
      array_push($test, 'number');
    }
    parent::validateParameters($test);
  }

  public function jsValidateParameters() {
    return <<< EOD
    var len = parameterTypes.length;
    if (len == 1) return parameterTypes[0] == 'number';
    if (len == 2) return parameterTypes[0] == 'number' && parameterTypes[1] == 'number';
    return false;
EOD;
  }

  public function jsEvaluate() {
    return 'return "http://url";';
  }

  public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
    $productIdExtractor = $childExtractors[0];
    $combinationIdExtractor = null;
    if (count($childExtractors) == 2) {
      $combinationIdExtractor = $childExtractors[1];
    }
    return new ProductUrlExtractor($productIdExtractor, $combinationIdExtractor, $context);
  }
}


class ProductUrlExtractor extends Extractor {
  private $extractor;
  private $combinationIdExtractor;
  private $language;
  private $shop;
  private $cache;

  public function __construct($extractor, $combinationIdExtractor, Context $context) {
    $this->extractor = $extractor;
    $this->combinationIdExtractor = $combinationIdExtractor;
    $this->link = \Context::getContext()->link;
    $this->language = $context->getValue('language');
    $this->shop = $context->getValue('shop');
    $this->cache = array();
  }

  public function getValue($resultset) {
    $productId = $this->extractor->getValue($resultset);
    $combinationId = null;
    if (is_null($productId)) {
      return null;
    }
    if (! is_null($this->combinationIdExtractor)) {
      $combinationId = $this->combinationIdExtractor->getValue($resultset);
    }
    $cacheId = "$productId-$combinationId";
    if (! isset($this->cache[$cacheId])) {
      $this->cache[$cacheId] = ProductUrlFunction::getUrl($this->link, $productId, $combinationId, $this->language, $this->shop);
    }
    return $this->cache[$cacheId];
  }
}
