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
require_once(DATAKICK_CORE.'/extractor/extractor.php');

class CsvColumnExtractor extends Extractor {
    public function __construct($index) {
      $this->index = $index;
    }

    public function getValue($resultset) {
      if (isset($resultset[$this->index])) {
        return $resultset[$this->index];
      }
      return null;
    }
}
