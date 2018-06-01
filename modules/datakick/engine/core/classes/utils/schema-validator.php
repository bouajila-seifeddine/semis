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

class SchemaValidator {
  private $factory;
  private $schema;
  private $errors;
  private $defaultParameters;
  private $links = array();

  public function __construct($factory, $defaultParameters) {
    $this->factory = $factory;
    $schemaCollector = new SchemaCollector();

    $this->collections = array();
    $coreSchemaLoader = new CoreSchemaLoader($schemaCollector, $factory);
    $coreSchemaLoader->load();
    $platformLoader = $factory->getPlatformSchemaLoader($schemaCollector);
    $platformLoader->load();
    $this->schema = $schemaCollector->getSchema();

    $this->errors = array();
    $this->defaultParameters = $defaultParameters;
  }

  public function validate() {
    foreach($this->schema as $key=>$col) {
      $prefix = "Collection '$key'";
      $this->validateCollection($prefix, $key, $col);
      $this->validateTables($prefix, $col);
      $this->validateFields($prefix, $col);
      $this->validateLinks($prefix, $key, $col, $this->schema);
    }
    foreach ($this->links as $source=>$targets) {
      foreach ($targets as $target) {
        $targetLinks = isset($this->links[$target]) ? $this->links[$target] : array();
        if (! in_array($source, $targetLinks)) {
          $this->addError("Links symetry", "link from $target to $source not found", "\n   either create link, or mark link in $source as unidirectional");
        }
      }
    }
  }

  private function validateCollection($prefix, $key, $collection) {
    $this->validateCamelCase($key, "Collection id");
    $this->validateExists($prefix, $collection, 'description');
    $this->validateExists($prefix, $collection, 'singular');
    $this->validateExists($prefix, $collection, 'fields');
    $this->validateExists($prefix, $collection, 'tables');
    $this->validateExists($prefix, $collection, 'key');
    $this->validateExists($prefix, $collection, 'category');
    if ($this->validateExists($prefix, $collection, 'create', false) && $collection['create']) {
      $this->validateCreate($prefix, $collection);
      $this->validateExists($prefix, $collection, 'delete');
    }
    if ($this->validateExists($prefix, $collection, 'delete', false)) {
      $this->validateMigratedToMappings($prefix, $collection);
      if (is_array($collection['delete'])) {
        $del = $collection['delete'];
        $this->validateExists($prefix, $del, 'value');
        if ($this->validateExists($prefix, $del, 'extraTables', false)) {
          if ($this->validateArray($prefix, 'extraTables', $del['extraTables'], false)) {
            $this->validateDelete($prefix, $del['extraTables']);
          }
        }
        if ($this->validateExists($prefix, $del, 'conditions', false)) {
          if ($this->validateArray($prefix, 'conditions', $del['conditions'], false)) {
          }
        }
      }
    }
    if ($this->validateExists($prefix, $collection, 'restrictions', false)) {
      $this->validateRestrictions($prefix, $collection, $collection['fields']);
    }
    if ($this->validateExists($prefix, $collection, 'expressions', false)) {
      $this->validateExpressions($prefix, $collection);
    }

    if ($this->validateExists($prefix, $collection, 'display')) {
      if (! (
        $this->validateExists($prefix, $collection['fields'], $collection['display'], false) ||
        $this->validateExists($prefix, $collection['expressions'], $collection['display'], false)
      )) {
        $this->addError($prefix, "display field not found", $collection['display']);
      }
    }
    if ($this->validateArray($prefix, 'key', $collection['key'], false)) {
      foreach ($collection['key'] as $k) {
        $this->validateExists($prefix, $collection['fields'], $k);
      }
    }
  }

