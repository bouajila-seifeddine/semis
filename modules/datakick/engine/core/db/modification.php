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

class Modification {
  private $allowClearCache;
  private $cacheClearing;
  private $dictionary;
  private $expressions;
  private $modifications;
  private $escape;
  private $stats;
  private $after = array();

  public function __construct(Factory $factory, Context $context) {
    $this->factory = $factory;
    $this->allowClearCache = true;
    $this->cacheClearing = 0;
    $this->escape = $factory->getConnection();
    $this->context = $context;
    $this->modifications = array();
    $this->stats = array();
  }

  public function addUpdate($collection, Array $fields, Array $conditions, $customContext=null) {
    $factory = $this->factory;
    $dict = $factory->getDictionary();
    $col = $this->getCollection($collection);
    $tables = array();
    $after = array();
    $context = is_null($customContext) ? $this->context : $customContext;
    foreach ($fields as $fieldName => $updateExpression) {
      $field = $dict->getField($collection, $fieldName);

      if (! $field->isEditable()) {
        throw new UserError("Field $collection.$fieldName is not mutable");
      }

      foreach ($field->getUpdate() as $table => $sql) {
        if (! isset($tables[$table]))
          $tables[$table] = array();
        $transform = '<field>';
        if (is_array($sql) && isset($sql['field'])) {
          if (isset($sql['write'])) {
            $transform = $sql['write'];
          }
          $sql = $sql['field'];
        }
        $tables[$table][$fieldName] = array(
          'field' => $sql,
          'transform' => $transform,
          'expression' => $updateExpression
        );
      }

      if ($field->hasAfterUpdateCallback() && !isset($after[$fieldName])) {
        $after[$fieldName] = new Callback($field->getAfterUpdateCallback());
      }
    }

    $expressions = $factory->getExpressions();
    foreach ($tables as $table => $fields) {
      $def = $col->getTable($table);
      if (isset($def['join'])) {
        $type = $def['join']['type'];
        if ($type === 'LEFT') {
          $insertMissing = new InsertMissing($this->escape, $col, $table);
          foreach ($conditions as $cond) {
            $expressions->exposeCondition($insertMissing, $cond, $context);
          }
          $this->registerModification($insertMissing, $customContext);
        }
      }

      $update = new Update($this->escape, $col, $table);
      foreach($fields as $expr) {
        $expressions->exposeUpdateField($update, $expr['field'], $expr['expression'], $expr['transform'], $context);
      }
      foreach ($conditions as $cond) {
        $expressions->exposeCondition($update, $cond, $context);
      }
      $this->registerModification($update, $customContext);
    }

    foreach ($after as $cb) {
      $this->registerModification($cb, $customContext);
    }
  }

  public function addAssociation($col, $linkKey, $keys, $values, $context=null) {
    $collection = $this->getCollection($col);
    $link = $collection->getLink($linkKey);
    if (! $link->canCreate()) {
      throw new UserError("Can't create association {$link->getFullId()}");
    }
    if ($link->isHABTM()) {
      $association = new CreateAssociation($this->escape, $link, $keys, $values);
      $this->registerModification($association, $context);
    } else {
      throw new UserError("Link {$link->getFullId()} is not HABTM, can't associate");
    }
  }

  public function deleteAssociation(Collection $collection, Link $link, $pks=null) {
    $this->registerModification(new DeleteAssociation($this->escape, $collection, $link, $pks));
  }

