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

class XmlOutputInMemory implements XmlOutput {
    public function __construct() {
        $this->path = array(array());
    }

    public function openNode($tag, $omitEmpty) {
        $node = array(
            'tag' => $tag,
            'omitEmpty' => $omitEmpty
        );
        array_push($this->path, $node);
    }

    public function closeNode() {
        $last = array_pop($this->path);
        if ($last['omitEmpty']) {
          if ($this->isEmpty($last))
            return;
        }
        $parent = array_pop($this->path);
        if (! isset($parent['children'])) {
            $parent['children'] = array();
        }
        array_push($parent['children'], $last);
        array_push($this->path, $parent);
    }

    private function isEmpty($node) {
        if (isset($node['attributes']))
          return false;
        if (isset($node['children']))
          return false;
        if (isset($node['content'])) {
          $content = $node['content'];
          if (!is_null($content) && $content != '')
            return false;
        }
        return true;
    }

    public function addAttribute($name, $value) {
        $cur = array_pop($this->path);
        if (! isset($cur['attributes'])) {
            $cur['attributes'] = array();
        }
        if (is_bool($value))
          $value = $value ? 'true' : 'false';
        $cur['attributes'][$name] = $value;
        array_push($this->path, $cur);
    }

    public function setContent($value, $cdata) {
        $cur = array_pop($this->path);
        $cur['content'] = $value;
        $cur['cdata'] = $cdata;
        array_push($this->path, $cur);
    }

    public function getXml() {
        $root = $this->path[0];
        if ($root && isset($root['children']) && $root['children']) {
          return $this->path[0]['children'][0];
        }
        return null;
    }

    public function finish() {
    }
}
