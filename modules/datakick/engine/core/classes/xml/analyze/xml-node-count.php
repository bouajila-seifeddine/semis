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

class XmlNodeCount extends XmlReader {
  private $counts = array();

  public function after($progress) {
    return $this->counts;
  }

  public function enterNode($path, $node) {
    $name = $node['name'];
    $id = $this->printPath($path);
    if (! isset($this->counts[$id])) {
      $this->counts[$id] = 1;
    } else {
      $this->counts[$id]++;
    }
  }
}
