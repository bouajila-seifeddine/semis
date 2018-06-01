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

class PrimaryKeyMatcher {
  private $factory;
  private $context;
  private $expression;
  private $collectionId;
  private $cache;
  private $cacheMissing;

  public function __construct(Factory $factory, Context $context, $collection, $matchConditions, $cacheMissing=false) {
    $this->factory = $factory;
    $this->context = $context;
    $this->expression = Utils::extract('expression', $matchConditions);
    $this->collectionId = $collection->getId();
    $this->cache = new LRUCache(300);
    $this->cacheMissing = $cacheMissing;
  }

  public function getPrimaryKeys($conditionValues) {
    $key = self::getKey($conditionValues);
    $key = strlen($key) > 50 ? md5($key) : $key;
    if ($this->cache->has($key)) {
      return $this->cache->get($key);
    }
    $ret = $this->doGetPrimaryKeys($conditionValues);
    if ($ret || $this->cacheMissing) {
      $this->cache->put($key, $ret);
    }
    return $ret;
  }

  public function doGetPrimaryKeys($conditionValues) {
    // popuplate context with input values
    $context = $this->context;
    foreach ($conditionValues as $key => $value) {
      $context->setInputValue($key, $value, 'string');
    }

    $expressions = $this->factory->getExpressions();
    $query = $this->factory->getQuery();
    $query->exposeCollection($this->collectionId, 'target');
    $keys = $query->exposeKeyFields('target');
    $expressions->exposeCondition($query, $this->expression, $context);
    $query->setLimit(1);
    $ret = $query->execute($this->factory, $context);
    if ($ret) {
      $row = $ret->fetch();
      if ($row) {
        $pks = array();
        foreach ($keys as $key) {
          $pks[] = $row[$key];
        }
        return $pks;
      }
    }
  }

  private static function getKey($arr) {
    $ret = '';
    foreach ($arr as $key=>$value) {
      $pair = "$key=$value";
      if ($ret) {
        $ret .= '|' . $pair;
      } else {
        $ret = $pair;
      }
    }
    return $ret;
  }

}
