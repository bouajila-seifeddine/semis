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

class XmlExecutor {
  private $counter = 0;
  private $id = null;
  private $stats = array();

  public function __construct($factory) {
    $this->factory = $factory;
  }

  public function buildXml($template, XmlOutput $xmlOutput, $limit, Context $context, Progress $progress) {
    $progress->start('Generate XML');
    $this->addIds($template);
    $builder = new XmlQueryBuilder($this->factory);

    $this->stats['queries'] = array();
    $queries = $builder->getQueries($template, $limit, $context);
    if (count($queries) == 1) {
      $this->stats['mode'] = 'simple';
      $q = $queries[0];
      $this->getXMLSimple($template, $xmlOutput, $q['query'], $q['extractors'], $q['set'], $context, $progress);
    } else {
      $this->stats['mode'] = 'multi';
      $builder = new XmlBuilderMulti($template, $progress);
      $files = array();
      $error = null;
      try {
        $progress->start('Get Data');
        $cnt = 0;
        foreach ($queries as $q) {
          $csv = $this->outputCSV($q['query'], $q['extractors'], $context, $progress);
          array_push($files, $csv['filename']);
          $this->stats['queries'][$cnt] =array(
            'rows' => $csv['count'],
            'columns' => count($csv['extractors'])
          );
          $builder->addSource($q['set'], $csv['results'], $csv['count'], $csv['extractors']);
          $cnt++;
          $progress->setProgress(count($queries), $cnt);
        }
        $builder->build($xmlOutput);
      } catch (\Exception $e) {
        $error = $e;
      }
      foreach ($files as $filename) {
        unlink($filename);
      }
      if ($error) {
        throw $error;
      }
    }
    return true;
    $progress->end();
  }

  public function getStats() {
    return $this->stats;
  }

  private function getXMLSimple($template, $xmlOutput, $query, $extractors, $usedNodes, $context, $progress) {
    $progress->start('Get Data');
    $results = $this->getResults($query, $context);
    $progress->setProgress(1, 1);
    $progress->end();
    $numRow = $this->factory->getConnection()->numRows();
    $this->stats['queries'][0] =array(
      'rows' => $numRow,
      'columns' => count($extractors)
    );
    $builder = new XmlBuilder($progress, $template, $usedNodes, $results, $numRow, $extractors);
    $builder->build($xmlOutput);
  }

  private function outputCSV($query, $extractors, $context, $progress) {
    $results = $this->getResults($query, $context);
    $columnExtractors = array();
    $mappedExtractors = array();
    foreach ($extractors as $nodeId => $arr) {
      $arr1 = array();
      foreach ($arr as $key => $extractor) {
        if ($key === '@@keys') {
          $extractor1 = array();
          foreach ($extractor as $keyId => $keyExtractor) {
            $extractor1[$keyId] = new CsvColumnExtractor(count($columnExtractors));
            $columnExtractors[] = $keyExtractor;
          }
          $arr1[$key] = $extractor1;
        } else {
          $arr1[$key] = new CsvColumnExtractor(count($columnExtractors));
          $columnExtractors[] = $extractor;
        }
      }
      $mappedExtractors[$nodeId] = $arr1;
    }
    $numRow = $this->factory->getConnection()->numRows();
    $builder = new ListBuilder($results, $numRow, $progress, $columnExtractors);

    $name = tempnam(sys_get_temp_dir(), 'csv-');
    $output = new CsvOutputStream(fopen($name, 'w'));
    $builder->build($output);

    return array(
      'filename' => $name,
      'count' => $builder->getCount(),
      'results' => new CsvResultset(fopen($name, 'r'), false),
      'extractors' => $mappedExtractors
    );
  }

  private function getResults($query, $context) {
    if ($query->isValid()) {
      return $query->execute($this->factory, $context);
    }
    return null;
  }

  private function addIds(&$tree) {
    $tree['id'] = $this->counter++;
    if (isset($tree['children'])) {
      foreach($tree['children'] as &$child) {
        $this->addIds($child);
      }
    }
  }
}
