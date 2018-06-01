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

class Directory {
  private $path;
  private $error = null;

  public function __construct($path) {
    $this->path = $path;
  }

  public function ensure($writable=true, $secure=true, $strict=false) {
    $dir = $this->path;
    if (! @file_exists($dir)) {
      return $this->create($secure, $strict);
    }
    if (! @is_dir($dir)) {
      return $this->error("Directory does not exists: $dir");
    }
    if ($writable && !@is_writable($dir)) {
      return $this->error("Directory is not writable: $dir");
    }
    if ($secure && !$this->secure($strict)) {
      return false;
    }
    return true;
  }

  public function fileExists($name) {
    return @file_exists($this->path . "/" . $name);
  }

  public function secure($strict=false) {
    $dir = dirname(__FILE__);
    $sourceHtaccess = $strict ? "$dir/htaccess.deny.txt" : "$dir/htaccess.allow.txt";
    $target = $this->path;
    if (!$this->fileExists('index.php')) {
      if (@file_exists("$dir/index.php")) {
        if (!$this->copyFile("$dir/index.php", "index.php")) {
          return false;
        }
      }
    }
    if (!$this->fileExists('.htaccess') && !$this->copyFile($sourceHtaccess, ".htaccess")) {
      return false;
    }
    return true;
  }

  public function copyFile($source, $newName=null) {
    if (is_null($newName)) {
      $newName = basename($source);
    }
    $target = $this->path . '/' . $newName;
    if (! @copy($source, $target)) {
      return $this->error("Failed to copy $source to $target");
    }
    return true;
  }

  private function create($secure=true, $strict=false) {
    $dir = $this->path;
    if (@mkdir($dir, 0777, true)) {
      if ($secure) {
        return $this->secure($strict);
      }
      return true;
    }
    return $this->error("Failed to create directory $dir");
  }

  public function rmdir() {
    self::rrmdir($this->path);
  }

  private static function rrmdir($dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (@filetype($dir . "/" . $object) == "dir") {
              self::rrmdir($dir . "/" . $object);
          } else {
            @unlink($dir . "/" . $object);
          }
        }
      }
      @rmdir($dir);
    }
  }

  public function getError() {
    return $this->error;
  }

  private function error($error) {
    $this->error = $error;
    return false;
  }
}
