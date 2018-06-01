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

class XmlBuilder {
    private $returned = array();

    public function __construct($progress, $template, $usedNodes, $results, $numRow, $extractors) {
        $this->template = $template;
        $this->usedNodes = $usedNodes;
        $this->results = $results;
        $this->extractors = $extractors;
        $this->node = null;
        $this->keyExtractors = array();
        $this->collectExtractors($template);
        $this->totalCount = $numRow;
        $this->consumed = 0;
        $this->progress = $progress;
    }

    public function build($xml) {
        $this->progress->start('Build XML');
        $this->fetch();
        $this->addNodes($this->template, $xml, "", $this->row);
        $xml->finish();
        $this->progress->end();
    }

    public function fetch() {
        if (count($this->returned) > 0) {
            $this->row = array_pop($this->returned);
        } else {
            if ($this->consumed < $this->totalCount) {
              $this->row = $this->results ? $this->results->fetch() : false;
              $this->consumed++;
              $this->progress->setProgress($this->totalCount, $this->consumed);
            } else {
              $this->row = false;
            }
        }
        if ($this->row) {
            $this->keys = $this->keysVector($this->keyExtractors);
        }
        return $this->row;
    }

    public function ret($row) {
        array_push($this->returned, $row);
    }

    public function keysVector($extractors) {
        $vector = '';
        foreach ($extractors as $extractor) {
            $val = $extractor->getValue($this->row);
            if ($val) {
                if ($vector) {
                    $vector .= "-$val";
                } else {
                    $vector = $val;
                }
            }
        }
        return $vector;
    }

    public function collectExtractors($node) {
        $id = $node['id'];
        if (in_array($id, $this->usedNodes)) {
            if (isset($node['data'])) {
                foreach ($this->extractors[$id]['@@keys'] as $e) {
                    array_push($this->keyExtractors, $e);
                }
            }
            if (isset($node['children'])) {
                foreach($node['children'] as $child) {
                    $this->collectExtractors($child);
                }
            }
        }
    }

    public function addNodes($node, $xml, $prefix, $row) {
        if (isset($node['data'])) {
            $unconsumed = null;
            while ($this->isPrefix($prefix)) {
                $id = $node['id'];
                $keys = $this->keysVector($this->extractors[$id]['@@keys']);
                if ($keys) {
                    $nextPrefix = ($prefix == '' ? '' : "$prefix-").$keys;
                    $this->addNode($node, $xml, $nextPrefix, $this->row);
                }
                $unconsumed = $this->fetch();
            }
            if ($unconsumed) {
                $this->ret($unconsumed);
            }
        } else {
            $this->addNode($node, $xml, $prefix, $row);
        }
    }

    public function isPrefix($prefix) {
        if (! $this->row)
            return false;
        return $prefix === '' || strpos($this->keys, $prefix) === 0;
    }

    public function addNode($node, $xml, $prefix, $row) {
        $tag = $node['tag'];
        $id = $node['id'];
        $extractors = isset($this->extractors[$id]) ? $this->extractors[$id] : array();

        $omitEmpty = isset($node['omitEmpty']) && $node['omitEmpty'];
        $xml->openNode($tag, $omitEmpty);
        if (isset($node['attributes']) && count($node['attributes']) > 0) {
            foreach ($node['attributes'] as $key=>$ignore) {
                $this->addAttribute($key, $xml, $extractors, $row);
            };
        }
        if (isset($node['children'])) {
            foreach ($node['children'] as $child) {
                $this->addNodes($child, $xml, $prefix, $row);
            }
        }

        if (isset($node['content'])) {
            $value = $this->extractors[$id]['@@content']->getValue($row);
            $cdata = isset($node['cdata']) && !!($node['cdata']);
            $xml->setContent($value, $cdata);
        }
        $xml->closeNode();
    }

    public function addAttribute($attr, $xml, $extractors, $row) {
        $value = $extractors[$attr]->getValue($row);
        $xml->addAttribute($attr, $value);
    }
}