  private function validateMigratedToMappings($prefix, $collection) {
    $tables = $this->factory->getDictionary()->getCollection($collection['id'])->getCoreTables();
    foreach ($collection['fields'] as $id => $fld) {
      if (isset($fld['update']) && $fld['update']) {
        $update = $fld['update'];
        if (is_array($update)) {
          $this->addError($prefix, 'all fields should be migrated to mapping', $id);
        } else {
          foreach ($fld['mapping'] as $key=>$_) {
            $tables[] = $key;
          }
        }
      }
    }
    $tables = array_unique($tables);
    return $tables;
  }

  private function validateCreate($prefix, $collection) {
    if ($collection['id'] === 'customFields') {
      return;
    }
    $tables = $this->validateMigratedToMappings($prefix, $collection);
    if ($tables) {
      foreach ($collection['tables'] as $key=>$def) {
        $tableName = $collection['category'] === 'system' ? $this->factory->getServiceTable($def['table']) : $this->factory->prefixTable($def['table']);
        $p = "$prefix table '$key'";
        if (isset($def['join'])) {
          if (in_array($key, $tables)) {
            if ($this->validateExists($p, $def, 'create')) {
              $hasPk = false;
              $params = array();
              foreach ($def['create'] as $column => $value) {
                if ($value === '<pk>') {
                  $hasPk = true;
                }
                $matches = array();
                $pattern = '/<param:([a-zA-Z0-9-_]+)>/';
                preg_match_all($pattern, $value, $matches, PREG_PATTERN_ORDER);
                $names = $matches[1];
                if ($names) {
                  $params = array_merge($params, $names);
                }
              }
              if (! $hasPk) {
                $this->addError($p, '`create` does not contains <pk>', $def['create']);
              }
              $params = array_unique($params);
              if ($params) {
                if ($this->validateExists($p.': missing parameters ['.implode(', ', $params).']', $def, 'parameters')) {
                  $pars = $def['parameters'];
                  foreach ($params as $par) {
                    if (! in_array($par, $pars) && !in_array($par, array('timestamp', 'shareStock', 'shopGroup'))) {
                      $this->addError($p, 'missing parameter', $par);
                    }
                  }
                }
              }
            }
          }
        }
        // validate default values
        if ($def['table'] != 'endpoint-parameter' && (isset($def['create']) || !isset($def['join']) || (isset($def['primary']) && $def['primary']))) {
          $c = isset($def['create']) ? $def['create'] : array();
          $values = array();
          foreach ($c as $column=>$value) {
            $val = $value;
            if ($value === '<pk>') {
              $val = '9999999';
            }
            $values[$column] = array(
              'value' => $val,
              'literal' => false
            );
          }
          foreach ($collection['fields'] as $alias => $field) {
            if (isset($field['required']) && $field['required'] && isset($field['mapping'][$key])) {
              $column = $field['mapping'][$key];
              if (is_array($column)) {
                $column = $column['field'];
              }
              $type = $field['type'];
              $value = 'value';
              if (isset($field['default'])) {
                $value = $field['default'];
              } else if ($type == 'boolean') {
                $value = 1;
              } else if ($type === 'number') {
                $value = 1;
              } else if ($type === 'datetime') {
                $value = new \DateTime();
              }
              $values[$column] = $value;
            }
          }
          $conn = $this->factory->getConnection();
          $sql = new Insert($conn, $tableName, $def, $values);
          $conn->execute('begin');
          try {
            $sql->execute($this->factory, $this->getContext());
          } catch (\Exception $e) {
            $this->addError($prefix, "create table `{$def['table']}`", preg_replace('/\s+/', " ", str_replace("\n", " ", $e->getMessage())));
          }
          $conn->execute('rollback');
        }
      }
    }
  }

  private function validateDelete($prefix, $deletes) {
    foreach ($deletes as $def) {
      if ($this->validateExists($prefix, $def, 'table')) {
        $t = $this->factory->prefixTable($def['table']);
        $p = $prefix . ": delete $t";
        if ($this->validateExists($p, $def, 'fkeys')) {
          foreach ($def['fkeys'] as $fkey) {
            $this->validateColumnExists($p, $t, $fkey);
          }
        }
      }
    }
  }

