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

class FtpPlace extends Place {
  private $server;
  private $username;
  private $password;
  private $root;
  private $conn;
  private $failure = false;
  private $cache = array();

  public function __construct($id, $name, $config, $factory) {
    parent::__construct('ftp', $id, $name, $config);
    $this->root = $this->getConfig('root');
    $this->server = $this->getConfig('server');
    $this->username = $this->getConfig('username');
    $this->password = $this->getConfig('pwd');
    $this->password = $factory->getCipher()->decrypt($this->password);
  }

  public function getPermissionError($path) {
    $path = array_merge(self::getPath($this->root), self::getPath($path));
    $file = array_pop($path);
    if (! empty($path)) {
      return $this->ensureDirectory($path);
    }
  }

  public function finalize() {
    if ($this->conn) {
      ftp_close($this->conn);
      $this->conn = null;
    }
  }

  protected function doSaveFile($file, array $path) {
    $local = implode($path, '/');
    $target = $this->root . '/' . $local;
    $conn = $this->getConnection();
    $put = @ftp_put($conn, $target, $file, FTP_BINARY);
    if (! $put) {
      $this->setError("Failed to write file: $local");
    }
    return $put;
  }

  protected function doDeleteFile(array $path) {
    $local = implode($path, '/');
    $target = $this->root . '/' . $local;
    $ret = @ftp_delete($this->getConnection(), $target);
    if (! $ret) {
      $this->setError('Failed to delete file: ' . $local);
    }
    return $ret;
  }

  private function getConnection() {
    if ($this->failure) {
      throw new UserError("Failed to login to ftp server");
    }
    if (! $this->conn) {
      $this->conn = ftp_connect($this->server);
      $res = @ftp_login($this->conn, $this->username, $this->password);
      if (! $res) {
        $this->failure = true;
        throw new UserError("Failed to login to ftp server");
      }
    }
    return $this->conn;
  }

  private function ensureDirectory($path) {
    $dir = '/' . implode($path, '/');
    if (! $this->directoryExists($dir)) {
      $dir = '';
      foreach ($path as $p) {
        $dir .= "/$p";
        if (! $this->directoryExists($dir)) {
          if (@ftp_mkdir($this->getConnection(), $dir)) {
            $this->cache[$dir] = true;
          } else {
            return $this->formatError("Failed to create directory $dir");
          }
        }
      }
    }
  }

  private function directoryExists($dir) {
    if (isset($this->cache[$dir])) {
      return $this->cache[$dir];
    }
    $ret = @ftp_chdir($this->getConnection(), $dir);
    if ($ret) {
      $d = '';
      foreach (explode('/', $dir) as $p) {
        if ($p && $p != '') {
          $d .= "/$p";
          $this->cache[$d] = true;
        }
      }
    } else {
      $this->cache[$dir] = false;
    }
    return $ret;
  }
}
