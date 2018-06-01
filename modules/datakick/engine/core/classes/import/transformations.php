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

class ImportTransformations {

  public function __construct(Factory $factory) {
    $this->factory = $factory;
  }

  public function getChain(Array $transformations) {
    $trans = array();
    foreach ($transformations as $transform) {
      if (is_array($transform)) {
        $type = $transform['type'];
        $trans[] = $this->getTransformation($type, $transform);
      } else {
        $trans[] = $transform;
      }
    }
    $cnt = count($trans);
    if ($cnt === 0) {
      return null;
    }
    if ($cnt === 1) {
      return $trans[0];
    }
    return $this->getTransformation('chain', $trans);
  }


  public function getTransformation($type, $def) {
    switch ($type) {
      case 'lookup':
        return new TransformerCache(new LookupTransform($this->factory, $def));
      case 'convert':
        $allowNull = self::getValue($def, 'boolean', 'allowNull', true);
        $returnType = self::getValue($def, 'string', 'targetType');
        return new ConvertToType($returnType, $allowNull);
      case 'defaultsTo':
        return new DefaultsTo(Utils::extract('value', $def));
      case 'expression':
        return new ExpressionTransformation($this->factory, Utils::extract('expression', $def));
      case 'chain':
        return new Chain($def);
      default:
        throw new \Exception("Transformation `$type` not found");
    }
  }

  public function getFieldRestriction(Field $field) {
    $restrictions = array();
    $isRequired = $field->isRequired();

    $restrictions[] = new TypeCheckTransformation($field->getType());

    if ($isRequired) {
      $restrictions[] = new NullCheckTransformation();
    }

    $values = $field->getValues();
    if ($values) {
      $restrictions[] = new EnumTransformation($field->getType(), $values, $isRequired);
    }

    $selectRecord = $field->getSelectRecord();
    if ($selectRecord) {
      $restrictions[] = new TransformerCache(new RecordExistsTransformation($this->factory, $selectRecord, $isRequired));
    }

    return $this->getChain($restrictions);
  }

  public function validateTransformation($transformation, $inputType, $input) {
    try {
      $output = $transformation->transform($input, $inputType);
      $outputType = $transformation->getOutputType($inputType);
      return array(
        'value' => $output,
        'type' => $outputType
      );
    } catch (\Exception $e) {
      $err = $e->__toString();//$e->getMessage();
      if (! $err) {
        $err = 'Unknown error';
      }
      return array('error' => $err);
    }
  }

  public function validateChain($transformations, $type, $input, $target) {
    $prev = array('value' => $input, 'type' => $type);
    $chain = array($prev);

    foreach ($transformations as &$transformation) {
      if (is_array($transformation)) {
        $type = Utils::extract('type', $transformation);
        $transformation = $this->getTransformation($type, $transformation);
      }
    }

    foreach ($transformations as $trans) {
      $inputType = $prev['type'];
      $value = $prev['value'];
      $prev = $this->validateTransformation($trans, $inputType, $value);
      $chain[] = $prev;
      if (isset($prev['error'])) {
        return $chain;
      }
    }
    if ($target) {
      $restriction = $this->getFieldRestriction($target, true);
      if ($restriction) {
        $inputType = $prev['type'];
        $value = $prev['value'];
        $prev = $this->validateTransformation($restriction, $inputType, $value);
      }
    }
    $chain[] = $prev;
    return $chain;
  }

  private static function getValue($arr, $type, $param, $default=null) {
    if (isset($arr[$param])) {
      return Types::convertValue($type, $arr[$param], false);
    }
    if (is_null($default)) {
      return Utils::extract($param, $arr);
    }
    return $default;
  }

}
