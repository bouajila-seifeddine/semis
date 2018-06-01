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

abstract class Place {
  private $type;
  private $id;
  private $name;
  private $config;
  private $error;

  public function __construct($type, $id, $name, $config) {
    $this->type = $type;
    $this->id = $id;
    $this->name = $name;
    $this->config = $config;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getType() {
    return $this->type;
  }

  public function saveFile($filepath, $path) {
    try {
      $permError = $this->getPermissionError($path);
      if ($permError) {
        return $this->setError($permError, true);
      }
      return $this->doSaveFile($filepath, self::getPath($path));
    } catch (UserError $e) {
      $this->setError($e->getMessage());
    } catch (\Exception $e) {
      $this->setError("Failed to upload file $filepath to $path");
    }
    return false;
  }

  public function deleteFile($path) {
    try {
      $permError = $this->getPermissionError($path);
      if ($permError) {
        return $this->setError($permError, true);
      }
      return $this->doDeleteFile(self::getPath($path));
    } catch (UserError $e) {
      $this->setError($e->getMessage());
    } catch (\Exception $e) {
      $this->setError("Failed to delete file $path");
    }
    return false;
  }

  public static function getPath($path, $allowRelative=false) {
    $arr = preg_split("/[\\/]/", $path);
    if ($allowRelative) {
      return array_filter($arr);
    }
    return array_reduce($arr, array('Datakick\Place', 'reducePath'), array());
  }

  private static function reducePath($path, $item) {
    $s = trim($item);
    if (empty($s)) {
      return $path;
    }
    if ($s == '.') {
      return $path;
    }
    if ($s == '..') {
      if (count($path) > 0) {
        array_pop($path);
      }
      return $path;
    }
    array_push($path, $s);
    return $path;
  }

  protected function setError($error, $formatted = false) {
    $this->error = $formatted ? $error : $this->formatError($error);
    return false;
  }

  public function getError() {
    return $this->error;
  }

  protected function formatError($error) {
    if ($error) {
      $name = $this->getName();
      $type = $this->getType();
      return "Place: $type `$name`: $error";
    }
    return null;
  }

  public function finalize() {
  }

  public function getWriteUri($path) {
    return null;
  }

  public function getConfig($name, $throw=true) {
    if (! isset($this->config[$name])) {
      if ($throw) {
        throw new \Exception("{$this->type} Place configuration does not contain: $name");
      }
      return null;
    }
    return $this->config[$name];
  }

  protected abstract function doSaveFile($filepath, array $path);
  protected abstract function doDeleteFile(array $path);
  public abstract function getPermissionError($filepath);
}
