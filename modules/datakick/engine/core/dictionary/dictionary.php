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

class Dictionary {
  private $factory;
  private $collections;

  private $usageCollections = array();
  private $collectionRole = array();

  public function __construct($factory) {
    $this->factory = $factory;
    $this->collections = array();

    // load system collections
    $coreSchemaLoader = new CoreSchemaLoader($this, $factory);
    $coreSchemaLoader->load();
    $this->registerUsageCollection();

    // load platform collections
    $platformLoader = $factory->getPlatformSchemaLoader($this);
    $platformLoader->load();

    // apply permissions and restrictions
    $this->removeCollections();
    $this->removeLinks();
    $this->fixFields();

    // enhance by custom fields
    $this->enhanceCollections($this->factory->getCustomization());
  }

  private function registerUsageCollection() {
    $tables = array();
    $first = true;
    foreach ($this->usageCollections as $id) {
      $col = $this->getCollection($id);
      $table = $this->factory->getServiceTable($col->getUsageTable());
      $key = $col->getId();
      $name = $col->getName();
      if ($first) {
        $first = false;
        $tables[] = "  SELECT '$key' as `key`, '$name' as `name`, count(1) as `used` FROM $table";
      } else {
        $tables[] = "  SELECT '$key', '$name', count(1) FROM $table";
      }
    }
    $usage = "(\n" . implode($tables, "\n  UNION\n") . "\n)";
    $this->addCollection(array(
      'id' => 'usage',
      'singular' => 'usage',
      'description' => 'Datakick usage',
      'key' => array('key'),
      'category' => 'system',
      'display' => 'name',
      'permissions' => array(
        'view' => true,
        'edit' => false,
        'create' => false,
        'delete' => false
      ),
      'tables' => array(
        'usage' => array(
          'table' => $usage
        )
      ),
      'fields' => array(
        'key' => array(
          'type' => 'string',
          'description' => 'usage key',
          'sql' => 'usage.key',
          'require' => array('usage'),
          'selectRecord' => 'usage',
          'update' => false
        ),
        'used' => array(
          'type' => 'number',
          'description' => 'used',
          'sql' => 'usage.used',
          'require' => array('usage'),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'usage.name',
          'require' => array('usage'),
          'update' => false
        ),
      ),
      'links' => array()
    ));
  }

  private function fixFields() {
    foreach ($this->collections as $key => $collection) {
      foreach ($collection->getFields() as $field) {
        // fix selectRecordFields
        if ($field->hasSelectRecord()) {
          $selectRecord = $field->getSelectRecord();
          if (is_array($selectRecord)) {
            $role = $selectRecord['role'];
            if (isset($this->collectionRole[$role])) {
              $selectRecord = $this->collectionRole[$role];
            } else {
              $selectRecord = null;
            }
          }
          if ($selectRecord && $this->hasCollection($selectRecord)) {
            $field->setSelectRecord($selectRecord);
          } else {
            $field->setSelectRecord(null);
            $field->setHidden(true);
          }
        }
      }
    }
  }

  // remove collections that current user doesn't have access to
  private function removeCollections() {
    $perm = $this->factory->getUser()->getPermissions();
    $rest = $this->factory->getUser()->getRestrictions();
    foreach ($this->collections as $key => $collection) {
      if ($perm->canView($collection)) {
        $collection->setCanCreate($perm->canCreate($collection));
        $collection->setCanDelete($perm->canDelete($collection));
        $collection->setCanEdit($perm->canEdit($collection));
        // add restrictions
        $canWrite = $perm->canEdit($collection) ? "1" : "0";
        $require = array();
        if ($collection->hasRestrictions()) {
          $writeCond = array();
          foreach ($collection->getRestrictions() as $key => $def) {
            $collection->addCondition($rest->getCondition($key, $def));
            $writeCond[] = $rest->getWriteCondition($key, $def);
          }
          if ($canWrite == "1") {
            list ($ret, $require) = Query::parametrizeFields($writeCond, $collection);
            $joined = join($ret, " AND ");
            $canWrite = $joined ? "$joined" : "0";
          }
        }
        if (count($require) == 0) {
          foreach ($collection->getTables() as $tableAlias => $table) {
            if (! isset($table['join'])) {
              $require[] = $tableAlias;
            }
          }
        }
        $collection->addField('canWrite', array(
          'type' => 'boolean',
          'description' => "write permission",
          'sql' => $canWrite,
          'require' => $require,
          'hidden' => true,
          'update' => false
        ));
      } else {
        unset($this->collections[$key]);
      }
    }
  }