  public function addCreate($col, $values) {
    $collection = $this->getCollection($col);

    if (! $collection->canCreate()) {
      throw new UserError("Can't create " . $collection->getName());
    }

    // register after batch callback
    if ($collection->hasCallback('afterBatch')) {
      $this->after[$col] = new Callback($collection->getCallback('afterBatch'));
    }

    // perform beforeCreate callback
    $collection->triggerCallback('beforeCreate', array(&$values, $this->factory));

    $tables = array();

    // validate required fields
    foreach ($collection->getFields() as $alias => $fld) {
      if (! $fld->isCalculated() && $fld->isRequired() && !isset($values[$alias])) {
        throw new UserError("Field $alias is required");
      }
    }

    $virtual = array();
    $after = array();
    foreach ($values as $alias=>$value) {
      $fld = $collection->getField($alias);
      if (! $fld->isVirtual()) {
        $this->addInsertField($collection, $fld, $value, $tables);
      } else {
        $virtual[$alias] = $value;
      }
      if ($fld->hasAfterUpdateCallback()) {
        $after[] = new Callback($fld->getAfterUpdateCallback());
      }
    }

    $primary = null;
    foreach ($tables as $def) {
      $customContext = $this->getCustomContext($def['parameters']);
      $table = $collection->getTable($def['table']);
      $tableName = $table['table'];
      $insert = new Insert($this->escape, $tableName, $table, $def['values'], !$primary);
      if ($primary) {
        $insert->setParent($primary);
      } else {
        $primary = $insert;
      }
      $this->registerModification($insert, $customContext);
    }
    foreach ($virtual as $alias => $value) {
      $fld = $collection->getField($alias);
      $setVirtualField = new SetVirtualField($this->escape, $collection, $fld, $value, $primary);
      $this->registerModification($setVirtualField);
    }
    foreach ($after as $afterCb) {
      $this->registerModification($afterCb);
    }
  }

  private function getCustomContext($params) {
    if ($params) {
      $c = $this->context;
      $newContext = $this->factory->getContext($c->getValue('executionSource'), $c->getValue('executionSourceId'));
      $newContext->setValues($params);
      return $newContext;
    }
  }

  private function addInsertField(Collection $collection, Field $fld, $value, &$tables) {
    if ($fld->isCalculated()) {
      throw new UserError('Field '.$fld->getDescription().' is not mapped to db');
    }
    $update = $fld->getUpdate();
    if (! $update) {
      throw new UserError('Field '.$fld->getDescription().' is not writable');
    }
    foreach ($collection->getCoreTables() as $table) {
      if (! isset($tables[$table])) {
        if (self::areParametersFulfilled($collection->getTable($table), $value)) {
          $this->addInsertFieldTable($collection, $table, null, $value, $tables);
        }
      }
    }
    foreach ($update as $tableAlias => $column) {
      $this->addInsertFieldTable($collection, $tableAlias, $column, $value, $tables);
    }
  }

  private function addInsertFieldTable(Collection $collection, $tableAlias, $column, $value, &$tables) {
    $table = $collection->getTable($tableAlias);

    if (isset($table['require'])) {
      foreach ($table['require'] as $req) {
        $this->addInsertFieldTable($collection, $req, null, $value, $tables);
      }
    }

    if (is_array($value)) {
      foreach ($value as $valueVariant) {
        $val = $valueVariant['value'];
        unset($valueVariant['value']);
        $parameters = array();
        if (isset($table['parameters'])) {
          foreach ($table['parameters'] as $param) {
            if (! isset($valueVariant[$param])) {
              throw new UserError("{$collection->getId()}.$column: parameter $param not set");
            }
            $parameters[$param] = $valueVariant[$param];
          }
          ksort($parameters);
        }
        // add default fields
        if (isset($table['create'])) {
          foreach ($table['create'] as $f => $expression) {
            if ($f) {
              $this->addInsertFieldValue($tableAlias, $parameters, $tables, $f, $expression, false);
            }
          }
        }
        $this->addInsertFieldValue($tableAlias, $parameters, $tables, $column, $val);
      }
    } else {
      if (isset($table['parameters'])) {
        throw new UserError("Can't initialize table `$tableAlias` - parameters not provided");
      }
      if (isset($table['create'])) {
        foreach ($table['create'] as $f => $expression) {
          if ($f) {
            $this->addInsertFieldValue($tableAlias, array(), $tables, $f, $expression, false);
          }
        }
      }
      $this->addInsertFieldValue($tableAlias, array(), $tables, $column, $value);
    }
  }

