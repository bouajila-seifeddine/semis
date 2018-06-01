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


class StatementBuilder {

  public function __construct(Escape $escape) {
    $this->escape = $escape;
  }

  public function getInsertUpdateSql($table, $keys, $values) {
    $all = array_merge($keys, $values);
    $sql = $this->getInsertSql($table, $all);
    $sql .= "\nON DUPLICATE KEY UPDATE\n  ";
    $set = array();
    foreach ($values as $key => $value) {
      $k = "`" . $this->escape($key) . "`";
      array_push($set, "$k = VALUES($k)");
    }
    $sql .= implode(",\n  ", $set);
    return $sql;
  }

  public function getInsertSql($table, $data, $insertIgnore=false) {
    $current = current($data);
    if (is_array($current) && isset($current['literal'])) {
      $data = array($data);
    } else if (! is_array($current)) {
      $data = array($data);
    }
    $keys = array();
    $values = array();
    $firstLoop = true;
    foreach ($data as $row_data) {
      $vals = array();
      foreach ($row_data as $key => $value) {
        if (!$firstLoop) {
          if (!in_array("`$key`", $keys)) {
            throw new \Exception("Keys don't match");
          }
        } else {
          $keys[] = '`'. $this->escape($key).'`';
        }
        $vals[] = $this->escapeValue($value);
      }
      $firstLoop = false;
      $values[] = '('.implode(', ', $vals).')';
    }
    $keys = implode(', ', $keys);
    $sep = count($values) > 1 ? "\n  " : "";
    $values = implode(",\n  ", $values);
    $insert = $insertIgnore ? "INSERT IGNORE" : "INSERT";

    return "$insert INTO `$table` ($keys) VALUES $sep$values";
  }

  public function getUpdateSql($table, $data, $where) {
    if (!$data) {
      return true;
    }

    $sql = "UPDATE `$table` SET \n  ";
    $sql .= $this->getUpdateFields($data);

    if ($where) {
      $sql .= "\nWHERE " . $this->getWhereCondition($where);
    }

    return $sql;
  }

  public function getDeleteSql($table, $where) {
    $sql = "DELETE FROM `$table`";
    if ($where) {
      $sql .= " WHERE " . $this->getWhereCondition($where);
    }
    return $sql;
  }

  public function getWhereCondition($where) {
    if (is_array($where)) {
      if (array_key_exists('$or', $where)) {
        $conditions = $where['$or'];
        $all = array();
        foreach ($conditions as $cond) {
          $all[] = '(' .$this->getWhereCondition($cond). ')';
        }
        return implode(" OR ", $all);
      }
      $conds = array();
      foreach ($where as $key => $value) {
        $k = $this->escape($key);
        $not = false;
        if (is_array($value) && array_key_exists('$value', $value)) {
          if (array_key_exists('$not', $value)) {
            $not = !!$value['$not'];
          }
          $value = $value['$value'];
        }
        $c;
        if (is_array($value)) {
          $values = array();
          foreach ($value as $val) {
            $values[] = $this->escapeValue($val);
          }
          $values= implode(', ', $values);
          $c = "IN (".$values.")";
        } else if ($value === '' || is_null($value)) {
          $c = "IS" . ($not ? ' NOT ' : ' ') ."NULL";
        } else {
          $c = ($not ? "!= " : "= ") . $this->escapeValue($value);
        }
        array_push($conds, "$k $c");
      }
      return implode(" AND ", $conds);
    }
    if (is_string($where)) {
      return $where;
    }
    throw new \Exception('Invalid where condition '+print_r($where, true));
  }

  private function getUpdateFields($data) {
    $set = array();
    foreach ($data as $key => $value) {
      $k = $this->escape($key);
      $v = $this->escapeValue($value);
      array_push($set, "`$k` = $v");
    }
    return implode(",\n  ", $set);
  }

  private function escapeValue($value) {
    if (is_array($value) && array_key_exists('value', $value) && array_key_exists('literal', $value)) {
      $literal = $value['literal'];
      $transform = isset($value['transform']) ? $value['transform'] : null;
      $value = $value['value'];
      if (! $literal && !is_null($value) && is_string($value)) {
        return $value;
      }
      if ($transform) {
        return str_replace('<field>', $this->escapeValue($value), $transform);
      }
    }
    if (is_null($value) || $value === '')
      return 'NULL';

    if (is_bool($value))
      return $value ? '1' : '0';

    if (is_int($value) || is_float($value)) {
      return $value;
    }

    if (is_a($value, 'DateTime')) {
      $ts = $value->getTimestamp();
      return "FROM_UNIXTIME($ts)";
    }

    if (is_array($value)) {
      $value = implode(',', $value);
    }

    return "'" . $this->escape($value) . "'";
  }

  public function escape($value) {
    return $this->escape->escape($value);
  }

}
