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

class LocalPlace extends Place {
  private $root;

  public function __construct($id, $name, $config, $factory) {
    parent::__construct('local', $id, $name, $config);
    $root = $this->getConfig('root');
    $absPath = isset($config['absPath']) && $config['absPath'];
    if (! $absPath) {
      $siteInfo = $factory->getSiteInfo();
      $root = implode(DIRECTORY_SEPARATOR, Place::getPath($root));
      $root = $siteInfo['rootDir'] . DIRECTORY_SEPARATOR . $root;
    }
    $this->root = $root;
  }

  public function getWriteUri($path) {
    return $this->root . DIRECTORY_SEPARATOR . implode(self::getPath($path), DIRECTORY_SEPARATOR);
  }

  public function getPermissionError($path) {
    $local = implode(self::getPath($path), DIRECTORY_SEPARATOR);
    $target = $this->root . DIRECTORY_SEPARATOR . $local;
    $dir = new Directory(dirname($target));
    if (! $dir->ensure()) {
      return $this->formatError($dir->getError());
    }
    return null;
  }

  protected function doSaveFile($file, array $path) {
    $local = implode($path, DIRECTORY_SEPARATOR);
    $target = $this->root . DIRECTORY_SEPARATOR . $local;
    $ok = copy($file, $target);
    if (! $ok) {
      $this->setError('Failed to write file: ' . $local);
    }
    return $ok;
  }

  protected function doDeleteFile(array $path) {
    $local = implode($path, DIRECTORY_SEPARATOR);
    $target = $this->root . DIRECTORY_SEPARATOR . $local;
    if (! file_exists($target)) {
      $this->setError('File not found: ' . $local);
      return false;
    }

    $ret = unlink($target);
    if (! $ret) {
      $this->setError('Failed to delete file: ' . $local);
    }
    return $ret;
  }
}
