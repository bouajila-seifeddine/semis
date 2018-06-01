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

class Field {
  private $id;
  private $type;
  private $description;
  private $selectRecord;
  private $virtual = false;
  private $hidden = false;
  private $sql;
  private $editable = false;
  private $update;
  private $mapping;
  private $customFieldId;
  private $enum;
  private $values;
  private $fieldset;
  private $requiredTables;
  private $required = false;
  private $parameters = array();
  private $fixedCurrency;
  private $callbacks = array();

  public function __construct($id, $definition, Collection $collection) {
    $this->id = $id;
    if (! isset($definition['type'])) {
      throw new UserError('Field definition: missing description: ' . print_r($definition, true));
    }
    $this->description = $definition['description'];

    if (! isset($definition['type'])) {
      throw new UserError('Field definition: missing type: ' . print_r($definition, true));
    }
    if (! Types::isKnownType($definition['type'])) {
      throw new UserError("Field definition: unknown type: $type");
    }
    $this->type = $definition['type'];

    if (isset($definition['selectRecord'])) {
      $this->setSelectRecord($definition['selectRecord']);
    }

    if (isset($definition['hidden'])) {
      $this->setHidden($definition['hidden']);
    }

    if (isset($definition['required'])) {
      $this->required = $definition['required'];
    }

    if (isset($definition['fixedCurrency'])) {
      $this->fixedCurrency = $definition['fixedCurrency'];
    }

    if (isset($definition['virtual'])) {
      $this->virtual = true;
      $this->callbacks['set'] = $definition['set'];
      if (isset($definition['sql'])) {
        $this->sql = $definition['sql'];
        $this->requiredTables = $definition['require'];
      } else {
        $this->requiredTables = array();
        $this->sql = "NULL";
      }
    } else {
      if (isset($definition['sql'])) {
        $this->sql = $definition['sql'];
        if (isset($definition['require'])) {
          $this->requiredTables = $definition['require'];
        } else {
          throw new UserError("Field definition: `require` is required for `sql` entries: ". print_r($definition, true));
        }
      } else {
        if (isset($definition['mapping'])) {
          $mapping = $definition['mapping'];
          $keys = array_keys($mapping);
          $sql;
          $tables = $collection->getCoreTables();
          if (isset($definition['sqlStrategy'])) {
            $strategy = $definition['sqlStrategy'];
            $fields = array();
            foreach ($keys as $key) {
              $fields[] = $this->deriveFieldSql($mapping, $key);
            }
            $sql = $strategy . '(' . implode(', ', $fields) . ')';
            $tables = array_unique(array_merge($tables, array_keys($mapping)));
          } else {
            if (count($keys)) {
              $table = $keys[0];
              $sql = $this->deriveFieldSql($mapping, $table);
              $mappingDef = $mapping[$table];
              if (isset($mappingDef['require'])) {
                $tables = array_unique(array_merge($tables, $mappingDef['require']));
              } else {
                if (! in_array($table, $tables)) {
                  $tables[] = $table;
                }
              }
            } else {
              $sql = "''";
            }
          }
          $this->sql = $sql;
          $this->requiredTables = $tables;
        } else {
          throw new UserError("Field definition: either `sql` or `mapping` has to be set: ".print_r($definition, true));
        }
      }
    }

    if (isset($definition['mapping'])) {
      $this->mapping = $definition['mapping'];
      $this->update = $this->mapping;
      if ($this->fixedCurrency) {
        $this->removeCurrency($this->update);
      }
    } else {
      if (isset($definition['update']) && is_array($definition['update'])) {
        $this->mapping = $definition['update'];
        $this->update = $this->mapping;
      } else {
        $this->mapping = array();
        $this->update = array();
      }
    }

    if (isset($definition['update']) && !!$definition['update']) {
      $this->editable = true;
    }

    if ($this->isVirtual() && $this->getSetFunction()) {
      $this->editable = true;
    }

    if (isset($definition['afterUpdate'])) {
      $this->callbacks['afterUpdate'] = $definition['afterUpdate'];
    }

    if (isset($definition['customFieldId'])) {
      $this->customFieldId = $definition['customFieldId'];
    }

    if (isset($definition['fieldset'])) {
      $this->fieldset = $definition['fieldset'];
    }

    if (isset($definition['values'])) {
      $this->values = $definition['values'];
    }

    if (isset($definition['enum'])) {
      $this->enum = $definition['enum'];
    }

    if (! is_array($this->requiredTables)) {
      throw new UserError("Field definitino invariant: required is not an array".print_r($definition, true));
    }
    foreach ($this->requiredTables as $tableAlias) {
      if ($tableAlias != 'habtm') {
        $table = $collection->getTable($tableAlias);
        if (isset($table['parameters'])) {
          $this->parameters = array_merge($this->parameters, $table['parameters']);
        }
      }
    }
    $this->parameters = array_unique($this->parameters);
  }

