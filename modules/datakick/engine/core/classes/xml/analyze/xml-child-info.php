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

class XmlChildInfo {
  private $minCount;
  private $maxCount;

  public function __construct($count) {
    $this->minCount = $this->maxCount = $count;
  }

  public function seen($count) {
    $this->minCount = min($count, $this->minCount);
    $this->maxCount = max($count, $this->maxCount);
  }

  public function getMin() {
    return $this->minCount;
  }

  public function getMax() {
    return $this->maxCount;
  }
}
