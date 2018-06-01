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

class XmlBuilderMulti {
    private $keys = '';
    private $template;
    private $totalCount = 0;
    private $consumed = 0;
    private $sources = array();

    public function __construct($template, $progress) {
        $this->template = $template;
        $this->progress = $progress;
    }

    public function addSource($usedNodes, $results, $count, $extractors) {
        $keyExtractors = array();
        self::collectExtractors($keyExtractors, $extractors, $usedNodes, $this->template);
        $this->sources[] = array(
            'usedNodes' => $usedNodes,
            'count' => $count,
            'results' => $results,
            'nextRow' => null,
            'nextEnd' => null,
            'extractors' => $extractors,
            'keyExtractors' => $keyExtractors,
            'end' => false,
            'head' => null,
            'key' => null
        );
        $this->totalCount += $count;
    }

    public function build($xml) {
        $this->progress->start('Build XML');
        foreach ($this->sources as $id => $source) {
          $this->fetchRow($id);
        }
        $this->addNodes($this->template, $xml, array(), array());
        $xml->finish();
        $this->progress->end();

    }

    private function selectSource($prefix, $path) {
        $selected = null;
        $selectedVector = array();
        foreach ($this->sources as $id => $source) {
            if (self::usableSource($source, $path)) {
                $vector = $this->keyVector($id);
                if (self::isPrefix($prefix, $vector)) {
                  if (is_null($selected) || self::smallerVector($vector, $selectedVector)) {
                    $selected = $id;
                    $selectedVector = $vector;
                  }
                }
            }
        }
        return $selected;
    }

    private static function usableSource($source, $path) {
        if ($source['end'])
            return false;
        foreach ($path as $nodeId) {
            if (! in_array($nodeId, $source['usedNodes'])) {
                return false;
            }
        }
        return true;
    }

    private function addNodes($node, $xml, $prefix, $path) {
        $id = $node['id'];
        array_push($path, $id);
        $sId = $this->selectSource($prefix, $path);
        if (!is_null($sId)) {
            if (isset($node['data'])) {
                $toReturn = array();
                $lastKeys = array();
                do {
                    $row = $this->getRow($sId);
                    $extractors = $this->sources[$row['sourceId']]['extractors'];
                    $keys = self::getVector($row['row'], $extractors[$id]['@@keys']);
                    if (is_array($keys) && $keys != $lastKeys) {
                        $this->addNode($node, $xml, array_merge($prefix, $keys), $path, $row);
                        $lastKeys = $keys;
                    }
                    $next = $this->fetchRow($sId);
                    if (! is_null($next)) {
                      $vector = $this->keyVector($sId);
                      if (!self::isPrefix($prefix, $vector)) {
                        $toReturn[$sId] = $row['row'];
                      }
                    }
                    $sId = $this->selectSource($prefix, $path);
                } while (!is_null($sId));

                if (count($toReturn) > 0) {
                  foreach ($toReturn as $id => $lastRow) {
                    $this->sources[$id]['nextRow'] = $this->sources[$id]['head'];
                    $this->sources[$id]['nextEnd'] = $this->sources[$id]['end'];
                    $this->sources[$id]['head'] = $lastRow;
                    $this->sources[$id]['end'] = false;
                    $this->sources[$id]['key'] = null;
                  }
                }
            } else {
                $this->addNode($node, $xml, $prefix, $path, $this->getRow($sId));
            }
        }
    }

    private function addNode($node, $xml, $prefix, $path, $row) {
        $tag = $node['tag'];
        $id = $node['id'];

        $omitEmpty = isset($node['omitEmpty']) && $node['omitEmpty'];
        $xml->openNode($tag, $omitEmpty);
        if (isset($node['attributes'])) {
            foreach ($node['attributes'] as $key=>$ignore) {
                $this->addAttribute($id, $key, $xml, $row);
            };
        }

        if (isset($node['content'])) {
            $this->addContent($id, $xml, $row, $node);
        }

        if (isset($node['children'])) {
            foreach ($node['children'] as $child) {
                $this->addNodes($child, $xml, $prefix, $path);
            }
        }

        $xml->closeNode();
    }

