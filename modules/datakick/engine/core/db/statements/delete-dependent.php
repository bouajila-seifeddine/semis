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

class DeleteDependent extends DBBase implements Statement {
  private $factory;
  private $escape;
  private $source;
  private $link;
  private $conditions;

  public function __construct(Factory $factory, Escape $escape, Collection $source, Link $link, array $pks) {
    parent::__construct($escape);
    $this->factory = $factory;
    $this->escape = $escape;
    $this->source = $source;
    $this->link = $link;
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
    foreach ($this->link->getTarget()->getKeyFields() as $key) {
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
    $target = $this->link->getTarget();
    $sql = new Component($this->escape, $target);
    $sql->setDistinct(true);
    $keys = $sql->exposePrimaryFields();

    if ($this->link->hasConditions()) {
      foreach ($this->link->getConditions() as $c) {
        if (strpos("<target:", $c) >= 0) {
          $this->fixCondition($c, $sql);
        }
      }
    }

    if ($this->pks) {
      $mapping = utils::zip($this->link->getSourceFields(), $this->link->getTargetFields());
      $c = DeleteRecord::getDeleteCondition($mapping, $this->pks);
      if (isset($c['$or'])) {
        // TODO compose OR condition
      } else {
        foreach ($c as $field => $values) {
          $fld = $target->getField($field);
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

  private function fixCondition($cond, $sql) {
    $matches = array();
    $pattern = "/<target:([a-zA-Z0-9-_]+)>/";
    preg_match_all($pattern, $cond, $matches, PREG_PATTERN_ORDER);
    $orig = $matches[0];
    $names = $matches[1];
    $req = array();
    for ($i = 0; $i<count($orig); $i++) {
      $param = $orig[$i];
      $name = $names[$i];
      $fld = $this->link->getTarget()->getField($name);
      $req = array_merge($req, $fld->getRequiredTables());
      $cond = str_replace($param, $fld->getSql(), $cond);
    }
    $sql->addCondition($cond, array_unique($req));
  }

  private function getDeleteRecordStatement($pks=null) {
    $collection = $this->link->getTarget();
    if (is_null($pks)) {
      $pks = array($this->getKeyNames(), $this->getKeyNames('...'));
    }
    return new DeleteRecord($this->factory, $this->escape, $collection, $pks);
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
