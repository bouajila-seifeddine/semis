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

class RecordExistsTransformation extends TypeCheckTransformation implements ImportTransformer {
  private $factory;
  private $context;
  private $collection;
  private $throw;
  private $key;

  public function __construct($factory, $collection, $throw) {
    $this->factory = $factory;
    $this->collection = $collection;
    $this->throw = $throw;

    $this->collection = $factory->getDictionary()->getCollection($collection);
    $keys = $this->collection->getKeys();
    if (count($keys) != 1) {
      throw new \Exception("Invariant: `$this->collection` collection: multiple key collection");
    }
    $key = $this->collection->getField($keys[0]);
    $this->key = $key->getSQL();
    $this->context = $factory->getContext();
    $this->context->setValue('shop', '$all');
    $this->context->setValue('language', '$all');
    parent::__construct($key->getType());
  }

  private function exists($value) {
    if (is_null($value)) {
      return false;
    }
    $conn = $this->factory->getConnection();
    $component = new Component($conn, $this->collection);
    $component->exposePrimaryFields();
    $component->addCondition("{$this->key} = $value");
    $ret = $conn->singleSelect($component->getSQL($this->context));
    return !!$ret;
  }

  public function transform($value, $inputType) {
    parent::transform($value, $inputType);
    if ($this->exists($value)) {
      return $value;
    }
    if ($this->throw) {
      throw new UserError("{$this->collection->getSingularName()} with id '$value' not found");
    } else {
      return null;
    }
  }

  public function getOutputType($inputType) {
    return $inputType;
  }
}
