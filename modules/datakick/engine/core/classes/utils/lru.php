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

class LRUCache {
  private $head;
  private $tail;
  private $capacity;
  private $hashmap;

  public function __construct($capacity) {
    $this->capacity = $capacity;
    $this->hashmap = array();
    $this->head = new LRUCacheItem(null, null);
    $this->tail = new LRUCacheItem(null, null);
    $this->head->setNext($this->tail);
    $this->tail->setPrevious($this->head);
  }

  public function has($key) {
    return isset($this->hashmap[$key]);
  }

  public function get($key) {
    if ($this->has($key)) {
      $node = $this->hashmap[$key];
      if (count($this->hashmap) == 1) {
        return $node->getData();
      }
      $this->detach($node);
      $this->attach($this->head, $node);
      return $node->getData();
    }
    return null;
  }

  public function put($key, $data) {
    if ($this->has($key) && !empty($this->hashmap[$key])) {
      $node = $this->hashmap[$key];
      // update data
      $this->detach($node);
      $this->attach($this->head, $node);
      $node->setData($data);
    } else {
      $node = new LRUCacheItem($key, $data);
      $this->hashmap[$key] = $node;
      $this->attach($this->head, $node);
      if (count($this->hashmap) > $this->capacity) {
        $nodeToRemove = $this->tail->getPrevious();
        $this->detach($nodeToRemove);
        unset($this->hashmap[$nodeToRemove->getKey()]);
      }
    }
    return true;
  }

  public function remove($key) {
    if (!isset($this->hashmap[$key])) {
      return false;
    }
    $nodeToRemove = $this->hashmap[$key];
    $this->detach($nodeToRemove);
    unset($this->hashmap[$nodeToRemove->getKey()]);
    return true;
  }

  private function attach($head, $node) {
    $node->setPrevious($head);
    $node->setNext($head->getNext());
    $node->getNext()->setPrevious($node);
    $node->getPrevious()->setNext($node);
  }

  private function detach($node) {
    $node->getPrevious()->setNext($node->getNext());
    $node->getNext()->setPrevious($node->getPrevious());
  }
}

class LRUCacheItem {
  private $key;
  private $data;
  private $next;
  private $previous;

  public function __construct($key, $data) {
    $this->key = $key;
    $this->data = $data;
  }

  public function setData($data) {
    $this->data = $data;
  }

  public function setNext($next) {
    $this->next = $next;
  }

  public function setPrevious($previous) {
    $this->previous = $previous;
  }

  public function getKey() {
    return $this->key;
  }

  public function getData() {
    return $this->data;
  }

  public function getNext() {
    return $this->next;
  }

  public function getPrevious() {
    return $this->previous;
  }

}
