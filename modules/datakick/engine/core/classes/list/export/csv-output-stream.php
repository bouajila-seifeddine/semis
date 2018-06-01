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

class CsvOutputStream {
  private $out;
  private $separator;
  private $eol;
  private $lastCol;
  private $columns;
  private $outputEncoding = false;
  private $convertFrom = false;
  private $totalWritten = 0;

  public function __construct($out, $columns=null, $exportLabels=false, $separator=',', $eol="\n", $inputEncoding='UTF-8', $outputEncoding='UTF-8') {
    $this->out = $out;
    $this->separator = $separator;
    $this->eol = $eol;
    $this->outputEncoding = $outputEncoding;
    $this->columns = $columns;
    if ($inputEncoding != $outputEncoding) {
      $this->convertFrom = $inputEncoding;
    }
    $this->regexp = '/[\r\n"'.preg_quote($separator, '/').']/';
    if ($columns) {
      $columnNames = array();
      $len = count($columns);
      for ($i=0; $i<$len; $i++) {
        $columnNames[] = $columns[$i]['label'];
        if ($this->notHidden($i)) {
          $this->lastCol = $i;
        }
      }
      if ($exportLabels) {
        $this->addRow($columnNames);
      }
    }
  }

  public function addRow($row) {
    $len = is_null($this->lastCol) ? count($row) : ($this->lastCol + 1);
    for ($i=0; $i<$len; $i++) {
      if ($this->notHidden($i)) {
        $isLast = ($i === $len-1);
        $this->write($row[$i], $isLast);
      }
    }
  }

  public function finish() {
    fclose($this->out);
  }

  public function encode($val) {
    if ($this->convertFrom) {
      $val = mb_convert_encoding($val, $this->outputEncoding, $this->convertFrom);
    }
    if (preg_match($this->regexp, $val)) {
      return '"' . str_replace('"', '""', $val) . '"';
    }
    return $val;
  }

  private function notHidden($index) {
    if ($this->columns) {
      if (isset($this->columns[$index]['hidden']))
        return ! ($this->columns[$index]['hidden']);
    }
    return true;
  }

  private function write($data, $eol) {
    $encoded = $this->encode($data) . ($eol ? $this->eol : $this->separator);
    $bytesToWrite = strlen($encoded);
    $totalBytesWritten = 0;
    while ($totalBytesWritten < $bytesToWrite) {
      $bytes = fwrite($this->out, substr($encoded, $totalBytesWritten));
      $totalBytesWritten += $bytes;
    }
    $this->totalWritten += $totalBytesWritten;
  }
}