  private function validateTables($prefix, $collection) {
    if ($this->validateExists($prefix, $collection, 'tables')) {
      foreach($collection['tables'] as $key=>$table) {
        $this->validateTable("$prefix table '$key'", $table, $this->getTableAliases($collection));
      }
    }
  }

  private function validateTable($prefix, $table, $tables) {
    $this->validateExists($prefix, $table, 'table');
    if ($this->validateExists($prefix, $table, 'require', false)) {
      $this->validateRequire($prefix, $tables, $table['require']);
    }
  }

  private function validateFields($prefix, $collection) {
    if ($this->validateExists($prefix, $collection, 'fields')) {
      $mappings = array();
      $updates = array();
      foreach($collection['fields'] as $key=>$field) {
        if (isset($field['mapping']) && is_array($field['mapping'])) {
          $mappings[] = $key;
        }
        if (isset($field['update']) && is_array($field['update'])) {
          $updates[] = $key;
        }
        $this->validateCamelCase($key, "$prefix field");
        $this->validateField("$prefix field '$key'", $key, $field, $this->getTableAliases($collection), $collection['id']);
      }
      if ($mappings && $updates) {
        $this->addError($prefix, "mixed versions - found both mappings and updates. Please migrate fields",  "[" . implode(', ', $updates) . "]");
      } else {
        if ($mappings) {
          $col = $this->factory->getDictionary()->getCollection($collection['id']);
          $tables = $col->getTables();
          $primary = $col->getPrimaryTable();
          $primaryTable;
          foreach ($tables as $id => $table) {
            if ($id === $primary) {
              $primaryTable = $table['table'];
            }
          }
          foreach ($tables as $id => $table) {
            if ($table['table'] == $primaryTable . '_shop') {
              $this->validateExists("$prefix: table `$id` should be marked as primary", $table, 'primary');
            }
          }
        }
      }
    }
  }

  private function validateField($prefix, $key, $field, $tables, $collection) {
    if ($this->validateExists($prefix, $field, 'type')) {
      if (! $this->isValidType($field['type']))
      $this->addError($prefix, "invalid type", $field['type']);

      if ($field['type'] === 'currency') {
        $this->validateExists($prefix, $field, 'fixedCurrency');
      }
    }
    $this->validateExists($prefix, $field, 'description');
    $isVirtual = $this->validateExists($prefix, $field, 'virtual', false);

    if ($isVirtual) {
      $this->validateExists($prefix, $field, 'set');
    } else {
      if (! $this->validateExists($prefix, $field, 'sql', false) && !$this->validateExists($prefix, $field, 'mapping', false)) {
        $this->addError($prefix, "either `sql` or `mapping` should exists", $key);
      }
      $hasSql = $this->validateExists($prefix, $field, 'sql', false);
      $hasMapping = $this->validateExists($prefix, $field, 'mapping', false);

      if ($hasSql) {
        if ($this->validateExists($prefix, $field, 'require')) {
          $this->validateRequire($prefix, $tables, $field['require']);
        }
      } else {
        if ($this->validateExists($prefix, $field, 'mapping')) {
          $this->validateMapping($prefix, $field['mapping'], $collection);
        }
        if ($this->validateExists($prefix, $field, 'require', false)) {
          $this->addError($prefix, "unnecesseary", "require");
        }
      }

      $this->validateFieldSql($prefix, $collection, $key);

      $keys = $this->schema[$collection]['key'];
      if (count($keys) == 1 && $key == $keys[0]) {
        $this->validateExists($prefix, $field, 'selectRecord');
      }

      if ($this->validateExists($prefix, $field, 'selectRecord', false)) {
        $rec = $field['selectRecord'];
        if (! $this->factory->getDictionary()->hasCollection($rec)) {
          $this->addError($prefix, "selectRecord does not exists", $rec);
        };
      }

      if ($this->validateExists($prefix, $field, 'update')) {
        if ($field['update']) {
          if (! $this->validateExists($prefix, $field, 'mapping', false)) {
            if ($this->validateArray($prefix, 'update', $field['update'])) {
              foreach ($field['update'] as $table => $field) {
                $this->validateUpdateFieldExists($prefix, $collection, $table, $field);
              }
            }
          }
        }
      }
    }
  }

