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

class AssetCollectVisitor implements Visitor {
  private $factory;
  private $timestamp;
  private $collected;

  public function __construct(Factory $factory) {
    $this->factory = $factory;
    $this->collected = array();
    $this->timestamp = new \DateTime();
  }

  public function visit($info) {
    $this->collected[] = array(
      'hash' => $info['hash'],
      'type' => Asset::detectType($info['path']),
      'size' => filesize($info['path'])
    );
    if (count($this->collected) > 100) {
      $this->flush();
    }
  }

  public function after() {
    $this->flush(true);
  }

  private function flush($deleteMissing=false) {
    if ($this->collected || $deleteMissing) {
      $factory = $this->factory;
      $builder = new StatementBuilder($this->factory->getConnection());
      $table = $factory->getServiceTable('assets');
      $ts = $this->timestamp;
      $conn = $factory->getConnection();
      if ($this->collected) {
        // update
        $keys = array();
        foreach ($this->collected as $info) {
          $keys[] = array(
            'hash' => $info['hash'],
            'created' => $ts,
            'last_checked' => $ts,
            'name' => $info['hash'],
            'sourceType' => 'file',
            'type' => $info['type'],
            'size' => $info['size']
          );
        }
        $sql = $this->getInsertUpdateSql($builder, $table, $keys);
        $conn->execute($sql);
      }
      if ($deleteMissing) {
        $sql = $builder->getDeleteSql($table, array('last_checked' => array('$not' => true, '$value' => $ts)));
        $conn->execute($sql);
      }
    }
  }

  private function getInsertUpdateSql($builder, $table, $keys) {
    $sql = $builder->getInsertSql($table, $keys);
    $sql .= " ON DUPLICATE KEY UPDATE last_checked=VALUES(last_checked)";
    return $sql;
  }

}
