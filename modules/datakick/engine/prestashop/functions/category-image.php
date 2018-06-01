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

class CategoryImageFunction extends Func {
  public function __construct() {
    parent::__construct('categoryImage', 'string', array(
      'names' => array('categoryId', 'friendlyUrl', 'type'),
      'types' => array('number', 'string', 'number')
    ), false);
  }

  public function evaluate($args, $argsTypes, Context $context) {
    return self::getUrl($context->link, $args[1], $args[0], null);
  }

  public static function getUrl($link, $name, $imageId, $type) {
    if (is_null($imageId) ||  $imageId == 0) {
      return null;
    }
    if (! @is_file(_PS_CAT_IMG_DIR_ . "$imageId.jpg")) {
      return null;
    }
    return  $link->getCatImageLink($name, "$imageId", $type);
  }

  public function validateParameters($args) {
    $cnt = count($args);
    $test = $args;
    if ($cnt == 2) {
      array_push($test, 'number');
    }
    parent::validateParameters($test);
  }

  public function jsValidateParameters() {
    return <<< EOD
    var len = parameterTypes.length;
    if (len == 2)
      return parameterTypes[0] == 'number' && parameterTypes[1] == 'string';
    if (len == 3)
      return parameterTypes[0] == 'number' && parameterTypes[1] == 'string' && parameterTypes[2] == 'number';
    return false;
EOD;
  }

  public function jsEvaluate() {
    return 'return "http://url";';
  }

  public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
    return new CategoryImageExtractor($childExtractors);
  }
}

class CategoryImageExtractor extends Extractor {
  private $extractors;
  private $link;
  private $hasType;
  private $types = array();

  public function __construct($extractors) {
    $this->extractors = $extractors;
    $this->hasType = count($extractors) == 3;
    $this->link = \Context::getContext()->link;
    if ($this->hasType) {
      $types = \ImageType::getImagesTypes('categories');
      foreach ($types as $type) {
        $this->types[$type['id_image_type']] = $type['name'];
      }
    }
  }

  public function getValue($resultset) {
    $imageId = $this->extractors[0]->getValue($resultset);
    $name = $this->extractors[1]->getValue($resultset);
    $type = null;
    if ($this->hasType) {
      $typeId = $this->extractors[2]->getValue($resultset);
      if (isset($this->types[$typeId])) {
        $type = $this->types[$typeId];
      }
    }
    return CategoryImageFunction::getUrl($this->link, $name, $imageId, $type);
  }
}
