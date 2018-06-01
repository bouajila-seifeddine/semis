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

class ListExecutor {
  private $id = null;
  private $extractors = array();
  private $usedLimit = null;
  private $usedOffset = 0;
  private $calcTotalRows;
  private $numRows = 0;
  private $totalRows = 0;

  public function __construct($factory, $calcTotalRows=false, $includeHighlighting=true, $formatOutput=true) {
    $this->factory = $factory;
    $this->calcTotalRows = $calcTotalRows;
    $this->includeHighlighting = $includeHighlighting;
    $this->formatOutput = $formatOutput;
  }

  public function buildList($list, $listOutput, $offset=null, $limit=null, Context $context, Progress $progress) {
    $query = $this->getSql($list, $offset, $limit, $context);
    $results = null;
    $numRows = 0;
    if ($query->isValid()) {
      $results = $query->execute($this->factory, $context);
      $connection = $this->factory->getConnection();
      $this->numRows = $connection->numRows();
      if ($this->calcTotalRows) {
        $this->totalRows = $connection->totalRows($query);
      }
    }
    $builder = new ListBuilder($results, $this->numRows, $progress, $this->extractors);
    return $builder->build($listOutput);
  }

  private function getSql($list, $offset, $limit, $context) {
    $query = $this->query = $this->factory->getQuery();
    $usedAliases = $this->getUsedCollections($list);
    //print_r($usedAliases); die();
    if (isset($list['collections'])) {
      foreach($list['collections'] as $collection) {
        $col = $collection['collection'];
        $alias = $collection['alias'];
        if (in_array($alias, $usedAliases)) {
          $link = isset($collection['link']) ? $collection['link'] : null;
          $query->exposeCollection($col, $alias, $link, false);
        }
      }
    }
    $sortRefs = $this->getSortReferences($list);
    $expressions = $this->factory->getExpressions();
    $exprs = $list['expressions'];
    if (isset($list['columns'])) {
      foreach($list['columns'] as $col) {
        $id = $col['id'];
        $isSort = isset($sortRefs[$id]);
        $expr = $exprs[$id];
        $extractor = $expressions->expose($query, $expr, $context, $this->formatOutput, $isSort);
        if ($isSort) {
          if (Types::isCurrency($expr['type'])) {
            $aliases = $expressions->expose($query, $expr, $context, false, true)->getAlias();
            $sortRefs[$id] = array($aliases['currency'], $aliases['value']);
          } else {
            $sortRefs[$id] = $extractor->getAlias();
          }
        }
        array_push($this->extractors, $extractor);
      }
    }
    if ($this->includeHighlighting && isset($list['highlightRules'])) {
      foreach($list['highlightRules'] as $col) {
        $id = $col['id'];
        $expr = $exprs[$id];
        $extractor = $expressions->expose($query, $expr, $context, false, false);
        array_push($this->extractors, $extractor);
      }
    }
    if (isset($list['conditions'])) {
      foreach($list['conditions'] as $expr) {
        $expressions->exposeCondition($query, $expr, $context);
      }
    }
    if (isset($list['sorts'])) {
      foreach($list['sorts'] as $sort) {
        $ref = $sort['id'];
        $alias = $sortRefs[$ref];
        if (! $alias)
          throw new \Exception("Sort references nonexisting expression: $ref");
        $isAsc = $sort['type'] !== 'desc';
        if (is_array($alias)) {
          foreach($alias as $a) {
            $query->addSort($a, $isAsc);
          }
        } else {
          $query->addSort($alias, $isAsc);
        }
      }
    }
    if (isset($list['limit'])) {
      $this->usedLimit = isset($list['limit']['count']) ? (int)($list['limit']['count']) : 1;
      if ($limit)
        $this->usedLimit = min($this->usedLimit, $limit);
        $this->usedOffset = isset($list['limit']['offset']) ? (int)($list['limit']['offset']) : 0;
      if ($offset)
        $this->usedOffset = min($this->usedOffset, $offset);
      $query->setLimit($this->usedLimit, $this->usedOffset);
    } else if ($limit) {
      $this->usedLimit = $limit;
      if ($offset)
        $this->usedOffset = $offset;
      $query->setLimit($this->usedLimit, $this->usedOffset);
    }
    if ($this->calcTotalRows && $this->usedLimit) {
      $query->calcRows(true);
    }
    if (isset($list['distinct'])) {
      $query->setDistinct(true);
    }
    return $query;
  }

  private function getUsedCollections($list) {
    $expressions = $this->factory->getExpressions();
    $usedAliases = array();
    $deps = $this->getCollectionDependencies($list);
    if (isset($list['expressions'])) {
      foreach($list['expressions'] as $expr) {
        $collections = $expressions->getUsedCollections($expr);
        $usedAliases = array_merge($usedAliases, $expressions->getUsedCollections($expr));
      }
    }
    if (isset($list['conditions'])) {
      foreach($list['conditions'] as $cond) {
        $usedAliases = array_merge($usedAliases, $expressions->getUsedCollections($cond));
      }
    }
    $usedAliases = array_unique($usedAliases);
    $ret = $usedAliases;
    foreach ($usedAliases as $alias) {
      $ret = array_merge($ret, $deps[$alias]);
    }
    return array_unique($ret);
  }

  private function getCollectionDependencies($list) {
    $resolved = array();
    $resolve = array();
    foreach ($list['collections'] as $col) {
      $alias = $col['alias'];
      if (! isset($col['link'])) {
        $resolved[$alias] = [];
      } else {
        $resolve[$alias] = $col['link']['alias'];
      }
    }
    for ($i=0; $i<1000; $i++) {
      if (empty($resolve))
        break;
      foreach($resolve as $key => $parent) {
        if (isset($resolved[$parent])) {
          $resolved[$key] = array_merge($resolved[$parent], [ $parent ]);
          unset($resolve[$key]);
        }
      }
    }
    if (! empty($resolve)) {
      throw new UserError('Loop detected');
    }
    return $resolved;
  }

  private function getSortReferences($list) {
    $refs = array();
    if (isset($list['sorts'])) {
      foreach($list['sorts'] as $sort) {
        $refs[$sort['id']] = false;
      }
    }
    return $refs;
  }

  public function getLimit() {
    return $this->usedLimit;
  }

  public function getOffset() {
    return $this->usedOffset;
  }

  public function getQueries() {
    return array($this->query);
  }

  public function getTotalCount() {
    return $this->totalRows;
  }

  public function getStats() {
    return array(
      'rows' => $this->numRows,
      'columns' => count($this->extractors)
    );
  }
}