  // remove links
  private function removeLinks() {
    $perm = $this->factory->getUser()->getPermissions();
    foreach ($this->collections as $collection) {
      foreach ($collection->getLinks() as $key => $link) {
        $col = $link->getTargetId();
        if (!$col || !$this->hasCollection($col) || !$perm->canView($this->getCollection($col))) {
          $collection->removeLink($key);
        } else {
          if ($link->shouldGenerateReverse()) {
            $target = $link->getTarget();
            $reverse = $link->getReverseDefinition();
            if (is_array($reverse)) {
              $id = $reverse['id'];
              $desc = $reverse['description'];
              $type = $reverse['type'];
              if (! $target->hasLink($id)) {
                $target->addLink($id, array(
                  'type' => $type,
                  'description' => $desc,
                  'collection' => $collection->getId(),
                  'joins' => $link->getReverseJoins(),
                ));
              }
            }
          }
        }
      }
    }
  }

  public function addCollection($collection) {
    if (! isset($collection['id'])) {
      throw new UserError('Collection does not contain id');
    }

    $id = $collection['id'];
    if (isset($collection['role'])) {
      $this->collectionRole[$collection['role']] = $id;
    }

    if (isset($collection['usage'])) {
      $this->usageCollections[] = $id;
    }

    if ($this->hasCollection($id)) {
      $col = $this->getCollection($id);
      foreach ($collection as $type => $extension) {
        if (is_array($extension) && in_array($type, array('links', 'fields', 'tables', 'expressions', 'parameters'))) {
          foreach ($extension as $key => $value) {
            $col->extend($type, $key, $value);
          }
        }
      }
    } else {
      $this->collections[$id] = new Collection($this, $collection, $this->factory->getPlatformCollectionFields());
    }
  }

  public function toJS() {
    return array_map(array($this, 'mapCollection'), $this->collections);
  }

  private function mapCollection(Collection $def) {
    $col = array(
      'id' => $def->getId(),
      'singular' => $def->getSingularId(),
      'description' => $def->getName(),
      'fields' => array_map(array($this, 'mapJs'), $def->getFields()),
      'expressions' => $def->getExpressions(),
      'links' => array_map(array($this, 'mapJs'), $def->getLinks()),
      'keys' => $def->getKeys(),
      'parameters' => $def->getParameters(),
      'display' => $def->getDisplayField(),
      'category' => $def->getCategory(),
      'permissions' => array(
        'create' => $def->canCreate(),
        'delete' => $def->canDelete(),
        'edit' => $def->canEdit()
      )
    );

    if ($def->getPriority()) {
      $col['priority'] = $def->getPriority();
    }

    if ($def->hasListDefinition()) {
      $col['list'] = $def->getListDefinition();
    }

    if ($def->hasRole()) {
      $col['role'] = $def->getRole();
    }

    return $col;
  }

  private function mapJs($field) {
    return $field->toJS();
  }

  public function getCollections() {
    return $this->collections;
  }

  public function getParameters() {
    return $this->parameters;
  }

  public function getParameterTypes() {
    return array_map(function($param) {
      return $param['type'];
    }, $this->getParameters());
  }

  public function hasCollection($collection) {
    return isset($this->collections[$collection]);
  }

  public function getCollection($collection) {
    if (! $this->hasCollection($collection))
      throw new UserError("Collection $collection not found");
    return $this->collections[$collection];
  }

  public function getDisplayField($collection) {
    return $this->getCollection($collection)->getDisplayField();
  }

  public function getLink($colName, $using) {
    return $this->getCollection($colName)->getLink($using);
  }

  public function hasField($collection, $fld) {
    return $this->getCollection($collection)->hasField($fld);
  }

  public function getField($collection, $fld) {
    return $this->getCollection($collection)->getField($fld);
  }

  public function getFieldType($collection, $fld) {
    return $this->getField($collection, $fld)->getType();
  }

  public function getTable($colName, $alias) {
    return $this->getCollection($colName)->getTable($alias);
  }

