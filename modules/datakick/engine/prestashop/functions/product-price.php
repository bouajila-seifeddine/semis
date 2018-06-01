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

class ProductPriceFunction extends Func {
  public function __construct() {
    parent::__construct('productPrice', 'currency', array(
      'names' => array('productId', 'attributeId', 'useTax',  'groupId', 'currencyId', 'countryId'),
      'types' => array('number',    'number',      'boolean', 'number',  'number',     'number'   )
    ), false);
  }


  public function validateParameters($args) {
    $cnt = count($args);
    // optional parameters
    $test = $args;
    if ($cnt < 2) array_push($test, 'number');
    if ($cnt < 3) array_push($test, 'boolean');
    if ($cnt < 4) array_push($test, 'number');
    if ($cnt < 5) array_push($test, 'number');
    if ($cnt < 6) array_push($test, 'number');
    parent::validateParameters($test);
  }

  public function jsValidateParameters() {
    $ret = <<< EOD
    var len = parameterTypes.length;
    if (len < 1 || len > expected.length) return false;
    for (var i=0; i<len; i++) {
      if (parameterTypes[i] !== expected[i]) {
        return false;
      }
    }
    return true;
EOD;
    $params = implode($this->parameters['types'], "','");
    $ret = "var expected = ['$params'];\n$ret";
    return $ret;
  }


  public function evaluate($args, $argsTypes, Context $context) {
    $count = count($args);
    $shopId = $context->getValue('shop');
    $productId = $args[0];
    $attrId = $count > 1 ? $args[1] : 0;
    $usetax = $count > 2 ? $args[2] : true;
    $groupId = $count > 3 ? $args[3] : (int)\Group::getCurrent()->id;
    $currencyId = $count > 4 ? $args[4] : $context->getValue('defaultCurrency');
    $countryId = $count > 5 ? $args[5] : (int)\Context::getContext()->country->id;
    $usetax = $usetax && !\Tax::excludeTaxeOption();
    return self::calculatePrice($shopId, $productId, $attrId, $usetax, $groupId, $currencyId, $countryId);
  }

  public static function calculatePrice($shopId, $productId, $attrId, $usetax, $groupId, $currencyId, $countryId) {
    $value;
    if (! is_null($productId)) {
      $decimals = 6;
      $divisor = null;
      $onlyReduc = false;
      $usereduc = true;
      $quantity = 1;
      $forceAssociatedTax = false;
      $idCustomer = null;
      $idCart = null;
      $idAddress = null;
      $specificPriceOutput = null;
      $withEcotax = true;
      $useGroupReduction = true;
      $useCustomerPrice = true;
      $cartQuantity = 0;
      $idState = 0;
      $zipcode = 0;

      $value = \Product::priceCalculation($shopId, $productId, $attrId, $countryId, $idState, $zipcode,
      $currencyId, $groupId, $quantity, $usetax, $decimals, $onlyReduc, $usereduc, $withEcotax, $specificPriceOutput,
      $useGroupReduction, $idCustomer, $useCustomerPrice, $idCart, $cartQuantity);
    }
    return new Currency($currencyId, $value);
  }

  public function jsEvaluate() {
    return 'return 1;';
  }

  public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
    return new ProductPriceExtractor($childExtractors, $context);
  }
}


class ProductPriceExtractor extends Extractor {
  private $extractors;
  private $cnt;
  private $shopId;
  private $groupId;
  private $currencyId;
  private $countryId;
  private $usetax;

  public function __construct($extractors, Context $context) {
    $this->extractors = $extractors;
    $this->cnt = count($extractors);
    $this->shopId = $context->getValue('shop');
    $this->groupId = (int)\Group::getCurrent()->id;
    $this->currencyId = $context->getValue('defaultCurrency');
    $this->countryId = (int)\Context::getContext()->country->id;
    $this->usetax = !\Tax::excludeTaxeOption();
  }

  public function getValue($resultset) {
    $productId = $this->extractValue($resultset, 0);
    $attrId = $this->extractValue($resultset, 1, 0);
    $usetax = $this->usetax && $this->extractValue($resultset, 2, true);
    $groupId = $this->extractValue($resultset, 3, $this->groupId);
    $currencyId = $this->extractValue($resultset, 4, $this->currencyId);
    $countryId = $this->extractValue($resultset, 5, $this->countryId);
    return ProductPriceFunction::calculatePrice($this->shopId, $productId, $attrId, $usetax, $groupId, $currencyId, $countryId);
  }

  private function extractValue($resultset, $index, $default=null) {
    if ($this->cnt > $index) {
      $value = $this->extractors[$index]->getValue($resultset);
    } else {
      return $default;
    }
    if (($value === 0 || is_null($value)) && !is_null($default)) {
      return $default;
    }
    return $value;
  }
}
