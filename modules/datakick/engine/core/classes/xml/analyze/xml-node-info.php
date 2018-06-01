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

class XmlNodeInfo {
  private $name;
  private $count;
  private $contentType;
  private $value;
  private $children;
  private $attributes;

  public function __construct($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function merge($children, $value, $attributes) {
    $this->count++;
    $this->contentType = $this->mergeContentType(empty($children) ? 'text' : 'nodes');
    if ($this->hasNodes()) {
      $this->mergeChildren($children);
    }
    if ($this->hasValue()) {
      $this->mergeValue($value);
    }
    $this->mergeAttributes($attributes);
  }

  private function hasNodes() {
    return ($this->contentType == 'nodes' || $this->contentType == 'mixed');
  }

  private function hasValue() {
    return ($this->contentType == 'text' || $this->contentType == 'mixed');
  }

  private function mergeContentType($contentType) {
    if (is_null($this->contentType) || $this->contentType === $contentType) {
      return $contentType;
    }
    return 'mixed';
  }

  private function mergeAttributes($attributes) {
    if (is_null($this->attributes)) {
      $this->attributes = array();
      foreach($attributes as $attr => $value) {
        $this->attributes[$attr] = new XmlTextInfo($value);
      }
    } else {
      foreach ($this->attributes as $attr => $info) {
        if (! isset($attributes[$attr])) {
          $info->addValue(null);
        }
      }
      foreach ($attributes as $attr => $val) {
        if (! isset($this->attributes[$attr])) {
          $this->attributes[$attr] = new XmlTextInfo(null);
        } else {
          $this->attributes[$attr]->addValue($val);
        }
      }
    }
  }


  private function mergeValue($value) {
    if (is_null($this->value)) {
      $this->value = new XmlTextInfo($value);
    } else {
      $this->value->addValue($value);
    }
  }

  private function mergeChildren($children) {
    if (is_null($this->children)) {
      $this->children = array();
      foreach ($children as $child=>$num) {
        $this->children[$child] = new XmlChildInfo($num);
      }
    } else {
      foreach ($this->children as $child => $info) {
        if (! isset($children[$child])) {
          $info->seen(0);
        }
      }
      foreach ($children as $child=>$num) {
        if (! isset($this->children[$child])) {
          $this->children[$child] = new XmlChildInfo(0);
        }
        $this->children[$child]->seen($num);
      }
    }
  }

  public function getContentType() {
    $type = $this->contentType;
    if ($type === 'text') {
      if (! $this->value) {
        return 'none';
      }
      if ($this->value->isAlwaysNull()) {
        return 'none';
      }
    }
    return $type;
  }

  public function toArray($prefix, $nodes, $min=1, $max=1) {
    $ret = array(
      'tag' => $this->getName(),
      'contentType' => $this->getContentType(),
      'totalCount' => $this->count,
      'min' => $min,
      'max' => $max
    );
    if ($this->hasNodes()) {
      $children = array();
      foreach ($this->children as $child => $info) {
        $path = $prefix . '/' . $child;
        $nodeInfo = $nodes[$path];
        $children[$child] = $nodeInfo->toArray($path, $nodes, $info->getMin(), $info->getMax());
      }
      $ret['children'] = $children;
    }
    if ($this->hasValue() && $ret['contentType'] != 'none') {
      $ret['value'] = $this->value ? $this->value->toArray() : null;
    }
    if ($this->attributes) {
      $attr = array();
      foreach ($this->attributes as $key => $info) {
        $attr[$key] = $info->toArray();
      }
      $ret['attributes'] = $attr;
    }
    return $ret;
  }

  public function getAttributeNames() {
    if ($this->attributes) {
      return array_keys($this->attributes);
    }
    return array();
  }

}
