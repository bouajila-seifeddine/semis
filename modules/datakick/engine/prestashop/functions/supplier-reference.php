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

class SupplierReferenceFunction extends Func {
  private $factory;

  public function __construct(Factory $factory) {
    $this->factory = $factory;
    parent::__construct('supplierReference', 'string', array(
      'names' => array('supplierId', 'productId', 'combinationId'),
      'types' => array('number', 'number', 'number')
    ), false);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $supplierId = (int)$args[0];
    $productId = (int)$args[1];
    $combinationId = null;
    if (count($args) == 3) {
      $combinationId = (int)$args[2];
    }
    return self::getSupplierRererence($this->factory, $supplierId, $productId, $combinationId);
  }

  public function validateParameters($args) {
    $cnt = count($args);
    $test = $args;
    if ($cnt == 2) {
      array_push($test, 'number');
    }
    parent::validateParameters($test);
  }

  public function jsValidateParameters() {
    return <<< EOD
    var len = parameterTypes.length;
    if (len == 2) return parameterTypes[0] == 'number' && parameterTypes[1] == 'number';
    if (len == 3) return parameterTypes[0] == 'number' && parameterTypes[1] == 'number' && parameterTypes[2] == 'number';
    return false;
EOD;
  }

  public function jsEvaluate() {
    return 'return "http://url";';
  }

  public static function getSupplierRererence($factory, $supplierId, $productId, $combinationId) {
    $conn = $factory->getConnection();
    $table = _DB_PREFIX_ . 'product_supplier';
    $supplierId = (int)$supplierId;
    $productId = (int)$productId;
    $combinationId = (int)$combinationId;
    $sql = "SELECT product_supplier_reference FROM $table where id_product=$productId AND id_supplier=$supplierId and id_product_attribute=$combinationId";
    return $conn->singleSelect($sql);
  }

  public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
    $supplierIdExtractor = $childExtractors[0];
    $productIdExtractor = $childExtractors[1];
    $combinationIdExtractor = null;
    if (count($childExtractors) == 3) {
      $combinationIdExtractor = $childExtractors[2];
    }
    return new SupplierReferenceExtractor($this->factory, $supplierIdExtractor, $productIdExtractor, $combinationIdExtractor);
  }
}


class SupplierReferenceExtractor extends Extractor {
  private $supplierIdExtractor;
  private $productIdExtractor;
  private $combinationIdExtractor;
  private $factory;
  private $cache = array();

  public function __construct(Factory $factory, $supplierIdExtractor, $productIdExtractor, $combinationIdExtractor) {
    $this->factory = $factory;
    $this->supplierIdExtractor = $supplierIdExtractor;
    $this->productIdExtractor = $productIdExtractor;
    $this->combinationIdExtractor = $combinationIdExtractor;
  }

  public function getValue($resultset) {
    $supplierId = $this->supplierIdExtractor->getValue($resultset);
    $productId = $this->productIdExtractor->getValue($resultset);
    $combinationId = null;
    if (is_null($productId) || is_null($supplierId)) {
      return null;
    }
    if (! is_null($this->combinationIdExtractor)) {
      $combinationId = $this->combinationIdExtractor->getValue($resultset);
    }
    $cacheId = "$supplierId-$productId-$combinationId";
    if (! isset($this->cache[$cacheId])) {
      $this->cache[$cacheId] = SupplierReferenceFunction::getSupplierRererence($this->factory, $supplierId, $productId, $combinationId);
    }
    return $this->cache[$cacheId];
  }
}