  private function validateMapping($prefix, $mapping, $collection) {
    $col = $this->factory->getDictionary()->getCollection($collection);
    $tables = $col->getTables();
    $prefix = $prefix . ': mapping';

    foreach ($mapping as $tableId => $def) {
      if ($this->validateExists($prefix, $tables, $tableId, "mapping table not found")) {
        $table = $tables[$tableId]['table'];
        $variantTable = Utils::endsWith($table, '_shop') ? substr($table, 0, strlen($table)-5) : $table.'_shop';
        $variant = null;
        foreach ($col->getTables() as $alias=>$t) {
          $table = $t['table'];
          if ($table === $variantTable) {
            $variant = $alias;
          }
        }
        $field;
        if (is_array($def)) {
          if ($this->validateExists($prefix, $def, 'field')) {
            $field = $def['field'];
          };
        } else {
          $field = $def;
        }
        if ($field && $variant && !isset($mapping[$variant])) {
          if (is_array($field)) {
            $field = $field['value'];
          }
          if ($this->columnExists($variantTable, $field)) {
            $this->addError($prefix, "multishop column `$field` exists in `$variantTable` table as well, it should be mapped", $variant);
          }
        }
      }
    }
  }

  private function validateRestrictions($prefix, $collection, $fields) {
    if ($this->validateExists($prefix, $collection, 'restrictions')) {
      foreach($collection['restrictions'] as $key => $restriction) {
        $this->validateRestriction("$prefix restriction '$key'", $key, $restriction, $fields);
      }
    }
  }

  private function validateRestriction($prefix, $type, $restriction, $fields) {
    if (! $this->factory->getUser()->getRestrictions()->validateFields($type, $restriction)) {
      $fields = join(array_keys($restriction), ', ');
      $this->addError($prefix, 'fields does not match', "[$fields]");
    } else {
      foreach ($restriction as $fld=>$sql) {
        foreach ($this->getExpressionFields($sql) as $field) {
          $this->validateExists($prefix." field '$field'", $fields, $field);
        }
      }
    }
  }

  private function validateUpdateFieldExists($prefix, $collection, $table, $field) {
    if (is_array($field)) {
      if ($this->validateExists($prefix.': update definition: field not found', $field, 'field', false)) {
        return $this->validateUpdateField($prefix, $collection, $table, $field['field']);
      }
    }
    $this->validateUpdateField($prefix, $collection, $table, $field);
  }

  private function validateUpdateField($prefix, $collection, $table, $field) {
    $factory = $this->factory;
    $col = $factory->getDictionary()->getCollection($collection);
    if ($this->validateExists($prefix.': update table not found', $col->getTables(), $table)) {
      $def = $col->getTable($table);
      if (isset($def['join']['type']) && $def['join']['type'] === 'LEFT') {
        $this->validateExists($prefix.': update table '.$table, $def, 'create');
      }
      $conn = $factory->getConnection();
      $t = $def['table'];
      if (! is_array($field))
      $field = array($field);

      foreach ($field as $f) {
        $sql = "SELECT `$f` FROM $t";
        try {
          $conn->query($sql);
        } catch (\Exception $e) {
          $this->addError($prefix, "invalid update sql", preg_replace('/\s+/', " ", str_replace("\n", " ", $e->getMessage())));
        }
      }
    }
  }

  private function validateFieldSql($prefix, $collection, $field) {
    try {
      $query = $this->factory->getQuery();
      $query->exposeCollection($collection, 'collection');
      $query->exposeField('collection', $field);
      $query->execute($this->factory, $this->getContext());
    } catch (\Exception $e) {
      $this->addError($prefix, "invalid sql", $e->getMessage());
    }
  }