    private function addAttribute($id, $attr, $xml, $row) {
        $extractors = $this->sources[$row['sourceId']]['extractors'];
        $value = $extractors[$id][$attr]->getValue($row['row']);
        $xml->addAttribute($attr, $value);
    }

    private function addContent($id, $xml, $row, $node) {
        $extractors = $this->sources[$row['sourceId']]['extractors'];
        $value = $extractors[$id]['@@content']->getValue($row['row']);
        $cdata = isset($node['cdata']) && !!($node['cdata']);
        $xml->setContent($value, $cdata);
    }

    private function getRow($sourceId) {
        $source = $this->sources[$sourceId];
        $row = $source['head'];
        $ret = array(
            'sourceId' => $sourceId,
            'row' => $source['head'],
        );
        return $ret;
    }

    private function fetchRow($id) {
        if ($this->sources[$id]['nextRow']) {
          $this->sources[$id]['head'] = $this->sources[$id]['nextRow'];
          $this->sources[$id]['end'] = $this->sources[$id]['nextEnd'];
          $this->sources[$id]['nextRow'] = null;
          $this->sources[$id]['nextEnd'] = null;
        } else {
          if (! $this->sources[$id]['end']) {
            $this->sources[$id]['head'] = $this->sources[$id]['results']->fetch();
            if ($this->sources[$id]['head']) {
              $this->consumed++;
              $this->progress->setProgress($this->totalCount, $this->consumed);
            } else {
              $this->sources[$id]['end'] = true;
            }
          }
        }
        $this->sources[$id]['key'] = null;
        return $this->sources[$id]['head'];
    }

    private function keyVector($id) {
        if (! is_null($this->sources[$id]['key'])) {
            return $this->sources[$id]['key'];
        }

        $row = $this->sources[$id]['head'];
        if ($row) {
            $this->sources[$id]['key'] = self::getVector($row, $this->sources[$id]['keyExtractors']);
        }
        return $this->sources[$id]['key'];
    }


    private static function getVector($row, $extractors) {
        $vector = array();
        foreach ($extractors as $extractor) {
            $val = $extractor->getValue($row);
            array_push($vector, $val);
        }
        return $vector;
    }

    private static function collectExtractors(&$keyExtractors, $extractors, $usedNodes, $node) {
        $id = $node['id'];
        if (in_array($id, $usedNodes)) {
            if (isset($node['data'])) {
                foreach ($extractors[$id]['@@keys'] as $e) {
                    array_push($keyExtractors, $e);
                }
            }
            if (isset($node['children'])) {
                foreach($node['children'] as $child) {
                    self::collectExtractors($keyExtractors, $extractors, $usedNodes, $child);
                }
            }
        }
    }

    private static function isPrefix($prefix, $keys) {
      $cnt = count($prefix);
      $cnt2 = count($keys);
      if ($cnt > $cnt2) {
        return false;
      }
      for ($i=0; $i<$cnt; $i++) {
        $p = $prefix[$i];
        $k = $keys[$i];
        if (is_null($k) || $k == '') {
          return false;
        }
        $neq = $p != $k;
        if ($neq)
          return false;
      }
      if ($cnt < $cnt2) {
        $k = $keys[$cnt];
        if (is_null($k) || $k == '') {
          return false;
        }
      }
      return true;
    }

    private static function smallerVector($a, $b) {
      $cnt = min(count($a), count($b));
      for ($i=0; $i<$cnt; $i++) {
        $aa = $a[$i];
        $bb = $b[$i];
        if ($aa < $bb)
          return true;
        if ($aa > $bb)
          return false;
      }
      return false;
    }
}
