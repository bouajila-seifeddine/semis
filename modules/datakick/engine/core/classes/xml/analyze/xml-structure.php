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

class XmlStructure implements ImportDataStructure {
  private $structure;
  private $paths;

  public function __construct($structure) {
    $this->structure = $structure;
    $this->paths = array();
    self::collectNodes('', $structure, $this->paths);
  }

  public function getData() {
    return $this->structure;
  }

  public function getPaths() {
    return $this->paths;
  }

  public function getFileType() {
    return "xml";
  }

  public function satisfies(array $requiredInputs) {
    return !array_diff($requiredInputs, $this->paths);
  }

  public function getGroupingIdentifier() {
    return $this->structure['tag'];
  }

  private static function collectNodes($prefix, array $node, array &$nodes) {
    $path = $prefix . '/' . $node['tag'];
    $nodes[] = $path;
    if (isset($node['attributes'])) {
      foreach ($node['attributes'] as $key => $_) {
        $nodes[] = $path . "[$key]";
      }
    }

    if (isset($node['children'])) {
      foreach ($node['children'] as $child) {
        self::collectNodes($path, $child, $nodes);
      }
    }
  }

}
