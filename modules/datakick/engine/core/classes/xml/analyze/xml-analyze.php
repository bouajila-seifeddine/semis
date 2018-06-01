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

class XmlAnalyze extends XmlReader {
  private $root;
  private $structure = array();
  private $seenChildren = array();

  public function after($progress) {
    if ($this->root) {
      $rootNode = $this->structure[$this->root];
      return new XmlStructure($rootNode->toArray($this->root, $this->structure));
    } else {
      throw new UserError('Invalid XML file');
    }
  }

  public function enterNode($path, $node) {
    $name = $node['name'];
    $cnt = count($this->seenChildren);
    if ($cnt > 0) {
      if (! isset($this->seenChildren[$cnt-1][$name])) {
        $this->seenChildren[$cnt-1][$name] = 1;
      } else {
        $this->seenChildren[$cnt-1][$name]++;
      }
    }
    $id = $this->printPath($path);
    if (! isset($this->structure[$id])) {
      $this->structure[$id] = new XmlNodeInfo($node['name']);
    }
    if (! $this->root) {
      $this->root = $id;
    }
    array_push($this->seenChildren, array());
  }

  public function leaveNode($path, $node) {
    $id = $this->printPath($path);
    $info = $this->structure[$id];
    if ($info) {
      $children = array_pop($this->seenChildren);
      $info->merge($children, $node['value'], $node['attributes']);
    }
  }
}
