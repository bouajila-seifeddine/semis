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

class EditRecordsService extends Service {

  public function __construct() {
    parent::__construct('edit-records');
  }

  public function process($factory, $request) {
    $type = $this->getParameter('type');
    $factory->getUser()->getPermissions()->checkEdit($type);

    $dict = $factory->getDictionary();
    $collection = $dict->getCollection($type);
    $keys = $collection->getKeys();

    $edits = $this->getArrayParameter('edits');
    self::validate($edits, count($keys));

    $parameters = $this->getArrayParameter('parameterValues');
    $context = $factory->getContext('app');
    $context->setUserParameters(array());
    $context->setValues($parameters);

    $modification = $factory->getModification($context);

    foreach ($edits as $edit) {
      $conditions = self::getConditions($dict, $type, $keys, $edit['keys']);
      $fields = self::getFields($dict, $type, $edit['fields']);
      $modification->addUpdate($type, $fields, $conditions);
    }

    $modification->execute(new Progress(true));
    return $modification->getStats();
  }

  public static function validate($edits, $keyLength) {
    foreach ($edits as $edit) {
      if (! self::valid($edit, 'fields'))
        throw new UserError('Edit does not contain fields');
      if (! self::valid($edit, 'keys') || count($edit['keys']) != $keyLength)
        throw new UserError('Edit does not contain valid keys');
    }
  }

  public static function valid($arr, $key) {
    return isset($arr[$key]) && is_array($arr[$key]) && count($arr[$key]) > 0;
  }

  public static function literal($type, $value) {
    return array(
      "func" => "identity",
      "type" => $type,
      "args" => array($value)
    );
  }

  public static function getFields($dict, $col, $fields) {
    $ret = array();
    foreach ($fields as $field=>$value) {
      $type = $dict->getFieldType($col, $field);
      $value = Types::convertValue($type, $value, true);
      $ret[$field] = self::literal($type, $value);
    }
    return $ret;
  }

  public static function getConditions($dict, $col, $keys, $values) {
    $conditions = array();
    $cnt = count($keys);
    for ($i=0; $i<$cnt; $i++) {
      $key = $keys[$i];
      $type = $dict->getFieldType($col, $key);
      $value = Types::convertValue($type, $values[$i], false);
      $conditions[] = array(
        'func' => 'equals',
        'type' => 'boolean',
        'args' => array(
          array(
            "func" => "variable",
            "type" => $type,
            "args" => array($type, $type, $key)
          ),
          self::literal($type, $value)
        )
      );
    }
    return $conditions;
  }


}
