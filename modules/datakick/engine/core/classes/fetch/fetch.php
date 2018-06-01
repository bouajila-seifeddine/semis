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

class Fetch {
  private static $mode = null;
  private $fetchMode = 'return';
  private $factory;
  private $url;
  private $method;
  private $body;
  private $headers = array();

  public static function detectMode() {
    if (is_null(self::$mode)) {
      if (function_exists('curl_init')) {
        self::$mode = 'curl';
      } else if (in_array(@ini_get('allow_url_fopen'), array('On', 'on', '1'))) {
        self::$mode = 'fopen';
      } else {
        self::$mode = 'none';
      }
    }
    return self::$mode;
  }

  public function __construct(Factory $factory, $url, $method='GET', $body=null) {
    $this->factory = $factory;
    $this->url = $url;
    $this->setMethod($method);
    $this->setBody($body);
  }

  public function setMethod($method) {
    $this->method = strtoupper($method);
    return $this;
  }

  public function setBody($body) {
    if (! is_null($body)) {
      if (is_string($body)) {
        $this->body = $body;
      } else if (is_array($body)) {
        $this->setHeader('Content-Type', 'application/json');
        $this->body = json_encode($body);
      }
    } else {
      $this->body = '';
    }
    return $this;
  }

  public function acceptsJSON() {
    $this->setHeader('accept', 'application/json');
    return $this;
  }

  public function setHeader($header, $value) {
    $this->headers[$header] = "$header: $value";
    return $this;
  }

  public function download() {
    $this->fetchMode = 'download';
    return $this->execute();
  }

  public function execute() {
    self::detectMode();
    $download = $this->fetchMode == 'download';
    if (self::$mode === 'curl') {
      return $this->curl($download);
    }
    if (self::$mode === 'fopen') {
      return $this->fopen($download);
    }
    return false;
  }

  private function getStreamContext() {
    $header = implode($this->headers, "\r\n");
    $data = array(
      'http' => array(
        'header'  => $header,
        'method'  => strtoupper($this->method),
        'content' => $this->body
      )
    );
    return @stream_context_create($data);
  }

  public function fopen($download) {
    $streamContext = $this->getStreamContext();
    if ($download) {
      $directory = $this->getDownloadDirectory();
      $filename = $directory . UUID::v4();
      $fp = @fopen($filename, 'w+');
      $fu = @fopen($this->url, 'r', false, $streamContext);
      $code = 404;
      if ($fp && $fu) {
        @stream_copy_to_stream($fu, $fp);
        @fclose($fp);
        @fclose($fu);
      }
      $headers = $http_response_header;
      $match;
      preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $headers[0], $match);
      $code = (int)$match[1];
      return $this->downloadInfo($code, $filename, $headers);
    } else {
      return @file_get_contents($this->url, false, $streamContext);
    }
  }

  private function extractHeader($array, $header) {
    foreach ($array as $line) {
      if (stripos($line, $header . ':') === 0) {
        return trim(substr($line, strlen($header)+1));
      }
    }
  }

  private function downloadInfo($code, $oldName, $headers) {
    $sha1 = null;
    $name = null;
    $etag = null;
    $contentType = null;
    $success = true;
    $file = null;

    if ($code >= 200 && $code < 300) {
      $directory = $this->getDownloadDirectory();
      $sha1 = sha1_file($oldName);
      $file = $directory . $sha1;
      @rename($oldName, $file);

      $rawUrl = $this->url;
      $queryPos = strpos($rawUrl, '?');
      if ($queryPos > 0) {
        $rawUrl = substr($rawUrl, 0, $queryPos);
      }
      $name = basename($rawUrl);
      $disp = $this->extractHeader($headers, 'Content-Disposition');
      if ($disp && stripos($disp, 'attachment') === 0 && stripos($disp, 'filename=') > 0) {
        $name = trim(substr($disp, stripos($disp, 'filename=') + 9), " \t\n\r\0\x0B\"\'");
      }
      $etag = $this->extractHeader($headers, 'ETag');
      $contentType = $this->extractHeader($headers, 'Content-Type');
    } else {
      unlink($oldName);
      $success = false;
    }
    return array(
      'request_url' => $this->url,
      'request_method' => $this->method,
      'request_body' => $this->body,
      'success' => $success,
      'code' => $code,
      'sha1' => $sha1,
      'name' => $name,
      'path' => $file,
      'etag' => $etag,
      'contentType' => $contentType
    );
  }

  public function curl($download) {
    $curl = @curl_init();
    @curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
    @curl_setopt($curl, CURLOPT_URL, $this->url);
    @curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);
    @curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
    @curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    @curl_setopt($curl, CURLOPT_HTTPHEADER, array_values($this->headers));
    if ($download) {
      $directory = $this->getDownloadDirectory();
      $filename = $directory . UUID::v4();
      $fp = @fopen($filename, 'w+');
      @curl_setopt($curl, CURLOPT_FILE, $fp);
      @curl_setopt($curl, CURLOPT_HEADER, true);
      $ret = @curl_exec($curl);
      $length = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
      $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      @curl_close($curl);
      @fclose($fp);
      if ($length > 0) {
        $headers = explode("\n", $this->removeFirstBytes($filename, $length));
      } else {
        $headers = array();
      }

      return $this->downloadInfo($code, $filename, $headers);
    } else {
      @curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $res = @curl_exec($curl);
      @curl_close($curl);
      return $res;
    }
  }

  private function removeFirstBytes($path, $count) {
    $temp = $path . '.' . UUID::v4();
    $r = @fopen($path, 'r');
    $w = @fopen($temp, 'w');
    $goners = fread($r, $count);
    if (strlen($goners) === $count){
      @stream_copy_to_stream($r, $w);
    }
    @fclose($r);
    @fclose($w);
    @unlink($path);
    @rename($temp, $path);
    return $goners;
  }

  private function getDownloadDirectory() {
    $directory = $this->factory->getDownloadDirectory();
    $dir = new Directory($directory);
    if (! $dir->ensure()) {
      throw new UserError($dir->getError());
    }
    return $directory . '/';
  }
}
