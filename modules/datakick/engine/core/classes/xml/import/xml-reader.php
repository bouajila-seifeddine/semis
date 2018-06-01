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

class XmlReader {
  private $progress;
  private $reader;
  private $path;
  private $nodes;
  private $total = -1;

  public function run($uri, Progress $progress) {
    $this->nodes = 0;
    $this->path = array();
    $this->progress = $progress;
    $this->reader = new \XMLReader();
    $this->reader->open($uri);
    $this->reader->setParserProperty(\XMLReader::SUBST_ENTITIES, true);
    $progress->start('XML Reader');
    $ret = $this->before($progress);
    if ($ret) {
      $this->read();
      $ret = $this->after($progress);
    }
    $progress->end();
    return $ret;
  }

  private function read() {
    while (@$this->reader->read()) {
      switch ($this->reader->nodeType) {
        case \XMLReader::ELEMENT:
          $this->doEnterNode();
          break;
        case \XMLReader::END_ELEMENT:
          return;
        case \XMLReader::CDATA:
        case \XMLReader::TEXT:
          $this->appendText($this->reader->value);
          break;
        default:
          break;
      }
    }
    $err = @libxml_get_last_error();
    if ($err) {
      throw new UserError("XML parsing error: ". $err->message);
    }
  }


  public function getProgress() {
    return $this->progress;
  }

  public function setTotal($total) {
    $this->total = $total;
  }

  private function appendText($text) {
    $idx = count($this->path) - 1;
    if (isset($this->path[$idx]['value'])) {
      $this->path[$idx]['value'] .= $text;
    } else {
      $this->path[$idx]['value'] = $text;
    }
  }

  private function doEnterNode() {
    $this->nodes++;
    $this->progress->setProgress($this->total, $this->nodes);
    $node = array(
      'name' => $this->reader->name,
      'attributes' => array(),
      'value' => null
    );
    $isEmpty = $this->reader->isEmptyElement;

    if ($this->reader->hasAttributes)  {
      while ($this->reader->moveToNextAttribute()) {
        $node['attributes'][$this->reader->name] = $this->reader->value;
      }
    }

    array_push($this->path, $node);

    $this->enterNode($this->path, $node);

    if (! $isEmpty) {
      $this->read();
    }

    $node = end($this->path);
    $this->leaveNode($this->path, $node);
    array_pop($this->path);
  }

  public function enterNode($path, $node) {
  }

  public function leaveNode($path, $node) {
  }

  public function before($progress) {
    return true;
  }

  public function after($progress) {
    return true;
  }

  protected function samePath($path1, $path2) {
    if (is_array($path1)) {
      $path1 = $this->printPath($path1);
    }
    if (is_array($path2)) {
      $path2 = $this->printPath($path1);
    }
    return $path1 == $path2;
  }

  protected function printPath($path, $includeAttributes=false) {
    $ret = '';
    foreach ($path as $node) {
      $ret .= '/' . $node['name'];
      if ($includeAttributes) {
        $attributes = $node['attributes'];
        if ($attributes) {
          $ret .= '[' . implode(',', $attributes) . ']';
        }
      }
    }
    return $ret;
  }
}
