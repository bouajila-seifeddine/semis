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

class ExtractAndTransform implements XmlImportExtractor  {

  public function __construct(XmlImportExtractor $extractor, ImportTransformer $transformer) {
    $this->extractor = $extractor;
    $this->transformer = $transformer;
  }

  public function extract(XmlNode $node) {
    $value = $this->extractor->extract($node);
    return $this->transformer->transform($value, 'string');
  }
}
