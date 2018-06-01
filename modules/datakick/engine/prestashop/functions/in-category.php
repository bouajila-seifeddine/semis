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

class InCategoryFunction extends Func {

  public function __construct($factory) {
    $this->factory = $factory;
    parent::__construct('inCategory', 'boolean', array(
      'names' => array('productId', 'categoryId'),
      'types' => array('number', 'number')
    ), true);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $productId = (int)$args[0];
    $categoryId = (int)$args[1];
    $conn = $this->factory->getConnection();
    $res = $conn->query("SELECT " . $this->getSQL($productId, $categoryId));
    $ret = $res->fetch();
    return !!$ret;
  }

  public function jsEvaluate() {
    return 'return false;';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    $productExpression = $args[0];
    $categoryExpression = $args[1];
    return $this->getSQL($productExpression, $categoryExpression);
  }

  private function getSQL($productId, $categoryId) {
    $table = _DB_PREFIX_ . 'category_product';
    return "EXISTS(SELECT 1 FROM `$table` cpe WHERE cpe.id_product = $productId AND cpe.id_category = $categoryId)";
  }
}
