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

class Utils {
  static function printXML($tree, $indent=0) {
    $padding = str_repeat(" ", $indent * 2);
    $tag = $tree['id'];
    if (isset($tree['data'])) {
      $tag = strtoupper($tag);
    }

    if (isset($tree['children'])) {
      $children = $tree['children'];
      print $padding . '<' . $tag . ">\n";
      foreach($tree['children'] as $child) {
        self::printXML($child, $indent+1);
      }
      print $padding . '</' . $tag . ">\n";
    } else {
      print $padding . '<' . $tag ."/>\n";
    }
  }

  static function decamelize($str, $delimiter='_') {
    return trim(strtolower(preg_replace('/[A-Z]/', $delimiter.'$0', $str)), $delimiter);
  }


  static function toCamelCase($string, $capitalizeFirstCharacter=false) {
    $str = str_replace(' ', '', ucwords(preg_replace('/[^a-z0-9]/', ' ', strtolower($string))));
    if (! $capitalizeFirstCharacter) {
      $str[0] = strtolower($str[0]);
    }
    return $str;
  }

  static function toUppercaseWords($string) {
    $str = ucwords(preg_replace('/[^a-z0-9]/', ' ', strtolower($string)));
    return preg_replace('/\s+/', ' ', $str);
  }


  public static function cartesian($input, $val) {
    $result = array();

    while (list($key, $values) = each($input)) {
      if (empty($values)) {
        // empty result if one of the input arrays is empty
        return array();
      }

      if (empty($result)) {
        // seed
        foreach($values as $value) {
          $result[] = array(
            'value' => $val,
            $key => $value
          );
        }
      } else {
        $append = array();
        foreach($result as &$product) {
          $product[$key] = array_shift($values);
          $copy = $product;
          foreach ($values as $item) {
            $copy[$key] = $item;
            $append[] = $copy;
          }
          array_unshift($values, $product[$key]);
        }
        $result = array_merge($result, $append);
      }
    }
    return $result;
  }

  public static function extract($key, array $arr) {
    if (isset($arr[$key])) {
      return $arr[$key];
    }
    throw new \Exception("Key `$key` not found in ".print_r($arr, true));
  }

  public static function endsWith($needle, $haystack) {
    $length = strlen($needle);
    return $length === 0 || (substr($haystack, -$length) === $needle);
  }

  public static function startsWith($needle, $haystack) {
    $length = strlen($needle);
    return substr($haystack, 0, $length) === $needle;
  }

  public static function zip($arr1, $arr2) {
    $cnt = count($arr1);
    if ($cnt !== count($arr2)) {
      throw new \Exception('Zip failed');
    }
    $mapping = array();
    for ($i = 0; $i < $cnt; $i++) {
      $mapping[$arr1[$i]] = $arr2[$i];
    }
    return $mapping;
  }
}
