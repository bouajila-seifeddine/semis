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

interface Escape {
  function escape($string);
}

abstract class Connection implements Escape {
  private $factory;
  private $queries = array();
  private $strictModeEnabled = false;

  public function __construct($factory) {
    $this->factory = $factory;
  }

  public function insert($table, $data) {
    if ($data) {
      $this->execute($this->getStatementBuidler()->getInsertSql($table, $data));
      return $this->getLastInsertId();
    } else {
      return true;
    }
  }

  public function insertUpdate($table, $keys, $values) {
    return $this->execute($this->getStatementBuidler()->getInsertUpdateSql($table, $keys, $values));
  }

  public function update($table, $data, $where='') {
    return $this->execute($this->getStatementBuidler()->getUpdateSql($table, $data, $where));
  }

  public function delete($table, $where=null) {
    return $this->execute($this->getStatementBuidler()->getDeleteSql($table, $where));
  }

  public function singleSelect($query, $default=null, $field=null) {
    $ret = $this->query($query);
    if ($ret) {
      $row = $ret->fetch();
      if ($row) {
        if ($field && isset($row[$field])) {
          return $row[$field];
        } else {
          return current($row);
        }
      }
    }
    return $default;
  }

  public function query($sql) {
    $success = $this->doQuery($sql);
    $this->log($sql, $success);
    if (! $success) {
      $error = $this->getLastError();
      throw new SqlError($error, $sql);
    }
    return $success;
  }

  public function execute($sql) {
    $success = false;
    $exception = null;
    try {
      if (false && !$this->strictModeEnabled) {
        $this->doExecute("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
        $this->strictModeEnabled = true;
      }
      $success = $this->doExecute($sql);
      if (! $success) {
        $error = $this->getLastError();
        throw new SqlError($error, $sql);
      }
    } catch (\Exception $e) {
      $exception = $e;
    }
    $this->log($sql, $success);
    if ($exception) {
      throw $exception;
    }
    return $success;
  }

  private function log($sql, $status) {
    if ($this->factory->debugMode()) {
      $entry = array(
        'sql' => $sql,
        'status' => !!$status
      );
      array_push($this->queries, $entry);
    }
  }

  public function getQueries() {
    return $this->queries;
  }

  public function totalRows($query) {
    if ($query->hasLimit()) {
      if ($query->hasCalcRows()) {
        $res = $this->query('SELECT FOUND_ROWS() AS r')->fetch();
        return (int)$res['r'];
      }
      throw new \Exception('totalRows for sql without SQL_CALC_FOUND_ROWS not supported yet');
    }
    return $this->numRows();
  }

  public function dropTable($table) {
    $sql = "DROP TABLE IF EXISTS `$table`;";
    return $this->execute($sql);
  }

  public function createTable($table, $fields, $primaryKey, $fkeys=array(), $ukeys=array()) {
    $engine = $this->getDefaultEngine();
    $keys = self::arrToKeys($primaryKey);

    $lines = array();
    foreach ($fields as $key => $value) {
      $lines[] = "  `$key` $value";
    }
    $lines[] = "  PRIMARY KEY ($keys)";

    if ($fkeys) {
      foreach ($fkeys as $ftable => $def) {
        $type = isset($def['type']) ? $def['type'] : 'CASCADE';
        $p = self::arrToKeys($def['source']);
        $f = self::arrToKeys($def['target']);
        $lines[] = "  FOREIGN KEY ($p) REFERENCES `$ftable`($f) ON DELETE $type";
      }
    }

    if ($ukeys) {
      foreach ($ukeys as $ukeysfields) {
        $u = self::arrToKeys($ukeysfields);
        $lines[] = "  UNIQUE KEY ($u)";
      }
    }

    $sql = "CREATE TABLE IF NOT EXISTS `$table` (\n";
    $sql .= implode($lines, ", \n");
    $sql .= "\n) ENGINE=$engine DEFAULT CHARSET=utf8;\n";

    return $this->execute($sql);
  }

  private static function arrToKeys($arr) {
    return implode(array_map(function($key) {
      return "`$key`";
    }, $arr), ', ');
  }

  public function addColumn($table, $column, $type, $subtype) {
    $sqlType = $this->getColumnSqlType($type, $subtype);
    if (is_array($sqlType)) {
      $ret = true;
      foreach($sqlType as $key => $type) {
        $ret = $ret && $this->createColumn($table, $column.'_'.$key, $type);
      }
      return $ret;
    } else {
      return $this->createColumn($table, $column, $sqlType);
    }
  }

  public function createColumn($table, $column, $sqlType) {
    $sql  = "ALTER TABLE `$table` ADD COLUMN `$column` $sqlType;";
    return $this->execute($sql);
  }

  public function dropColumn($table, $column, $type, $subtype) {
    $sqlType = $this->getColumnSqlType($type, $subtype);
    if (is_array($sqlType)) {
      $ret = true;
      foreach($sqlType as $key => $type) {
        $ret = $ret && $this->removeColumn($table, $column . '_' . $key);
      }
      return $ret;
    } else {
      return $this->removeColumn($table, $column);
    }
  }

  public function removeColumn($table, $column) {
    $sql  = "ALTER TABLE `$table` DROP COLUMN `$column`;";
    return $this->execute($sql);
  }

  public function alterColumn($table, $column, $def) {
    $sql  = "ALTER TABLE `$table` MODIFY `$column` $def;";
    return $this->execute($sql);
  }

  private function getColumnSqlType($type, $subtype) {
    if (Types::isString($type)) {
      if ($subtype) {
        $length = (int)$subtype;
        if ($length > 255) {
          return 'mediumtext';
        } else {
          if ($length == 0)
            $length = 1;
          return "varchar($length)";
        }
      }
      return 'varchar(256)';
    }

    if (Types::isNumber($type)) {
      if ($subtype == 'decimal') {
        return 'decimal(20, 6)';
      }
      return 'int(11)';
    }

    if (Types::isBoolean($type)) {
      return 'tinyint(1)';
    }

    if (Types::isDateTime($type)) {
      if ($subtype == 'date' || $subtype = 'datetime') {
        return $subtype;
      }
      $sqlType = 'datetime';
    }

    if (Types::isCurrency($type)) {
      $subtype = (int)$subtype;
      if ($subtype > 0) {
        return array(
          'value' => 'decimal(20, 6)'
        );
      } else {
        return array(
          'value' => 'decimal(20, 6)',
          'currency' => 'int(11)'
        );
      }
    }

    throw new UserError("Can't determine column sql type for $type/$subtype");
  }

  private function getStatementBuidler() {
    return new StatementBuilder($this);
  }

  public abstract function escape($input);
  public abstract function doQuery($sql);
  public abstract function getDefaultEngine();
  public abstract function doExecute($sql);
  public abstract function getLastInsertId();
  public abstract function getLastError();
  public abstract function numRows();
  public abstract function getVersion();

}
