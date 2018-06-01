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

class XmlImportValidateMatching extends XmlReader {
  private $importer;

  public function __construct(Factory $factory, $collection, $root, $condition) {
    $definition = array(
      'dataset' => array(
        'root' => $root
      ),
      'collection' => $collection,
      'importMode' => array(
        'type' => 'update',
        'condition' => $condition
      ),
      'fields' => array(),
      'associations' => array(),
      'parameters' => array()
    );
    $this->importer = new XmlImporter($factory, $factory->getContext(), $definition, XmlImporter::TEST_MATCHING);
  }

  public function run($file, Progress $progress) {
    $ret = $this->importer->run($file, $progress);
    $matched = 0;
    $matchedSample = array();
    $unmatchedSample = array();
    foreach ($ret['entries'] as $entry) {
      if ($entry['status'] === 'matched') {
        $matched++;
        if (count($matchedSample) < 10) {
          $matchedSample[] = $entry['conditions'];
        }
      } else {
        if (count($unmatchedSample) < 10) {
          $unmatchedSample[] = $entry['conditions'];
        }
      }
    }
    return array(
      'total' => $ret['completed'],
      'matched' => $matched,
      'matchedSample' => $matchedSample,
      'unmatchedSample' => $unmatchedSample
    );
  }

}
