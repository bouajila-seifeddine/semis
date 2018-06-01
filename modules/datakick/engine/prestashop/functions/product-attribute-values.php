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

class ProductAttributeValuesFunction extends Func {
  private $factory;

  public function __construct($factory) {
    $this->factory = $factory;
    parent::__construct('productAttributeValues', 'array[string]', array(
      'names' => array('productId', 'attribute', 'omitZeroQuantity'),
      'types' => array('number', 'number', 'boolean')
    ), true);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $productId = (int)$args[0];
    $attributeId = (int)$args[1];
    $omitZeroQuantity = false;
    if (count($args) == 3) {
      $omitZeroQuantity = (bool)$args[2];
    }

    $query = $this->factory->getQuery();
    $sql = $query->parametrizeExpression($context, self::getSQL($productId, $attributeId, $omitZeroQuantity));
    $res = $this->factory->getConnection()->query($sql);
    if ($res) {
      $row = $res->fetch();
      $val = $row[0];
      return explode(chr(1), $val);
    }
  }

  public function validateParameters($args) {
    $cnt = count($args);
    $test = $args;
    if ($cnt == 2) {
      array_push($test, 'boolean');
    }
    parent::validateParameters($test);
  }

  public function jsValidateParameters() {
    return <<< EOD
      var len = parameterTypes.length;
      if (len == 2)
        return parameterTypes[0] == 'number' && parameterTypes[1] == 'number';
      if (len == 3)
        return parameterTypes[0] == 'number' && parameterTypes[1] == 'number' && parameterTypes[2] == 'boolean';
      return false;
EOD;
  }


  public function jsEvaluate() {
    return 'return "";';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    if (count($args) == 3) {
      return self::getSQL($args[0], $args[1], (bool)$args[2]);
    } else {
      return self::getSQL($args[0], $args[1]);
    }
  }

  private static function getSQL($productId, $attribute, $omitZeroQuantity=false) {
    $sql = '(SELECT
      REPLACE(GROUP_CONCAT(DISTINCT al.name ORDER BY a.position SEPARATOR "@SEP@"), "@SEP@", CHAR(1))
      FROM '._DB_PREFIX_.'product_attribute_shop pas
      INNER JOIN '._DB_PREFIX_.'product_attribute_combination comb ON (comb.id_product_attribute = pas.id_product_attribute)
      INNER JOIN '._DB_PREFIX_.'attribute a ON (comb.id_attribute = a.id_attribute)
      INNER JOIN '._DB_PREFIX_.'attribute_lang al ON (comb.id_attribute = al.id_attribute AND <bind-param:language:al.id_lang>)';

    if ($omitZeroQuantity) {
      $sql .= '
      INNER JOIN '._DB_PREFIX_.'stock_available sa ON (sa.id_product = pas.id_product AND sa.id_product_attribute = pas.id_product_attribute AND sa.id_shop = IF(<bind-param:shareStock:1>, 0, pas.id_shop) AND sa.id_shop_group = IF(<bind-param:shareStock:1>, <param:shopGroup>, 0))';
    }

    $sql .= '
      WHERE <bind-param:shop:pas.id_shop>
        AND pas.id_product = '.$productId.'
        AND a.id_attribute_group = '.$attribute;

    if ($omitZeroQuantity) {
      $sql .= '
      AND sa.quantity > 0';
    }

    $sql .= '
    )';

    return $sql;
  }
}
