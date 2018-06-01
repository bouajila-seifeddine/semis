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

class ProductImagesFunction extends Func {

    public function __construct() {
      parent::__construct('productImages', 'array[string]', array(
        'names' => array('productId', 'type', 'attributeId'),
        'types' => array('number', 'number', 'number')
      ), false);
    }
    public function validateParameters($args) {
      $cnt = count($args);
      $test = $args;
      for ($i=$cnt; $i<3; $i++) {
        array_push($test, 'number');
      }
      parent::validateParameters($test);
    }

    public function jsValidateParameters() {
      return <<< EOD
var len = parameterTypes.length;
if (len == 1)
  return parameterTypes[0] == 'number';
if (len == 2)
  return parameterTypes[0] == 'number' && parameterTypes[1] == 'number';
if (len == 3)
  return parameterTypes[0] == 'number' && parameterTypes[1] == 'number' && parameterTypes[2] == 'number';
return false;
EOD;
  }

    public function evaluate($args, $argsTypes, Context $context) {
    }

    public function jsEvaluate() {
      return 'return ["http://url"];';
    }

    public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
      return new ProductImagesExtractor($childExtractors, $context, $factory);
    }
}

class ProductImagesExtractor extends Extractor {
    private $extractors;
    private $images;
    private $hasType;
    private $hasAttribute;
    private $link;

    public function __construct($extractors, $context, $factory) {
        $this->extractors = $extractors;
        $this->link = \Context::getContext()->link;
        $this->hasType = count($extractors) == 2;
        if ($this->hasType) {
          $types = \ImageType::getImagesTypes('products');
          foreach ($types as $type) {
            $this->types[$type['id_image_type']] = $type['name'];
          }
        }
        $this->hasAttribute = count($extractors) == 3;
        $this->images = $this->loadImages($context, $factory, $this->hasAttribute);
    }

    public function getValue($resultset) {
        $productId = $this->extractors[0]->getValue($resultset);
        $type = null;
        if ($this->hasType) {
          $typeId = $this->extractors[1]->getValue($resultset);
          if (isset($this->types[$typeId])) {
            $type = $this->types[$typeId];
          }
        };
        $id = $productId;
        if ($this->hasAttribute) {
          $attributeId = $this->extractors[2]->getValue($resultset);
          $id .= '-' . $attributeId;
        }
        if (isset($this->images[$id])) {
          $typeId = "t-$type";
          if (! isset($this->images[$id][$typeId])) {
            $this->images[$id][$typeId] = array();
            $name = $this->images[$id]['name'];
            foreach ($this->images[$id]['images'] as $imageId) {
              array_push($this->images[$id][$typeId], ProductImageFunction::getUrl($this->link, $name, $imageId, $type));
            }
          }
          return $this->images[$id][$typeId];
        }
        return array();
    }

    private function loadImages($context, $factory, $hasAttribute) {
      $image = _DB_PREFIX_.'image';
      $productLang = _DB_PREFIX_.'product_lang';
      $productAttributeImg = _DB_PREFIX_.'product_attribute_image';
      $idShop = (int)$context->getValue('shop');
      $idLang = (int)$context->getValue('language');
      if ($hasAttribute) {
        $sql = "SELECT i.id_image, CONCAT(i.id_product,'-',pai.id_product_attribute) as id, pl.link_rewrite FROM $image i, $productLang pl, $productAttributeImg pai WHERE pl.id_product = i.id_product AND pl.id_shop = $idShop and pl.id_lang = $idLang and pai.id_image = i.id_image";
      } else {
        $sql = "SELECT i.id_image, i.id_product as id, pl.link_rewrite FROM $image i, $productLang pl WHERE pl.id_product = i.id_product AND pl.id_shop = $idShop and pl.id_lang = $idLang";
      }
      $res = $factory->getConnection()->query($sql);
      $images = array();
      $type = null;
      while ($row = $res->fetch()) {
        $id = $row['id'];
        $name = $row['link_rewrite'];
        $imageId = $row['id_image'];
        if (! isset($images[$id])) {
          $images[$id] = array(
            'name' => $name,
            'images' => array()
          );
        }
        array_push($images[$id]['images'], $imageId);
      }
      return $images;
    }
}
