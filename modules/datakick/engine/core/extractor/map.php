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

class MapExtractor extends Extractor {
    private $keyExtractor;
    private $notFoundValue;
    private $map;

    public function __construct($keyExtractor, $map, $notFoundValue=null) {
      $this->keyExtractor = $keyExtractor;
      $this->map = $map;
      $this->notFoundValue = $notFoundValue;
    }

    public function getValue($resultset) {
        $key = $this->keyExtractor->getValue($resultset);
        return isset($this->map[$key]) ? $this->map[$key] : $this->notFoundValue;
    }
}
