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

class ExtractXmlAttribute implements XmlImportExtractor  {
  private $attribute;

  public function __construct($attribute) {
    $this->attribute = $attribute;
  }

  public function extract(XmlNode $node) {
    return $node->getAttribute($this->attribute);
  }
}
