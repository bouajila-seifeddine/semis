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

class PrestashopImageHandler {
  private $assetManager;
  private $type;
  private $memoryLimit;
  private $useHighDPI;
  private $formats;
  private $basePath;
  private $tmp;

  public function __construct(Factory $factory, $type) {
    $this->assetManager = $factory->getAssetManager();
    $this->type = $type;
    $this->memoryLimit = (int)(\Tools::getMemoryLimit());
    $this->useHighDPI = $type === 'products' && (bool)\Configuration::get('PS_HIGHT_DPI');
    $this->formats = \ImageType::getImagesTypes($type);
    $this->basePath = rtrim(self::getBasePath($type), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    $this->tmp = self::getTmpName($type);
  }

  public function setImage($id, $source, $canThrow = true) {
    if ($canThrow) {
      return $this->doSetImage($id, $source);
    }
    try {
      return $this->doSetImage($id, $source);
    } catch (\Exception $e) {
      return false;
    }
  }

  private static function deleteImage($file) {
    if (@is_file($file)) {
      @unlink($file);
    }
  }

  public function deleteTemp($id) {
    $items = @scandir(_PS_TMP_IMG_DIR_);
    $p1 = $this->tmp . '_' . $id;
    $p2 = $this->tmp . '_mini_' . $id;
    foreach ($items as $item) {
      if (
        ($item === "$p1.jpg") ||
        ($item === "$p2.jpg") ||
        Utils::startsWith("${p1}-", $item) ||
        Utils::startsWith("${p1}_", $item) ||
        Utils::startsWith("${p2}-", $item) ||
        Utils::startsWith("${p2}_", $item)) {
        self::deleteImage(_PS_TMP_IMG_DIR_ . $item);
      }
    }
  }

  public function delete($id) {
    $dir = $this->getTargetDir($id);
    if (@is_dir($dir)) {
      $items = @scandir($dir);
      foreach ($items as $item) {
        if ($item === "$id.jpg") {
          self::deleteImage($dir . $item);
        }
        if (Utils::startsWith("$id-", $item)) {
          self::deleteImage($dir . $item);
        }
      }
    }
    if ($this->type != 'products') {
      $this->deleteTemp($id);
    }
  }

  private function doSetImage($id, $source) {
    $target = $this->getTarget($id);
    $targetFile = "$target.jpg";
    self::deleteImage($targetFile);

    $asset = $this->getAsset($source);
    $source = $this->assetManager->getPath($asset);

    if (! $this->checkMemory($source)) {
      throw new UserError('Due to memory limit restrictions, this image cannot be loaded. Please increase your memory_limit value via your server\'s configuration settings. ');
    }

    self::resize($asset, $source, $targetFile);

    foreach ($this->formats as $imageType) {
      $width = (int)$imageType['width'];
      $height = (int)$imageType['height'];
      $name = stripslashes($imageType['name']);
      $targetFile = "$target-$name.jpg";
      self::resize($asset, $source, $targetFile, $width, $height);
      if ($this->useHighDPI) {
        $highDPI = "$target-{$name}2x.jpg";
        self::resize($asset, $source, $highDPI, $width*2, $height*2);
      }
    }
  }

  private function checkMemory($image) {
    if (function_exists('memory_get_usage') && $this->memoryLimit != -1) {
      $currentMemory = @memory_get_usage();
      list($width, $height) = @getimagesize($image);
      $require = $width * $height * 5 * 1.8 + 1048576;
      if ($require + $currentMemory > $this->memoryLimit) {
        return false;
      }
    }
    return true;
  }

  private function resize($asset, $source, $target, $width=null, $height=null) {
    self::deleteImage($target);
    $variant = "resized";
    if (!is_null($width) && !is_null($height)) {
      $variant .= "-{$width}x{$height}";
    }
    if ($this->assetManager->hasVariant($asset, $variant)) {
      $sourceFile = $this->assetManager->getVariantPath($asset, $variant);
      @copy($sourceFile, $target);
    } else {
      \ImageManager::resize($source, $target, $width, $height);
      if (is_file($target)) {
        $this->assetManager->addVariant($asset, $variant, $target);
      }
    }
  }

  public function getProductImageDir($imageId, $ensure=false) {
    $targetDir = $this->basePath;
    $index = $targetDir.'index.php';
    $hasIndex = $ensure && file_exists($index);

    $folders = str_split((string)$imageId);
    foreach ($folders as $folder) {
      $targetDir = $targetDir . $folder . DIRECTORY_SEPARATOR;
      if ($ensure) {
        if (!file_exists($targetDir)) {
          @mkdir($targetDir, 0775, true);
        }
        if ($hasIndex && !file_exists($targetDir.'index.php')) {
          @copy($index, $targetDir.'index.php');
        }
      }
    }
    return $targetDir;
  }

  private function getTargetDir($id) {
    $id = (int)$id;
    if ($this->type === 'products') {
      return $this->getProductImageDir($id, true);
    }
    return $this->basePath;
  }

  private function getTarget($id) {
    return $this->getTargetDir($id) . $id;
  }

  private function getAsset($source) {
    $asset = $this->assetManager->addAsset($source);
    if (!$asset || !$asset->isImage()) {
      throw new UserError("`$source` is not image");
    }
    return $asset;
  }

  private static function getBasePath($type) {
    switch ($type) {
      case 'products':
        return _PS_PROD_IMG_DIR_;
      case 'categories':
        return _PS_CAT_IMG_DIR_;
      case 'manufacturers':
        return _PS_MANU_IMG_DIR_;
      case 'suppliers':
        return _PS_SUPP_IMG_DIR_;
    }
    throw new \Exception("Invalid image type {$this->type}");
  }

  private static function getTmpName($type) {
    switch ($type) {
      case 'products':
        return 'product';
      case 'categories':
        return 'category';
      case 'manufacturers':
        return 'manufacturer';
      case 'suppliers':
        return 'supplier';
    }
  }

}
