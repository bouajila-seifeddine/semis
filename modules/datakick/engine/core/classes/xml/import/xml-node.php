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

class XmlNode {
  private $name;
  private $children;
  private $value;
  private $attributes;

  public function __construct($name) {
    $this->name = $name;
  }

  public function traverse(&$fields, $parameterValues, $parameterPaths, $fieldsPaths, $prefix='') {
    $path = $prefix . ($prefix ? '/' : '') . $this->name;
    if (isset($parameterPaths[$path])) {
      $parameterValues = $this->restrictParameterValues($parameterValues, $parameterPaths, $path);
    }
    if (isset($fieldsPaths[$path])) {
      foreach ($fieldsPaths[$path] as $alias => $arr) {
        $field = $arr['def'];
        $extractor = $arr['extractor'];
        $value = $this->extract($extractor, $this->getFieldMessage($field, $path));
        $this->importField($fields, $alias, $field, $value, $parameterValues);
      }
    }
    if (isset($this->children)) {
      foreach ($this->children as $child) {
        foreach ($child as $variant) {
          $variant->traverse($fields, $parameterValues, $parameterPaths, $fieldsPaths, $path);
        }
      }
    }
  }

  private function getFieldMessage($fld, $path) {
    $name = $fld->getName();
    return "Field '$name': failed to process value from xml path '$path'";
  }

  public function extract($extractor, $message) {
    try {
      return $extractor->extract($this);
    } catch (\Exception $e) {
      throw new UserError($message . ': '. $e->getMessage());
    }
  }

  public function importField(&$fields, $alias, $field, $value, $parameterValues) {
    $parameters = $field->getParameters();
    if (empty($parameters)) {
      $fields[$alias] = $value;
    } else {
      if (! array_key_exists($alias, $fields)) {
        $fields[$alias] = array();
      }
      $paramValues = array();
      foreach ($parameters as $param) {
        if (isset($parameterValues[$param])) {
          $paramValues[$param] = $parameterValues[$param];
        } else {
          // error if parameter not found
          throw new \Exception("Parameter `$param` not defined for `$alias`");
        }
      }
      $fields[$alias] = array_merge($fields[$alias], Utils::cartesian($paramValues, $value));
    }
  }


  public function enter($child) {
    $c = new XmlNode($child);

    if (! $this->children) {
      $this->children = array();
    }

    if (! isset($this->children[$child])) {
      $this->children[$child] = array();
    }

    array_push($this->children[$child], $c);
    return $c;
  }

  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }

  public function setAttribute($name, $value) {
    if (is_null($this->attributes)) {
      $this->attributes = array();
    }
    $this->attributes[$name] = $value;
  }

  public function getNodes($path) {
    if (empty($path)) {
      return array();
    }
    $child = array_shift($path);
    if ($child === '.') {
      if (empty($path)) {
        return array($this);
      }
      $child = array_shift($path);
    }
    if (isset($this->children[$child])) {
      $c = $this->children[$child];
      if (empty($path)) {
        return $c;
      }
      if (count($c) == 1) {
        return $c[0]->getNodes($path);
      }
      $ret = array();
      foreach ($c as $cc) {
        $ret = array_merge($ret, $cc->getNodes($path));
      }
      return $ret;
    } else {
      return array();
    }
  }

  public function extractAll($path, XmlImportExtractor $extractor, $fullpath='') {
    if (empty($path) || count($path) == 1 && $path[0] == '.') {
      $value = $this->extract($extractor, "Failed to process value from xml path '$fullpath'");
      return array($value);
    }
    $child = array_shift($path);
    $fullpath .= ($fullpath ? '/' : '') . $child;
    if ($child === '.') {
      $child = array_shift($path);
      $fullpath .= ($fullpath ? '/' : '') . $child;
    }
    if (isset($this->children[$child])) {
      $ret = array();
      foreach ($this->children[$child] as $c) {
        $ret = array_merge($ret, $c->extractAll($path, $extractor, $fullpath));
      }
      return $ret;
    } else {
      return array();
    }
  }

  public function getAttribute($attribute) {
    if (is_null($this->attributes)) {
      return null;
    }
    return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
  }

  private function restrictParameterValues($values, $parameterPaths, $path) {
    $parameters = $parameterPaths[$path];
    foreach ($parameters as $param => $extractor) {
      $value = $this->extract($extractor, "Failed to process value from xml path '$path'");
      $values[$param] = array($value);
    }
    return $values;
  }
}
