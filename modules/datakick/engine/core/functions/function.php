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

abstract class Func {

    const VARIABLE = 'variable';
    const VARIADIC = 'variadic';

    private $name;
    private $sql;

    public function __construct($name, $type, $parameters, $sql) {
        $this->name = $name;
        $this->type = $type;
        $this->parameters = $parameters;
        $this->sql = $sql;
    }

    public function getName() {
        return $this->name;
    }

    public function supportSql() {
        return $this->sql;
    }

    public function requiresSql() {
        return false;
    }

    public function isDeterministic() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function partialReduce($expression, $args, $argsTypes, Context $context) {
      return $expression;
    }

    public abstract function evaluate($args, $argsTypes, Context $context);

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
        if ($this->type === self::VARIABLE)
        throw new \Exception('getType should be overriden for variable types');
        return $this->type;
    }

    public function jsGetType() {
        if ($this->type === self::VARIABLE)
        throw new \Exception('jsGetType should be overriden for variable types');
        return "return '{$this->type}';";
    }

    public function validateParameters($parameterTypes) {
        if ($this->parameters === self::VARIADIC) {
            throw new \Exception('validateParameters should be overriden by subclass for variadic functions');
        }
        $expected = $this->parameters['types'];
        if (count($expected) != count($parameterTypes)) {
            $this->validationFailed($expected, $parameterTypes);
        }
        for ($i = 0; $i<count($expected); $i++) {
            if (! $this->validateParameter($expected[$i], $parameterTypes[$i])) {
                $this->validationFailed($expected, $parameterTypes);
            }
        }
        return true;
    }

    private function validateParameter($type1, $type2) {
        if (Types::isAny($type1) || Types::isAny($type2))
            return true;

        if ($type1 === $type2)
            return true;

        if (Types::isArray($type1) && Types::isArray($type2)) {
            return $this->validateParameter(Types::getArrayType($type1), Types::getArrayType($type2));
        }
        return false;
    }

    public function jsValidateParameters() {
        return null;
    }

    protected function validationFailed($expected, $actual) {
        $expectedStr = implode(', ', $expected);
        $actualStr = implode(', ', $actual);
        throw new \Exception("Parameter validation failed for '{$this->name}': expected [ $expectedStr ], got [ $actualStr ]");
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        if ($this->sql)
          throw new \Exception('getSqlExpression should be overriden by subclass for sql functions');
        throw new \Exception("Can't call getSqlExpression on non-sql function");
    }

    public function getSignature() {
        $type = $this->type !== self::VARIABLE ? $this->type : array(
            'parameterNames' => array('parameterTypes, parameters'),
            'evaluate' => $this->jsGetType()
        );
        $validateParameters = $this->jsValidateParameters();
        $isVariadic = $this->parameters === self::VARIADIC;
        return array(
            'name' => $this->getName(),
            'parameters' => $validateParameters ? $validateParameters : ($isVariadic ? self::VARIADIC : $this->parameters['types']),
            'parameterNames' => $isVariadic ? self::VARIADIC : $this->parameters['names'],
            'type' => $type,
            'evaluate' => $this->jsEvaluate(),
            'sql' => $this->supportSql(),
            'hidden' => $this->isHidden()
        );
    }

    public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
        return new EvaluateExtractor($this, $childExtractors, $childTypes, $context);
    }
}
