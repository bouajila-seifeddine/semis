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

class UploadFileService extends Service {

  public function __construct() {
    parent::__construct('upload-file');
  }

  public function process($factory, $request) {

    if (empty($_FILES)) {
      return $this->error('Request does not contain file data');
    }

    $file = current($_FILES);
    if ($file['error'] != 0) {
      return $this->error('error '.$file['error'].' in file '.$request['resumableFilename']);
    }

    $id = $this->getId($request);
    if (! $id) {
      return $this->error("Request does not contains valid identifier");
    }

    $filename = $this->getFilename($request);
    if (! $filename) {
      return $this->error("Request does not contains valid filename");
    }

    $chunkId = $this->getChunkId($request);
    if (! $chunkId) {
      return $this->error("Request does not contains valid chunkId");
    }

    $uploadPath = $factory->getUploadDirectory();
    $chunkPath = "$uploadPath/$id";
    $uploadDir = new Directory($uploadPath);
    $uploadDir->ensure();
    $chunkDir = new Directory($chunkPath);
    $chunkDir->ensure();

    $chunkFileName = "$chunkPath/$filename.part$chunkId";
    $target = "$uploadPath/$filename";
    $targetFilename = $chunkFileName;

    if (!move_uploaded_file($file['tmp_name'], $chunkFileName)) {
      return $this->error("Error saving chunk $chunkFileName");
    } else {
      $totalFiles = (int)$request['resumableTotalChunks'];
      $totalSize = (int)$request['resumableTotalSize'];
      if ($this->hasAllChunks($chunkPath, $filename, $totalFiles, $totalSize)) {
        return $this->createFileFromChunks($chunkDir, $chunkPath, $target, $filename, $totalFiles);
      } else {
        return $this->ok(array('filename' => $targetFilename));
      }
    }

  }

  private function hasAllChunks($temp_dir, $filename, $totalFiles, $totalSize) {
    $total_files_on_server_size = 0;
    $files = array();
    foreach(scandir($temp_dir) as $file) {
      if (strpos($file, "$filename.part") === 0) {
        $files[] = $file;
        $tempfilesize = filesize($temp_dir.'/'.$file);
        $total_files_on_server_size += $tempfilesize;
      }
    }
    return count($files) == $totalFiles && $total_files_on_server_size >= $totalSize;
  }

  private function createFileFromChunks($chunkDir, $temp_dir, $target, $filename, $totalFiles) {
    if (($fp = fopen($target, 'w')) !== false) {
      for ($i=1; $i<=$totalFiles; $i++) {
        fwrite($fp, file_get_contents($temp_dir.'/'.$filename.'.part'.$i));
      }
      fclose($fp);
      $chunkDir->rmdir();
      return $this->ok(array('filename' => $target));
    } else {
      return $this->error('cannot create the destination file');
    }
  }

  private function getId($request) {
    if (isset($request['resumableIdentifier'])) {
      return preg_replace('/[^a-zA-Z0-9_-]/', '', $request['resumableIdentifier']);
    }
  }

  private function getFilename($request) {
    if (isset($request['resumableFilename'])) {
      return preg_replace('/[^a-zA-Z0-9._-]/', '', $request['resumableFilename']);
    }
  }

  private function getChunkId($request) {
    if (isset($request['resumableChunkNumber'])) {
      $number = $request['resumableChunkNumber'];
      return (int)$number;
    }
    return false;
  }

  private function error($response) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array(
      "error" => $response
    ));
    return Service::OUTPUT_HANDLED;
  }

  private function ok($response) {
    echo json_encode($response);
    return Service::OUTPUT_HANDLED;
  }

  public function payloadType() {
    return 'form-data';
  }

}
