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

class OrderedProductsCount extends Func {

  public function __construct() {
    parent::__construct('orderedProductsCount', 'number', array(
      'names' => array('orderOd', 'productId', 'combinationId'),
      'types' => array('number', 'number', 'number')
    ), false);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    throw new \Exception("Not Implemented");
  }

  public function jsEvaluate() {
    return 'return false;';
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


  public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
    $orderIdExtractor = $childExtractors[0];
    $productIdExtractor = $childExtractors[1];
    $combinationIdExtractor = null;
    if (count($childExtractors) == 3) {
      $combinationIdExtractor = $childExtractors[2];
    }

    return new OrderedProductsCountExtractor($orderIdExtractor, $productIdExtractor, $combinationIdExtractor, $factory);
  }

}

class OrderedProductsCountExtractor extends Extractor {
  private $orderIdExtractor;
  private $productIdExtractor;
  private $combinationIdExtractor;
  private $factory;
  private $cache;

  public function __construct($orderIdExtractor, $productIdExtractor, $combinationIdExtractor, $factory) {
    $this->factory = $factory;
    $this->orderIdExtractor = $orderIdExtractor;
    $this->productIdExtractor = $productIdExtractor;
    $this->combinationIdExtractor = $combinationIdExtractor;
    $this->cache = array();
  }

  public function getValue($resultset) {
    $orderId = $this->orderIdExtractor->getValue($resultset);
    if (! $orderId) {
      return null;
    } else {
      $orderId = (int)$orderId;
    }

    $productId = $this->productIdExtractor->getValue($resultset);
    if (! $productId) {
      return null;
    } else {
      $productId = (int)$productId;
    }

    $combinationId = null;
    if ($this->combinationIdExtractor) {
      $cId = $this->combinationIdExtractor->getValue($resultset);
      if ($cId) {
        $combinationId = (int)$cId;
      }
    }

    $cacheId = $this->getCacheId($productId, $combinationId);

    if (! isset($this->cache[$orderId])) {
      $this->cache[$orderId] = $this->loadOrder($orderId, $combinationId);
    }

    if (isset($this->cache[$orderId][$cacheId])) {
      $cnt = $this->cache[$orderId][$cacheId];
      return $cnt;
    }

    return null;
  }

  private function getCacheId($productId, $combinationId=null) {
    $cacheId = "p:$productId";
    if (! is_null($combinationId)) {
      $cacheId .= ",c:$combinationId";
    }
    return $cacheId;
  }

  public function loadOrder($orderId, $combinationId) {
    if (is_null($combinationId)) {
      return $this->loadOrderByProduct($orderId);
    } else {
      return $this->loadOrderByCombination($orderId);
    }
  }

  public function loadOrderByProduct($orderId) {
    $conn = $this->factory->getConnection();
    $query = "SELECT product_id, SUM(product_quantity) AS `cnt` FROM "._DB_PREFIX_."order_detail WHERE id_order = $orderId GROUP BY product_id";
    $res = $conn->query($query);
    $ret = array();
    if ($res) {
      while ($row = $res->fetch()) {
        $productId = $row['product_id'];
        $cacheId = $this->getCacheId($productId);
        $cnt = $row['cnt'];
        $ret[$cacheId] = $cnt;
      }
    }
    return $ret;
  }

  public function loadOrderByCombination($orderId) {
    $conn = $this->factory->getConnection();
    $query = "SELECT product_id, product_attribute_id, SUM(product_quantity) AS `cnt` FROM "._DB_PREFIX_."order_detail WHERE id_order = $orderId GROUP BY product_id, product_attribute_id";
    $res = $conn->query($query);
    $ret = array();
    if ($res) {
      while ($row = $res->fetch()) {
        $productId = $row['product_id'];
        $combinationId = $row['product_attribute_id'];
        $cacheId = $this->getCacheId($productId, $combinationId);
        $cnt = $row['cnt'];
        $ret[$cacheId] = $cnt;
      }
    }
    return $ret;
  }

}