  private function removeCurrency(&$updates) {
    foreach ($updates as $table => &$field) {
      if (is_array($field) && isset($field['field'])) {
        unset($field['field']['currency']);
      }
    }
  }

  private function deriveFieldSql($mapping, $table) {
    $field = $mapping[$table];
    if (is_array($field) && isset($field['field'])) {
      $fld = $field['field'];
      $sql;
      if (is_array($fld)) {
        $sql = array();
        foreach ($fld as $key => $value) {
          $sql[$key] = $table . "." . $value;
          if ($key === 'currency' && $this->fixedCurrency) {
            $sql[$key] = $value;
          }
        }
      } else {
        $sql = $table . "." .$field['field'];
        if (isset($field['read'])) {
          $read = $field['read'];
          $sql = str_replace("<field>", $sql, $read);
        }
      }
      return $sql;
    }
    return $table . "." . $field;
  }

  public function hasAfterUpdateCallback() {
    return isset($this->callbacks['afterUpdate']);
  }

  public function getAfterUpdateCallback() {
    return $this->callbacks['afterUpdate'];
  }

  public function getSetFunction() {
    return isset($this->callbacks['set']) ? $this->callbacks['set'] : null;
  }

  public function isRequired() {
    return $this->required;
  }

  public function isEditable() {
    return $this->editable;
  }

  public function getMapping() {
    return $this->mapping;
  }

  public function getMappingField($table) {
    if (isset($this->mapping[$table])) {
      return $this->mapping[$table];
    }
    return null;
  }

  public function getUpdate() {
    return $this->update;
  }

  public function getType() {
    return $this->type;
  }

  public function getName() {
    return $this->description;
  }

  public function hasSelectRecord() {
    return !!$this->selectRecord;
  }

  public function getSelectRecord() {
    return $this->selectRecord;
  }

  public function setSelectRecord($selectRecord) {
    $this->selectRecord = $selectRecord;
  }

  public function setFixedCurrency($fixed) {
    $this->fixedCurrency = $fixed;
  }

  public function setHidden($hidden) {
    $this->hidden = !!$hidden;
  }

  public function isCalculated() {
    return is_null($this->mapping);
  }

  public function isVirtual() {
    return $this->virtual;
  }

  public function getSql() {
    return $this->sql;
  }

  public function getRequiredTables() {
    return $this->requiredTables;
  }

  public function getParameters() {
    return $this->parameters;
  }

  public function getId() {
    return $this->id;
  }

  public function isHidden() {
    return $this->hidden;
  }

  public function isCustomField() {
    return !is_null($this->customFieldId);
  }

  public function getValues() {
    return $this->values;
  }

  public function toJS() {
    $ret = array(
      'type' => $this->type,
      'description' => $this->description
    );

    if ($this->hidden || $this->isVirtual()) {
      $ret['hidden'] = true;
    }

    if ($this->isVirtual()) {
      $ret['virtual'] = true;
    }

    if ($this->selectRecord) {
      $ret['selectRecord'] = $this->selectRecord;
    }

    if ($this->isEditable()) {
      $ret['editable'] = true;
    }

    if ($this->isRequired()) {
      $ret['isRequired'] = true;
    }

    if ($this->fixedCurrency) {
      $ret['fixedCurrency'] = $this->fixedCurrency;
    }

    if ($this->customFieldId) {
      $ret['customFieldId'] = $this->customFieldId;
    }

    if ($this->fieldset) {
      $ret['fieldset'] = $this->fieldset;
    }

    if ($this->values) {
      $ret['values'] = $this->values;
    }

    if ($this->enum) {
      $ret['isEnum'] = $this->enum;
    }

    return $ret;
  }
}
