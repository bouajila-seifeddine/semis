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

class ProductFeatureValueFunction extends Func {
  private $factory;

  public function __construct($factory) {
    $this->factory = $factory;
    parent::__construct('productFeatureValue', 'string', array(
      'names' => array('productId', 'featureId'),
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

  private static function getSQL($productId, $featureId) {
    $sql = '(SELECT fvl.value
      FROM '._DB_PREFIX_.'feature_product fp
      INNER JOIN '._DB_PREFIX_.'feature_value_lang fvl ON (fvl.id_feature_value = fp.id_feature_value AND <bind-param:language:fvl.id_lang>)
      WHERE fp.id_feature = '.$featureId.'
        AND fp.id_product = '.$productId.'
    )';
    return $sql;
  }
}
