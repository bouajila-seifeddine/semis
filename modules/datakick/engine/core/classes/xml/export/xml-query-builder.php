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

class XmlQueryBuilder {
    private $expressions;

    public function __construct($factory) {
        $this->factory = $factory;
        $this->expressions = $factory->getExpressions();
    }

    public function getQueries($def, $limit, $context) {
        $paths = $this->getPaths($def);
        $aliases = $this->getAliases($def);
        $sets = $this->mergePaths($aliases, $paths);
        $queries = array();
        foreach ($sets as $set) {
            $query = $this->factory->getQuery();
            if ($limit) {
                $query->setLimit($limit);
            }
            $extractors = array();
            $this->exposeTree($query, $extractors, $def, $set, $context);
            array_push($queries, array(
                'query' => $query,
                'set' => $set,
                'extractors' => $extractors
            ));
        }
        return $queries;
    }

    private function exposeTree($query, &$extractors, $node, $set, $context) {
        $id = $node['id'];

        if (! in_array($id, $set))
            return;

        if (isset($node['data'])) {
            $data = $node['data'];
            $alias = $data['alias'];
            $link = isset($data['link']) ? $data['link'] : null;
            $query->exposeCollection($data['collection'], $alias, $link);
            $keys = $query->exposeKeyFields($alias);
            $query->addSorts($keys);
            $extractors[$id]['@@keys'] = array_map(function($key) {
                return new AliasExtractor($key, 'string');
            }, $keys);
            if (isset($data['conditions'])) {
                foreach($data['conditions'] as $condExpression) {
                    $this->expressions->exposeCondition($query, $condExpression, $context);
                }
            }
        }

        if (isset($node['content'])) {
            $extractor = $this->expressions->expose($query, $node['content'], $context, true);
            $extractors[$id]['@@content'] = $extractor;
        }

        if (isset($node['attributes'])) {
            foreach($node['attributes'] as $key=>$attr) {
                $extractor = $this->expressions->expose($query, $attr, $context, true);
                $extractors[$id][$key] = $extractor;
            }
        }

        if (isset($node['children'])) {
            $children = $node['children'];
            foreach($node['children'] as $child) {
                $this->exposeTree($query, $extractors, $child, $set, $context);
            }
        }
    }

    private function getPaths($tree) {
        if (isset($tree['children'])) {
            $children = $tree['children'];
            if (! empty($children)) {
                $paths = array();
                foreach ($tree['children'] as $child) {
                    $childPaths = $this->getPaths($child);
                    if (empty($childPaths)) {
                        return array(array($tree));
                    } else {
                        foreach($childPaths as &$p) {
                            array_unshift($p, $tree);
                        }
                    }
                    $paths = array_merge($paths, $childPaths);
                }
                return $paths;
            }
        }
        return array(array($tree));
    }

    private function mergePaths($aliases, $paths) {
        $ret = array();
        foreach ($paths as $p1) {
          $ids = $this->getIds($p1);
          foreach ($paths as $p2) {
            if ($p1 != $p2) {
              $ids2 = $this->getIds($p2);
              if ($this->canMerge($aliases, $ids, $ids2)) {
                  $ids = array_unique(array_merge($ids, $ids2));
              }
            }
          }
          sort($ids);
          if (! in_array($ids, $ret)) {
            array_push($ret, $ids);
          }
        }
        return $ret;
    }

    private function canMerge($aliases, $ids1, $ids2) {
        $aliases1 = $this->filterBy($ids1, $aliases);
        $aliases2 = $this->filterBy($ids2, $aliases);
        if ($aliases1 == $aliases2) {
            return true;
        }

        $intersect = array_intersect($aliases1, $aliases2);
        $ret = $intersect == $aliases1 || $intersect == $aliases2;
        return $ret;
    }

    private function getIds($list) {
        $ret = array();
        foreach($list as $item) {
            array_push($ret, $item['id']);
        }
        return $ret;
    }

    private function isDataNode($node) {
        return isset($node['data']);
    }

    private function filterBy($arr, $filter) {
      $ret = array();
      foreach($arr as $item) {
        if (isset($filter[$item])) {
          array_push($ret, $item);
        }
      }
      return $ret;
    }

    private function collectAliases($node, &$aliases) {
        if ($this->isDataNode($node)) {
            $aliases[$node['id']] = $node['data']['alias'];
        }
        if (isset($node['children'])) {
          foreach ($node['children'] as $child) {
            $this->collectAliases($child, $aliases);
          }
        }
    }

    private function getAliases($def) {
        $aliases = array();
        $this->collectAliases($def, $aliases);
        return $aliases;
    }
}
