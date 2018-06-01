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

class CsvResultset {
  private $input;
  private $separator;
  private $eol;
  private $outputEncoding = false;
  private $convertFrom = false;
  private $finished = false;

  public function __construct($input, $hasColumnNames=true, $separator=',', $eol="\n", $inputEncoding='UTF-8', $outputEncoding='UTF-8') {
    $this->input = $input;
    $this->separator = $separator;
    $this->eol = $eol;
    $this->outputEncoding = $outputEncoding;
    if ($inputEncoding != $outputEncoding) {
      $this->convertFrom = $inputEncoding;
    }
    if ($hasColumnNames) {
      $this->columnNames = $this->fetch();
    }
  }

  private function fetchLine() {
    if ($this->finished)
    return false;
    $line = fgets($this->input);
    if ($line === false) {
      $this->finished = true;
      fclose($this->input);
      return false;
    }
    if ($this->convertFrom) {
      $line = mb_convert_encoding($line, $this->outputEncoding, $this->convertFrom);
    }
    return $line;
  }

  public function fetch() {
    $o = array();
    $num = 0;
    $esc = false;
    $escesc = false;
    $o[0] = '';
    while (true) {
      $string = $this->fetchLine();
      if ($string === false)
        return false;
      $cnt = strlen($string);
      $i = 0;
      if ($i < $cnt) {
        while ($i < $cnt) {
          $s = $string[$i];
          if ($s == $this->eol) {
            if ($esc) {
              $o[$num] .= $s;
            } else {
              $i++;
              break;
            }
          } elseif ($s == $this->separator) {
            if ($esc) {
              $o[$num] .= $s;
            } else {
              $num++;
              $o[] = '';
              $esc = false;
              $escesc = false;
            }
          } elseif ($s == '"') {
            if ($escesc) {
              $o[$num] .= '"';
              $escesc = false;
            }

            if ($esc) {
              $esc = false;
              $escesc = true;
            } else {
              $esc = true;
              $escesc = false;
            }
          } else {
            if ($escesc) {
              $o[$num] .= '"';
              $escesc = false;
            }

            $o[$num] .= $s;
          }

          $i++;
        }
        if (! $esc)
          return $o;
      }
    }
  }
}
