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

class XmlOutputStream implements XmlOutput {
    private $stack = array();
    private $convertFrom = null;
    private $indentation = 0;
    private $indentStep = 0;
    private $first = true;
    private $closed = true;
    private $hasContent = false;
    private $buffering = false;
    private $buffer = '';

    public function __construct($out, $indent=2, $declaration=true, $inputEncoding='UTF-8', $outputEncoding='UTF-8') {
        $this->out = $out;
        $this->indentStep = $indent;
        $this->declaration = $declaration;
        $this->outputEncoding = $outputEncoding;
        if ($inputEncoding != $outputEncoding) {
            $this->convertFrom = $inputEncoding;
        }
    }

    public function openNode($tag, $omitEmpty) {
        if (! $this->closed) {
            $this->write(">");
            $this->flush();
        }
        if ($this->first) {
            if ($this->declaration) {
                $this->write("<?xml version=\"1.0\" encoding=\"{$this->outputEncoding}\" ?>");
            }
            $this->first = false;
        }

        $this->closed = false;
        if (! $this->buffering) {
          $this->buffering = $omitEmpty;
        }
        $this->newLine("<$tag");
        array_push($this->stack, $tag);
        $this->increase();
    }

    public function closeNode() {
        $tag = array_pop($this->stack);
        $this->decrease();
        if (! $this->closed) {
            $this->write("/>");
            $this->closed = true;
        } else {
            if ($this->hasContent) {
                $this->write("</$tag>");
                $this->hasContent = false;
            } else {
                $this->newLine("</$tag>");
            }
        }
        if ($this->buffering) {
          $this->buffering = false;
          $this->buffer = '';
        }
    }

    public function addAttribute($name, $value) {
        if (is_bool($value)) {
          $value = $value ? 'true' : 'false';
        }
        $this->flush();
        $this->write(" $name=\"" . htmlspecialchars($value, ENT_COMPAT | ENT_XML1) . '"');
    }

    public function setContent($value, $cdata) {
        if (is_bool($value)) {
          $value = $value ? 'true' : 'false';
        }
        if ($cdata) {
            $data = "<![CDATA[" . str_replace(']]>', ']]]]><![CDATA[>', $value) . ']]>';
        } else {
            $data = htmlspecialchars($value, ENT_COMPAT | ENT_XML1);
        }
        $this->write(">$data");
        if (!is_null($value) && $value != '')
          $this->flush();
        $this->closed = true;
        $this->hasContent = true;
    }

    public function encode($val) {
        if ($this->convertFrom) {
            return mb_convert_encoding($val, $this->outputEncoding, $this->convertFrom);
        }
        return $val;
    }

    public function finish() {
        $this->newLine("");
        fclose($this->out);
    }

    private function newLine($data) {
        if ($this->indentStep) {
            $prefix = "\n" . str_repeat(' ', $this->indentStep * $this->indentation);
            $this->write("{$prefix}{$data}");
        } else {
            $this->write($data);
        }
    }

    private function increase() {
        if ($this->indentStep) {
            $this->indentation++;
        }
    }

    private function decrease() {
        if ($this->indentStep) {
            $this->indentation--;
        }
    }

    private function write($data) {
      if ($this->buffering) {
        $this->buffer .= $data;
      } else {
        $this->output($data);
      }
    }

    private function flush() {
      if ($this->buffering) {
        $this->buffering = false;
        $this->output($this->buffer);
        $this->buffer = '';
      }
    }

    private function output($data) {
        $encoded = $this->encode($data);
        $bytesToWrite = strlen($encoded);
        $totalBytesWritten = 0;
        while ($totalBytesWritten < $bytesToWrite) {
            $bytes = fwrite($this->out, substr($encoded, $totalBytesWritten));
            $totalBytesWritten += $bytes;
        }
    }
}
