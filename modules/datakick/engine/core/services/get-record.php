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

class GetRecordService extends Service {

  public function __construct() {
    parent::__construct('get-record');
  }

  public function process($factory, $request) {
    $type = $this->getParameter('type');
    $keys = $this->getArrayParameter('key');
    $fields = $this->getArrayParameter('fields', false);
    $parameters = $this->getArrayParameter('parameters', false);
    $associations = $this->getArrayParameter('associations', false);

    $record = $factory->getRecord($type);
    return array_map(array('Datakick\GetRecordService', 'formatValue'), $record->load($keys, $fields, $associations));
  }

  private static function formatValue($val) {
    if (is_a($val, 'DateTime')) {
      return $val->format(\DateTime::ATOM);
    }
    return $val;
  }
}
