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

class SchemaCollector {
  private $schema = array();
  private $collectionRole = array();

  public function getSchema() {
    $this->fixLinks();
    $this->fixFields();
    return $this->schema;
  }

  private function add($collection) {
    $id = $collection['id'];
    if (isset($collection['role'])) {
      $this->collectionRole[$collection['role']] = $id;
    }
    $this->schema[$id] = $collection;
  }

  public function registerSystemCollection($collection) {
    $this->add($collection);
  }

  public function registerCollection($collection) {
    $this->add($collection);
  }

  // remove links
  private function fixLinks() {
    foreach ($this->schema as &$collection) {
      if (isset($collection['links'])) {
        foreach ($collection['links'] as $key => &$link) {
          $col = $link['collection'];
          if (is_array($col) && isset($col['role'])) {
            $role = $col['role'];
            if (isset($this->collectionRole[$role])) {
              $col = $this->collectionRole[$role];
              $link['collection'] = $col;
            } else {
              $col = null;
            }
          }
        }
      }
    }
  }

  private function fixFields() {
    foreach ($this->schema as $key => &$collection) {
      foreach ($collection['fields'] as &$field) {
        if (isset($field['selectRecord'])) {
          $selectRecord = $field['selectRecord'];
          if (is_array($selectRecord)) {
            $role = $selectRecord['role'];
            if (isset($this->collectionRole[$role])) {
              $selectRecord = $this->collectionRole[$role];
            } else {
              $selectRecord = null;
            }
            $field['selectRecord'] = $selectRecord;
          }
        }
      }
    }
  }

}
