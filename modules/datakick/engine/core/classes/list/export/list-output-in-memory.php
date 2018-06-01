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

class ListOutputInMemory implements ListOutput {
  private $list = array();

  public function getList() {
    return $this->list;
  }

  public function addRow($row) {
    $mapped = array_map(function($col) {
      $type = 'string';
      if (is_a($col, 'DateTime')) {
        $type = 'datetime';
      } else if (is_a($col, 'DataKick\Currency')) {
        $type = 'currency';
      }
      return Types::jsonValue($type, $col);
    }, $row);
    array_push($this->list, $mapped);
  }

  public function finish() {
  }

  public function getCount() {
    return count($this->list);
  }
}