  private function getCustomFields($collection, $customization) {
    return array_map(function($def) {
      $tableAlias = $def['table'];
      $columnName = $def['column_name'];
      $type = $def['type'];
      $sql = "$tableAlias.$columnName";
      $default = null;
      if (Types::isBoolean($type)) {
        $default = 0;
      }
      if (! is_null($default)) {
        $sql = "IFNULL($sql, $default)";
      }
      if (Types::isCurrency($type)) {
        $subtype = (int)$def['subtype'];
        if ($subtype > 0) {
          $sql = array(
            'value' => "$tableAlias.${columnName}_value",
            'currency' => "$subtype"
          );
          $columnName = array(
            'value' => "${columnName}_value"
          );
        } else {
          $sql = array(
            'value' => "$tableAlias.${columnName}_value",
            'currency' => "$tableAlias.${columnName}_currency"
          );
          $columnName = array(
            'value' => "${columnName}_value",
            'currency' => "${columnName}_currency"
          );
        }
      }
      $ret =  array(
        'type' => $def['type'],
        'customFieldId' => (int)$def['id'],
        'fieldset' => $def['fieldset'],
        'description' => $def['name'],
        'sql' => $sql,
        'require' => array($tableAlias),
        'update' => true,
        'mapping' => array(
          $tableAlias => $columnName
        )
      );

      if (Types::isCurrency($type)) {
        $ret['fixedCurrency'] = ! is_null($def['subtype']);
      }
      return $ret;
    }, $customization->getCustomFields($collection));
  }

  private function getCustomTables($tables, $col) {
    $require = array();
    $sqls = array();
    $parameters = array();
    foreach ($col->getKeyFields() as $fld) {
      $mapping = $fld->getMapping();
      if ($mapping) {
        $table = array_keys($mapping)[0];
        $tableDef = $col->getTable($table);
        $field = $mapping[$table];
        if (isset($tableDef['parameters'])) {
          $parameters = array_unique(array_merge($parameters, $tableDef['parameters']));
        }
        $require[] = $table;
        $sqls[] = "$table.$field";
      } else {
        $require = array_unique(array_merge($require, $fld->getRequiredTables()));
        $sqls[] = $fld->getSql();
      }
    }
    $ret = array();
    foreach ($tables as $alias => $def) {
      $tableName = $def['table'];
      $conditions = array();
      $create = array();
      for ($i=0; $i < count($sqls); $i++) {
        $sourceSql = $sqls[$i];
        $targetField = "key_" . ($i + 1);
        $create[$targetField] = "<pk>";
        $targetSql = "$alias.$targetField";
        array_push($conditions, "$sourceSql = $targetSql");
      }
      $ret[$alias] = array(
        'table' => $tableName,
        'require' => $require,
        'create' => $create,
        'join' => array(
          'type' => 'LEFT',
          'conditions' => $conditions,
        )
      );
      if ($parameters) {
        $ret[$alias]['parameters'] = $parameters;
      }
    };
    return $ret;
  }

  public function registerSystemCollection($collection) {
    if ($collection && $collection['category'] == 'system') {
      foreach ($collection['tables'] as &$table) {
        $table['table'] = $this->factory->getServiceTable($table['table']);
      }
      $this->addCollection($collection);
    }
  }

  public function registerCollection($collection) {
    if ($collection && is_array($collection)) {
      if (isset($collection['tables'])) {
        foreach ($collection['tables'] as &$table) {
          $prefix = isset($table['prefix']) ? $table['prefix']: true;
          if ($prefix && !is_array($table['table'])) {
            $table['table'] = $this->factory->prefixTable($table['table']);
          }
        }
      }
      if (isset($collection['delete']['extraTables'])) {
        foreach ($collection['delete']['extraTables'] as $key => &$def) {
          $def['table'] = $this->factory->prefixTable($def['table']);
        }
      }
      if (isset($collection['links'])) {
        foreach ($collection['links'] as &$link) {
          if (isset($link['joinTable']) && !is_array($link['joinTable'])) {
            $link['joinTable'] = $this->factory->prefixTable($link['joinTable']);
          }
        }
      }
      $this->addCollection($collection);
    }
  }

  private function enhanceCollections($customization) {
    $this->collections = array_map(function($def) use ($customization) {
      $id = $def->getId();
      $tables = $customization->getCustomTables($id);
      if (count($tables) > 0) {
        $customTables = $this->getCustomTables($tables, $def);
        $def->addTables($customTables);
        $def->addFields($this->getCustomFields($id, $customization));
        foreach ($customTables as $table) {
          $def->addDeleteTable($table['table'], array_keys($table['create']));
        }
      }
      return $def;
    }, $this->collections);
  }

  public function getCollectionWithRole($role) {
    $id = $this->getCollectionIdWithRole($role);
    if ($id) {
      return $this->getCollection($id);
    }
    throw new UserError("Collection with role $role not found");
  }

  public function getCollectionIdWithRole($role) {
    if (isset($this->collectionRole[$role])) {
      return $this->collectionRole[$role];
    }
    return null;
  }

}
