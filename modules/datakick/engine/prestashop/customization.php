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

class PrestashopCustomization {
  private $factoryCreator;

  public function __construct($factoryCreator) {
    $this->factoryCreator = $factoryCreator;
  }

  public function getFactory() {
    return $this->factoryCreator->getFactory();
  }

  // public interface
  public function addCustomFieldsToForm($type, &$params) {
    try {
      return $this->doAddCustomFieldsToForm($type, $params);
    } catch (\Exception $e) {
      return $this->log($e->getMessage());
    }
  }

  public function updateCustomFields($type, $keys) {
    try {
      return $this->doUpdateCustomFields($type, $keys);
    } catch (\Exception $e) {
      return $this->log($e->getMessage());
    }
  }

  private function log($reason) {
    error_log("DataKick custom fields: ". $reason);
    return false;
  }

  private function doUpdateCustomFields($type, $keys) {
    $vals = $this->getCustomFieldValues($type);
    if ($vals) {
      $recordType = $vals['recordType'];
      $factory = $this->getFactory();
      if ($factory->trialEnded()) {
        return false;
      }
      $context = $factory->getContext('app');
      $modification = $factory->getModification($context);
      $fields = array();
      foreach ($vals['values'] as $key => $value) {
        $fields[$key] = array(
          'func' => 'identity',
          'type' => $vals['customFields'][$key]['type'],
          'args' => array( $value )
        );
      }
      $col = $factory->getDictionary()->getCollection($recordType);
      $conditions = array();
      $keyDefs = $col->getKeys();
      for ($i=0; $i<count($keyDefs); $i++) {
        $key = $keyDefs[$i];
        $type = $col->getField($key)->getType();
        $value = Types::convertValue($type, $keys[$i]);
        $conditions[] = array(
          'func' => 'equals',
          'type' => 'boolean',
          'args' => array(
            array('func' => 'variable', 'type' => $type, 'args' => array($type, 'rec', $key)),
            array('func' => 'identity', 'type' => $type, 'args' => array($value)),
          )
        );
      }
      $modification->addUpdate($vals['recordType'], $fields, $conditions);
      return $modification->execute(new Progress(true));
    }
    return false;
  }

  private function getCustomFieldsDefinitions($type) {
    $candidates = array();
    foreach ($this->getFactory()->getDictionary()->getCollections() as $col) {
      if ($col->hasPlatformField('psController') && $col->getPlatformField('psController') == $type) {
        return $this->getCustomFieldDefinition($col);
      }
      if ($col->hasPlatformField('psClass') && $col->getPlatformField('psClass') == $type) {
        return $this->getCustomFieldDefinition($col);
      }
    }
  }

  private function getKeysFromRequest($recordType) {
    try {
      $col = $this->getFactory()->getDictionary()->getCollection($recordType);
      $keys = array();
      foreach ($col->getKeyFields() as $fld) {
        $sql = $fld->getSql();
        $matches = array();
        if (! preg_match("/^[a-z0-9_]+\.([a-z0-9_]+)$/i", $sql, $matches)) {
          return null;
        }
        $value = \Tools::getValue($matches[1]);
        if ($value === false) {
          return null;
        } else {
          $keys[] = Types::convertValue($fld->getType(), $value);
        }
      }
      return $keys;
    } catch (\Exception $e) {
      return null;
    }
  }

  private function getCustomFieldDefinition(Collection $col) {
    $factory = $this->getFactory();
    $recordType = $col->getId();
    $customFields = $factory->getCustomization()->getCustomFields($recordType);
    if (count($customFields) > 0) {
      return array(
        'recordType' => $recordType,
        'customFields' => $customFields
      );
    }
    return null;
  }

