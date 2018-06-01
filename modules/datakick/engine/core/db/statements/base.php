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

class DBBase {
  private $statementBuilder;
  private $parent;

  public function __construct($escape) {
    if ($escape) {
      $this->statementBuilder = new StatementBuilder($escape);
    }
  }

  public function encodeLiteral($val, $type) {
    if (is_null($val))
      return 'NULL';

    if (Types::isNumber($type)) {
      return is_numeric($val) ? ($val + 0) : 0;
    }
    if (Types::isBoolean($type)) {
      return $val ? 1 : 0;
    }
    if (Types::isDateTime($type)) {
      $ts = $val->getTimestamp();
      return "FROM_UNIXTIME($ts)";
    }
    if (Types::isCurrency($type)) {
      return array(
        'currency' => (int)$val->getCurrencyId(),
        'value' => $this->encodeLiteral($val->getValue(), 'number')
      );
    }
    return "'" . $this->getStatementBuilder()->escape($val). "'";
  }

  public function getSQLTables(Context $context, $tables, $set, $prefix=" ") {
    $ret = "";
    $first = true;
    foreach ($tables as $alias=>$table) {
      if (in_array($alias, $set)) {
        $obj = $table['table'];
        if (is_array($obj)) {
          $obj = $this->parametrizeArray($context, $obj)['sql'];
        }
        $join = $joinCond = "";
        if (! $first) {
          if (isset($table['join'])) {
            $join = $table['join']['type'] . " JOIN ";
            if (isset($table['join']['conditions'])) {
              $conds = $this->parametrizeArray($context, $table['join']['conditions']);
              $conds = $this->processConditions($conds);
              $joinCond = " ON (" . join(" AND ", $conds) . ")";
            }
          }
          else {
            $join = 'INNER JOIN ';
          }
        }
        $ret .= "$prefix$join$obj AS " . $this->getSQLAlias($alias, true) . $joinCond;
        $first = false;
      }
    }
    return $ret;
  }

  protected function parametrizeArray(Context $context, $input) {
    $ret = array();
    foreach ($input as $key=>$entry) {
      $ret[$key] = $this->parametrizeExpression($context, $entry);
    }
    return $ret;
  }

  public function parametrizeExpression(Context $context, $entry) {
    $matches = array();
    $pattern = '/<param:([a-zA-Z0-9_]+)>/';
    preg_match_all($pattern, $entry, $matches, PREG_PATTERN_ORDER);
    $orig = $matches[0];
    $names = $matches[1];
    for ($i = 0; $i<count($orig); $i++) {
      $param = $orig[$i];
      $name = $names[$i];
      $type = $context->getType($name);
      $value = $context->getValue($name);
      $entry = str_replace($param, $this->encodeLiteral($value, $type), $entry);
    }

    $matches = array();
    $pattern = '/<bind-param:([a-zA-Z0-9_]+):([a-zA-Z0-9._-]+)>/';
    preg_match_all($pattern, $entry, $matches, PREG_PATTERN_ORDER);
    $orig = $matches[0];
    $names = $matches[1];
    $cols = $matches[2];
    for ($i = 0; $i<count($orig); $i++) {
      $param = $orig[$i];
      $name = $names[$i];
      $col = $cols[$i];
      $type = $context->getType($name);
      $value = $context->getValue($name);
      if ($value === '$all') {
        $entry = str_replace($param, '1', $entry);
      } else {
        if (is_array($value)) {
          $vals = array();
          foreach ($value as $val) {
            $vals[] = $this->encodeLiteral($value, $type);
          }
          $val = $col .= ' IN (' . implode(', ', $vals) . ')';
          $entry = str_replace($param, $val, $entry);
        } else {
          $literal = $this->encodeLiteral($value, $type);
          if (is_numeric($col)) {
            if ($literal == $col) {
              $val = "1";
            } else {
              $val = "0";
            }
          } else {
            $val = "$col = $literal";
          }
          $entry = str_replace($param, $val, $entry);
        }
      }
    }
    return $entry;
  }

  protected function getSQLAlias($alias, $escape=false) {
    $alias = Utils::decamelize($alias);
    return $escape ? $this->escapeObj($alias) : $alias;

  }

  private function escapeObj($obj) {
    return "`$obj`";
  }

  protected function collectRequiredTables($fields, $tables) {
    $tablesRequire = array();
    foreach($tables as $alias => $tbl) {
      $tablesRequire[$alias] = self::getTableDependencies($alias, $tables);
    }
    $aliases = array();
    foreach ($fields as $fld) {
      foreach ($fld->getRequiredTables() as $req) {
        array_push($aliases, $req);
        if (! isset($tablesRequire[$req])) {
          throw new \Exception("Table not found: $req");
        }
        $aliases = array_merge($aliases, $tablesRequire[$req]);
      }
    }
    return array_unique($aliases);
  }

  protected static function getTableDependencies($alias, $tables) {
    $deps = array();
    self::collectTableDependencies($alias, $tables, $deps);
    return $deps;
  }

  private static function collectTableDependencies($alias, $tables, &$deps) {
    if (isset($tables[$alias]['require'])) {
      foreach($tables[$alias]['require'] as $r) {
        if (! in_array($r, $deps)) {
          array_push($deps, $r);
          self::collectTableDependencies($r, $tables, $deps);
        }
      }
    }
  }

  protected function processConditions($conditions) {
    if ($conditions) {
      $ret = array();
      foreach ($conditions as $cond) {
        if (! ($cond === "1" || is_null($cond))) {
          $ret[] = $cond;
        }
        if ($cond === "0") {
          // short ciruit
          return array("0");
        }
      }
      return $ret ? $ret : array("1");
    }
    return array();
  }

  protected function getStatementBuilder() {
    if ($this->statementBuilder) {
      return $this->statementBuilder;
    }
    throw new \Exception('Statement builder not set');
  }

  public function setParent($parent) {
    $this->parent = $parent;
  }

  public function getParent() {
    return $this->parent;
  }

}
