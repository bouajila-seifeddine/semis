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

class ExpressionTransformation implements ImportTransformer {
  private $expressions;
  private $expression;
  private $context;

  public function __construct(Factory $factory, array $expression) {
    $this->expressions = $factory->getExpressions();
    if (! $this->expressions->canReduceToLiteral($expression)) {
      throw new UserError("Expression can't be reduced to literal value");
    }
    $this->context = $factory->getContext();
    $this->expression = $expression;
  }

  public function transform($value, $inputType) {
    $this->context->setInputValue('input', $value, $inputType);
    $ret = $this->expressions->reduceExpression($this->expression, $this->context);
    if (is_array($ret) && isset($ret['func']) && $ret['func'] == 'identity') {
      $ret = $ret['args'][0];
    }
    return $ret;
  }

  public function getOutputType($inputType) {
    return $this->expression['type'];
  }
}
