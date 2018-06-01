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

class DBExecutor implements ImportExecutor {
  private $lastId;
  private $factory;
  private $clearCache = 0;

  public function __construct(Factory $factory) {
    $this->factory = $factory;
    $this->clearCache = 0;
  }

  public function execute(Modification $modification, Progress $progress) {
    $this->lastId = null;
    $modification->setAllowClearCache(false);
    $ret = $modification->execute($progress);
    $this->clearCache += $modification->getCacheClearCount();
    if ($ret) {
      $stats = $modification->getStats();
      if (is_array($stats)) {
        foreach ($stats as $key => $results) {
          if (isset($results['id'])) {
            $id = $results['id'];
            $this->lastId = is_array($id) ? $id : array($id);
            return true;
          }
        }
      }
    }
    return $ret;
  }

  public function cleanup(Progress $progress) {
    if ($this->clearCache) {
      $this->factory->clearCache();
    }
  }

  function getLastId() {
    return $this->lastId;
  }
}