  private function validateExpressions($prefix, $collection) {
    if ($this->validateExists($prefix, $collection, 'expressions')) {
      foreach($collection['expressions'] as $key=>$expr) {
        $this->validateCamelCase($key, "$prefix expression");
        $this->validateExpression("$prefix expression '$key'", $expr, $collection['fields']);
      }
    }
  }

  private function validateExpression($prefix, $expr, $fields) {
    if ($this->validateExists($prefix, $expr, 'type')) {
      if (! $this->isValidType($expr['type']))
      $this->addError($prefix, "invalid type", $expr['type']);
    }
    $this->validateExists($prefix, $expr, 'expression');
    $this->validateExists($prefix, $expr, 'description');
    foreach ($this->getExpressionFields($expr['expression']) as $field) {
      $this->validateExists($prefix, $fields, $field);
    }
  }

  private function getExpressionFields($expr) {
    $matches = array();
    $pattern = '/<field:([a-zA-Z0-9-_]+)>/';
    preg_match_all($pattern, $expr, $matches, PREG_PATTERN_ORDER);
    return $matches[1];
  }

  private function validateLinks($prefix, $alias, $collection, $collections) {
    if ($this->validateExists($prefix, $collection, 'links', false)) {
      $l = array();
      $canDelete = isset($collection['delete']) && $collection['delete'];
      foreach($collection['links'] as $key=>$link) {
        $this->validateCamelCase($key, "$prefix link");
        $this->validateLink("$prefix link '$key'", $link, $alias, $collections);
        $unidirectional = isset($link['unidirectional']) ? $link['unidirectional'] : false;
        if (! $unidirectional) {
          array_push($l, $link['collection']);
        }
        if ($link['type'] === 'BELONGS_TO' || $link['type'] === 'HAS_ONE' && count($link['sourceFields']) == 1) {
          $sourceField = $link['sourceFields'][0];
          if (count($collection['key']) != 1 || $collection['key'][0] != $sourceField) {
            $col = $link['collection'];
            $field = $collection['fields'][$sourceField];
            if (! isset($field['selectRecord'])) {
              $this->addError($prefix . ": $sourceField", "missing selectRecord entry", "'selectRecord' => '$col'");
            }
          }
        }
        if (isset($link['generateReverse'])) {
          $this->links[$link['collection']][] = $collection['id'];
        }
        if ($canDelete && ($link['type'] === 'HAS_MANY' || $link['type'] === 'HABTM')) {
          if ($this->validateExists("$prefix link '$key'", $link, 'delete')) {
            $delete = $link['delete'];
            if (! ($delete === false || $delete === true || $delete === 'dissoc')) {
              $this->addError("$prefix link '$key'", "invalid 'delete' value", $delete);
            }
          }
        }
      }
      $ix = $collection['id'];
      if (isset($this->links[$ix])) {
        $this->links[$ix] = array_unique(array_merge($this->links[$ix], $l));
      } else {
        $this->links[$ix] = array_unique($l);
      }
    }
  }

