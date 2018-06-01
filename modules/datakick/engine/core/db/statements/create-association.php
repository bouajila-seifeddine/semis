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

class CreateAssociation extends DBBase implements Statement {
  private $link;
  private $keys;
  private $values;

  public function __construct(Escape $escape, Link $link, $keys, $values) {
    parent::__construct($escape);
    $this->link = $link;
    $this->keys = $keys;
    $this->values = $values;
  }

  public function getSQL(Context $context) {
    return $this->doGetSQL($context);
  }

  private function doGetSQL(Context $context, $factory=null) {
    $link = $this->link;
    $id = $link->getId();
    $keys = $this->keys;
    $values = $this->values;
    $table = $link->getJoinTable();
    $sourceFields = $link->getSourceFields();
    $targetFields = $link->getTargetFields();
    $joinTargetFields = $link->getJoinTargetFields();
    $joinSourceFields = $link->getJoinSourceFields();

    if ($sourceFields == $link->getSource()->getKeys()) {
      $vals = $link->getDefaultCreateValues();
      $extra = array();
      for ($i = 0; $i<count($joinSourceFields); $i++) {
        $vals[$joinSourceFields[$i]] = $keys[$i];
      }
      for ($i = 0; $i<count($joinTargetFields); $i++) {
        $fieldId = $joinTargetFields[$i];
        if (! isset($values[$fieldId])) {
          throw new \Exception("Can't create association $id - field $fieldId is missing");
        }
        $vals[$fieldId] = $values[$fieldId];
      }
      foreach ($values as $key => $val) {
        if ($link->hasExtraField($key)) {
          $fld = $link->getExtraField($key);
          $id = $fld->getSQLField();
          if (! is_null($val)) {
            $extra[$id] = $val;
          }
        }
      }

      if ($factory) {
        $link->triggerCallback('beforeCreate', array(&$vals, $factory));
      }

      $builder = $this->getStatementBuilder();
      if ($extra) {
        return $builder->getInsertUpdateSql($table, $vals, $extra);
      } else {
        return $builder->getInsertSql($table, $vals, true);
      }
    } else {
      throw new UserError('Create associations with non-pk not supported yet');
    }
  }

  public function execute(Factory $factory, Context $context) {
    $sql = $this->doGetSQL($context, $factory);
    $num = 0;
    if ($sql) {
      $conn = $factory->getConnection();
      $ret = $conn->execute($sql);
      $num = $conn->numRows();
    }
    $tableName = $this->link->getJoinTable();
    return array($tableName => array(
      'insert' => $num
    ));
  }
}
