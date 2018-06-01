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
require_once(dirname(__FILE__).'/version.php');

class MigrationManager {
  private $versions;
  private $factory;

  public function __construct($factory) {
    $this->versions = array();
    $versions = array();
    $this->factory = $factory;
    $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'versions' . DIRECTORY_SEPARATOR;
    foreach (scandir($path) as $filename) {
      $matches = array();
      if (preg_match('/^(\d+)_(\d+)_(\d+).php$/', $filename, $matches)) {
        $dir = $path . $filename;
        $filepath = $path . $filename;
        if (is_file($filepath)) {
          array_push($versions, str_replace('.php', '', $filename));
        }
      }
    }
    usort($versions, array('Datakick\MigrationManager', 'compare'));
    $prev = null;
    foreach ($versions as $ver) {
      $thisVer = $this->loadVersion($ver, $prev);
      $this->versions[$ver] = $prev = $thisVer;
    }
  }

  public function getVersions() {
    return array_map(array('Datakick\MigrationManager', 'toVersion'), $this->versions);
  }

  public function getLatestDir() {
    $keys = array_keys($this->versions);
    return end($keys);
  }

  public function getLatest() {
    return self::toVersion($this->getLatestDir());
  }

  public function migrateSingle($version) {
    return $this->getVersion($version)->migrate();
  }

  public function migrateFrom($from) {
    $path = $this->getMigrationPath($from);
    $migratedTo = $from;
    foreach($path as $ver) {
      if (! $this->migrateSingle($ver)) {
        return $migratedTo;
      }
      $migratedTo = self::toVersion($ver);
    }
    return $migratedTo;
  }

  public function getMigrationPath($from) {
    $dir = self::fromVersion($from);
    $path = array();
    foreach($this->versions as $version) {
      if (self::compare($version, $dir) > 0) {
        array_push($path, $version);
      }
    }
    return $path;
  }

  public function isLatest($version) {
    return $this->getLatest() == $version;
  }

  public function install($version=null) {
    return $this->getVersion($version)->install();
  }

  public function uninstall($version=null) {
    return $this->getVersion($version)->uninstall();
  }

  public function getVersion($version=null) {
    if (is_null($version))
      $version = $this->getLatestDir();
    $underscored = self::fromVersion($version);
    return $this->versions[$underscored];
  }

  private function loadVersion($version, $prev=null) {
    $underscored = self::fromVersion($version);
    $clazz = "Datakick\\MigrationVersion_$underscored";

    $filename = self::getVersionFile($underscored);
    require_once($filename);
    if (class_exists($clazz))
      return new $clazz($version, $this->factory, $prev);

    throw new \Exception("file $filename does not contain class $clazz");
  }

  private static function getVersionFile($ver) {
    return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'versions' . DIRECTORY_SEPARATOR . $ver . '.php';
  }

  private static function fromVersion($version) {
    return str_replace('.', '_', $version);
  }

  private static function toVersion($version) {
    return str_replace('_', '.', $version);
  }

  private static function getKey($version) {
    $matches = array();
    preg_match('/^(\d+)[_\.](\d+)[_\.](\d+)$/', $version, $matches);
    if (! isset($matches[3])) {
      throw new \Exception("Invalid version `$version`");
    }
    return $matches[1] * 1000000 + $matches[2] * 1000 + $matches[3];
  }

  public static function compare($ver1, $ver2) {
    return self::getKey($ver1) - self::getKey($ver2);
  }
}
