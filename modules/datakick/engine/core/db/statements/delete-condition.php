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

class DeleteCondition extends DBBase implements Statement {
  private $factory;
  private $escape;
  private $col;
  private $conditions;

  public function __construct(Factory $factory, Escape $escape, Collection $col, array $pks) {
    parent::__construct($escape);
    $this->factory = $factory;
    $this->escape = $escape;
    $this->col = $col;
    $this->pks = $pks;
  }

  public function getSQL(Context $context) {
    $keys = implode(', ', $this->getKeyNames());

    list($sql, $_) = $this->getSelectSQL(false);
    $select = str_replace("\n", "\n        ", $sql->getSQL($this->getUnrestrictedContext($context)));
    $deleteRecord = $this->getDeleteRecordStatement();
    $ret = "BEGIN";
    $ret .= "\n    <".$keys."> = $select;\n";
    $ret .= str_replace("\n", "\n    ", "    ".$deleteRecord->getSQL($context)) ."\n";
    $ret .= "END";
    return $ret;
  }

  public function execute(Factory $factory, Context $context) {
    list($sql, $keys) = $this->getSelectSQL(true);
    $ret = $sql->execute($factory, $this->getUnrestrictedContext($context));
    $keyFields = array();
    foreach ($this->col->getKeyFields() as $key) {
      $keyFields[$key->getId()] = $key->getType();
    }
    if ($ret) {
      $pks = array();
      while ($row = $ret->fetch()) {
        $pk = array();
        foreach ($keys as $id=>$column) {
          $pk[$id] = Types::convertValue($keyFields[$id], $row[$column]);
        }
        $pks[] = $pk;
      }
    }
    if ($pks) {
      $chunks = array_chunk($pks, 999);
      foreach ($chunks as $chunk) {
        $deleteRecord = $this->getDeleteRecordStatement($chunk);
        $deleteRecord->execute($factory, $context);
      }
    }
  }

  private function getSelectSQL($encode=true) {
    $sql = new Component($this->escape, $this->col);
    $sql->setDistinct(true);
    $keys = $sql->exposePrimaryFields();
    foreach ($this->col->getDeleteConditions() as $field => $expr) {
      $this->fixCondition($field, $expr, $sql);
    }
    if ($this->pks) {
      $c = DeleteRecord::getDeleteCondition(null, $this->pks);
      if (isset($c['$or'])) {
        // TODO compose OR condition
      } else {
        foreach ($c as $field => $values) {
          $fld = $this->col->getField($field);
          $vals = $values;
          if ($encode) {
            foreach ($vals as &$v) {
              $v = $this->encodeLiteral($v, $fld->getType());
            }
          }
          $sql->addCondition($fld->getSql() .' IN (' . implode(', ', $vals) .')', $fld->getRequiredTables());
        }
      }
    }
    return array($sql, $keys);
  }

  private function fixCondition($field, $expr, $sql) {
    $fld = $this->col->getField($field);
    $req = $fld->getRequiredTables();
    $cond = str_replace('<field>', $fld->getSql(), $expr);
    $sql->addCondition($cond, array_unique($req));
  }

  private function getDeleteRecordStatement($pks=null) {
    if (is_null($pks)) {
      $pks = array($this->getKeyNames(), $this->getKeyNames('...'));
    }
    return new DeleteRecord($this->factory, $this->escape, $this->col, $pks, false);
  }

  private function getKeyNames($replace=null) {
    $ret = array();
    $col = $this->link->getTarget();
    $name = $col->getSingularId();
    foreach ($col->getKeys() as $key) {
      $ret[$key] = $replace ? $replace : "@".$name.ucfirst($key);
    }
    return $ret;
  }

  private function getUnrestrictedContext(Context $context) {
    $cloned = clone($context);
    $cloned->setValue('shop', '$all');
    $cloned->setValue('language', '$all');
    return $cloned;
  }

}