  public function doAddCustomFieldsToForm($type, &$params) {
    $factory = $this->getFactory();
    if ($factory->trialEnded()) {
      return false;
    }

    $defs = $this->getCustomFieldsDefinitions($type);
    $defaultCurrency = 1;
    $currencies = array();
    foreach ($factory->getEnums()['currencies'] as $id=>$cur) {
      $currencies[] = array(
        'id' => $id,
        'name' => $cur['name']
      );
    }
    $defaultCurrency = $currencies[0]['id'];

    if ($defs) {
      $recordType = $defs['recordType'];
      $fields = array();
      $types = array();
      $inputs = array();
      foreach ($defs['customFields'] as $cust) {
        $name = 'datakick_' . Utils::decamelize($cust['alias'], '_');
        $types[$name] = $cust['type'];
        $fields[$name] = $cust['alias'];
        if (Types::isCurrency($cust['type']) && !$cust['subtype']) {
          // variable currency
          $inputs[] = array(
            'label' => $cust['name'] . ' - currency',
            'name' => $name . "_currency",
            'type' => 'select',
            'options' => array(
              'query' => $currencies,
              'id' => 'id',
              'name' => 'name'
            )
          );
        }
        $inputs[] = $this->getCustomField($name, $cust, $factory);
      }

      $keys = $this->getKeysFromRequest($recordType);
      $values = $this->getValues($recordType, $keys, $fields);
      $fieldValues = array();
      foreach ($fields as $name => $alias) {
        if (Types::isCurrency($types[$name])) {
          $fieldValues[$name.'_currency'] = $values ? $values[$alias]->getCurrencyId() : $defaultCurrency;
          $fieldValues[$name.'_value'] = $values ? $values[$alias]->getValue() : '';
        } else {
          $fieldValues[$name] = $values ? Types::toString($types[$name], $values[$alias]) : '';
        }
      }
      if ($inputs) {
        if (isset($params['fields'][0]['form']['input'])) {
          $params['fields'][0]['form']['input'] = array_merge($params['fields'][0]['form']['input'], $inputs);
        } else {
          $params['fields'][0]['form']['input'] = $inputs;
        }
        if (isset($params['fields_value']) && is_array($params['fields_value'])) {
          $params['fields_value'] = array_merge($params['fields_value'], $fieldValues);
        } else {
          $params['fields_value'] = $fieldValues;
        }
      }
    }
    return !$defs;
  }

  private function getValues($recordType, $keys, $fields) {
    if ($keys) {
      try {
        return $this->getFactory()->getRecord($recordType)->load($keys, $fields);
      } catch (\Exception $e) {}
    }
    return null;
  }

  private function getCustomField($id, $cust, $factory) {
    $ret = array(
      'label' => $cust['name'],
      'name' => $id
    );
    if ($cust['type'] === 'boolean') {
      $ret['type'] = 'switch';
      $ret['is_bool'] = true;
      $ret['values'] = array(
        array('id' => 'active_on', 'value' => 1, 'label' => 'Enabled'),
        array('id' => 'active_off', 'value' => 0, 'label' => 'Disabled')
      );
    } else if ($cust['type'] === 'datetime') {
      $ret['type'] = 'date';
    } else if ($cust['type'] === 'number') {
      $ret['type'] = 'text';
    } else if ($cust['type'] === 'currency') {
      $ret['type'] = 'text';
      $ret['name']  .= '_value';
      if ($cust['subtype']) {
        $currencies = $factory->getEnums()['currencies'];
        $ret['prefix'] = $currencies[$cust['subtype']]['symbol'];
      } else {
        $ret['label'] .= ' - value';
      }
    } else {
      $ret['type'] = 'text';
    }
    return $ret;
  }

  private static function getAllValues() {
    if (isset($_POST) && is_array($_POST) && isset($_GET) && is_array($_GET)) {
      return $_POST + $_GET;
    }
    if (isset($_POST) && is_array($_POST)) {
      return $_POST;
    }
    if (isset($_GET) && is_array($_GET)) {
      return $_GET;
    }
    return array();
  }

  private function getCustomFieldValues($type) {
    $values = array();
    foreach (self::getAllValues() as $key => $value) {
      if (strpos($key, 'datakick_') === 0) {
        $values[substr($key, 9)] = $value == '' ? null : $value;
      }
    }
    if (count($values)) {
      $defs = $this->getCustomFieldsDefinitions($type);
      if ($defs) {
        $ret = array();
        foreach ($defs['customFields'] as $def) {
          $a = $def['alias'];
          $v = null;
          $alias = Utils::decamelize($a);
          if (Types::isCurrency($def['type'])) {
            if ($def['subtype']) {
              if (isset($values[$alias . '_value'])) {
                $v = new Currency($def['subtype'], (int)$values[$alias.'_value']);
              }
            } else {
              $value = null;
              $currency = null;
              if (isset($values[$alias . '_currency'])) {
                $currency = (int)$values[$alias.'_currency'];
              }
              if (isset($values[$alias . '_value'])) {
                $value = (int)$values[$alias.'_value'];
              }
              if (!is_null($value) && !is_null($currency)) {
                $v = new Currency($currency, $value);
              }
            }
          } else {
            if (isset($values[$alias])) {
              $v = Types::convertValue($def['type'], $values[$alias], true, false);
            }
          }
          $ret[$a] = $v;
        }
        $defs['values'] = $ret;
        return $defs;
      }
    }
    return null;
  }
}
