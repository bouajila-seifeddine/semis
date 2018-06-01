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

class ForeignKeyMatcher {
  private $factory;
  private $context;
  private $expression;
  private $targetFields;
  private $targetCollection;
  private $cache;

  public function __construct(Factory $factory, Context $context, Link $link, $relation) {
    $this->factory = $factory;
    $this->context = $context;
    $this->expression = Utils::extract('expression', Utils::extract('condition', $relation));
    $this->targetFields = array_combine($link->getJoinTargetFields(), $link->getTargetFields());
    $this->targetCollection = $link->getTargetId();
    $this->cache = new LRUCache(300);
  }

  public function getForeignKeys($conditionValues) {
    $key = self::getKey($conditionValues);
    $key = strlen($key) > 50 ? md5($key) : $key;
    if ($this->cache->has($key)) {
      return $this->cache->get($key);
    }
    $ret = $this->doGetForeignKeys($conditionValues);
    if ($ret) {
      $this->cache->put($key, $ret);
    }
    return $ret;
  }

  public function doGetForeignKeys($conditionValues) {
    $context = $this->context;
    foreach ($conditionValues as $key => $value) {
      $context->setInputValue($key, $value, 'string');
    }

    $col = $this->factory->getDictionary()->getCollection($this->targetCollection);

    $expressions = $this->factory->getExpressions();
    $query = $this->factory->getQuery();

    $query->exposeCollection($this->targetCollection, 'target');
    $expressions->exposeCondition($query, $this->expression, $context);

    $fieldMap = array();
    $fieldTypes = array();
    foreach ($this->targetFields as $id => $targetField) {
      $fieldMap[$id] = $query->exposeField('target', $targetField);
      $fieldTypes[$id] = $col->getField($targetField)->getType();
    }

    $entries = array();
    $ret = $query->execute($this->factory, $context);
    if ($ret) {
      while ($row = $ret->fetch()) {
        $pks = array();
        foreach ($fieldMap as $id => $key) {
          $type = $fieldTypes[$id];
          $pks[$id] = Types::convertValue($fieldTypes[$id], $row[$key]);
        }
        $entries[] = $pks;
      }
    }
    return $entries;
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
