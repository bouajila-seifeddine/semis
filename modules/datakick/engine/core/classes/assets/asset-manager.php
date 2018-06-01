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

class AssetManager {
  const LEVELS = 2;

  public function __construct(Factory $factory) {
    $this->factory = $factory;
  }

  public function addAsset($uri, $sourceType=null) {
    if (! $sourceType) {
      $sourceType = self::detectSourceType($uri);
    }
    if ($sourceType === 'file') {
      if (is_file($uri)) {
        return $this->addFile($uri, $sourceType, $uri);
      } else {
        throw new UserError("File `$uri` not found");
      }
    } else if ($sourceType === 'url') {
      $asset = $this->getUrlAsset($uri);
      if ($asset) {
        $path = $this->getPath($asset);
        if (is_file($path)) {
          return $asset;
        }
        $table = $this->factory->getServiceTable('assets');
        $this->factory->getConnection()->delete($table, array('hash' => $asset->getHash()));
      }
      $ret = $this->factory->fetch($uri)->download();
      if ($ret['success']) {
        $asset = $this->addFile($ret['path'], $sourceType, $uri, $ret['name']);
        @unlink($ret['path']);
        return $asset;
      } else {
        $code = $ret['code'];
        throw new UserError("Failed to download `$uri`: error code $code");
      }
    } else {
      throw new UserError("Invalid asset source type $sourceType");
    }
  }

  public function hasVariant($asset, $variant) {
    return @is_file($this->getVariantPath($asset, $variant));
  }

  public function getVariantPath($asset, $variant) {
    $path = $this->getPath($asset);
    return "$path-$variant";
  }

  public function addVariant($asset, $variant, $source) {
    if (@is_file($source)) {
      if (@is_file($target)) {
        @unlink($target);
      }
      $target = $this->getVariantPath($asset, $variant);
      @copy($source, $target);
    }
  }

  public function getPath(Asset $asset) {
    return $this->getPathForHash($asset->getHash());
  }

  public function getPathForHash($hash) {
    return $this->getDirectoryForHash($hash) . $hash;
  }

  private function addFile($file, $sourceType, $origin, $name=null) {
    $hash = sha1_file($file);
    $size = filesize($file);
    if (! $name) {
      $name = basename($file);
    }
    $type = Asset::detectType($file);
    $path = $this->getPathForHash($hash);
    if (! @is_file($path)) {
      @copy($file, $path);
    }
    $table = $this->factory->getServiceTable('assets');
    $conn = $this->factory->getConnection();
    if (! $conn->singleSelect("SELECT 1 FROM $table WHERE hash='$hash'")) {
      $conn->insert($table, array(
        'hash' => $hash,
        'type' => $type,
        'name' => $name,
        'size' => $size,
        'source_type' => $sourceType,
        'origin' => $origin
      ));
    }
    return new Asset($type, $hash, $name, $origin, $size);
  }

  public function getUrlAsset($url) {
    $table = $this->factory->getServiceTable('assets');
    $conn = $this->factory->getConnection();
    $url = $conn->escape($url);
    $ret = $conn->query("SELECT * FROM $table WHERE source_type='url' AND origin='$url'");
    if ($ret) {
      $row = $ret->fetch();
      if ($row) {
        return new Asset($row['type'], $row['hash'], $row['name'], $row['origin'], (int)$row['size']);
      }
    }
    return false;
  }

  private function getDirectoryForHash($hash) {
    $dir = $this->getDirectory();
    for ($i=0; $i<self::LEVELS; $i++) {
      $char = $hash[$i];
      $dir = $this->ensureDirectory($dir . $char);
    }
    return $this->ensureDirectory($dir . $hash);
  }

  private function getDirectory() {
    return $this->ensureDirectory($this->factory->getAssetsDirectory());
  }

  private function ensureDirectory($path) {
    $dir = new Directory($path);
    if (! $dir->ensure(true, true, true)) {
      throw new UserError($dir->getError());
    }
    return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
  }

  public function deleteAll() {
    self::traverse($this->getDirectory(), 0, '', new AssetDeleteVisitor($this->factory));
  }

  public function scan() {
    self::traverse($this->getDirectory(), 0, '', new AssetCollectVisitor($this->factory));
  }

  private static function traverse($root, $depth, $prefix, $visitor) {
    $items = scandir($root);
    foreach ($items as $item) {
      if ($item == '.' || $item == '..') {
        continue;
      }
      $path = $root . $item;
      if ($depth == self::LEVELS && is_file($path) && self::isHash($prefix, $item)) {
        $hash = sha1_file($path);
        if ($hash === $item) {
          $visitor->visit(array(
            'path' => $path,
            'directory' => $root,
            'hash' => $item
          ));
        }
      } else if (is_dir($path) && strlen($item) === 1) {
        self::traverse($path . DIRECTORY_SEPARATOR, $depth + 1, $prefix.$item, $visitor);
      }
    }
    if ($depth === 0) {
      $visitor->after();
    }
  }

  private static function isHash($prefix, $str) {
    if (Utils::startsWith($prefix, $str)) {
      return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
    }
    return false;
  }

  private static function detectSourceType($uri) {
    if (Utils::startsWith('http', strtolower($uri))) {
      if (preg_match('/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/', $uri)) {
        return 'url';
      }
    } else {
      if (preg_match('/^\/?[^\/ ]+(\/[^\/ ]+)+\/?$/', $uri)) {
        return 'file';
      }
    }
    throw new UserError("`$uri` is not a valid url or file path");
  }

}