  private function validateLink($prefix, $link, $collection, $collections) {
    $this->validateExists($prefix, $link, 'description');
    $this->validateEnum($prefix, $link, 'type', array('HAS_MANY', 'HAS_ONE', 'BELONGS_TO', 'HABTM'));
    if ($this->validateExists($prefix, $link, 'collection')) {
      if (! $this->validateExists($prefix, $collections, $link['collection'], false)) {
        $this->addError($prefix, "target collection not found", $link['collection']);
      }
    }
    if ($this->validateExists($prefix, $link, 'joins', false)) {
      $this->validateArray($prefix, 'joins', $link, false);
    } else {
      if ($this->validateExists($prefix, $link, 'sourceFields')) {
        $this->validateFieldsExists("$prefix sourceFields", $collection, $link['sourceFields']);
      }
      if ($this->validateExists($prefix, $link, 'targetFields')) {
        $this->validateFieldsExists("$prefix targetFields", $link['collection'], $link['targetFields']);
      }
      if ($this->validateExists($prefix, $link, 'sourceFields', false) && $this->validateExists($prefix, $link, 'targetFields', false)) {
        $type = $link['type'];
        if ($type == 'HABTM') {
          $this->validateExists($prefix, $link, 'joinFields');
          $sourceFields = count($link['sourceFields']);
          $joinSourceFields = count($link['joinFields']['sourceFields']);
          if ($sourceFields != $joinSourceFields)
          $this->addError($prefix, "sourceFields.length != joinFields.sourceFields.length", "$sourceFields != $joinSourceFields");
        } else {
          $sourceFields = count($link['sourceFields']);
          $targetFields = count($link['targetFields']);
          if ($sourceFields != $targetFields)
          $this->addError($prefix, "sourceFields.length != targetFields.length", "$sourceFields != $targetFields");
        }
      }
    }
  }

  private function validateArray($prefix, $name, $arr, $allowEmpty=true, $throw=true) {
    $is = is_array($arr);
    if ($throw && !$is) {
      $this->addError($prefix, $name . " is not an array", $arr);
    }
    if (! $allowEmpty && count($arr) === 0) {
      $is = false;
      if ($throw) {
        $this->addError($prefix, $name . " is empty array", $arr);
      }
    }
    return $is;
  }

  private function validateRequire($prefix, $tables, $req) {
    if ($this->validateArray($prefix, 'require', $req)) {
      foreach($req as $table) {
        if (! in_array($table, $tables)) {
          $this->addError($prefix, "required table not found", $table);
        }
      }
    }
  }

  private function validateFieldsExists($prefix, $collection, $fields) {
    $this->validateArray($fields, 'fields', $fields);
    $defs = $this->schema[$collection]['fields'];
    foreach($fields as $fld) {
      $this->validateExists($prefix, $defs, $fld);
    }
  }

  private function validateExists($prefix, $data, $field, $throw=true) {
    if ($data && isset($data[$field])) {
      return true;
    }
    if ($throw) {
      $this->addError($prefix, "Missing field", $field);
    }
    return false;
  }

  private function validateEnum($prefix, $data, $field, $enum) {
    if ($this->validateExists($prefix, $data, $field)) {
      $value = $data[$field];
      if (in_array($value, $enum))
      return true;
      $this->addError($prefix, "Invalid enum value of field '$field'", $value);
    }
    return false;
  }

  private function validateCamelCase($name, $errorPrefix) {
    if (! preg_match("/^[a-z][a-zA-Z0-9]*$/", $name)) {
      $this->addError($errorPrefix, "camelCase indentifier required", $name);
    }
  }

  private function isValidType($type) {
    return Types::isKnownType($type);
  }

  private function getTableAliases($collection) {
    if (isset($collection['tables'])) {
      return array_keys($collection['tables']);
    }
    return array();
  }

  private function addError($prefix, $reason, $value) {
    if (is_array($value)) {
      $value = print_r($value, true);
    }
    print "$prefix: $reason: $value\n";
  }

  private function getContext() {
    if (! isset($this->param)) {
      $this->param = $this->factory->getContext();
      $this->param->setValues($this->defaultParameters);
    }
    return $this->param;
  }

  private function validateColumnExists($prefix, $table, $field) {
    if (! $this->columnExists($table, $field)) {
      $this->addError($prefix, "column not found in `$table`", $field);
    }
  }

  private function columnExists($table, $column) {
    $q = "SELECT * FROM information_schema.COLUMNS WHERE table_schema = database() AND table_name ='$table' AND column_name = '$column'";
    $res = $this->factory->getConnection()->query($q);
    if ($res && $res->fetch()) {
      return true;
    }
    return false;
  }
}
