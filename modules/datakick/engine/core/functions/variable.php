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

class VariableFunction extends Func {
    public function __construct() {
      parent::__construct('variable', parent::VARIABLE, array(
        'names' => array('type', 'object', 'property'),
        'types' => array('string', 'string', 'string')
      ), true);
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
        $type = $parameters[0];
        $objectAlias = $parameters[1];
        $id = $parameters[2];
        if (gettype($type) != 'string' || gettype($objectAlias) != 'string' || gettype($id) != 'string') {
            throw new \Exception("Parameters must be literals: $type $objectAlias $id");
        }
        $collection = $query->getCollectionByAlias($objectAlias);
        $field = $dictionary->getField($collection, $id);
        if (! $field) {
            throw new \Exception("Field not found $objectAlias.$id [$collection]");
        }
        if ($type !== $field->getType()) {
            throw new \Exception("Illegal field type $objectAlias.$id [$collection] $type {$field->getType()}");
        }
        return $type;
    }

    public function isHidden() {
        return true;
    }

    public function jsGetType() {
      return 'return parameters[0]';
    }

    public function requiresSql() {
        return true;
    }

    public function evaluate($args, $argsTypes, Context $context) {
        throw new \Exception("Cant' evaluate 'variable' function");
    }

    public function jsEvaluate() {
      return 'return runtime.getVariableValue(object, property)';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        return $query->exposeComponentField($args[1], $args[2]);
    }
}
