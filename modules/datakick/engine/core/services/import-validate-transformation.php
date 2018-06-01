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

class ImportValidateTransformationService extends Service {

  public function __construct() {
    parent::__construct('import-validate-transformation');
  }

  public function process($factory, $request) {
    $transformations = $this->getArrayParameter('transformations');
    $inputs = $this->getArrayParameter('inputs');

    $collection = $this->getParameter('collection', false);
    $field = $this->getParameter('field', false);
    $target = null;
    if ($collection && $field) {
      $target = $factory->getDictionary()->getCollection($collection)->getField($field);
    }

    $t = new ImportTransformations($factory);
    $ret = array();
    foreach ($inputs as $input) {
      $type = Utils::extract('type', $input);
      $value = Utils::extract('value', $input);
      $value = Types::convertValue($type, $value);
      $chain = $t->validateChain($transformations, $type, $value, $target);
      foreach ($chain as &$step) {
        if (array_key_exists('value', $step)) {
          $step['value'] = Types::jsonValue($step['type'], $step['value']);
        }
      }
      $ret[] = $chain;
    }
    return $ret;
  }
}
