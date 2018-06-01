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

class LookupTransform implements ImportTransformer {
  private $factory;
  private $type;
  private $collection;
  private $alias;
  private $expression;

  public function __construct(Factory $factory, $def) {
    $this->factory = $factory;
    $this->collection = Utils::extract('collection', $def);
    $this->expression = Utils::extract('expression', $def);
    $this->alias = Utils::extract('alias', $def);
    $collection = $factory->getDictionary()->getCollection($this->collection);
    $keys = $collection->getKeys();
    if (count($keys) != 1) {
      throw new \Exception("Invariant: can't lookup in `$this->collection` collection: multiple key collection");
    }
    $this->key = $keys[0];
    $keyField = $collection->getField($this->key);
    $this->type = $keyField->getType();
    $this->context = $factory->getContext();
    if (isset($def['parameters'])) {
      $this->context->setValues($def['parameters']);
    }
  }

  private function getQuery($value, $inputType) {
    $context = $this->context;
    $context->setInputValue('input', $value, $inputType);
    $query = $this->factory->getQuery();
    $query->exposeCollection($this->collection, $this->alias);
    $e = $this->factory->getExpressions();
    $extractor = $e->expose($query, array(
      'func' => 'variable',
      'type' => $this->type,
      'args' => array($this->type, $this->alias, $this->key)
    ), $context, false);
    $e->exposeCondition($query, $this->expression, $context);
    $query->setLimit(1);
    return array($query, $extractor);
  }

  public function transform($value, $inputType) {
    list($query, $extractor) = $this->getQuery($value, $inputType);
    $res = $query->execute($this->factory, $this->context);
    if ($res) {
      $row = $res->fetch();
      if ($row) {
        $v = $extractor->getValue($row);
        $next = $res->fetch();
        if ($next) {
          throw new UserError("Failed to lookup '".$this->getCollectionName()."' for input value '$value' - multiple records found");
        }
        return $v;
      } else {
        return null;
      }
    } else {
      throw new UserError("Error while looking up '".$this->getCollectionName()."' for input value '$value': ".$conn->getLastError());
    }
  }

  private function getCollectionName() {
    $collection = $this->factory->getDictionary()->getCollection($this->collection);
    return $collection->getSingularName();
  }

  public function getOutputType($inputType) {
    return $this->type;
  }
}
