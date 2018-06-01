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

class Asset {
  private $type;
  private $hash;
  private $filename;
  private $origin;
  private $size;

  public function __construct($type, $hash, $filename, $origin, $size) {
    $this->type = $type;
    $this->hash = $hash;
    $this->filename = $filename;
    $this->origin = $origin;
    $this->size = $size;
  }

  public function getHash() {
    return $this->hash;
  }

  public function isImage() {
    return Utils::startsWith('image', $this->type);
  }

  public static function detectType($file) {
    if (function_exists('mime_content_type')) {
      return mime_content_type($file);
    }
    if (function_exists('finfo_open')) {
      $const = defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME;
      $finfo = finfo_open($const);
      $mimeType = finfo_file($finfo, $file);
      finfo_close($finfo);
      return $mimeType;
    }
    if (function_exists('exif_imagetype')) {
      return image_type_to_mime_type(exif_imagetype($file));
    }
    if (function_exists('getimagesize')) {
      $info = getimagesize($file);
      if ($info != false) {
        return image_type_to_mime_type($info[2]);
      }
    }
    return 'unknown';
  }
}