  private static function areParametersFulfilled($table, $value) {
    if (isset($table['parameters'])) {
      if (is_array($value)) {
        foreach ($table['parameters'] as $param) {
          if (! array_key_exists($param, $value)) {
            return false;
          }
        }
        return true;
      }
      return false;
    }
    return true;
  }

  private function addInsertFieldValue($tableAlias, $parameters, &$tables, $column, $value, $literal=true) {
    $unique = $tableAlias;
    $transform = null;
    foreach ($parameters as $key => $val) {
      $unique .= '-' . $val;
    }
    if (! isset($tables[$unique])) {
      $tables[$unique] = array(
        'table' => $tableAlias,
        'parameters' => $parameters,
        'values' => array()
      );
    }

    if ($column) {
      if (is_array($column) && isset($column['field'])) {
        $transform = isset($column['write']) ? $column['write'] : null;
        $column = $column['field'];
      }
      if ($literal || !isset($tables[$unique]['values'][$column])) {
        if (! is_array($column)) {
          $column = array($column);
        }
        foreach ($column as $k => $c) {
          if ($k === 'currency') {
            $v = is_array($value) ? $value['currency']  : $value->getCurrencyId();
          } else if ($k === 'value') {
            $v = is_array($value) ? $value['value']  : $value->getValue();
          } else {
            $v = $value;
          }
          $tables[$unique]['values'][$c] = array(
            'transform' => $transform,
            'literal' => $literal,
            'value' => $v
          );
        }
      }
    }
  }

  public function addDelete($col, $pks=array()) {
    $this->registerModification(new DeleteRecord($this->factory, $this->escape, $this->getCollection($col), $pks));
  }

  public function execute(Progress $progress) {
    $progress->start('modification');
    $len = count($this->modifications);
    $clearCache = false;
    for ($i=0; $i<$len; $i++) {
      $mod = $this->modifications[$i];
      $statement = $mod['statement'];
      $context = $mod['customContext'];
      if (is_null($context)) {
        $context = $this->context;
      }
      $ret = $statement->execute($this->factory, $context);
      if (is_array($ret)) {
        foreach($ret as $table => $info) {
          foreach($info as $operation => $count) {
            if ($count > 0) {
              $clearCache = true;
              if (! isset($this->stats[$table])) {
                $this->stats[$table] = array();
              }
              if (! isset($this->stats[$table][$operation])) {
                $this->stats[$table][$operation] = 0;
              }
              $this->stats[$table][$operation] += $count;
            }
          }
        }
      }
      $progress->setProgress($len, $i);
    }

    foreach ($this->after as $callback) {
      $ret = $callback->execute($this->factory, $this->context);
    }
    if ($clearCache) {
      $this->cacheClearing++;
      if ($this->allowClearCache) {
        $this->factory->clearCache();
      }
    }
    $progress->end();
    return true;
  }

  public function getStats() {
    return $this->stats;
  }

  public function getSQL() {
    $ret = '';
    foreach($this->modifications as $mod) {
      $statement = $mod['statement'];
      $context = $mod['customContext'];
      if (is_null($context)) {
        $context = $this->context;
      }
      $ret .= $statement->getSQL($context)."\n\n";
    }
    foreach ($this->after as $cb) {
      $ret .= $cb->getSQL($this->context)."\n\n";
    }
    return $ret;
  }

  private function getCollection($collection) {
    if (is_string($collection)) {
      return $this->factory->getDictionary()->getCollection($collection);
    }
    return $collection;
  }

  public function addStatement(Statement $statement) {
    $this->registerModification($statement);
  }

  private function registerModification($statement, $customContext=null) {
    array_push($this->modifications, array(
      'statement' => $statement,
      'customContext' => $customContext
    ));
  }

  public function setAllowClearCache($allow) {
    $this->allowClearCache = $allow;
  }

  public function getCacheClearCount() {
    return $this->cacheClearing;
  }

}
