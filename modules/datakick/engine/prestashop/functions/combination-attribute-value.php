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

class CombinationAttributeValueFunction extends Func {
  private $factory;

  public function __construct($factory) {
    $this->factory = $factory;
    parent::__construct('combinationAttributeValue', 'string', array(
      'names' => array('combinationId', 'attribute'),
      'types' => array('number', 'number')
    ), true);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    $combinationId = (int)$args[0];
    $attributeId = (int)$args[1];

    $query = $this->factory->getQuery();
    $sql = $query->parametrizeExpression($context, self::getSQL($combinationId, $attributeId));
    $res = $this->factory->getConnection()->query($sql);
    if ($res) {
      $row = $res->fetch();
      if ($row) {
        return $row[0];
      }
    }
  }

  public function jsEvaluate() {
    return 'return "";';
  }

  public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
    return self::getSQL($args[0], $args[1]);
  }

  private static function getSQL($combinationId, $attribute) {
    $sql = '(SELECT al.name
      FROM '._DB_PREFIX_.'product_attribute_shop pas
      INNER JOIN '._DB_PREFIX_.'product_attribute_combination comb ON (comb.id_product_attribute = pas.id_product_attribute)
      INNER JOIN '._DB_PREFIX_.'attribute a ON (comb.id_attribute = a.id_attribute)
      INNER JOIN '._DB_PREFIX_.'attribute_lang al ON (comb.id_attribute = al.id_attribute AND <bind-param:language:al.id_lang>)
      WHERE <bind-param:shop:pas.id_shop>
        AND comb.id_product_attribute = '.$combinationId.'
        AND a.id_attribute_group = '.$attribute.'
    )';
    return $sql;
  }
}
