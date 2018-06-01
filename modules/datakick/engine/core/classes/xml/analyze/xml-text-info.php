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

class XmlTextInfo {
  const MAX_VALUES = 100;
  const ENUM_THRESHOLD = 30;

  private $total = 0;
  private $type;
  private $mixed = false;
  private $nullable = 0;
  private $values = array();
  private $min;
  private $max;

  private static $types = array('datetime', 'boolean', 'image-path', 'path', 'image-url', 'url', 'email', 'currency', 'integer', 'number', 'alphanum', 'identifier', 'string');

  public function __construct($value) {
    $this->addValue($value);
  }

  public function setNullable() {
    $this->nullable++;
  }

  public function isAlwaysNull() {
    return $this->nullable == $this->total;
  }

  public function canBeEmpty() {
    return $this->nullable > 0;
  }

  public function addValue($value) {
    $this->total++;
    if (is_null($value) || empty($value)) {
      $this->setNullable();
    } else {
      $this->mergeType($value);
      $this->mergeLength($value);
      if (strlen($value) < 256) {
        if (isset($this->values[$value])) {
          $this->values[$value] += 1;
        } else {
          if (! $this->mixed) {
            $this->values[$value] = 1;
            if (count($this->values) >= self::MAX_VALUES) {
              $this->mixed = true;
            }
          }
        }
      } else {
        $this->mixed = true;
      }
    }
  }

  private function mergeType($value) {
    $this->type = $this->detectType($value);
  }

  private function mergeLength($value) {
    $len = strlen($value);
    if (is_null($this->min)) {
      $this->min = $this->max = $len;
    } else {
      $this->min = min($len, $this->min);
      $this->max = max($len, $this->max);
    }
  }

  private function isDate($value) {
    if (is_numeric($value)) {
      // prevent 12.3 to be considered date
      return false;
    }
    try {
      new \DateTime($value);
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  private function isCurrency($value) {
    if ($value) {
      return !!Types::parseCurrency($value);
    }
    return true;
  }

  private function canBe($type) {
    if (false && !in_array($type, self::$types)) {
      throw new \Exception("Error: $type");
    }
    if (is_null($this->type))
      return true;
    return in_array($type, $this->type);
  }

  private function isImage($uri) {
    $str = strtolower($uri);
    foreach (array('.jpg', '.png', '.jpeg', '.gif', '.svg', '.tiff', '.bmp') as $ext) {
      if (Utils::endsWith($str, $ext)) {
        return true;
      }
    }
    return false;
  }

  private function isFQDN($FQDN) {
    return preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $FQDN) > 0;
  }

  private function detectType($value) {
    $type = array('string');

    if ($this->canBe('currency') && $this->isCurrency($value)) {
      $type[] = 'currency';
    }

    if ($this->canBe('identifier') && preg_match('/^[a-zA-Z0-9_#.-]+$/', $value)) {
      $type[] = 'identifier';
    }

    if ($this->canBe('alphanum') && preg_match('/^[a-zA-Z0-9]+$/', $value)) {
      $type[] = 'alphanum';
    }

    if ($this->canBe('email') && filter_var($value, FILTER_VALIDATE_EMAIL)) {
      $type[] = 'email';
    }

    if ($this->canBe('datetime') && $this->isDate($value)) {
      $type[] = 'datetime';
    }

    if ($this->canBe('path') && !Utils::startsWith('http', strtolower($value)) && preg_match('/^\/?[^\/ ]+(\/[^\/ ]+)+\/?$/', $value)) {
      $p = explode('/', $value);
      if (! $this->isFQDN($p[0])) {
        $type[] = 'path';
        if ($this->canBe('image-path') && $this->isImage($value)) {
          $type[] = 'image-path';
        }
      }
    }

    if ($this->canBe('url') && preg_match('/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/', $value)) {
      $type[] = 'url';
      if ($this->canBe('image-url') && $this->isImage($value)) {
        $type[] = 'image-url';
      }
    }

    if ($this->canBe('number') && is_numeric($value)) {
      $type[] = 'number';
      if ($this->canBe('integer') && ctype_digit($value)) {
        $type[] = 'integer';
      }
    }

    if ($this->canBe('boolean') && (Types::isTruthyValue($value) || Types::isFalsyValue($value))) {
      $type[] = 'boolean';
    }
    return $type;
  }

  private function isEnum() {
    // do we have enought data?
    if ($this->total < self::ENUM_THRESHOLD)
      return false;
    // nullable fields can't be enums
    if ($this->nullable)
      return false;
    // length limith
    if ($this->max > 32)
      return false;
    // check values
    if ($this->mixed)
      return false;
    $threshold = max(3, min(10, (int)($this->total / 10)));
    if (count($this->values) > $threshold)
      return false;
    // and it shouldn't by any one of these types
    if (array_intersect(array('email', 'datetime', 'image-url', 'url', 'boolean'), $this->type))
      return false;
    return true;
  }

  public function getType() {
    if (is_null($this->type)) {
      return 'string';
    }
    foreach (self::$types as $type) {
      if ($this->canBe($type)) {
        if ($type === 'boolean') {
          if ($this->canBe('number') && $this->total <= 5) {
            return 'number';
          }
        }
        return $type;
      }
    }
    return 'string';
  }

  private function isUnique() {
    foreach ($this->values as $key=>$cnt) {
      if ($cnt > 1) {
        return false;
      }
    }
    return true;
  }

  public function toArray() {
    $vals = $this->values;
    if (! $this->mixed) {
      if ($this->nullable) {
        $vals[''] = $this->nullable;
      }
    }
    $unique = false;
    if (! $this->nullable && $this->values) {
      $unique = $this->isUnique();
    }
    $ret = array(
      'type' => $this->getType(),
      'isEnum' => $this->isEnum(),
      'nullable' => $this->nullable,
      'minLength' => is_null($this->min) ? 0 : $this->min,
      'maxLength' => is_null($this->max) ? 0 : $this->max,
      'total' => $this->total,
      'hasAllValues' => !$this->mixed,
      'isUnique' => $unique
    );
    if (! empty($vals)) {
      $ret['values'] = $vals;
    }
    return $ret;
  }

}
