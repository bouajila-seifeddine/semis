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

class DeleteRecord extends DBBase implements Statement {
  private $statements;

  public function __construct(Factory $factory, Escape $escape, Collection $collection, array $pks, $restrict=true) {
    parent::__construct($escape);
    if ($restrict && (
      $collection->getDeleteConditions() ||
      $collection->hasCallback('customDelete') ||
      $collection->hasCallback('afterDelete') ||
      $collection->hasCallback('beforeDelete')
    )) {
      $this->statements = array(new DeleteCondition($factory, $escape, $collection, $pks));
      return;
    }
    $this->statements = $this->prepareStatements($factory, $escape, $collection, $pks);
  }

  private function prepareStatements($factory, $escape, $collection, $pks) {
    $statements = array();

    if (! $collection->canDelete()) {
      throw new UserError("Can't delete " . $collection->getName());
    }

    $keys = $collection->getKeys();
    self::validateKeys($keys, $pks);

    if ($pks && $collection->hasCallback('customDelete')) {
      $statements[] = new Callback($collection->getCallback('customDelete'), $pks);
      return $statements;
    }

    if ($pks && $collection->hasCallback('beforeDelete')) {
      $statements[] = new Callback($collection->getCallback('beforeDelete'), $pks);
    }

    // delete extra tables
    foreach ($collection->getDeleteTables() as $def) {
      $table = $def['table'];
      $conds = self::getDeleteCondition(Utils::zip($collection->getKeys(), $def['fkeys']), $pks);
      $statements[] = new Delete($escape, $table, $conds);
    }


    // delete HABTM associations
    $links = $collection->getLinks();
    foreach ($links as $linkKey => $link) {
      if ($link->canDelete()) {
        if ($link->isHABTM()) {
          $statements[] = new DeleteAssociation($escape, $collection, $link, $pks);
        } else {
          // has many
          if ($link->hasConditions()) {
            $statements[] = new DeleteDependent($factory, $escape, $collection, $link, $pks);
          } else if ($pks) {
            if ($this->isPkLink($link)) {
              $subkeys = $this->mapKeys($pks, $collection, $link);
              $statements[] = new DeleteRecord($factory, $escape, $link->getTarget(), $subkeys);
            } else {
              $statements[] = new DeleteDependent($factory, $escape, $collection, $link, $pks);
            }
          } else {
            $statements[] = new DeleteRecord($factory, $escape, $link->getTarget(), array());
          }
        }
      } else if ($link->canDissoc()) {
        // TODO - dissoc link
      }
    }

    // delete record by pk
    $keys = $collection->getKeyFields();
    $pkTables = array();
    foreach ($keys as $key) {
      $mapping = array_reverse($key->getMapping());
      foreach ($mapping as $tableAlias => $column) {
        $table = $collection->getTable($tableAlias)['table'];
        if (! isset($pkTables[$table])) {
          $pkTables[$table] = array();
        }
        $pkTables[$table][$key->getId()] = $column;
      }
    }
    foreach ($pkTables as $table => $keys) {
      $conds = self::getDeleteCondition($keys, $pks);
      $statements[] = new Delete($escape, $table, $conds);
    }

    if ($pks && $collection->hasCallback('afterDelete')) {
      $statements[] = new Callback($collection->getCallback('afterDelete'), $pks);
    }
    return $statements;
  }

  public static function getDeleteCondition($keys, $keyValues=null) {
    $conds = array();
    if ($keyValues) {
      $firstRow = $keyValues[0];
      if (count($firstRow) == 1) {
        $key = array_keys($firstRow)[0];
        $column = $keys ? Utils::extract($key, $keys) : $key;
        $values = array();
        foreach ($keyValues as $keyValue) {
          $values[] = $keyValue[$key];
        }
        $conds = array($column => $values);
      } else {
        foreach ($keyValues as $keyValue) {
          $cond = array();
          foreach ($keyValue as $key=>$value) {
            $column = $keys ? Utils::extract($key, $keys) : $key;
            $cond[$column] = $value;
          }
          $conds[] = $cond;
        }
        $conds = array('$or' => $conds);
      }
    }
    return $conds;
  }

  private function mapKeys($pks, $collection, $link) {
    $source = $link->getSourceFields();
    $target = $link->getTargetFields();
    $mapping = array();
    for ($i=0; $i<count($source); $i++) {
      $mapping[$source[$i]] = $target[$i];
    }
    $ret = array();
    foreach ($pks as $pk) {
      $newPk = array();
      foreach ($pk as $k=>$v) {
        $map = Utils::extract($k, $mapping);
        $newPk[$map] = $v;
      }
      $ret[] = $newPk;
    }
    return $ret;
  }

  private function isPkLink($link) {
    $target = $link->getTarget();
    $keys = $target->getKeys();
    $fkeys = $link->getTargetFields();
    foreach ($fkeys as $fkey) {
      if (! in_array($fkey, $keys)) {
        return false;
      }
    }
    return true;
  }

  public static function validateKeys($keys, $pks) {
    if ($pks && is_array($pks)) {
      $firstRow = $pks[0];
      if (is_array($firstRow)) {
        foreach ($firstRow as $key=>$_) {
          if (! in_array($key, $keys)) {
            throw new UserError("Collection {$collection->getId()} does not contain key $key");
          }
        }
      } else {
        throw new UserError('Invariant: primary keys should be array of array');
      }
    }
  }

  public function getSQL(Context $context) {
    $sql = array();
    foreach ($this->statements as $stm) {
      $sql[] = $stm->getSQL($context);
    }
    return implode("\n", $sql);
  }

  public function execute(Factory $factory, Context $context) {
    foreach ($this->statements as $stm) {
      $stm->execute($factory, $context);
    }
  }


}
