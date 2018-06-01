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

class Customization {
  private $factory;
  private $customFields;
  private $customTables;

  public function __construct($factory) {
    $this->factory = $factory;
  }

  public function getCustomFields($collection) {
    $fields = $this->ensureLoaded();
    if (isset($fields[$collection])) {
      return $fields[$collection];
    }
    return array();
  }

  public function getCustomTables($collection) {
    $this->ensureLoaded();
    if (isset($this->customTables[$collection])) {
      return $this->customTables[$collection];
    }
    return array();
  }

  public function createCustomField($collection, $alias, $type, $subtype, $name, $fieldset) {
    $factory = $this->factory;
    $factory->getUser()->getPermissions()->checkCreate('customFields');
    $dict = $factory->getDictionary();
    if ($dict->hasField($collection, $alias))
      throw new UserError("Field already exists: $collection.$alias");

    if (! $this->hasCustomTable($collection)) {
      $this->createCustomTable($collection);
    }
    $table = $this->getCustomTable($collection);
    $conn = $factory->getConnection();

    // create column
    $columName = self::getColumnName($alias, $type);
    $conn->addColumn($table['table'], $columName, $type, $subtype);

    // register column
    $fields = $factory->getServiceTable('custom-field');
    $this->customFields = null;
    return $conn->insert($fields, array(
      'custom_table_id' => $table['id'],
      'alias' => $alias,
      'type' => $type,
      'subtype' => $subtype,
      'fieldset' => $fieldset,
      'name' => $name,
      'position' => 0,
      'column_name' => $columName
    ));
  }

  public function deleteCustomField($id) {
    $id = (int)$id;
    $factory = $this->factory;
    $factory->getUser()->getPermissions()->checkDelete('customFields');
    $current = $this->getCustomFieldById($id);
    $table = $this->customTables[$current['collection']][$current['table']];
    $conn = $factory->getConnection();
    $conn->dropColumn($table['table'], $current['column_name'], $current['type'], $current['subtype']);
    $record = $factory->getRecord('customFields');
    return $record->delete($id);
  }

  public function updateCustomField($id, $collection, $alias, $type, $subtype, $name, $fieldset) {
    $id = (int)$id;
    $factory = $this->factory;
    $factory->getUser()->getPermissions()->checkEdit('customFields');
    $current = $this->getCustomFieldById($id);
    $columnName = $current['column_name'];
    $table = $this->customTables[$current['collection']][$current['table']];
    $conn = $factory->getConnection();
    if ($current['type'] != $type || $current['subtype'] != $subtype) {
      // delete column
      $conn->dropColumn($table['table'], $current['column_name'], $current['type'], $current['subtype']);

      // create column
      $table = $this->getCustomTable($collection);
      $columnName = self::getColumnName($alias, $type);
      $conn->addColumn($table['table'], $columnName, $type, $subtype);
    }

    // update custom field definition
    $fields = $factory->getServiceTable('custom-field');
    if ($conn->update($fields, array(
      'custom_table_id' => $table['id'],
      'alias' => $alias,
      'type' => $type,
      'subtype' => $subtype,
      'fieldset' => $fieldset,
      'name' => $name,
      'column_name' => $columnName
    ), array(
      'id' => $id
    )) != false) {
      return $id;
    }
  }

  public function removeAllCustomization() {
    $this->ensureLoaded();
    $factory = $this->factory;
    $conn = $factory->getConnection();
    foreach ($this->customTables as $collection => $tables) {
      foreach ($tables as $alias => $table) {
        $tableName = $table['table'];
        $conn->dropTable($tableName);
      }
    }
    $tables = $factory->getServiceTable('custom-table');
    $fields = $factory->getServiceTable('custom-field');
    $conn->delete($tables);
    $conn->delete($fields);
  }

  private function hasCustomTable($collection) {
    return count($this->getCustomTables($collection)) > 0;
  }

  private function getCustomTable($collection) {
    $tables = $this->getCustomTables($collection);
    if (count($tables) == 0)
      throw new UserError('Could not resolve custom table');
    return reset($tables);
  }

  private function getCustomFieldById($id) {
    $this->ensureLoaded();

    foreach($this->customFields as $fields) {
      foreach($fields as $field) {
        if ($field['id'] == $id)
          return $field;
      }
    }
    throw new UserError("Custom field $id not found");
  }

  private function createCustomTable($collection) {
    $factory = $this->factory;
    $col = $factory->getDictionary()->getCollection($collection);
    $conn = $factory->getConnection();

    // new table name
    $tableName = $factory->getCustomTable($col->getId());
    $tableName = Utils::decamelize($tableName);

    // create table
    $keys = array();
    $i = 1;
    foreach ($col->getKeyFields() as $def) {
      $fieldName = 'key_'.$i;
      $type = Types::isNumber($def->getType()) ? 'int(11)' : 'varchar(255)';
      $keys[$fieldName] = "$type NOT NULL";
      $i++;
    }
    $conn->createTable($tableName, $keys, array_keys($keys));

    // register table
    $table = $factory->getServiceTable('custom-table');
    $this->customFields = null;
    return $conn->insert($table, array(
      'collection' => $collection,
      'table_name' => $tableName
    ));
  }

  private function ensureLoaded() {
    try {
      if (is_null($this->customFields)) {
        $fields = array();
        $tables = array();
        $conn = $this->factory->getConnection();
        $table = $this->factory->getServiceTable('custom-table');
        $field = $this->factory->getServiceTable('custom-field');
        $sql = "SELECT t.id as custom_table_id, t.collection, t.table_name, f.id, f.alias, f.fieldset, f.name, f.description, f.type, f.position, f.column_name, f.subtype";
        $sql .= "\n  FROM $table t";
        $sql .= "\n  LEFT JOIN $field f ON (t.id = f.custom_table_id)";
        $sql .= "\n  ORDER BY t.collection, t.table_name";
        $res = $conn->query($sql);
        while ($row = $res->fetch()) {
          $collection = $row['collection'];
          $tableId = "custom_" . $row['custom_table_id'];
          $alias = $row['alias'];

          if (! isset($fields[$collection])) {
            $fields[$collection] = array();
          }

          if (! isset($tables[$collection])) {
            $tables[$collection] = array();
          }

          $tables[$collection][$tableId] = array(
            'id' => $row['custom_table_id'],
            'alias' => $tableId,
            'table' => $row['table_name']
          );

          if ($alias) {
            $fields[$collection][$alias] = array(
              'id' => $row['id'],
              'table' => $tableId,
              'collection' => $collection,
              'alias' => $alias,
              'fieldset' => $row['fieldset'],
              'name' => $row['name'],
              'description' => $row['description'],
              'type' => $row['type'],
              'subtype' => $row['subtype'],
              'position' => $row['position'],
              'column_name' => $row['column_name']
            );
          }
        }
        $this->customFields = $fields;
        $this->customTables = $tables;
      }
    } catch (\Exception $e) {}
    return $this->customFields;
  }

  public static function getColumnName($alias, $type) {
    $prefix = self::getTypePrefix($type);
    return $prefix . '_'. Utils::decamelize($alias);
  }

  private static function getTypePrefix($type) {
    if (Types::isString($type))
      return 'str';
    if (Types::isNumber($type))
      return 'num';
    if (Types::isCurrency($type))
      return 'curr';
    if (Types::isDateTime($type))
      return 'date';
    if (Types::isBoolean($type))
      return 'bool';
    return 'col';
  }
}
