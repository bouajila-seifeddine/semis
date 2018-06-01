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

class TransformerCache implements ImportTransformer {
  private $transformer;
  private $outputTypes = array();

  private $valuesCache;
  private $throwCache;

  public function __construct(ImportTransformer $transformer, $capacity=300) {
    $this->transformer = $transformer;
    $this->valuesCache = new LRUCache($capacity);
    $this->throwCache = new LRUCache($capacity);
  }

  public function transform($value, $inputType) {
    $key = is_null($value) ? '@$@key@$@' : $value;
    if ($this->throwCache->has($key)) {
      throw new UserError($this->throwCache->get($key));
    }
    if ($this->valuesCache->has($key)) {
      return $this->valuesCache->get($key);
    }
    try {
      $ret = $this->transformer->transform($value, $inputType);
      $this->valuesCache->put($key, $ret);
      return $ret;
    } catch (\Exception $e) {
      $this->throwCache->put($key, $e->getMessage());
      throw $e;
    }
  }

  public function getOutputType($inputType) {
    if (! isset($this->outputTypes[$inputType])) {
      $this->outputTypes[$inputType] = $this->transformer->getOutputType($inputType);
    }
    return $this->outputTypes[$inputType];
  }

}
