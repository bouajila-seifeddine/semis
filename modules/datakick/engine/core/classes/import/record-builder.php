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

abstract class ImportRecordBuilder {
  private $valid = true;
  private $result = null;
  private $errors = array();
  private $fullErrors = array();
  private $conditionValues = null;

  // static processing instructions
  private $factory;
  private $transformations;
  private $executor;
  private $collection;
  private $context;
  private $importMode;
  private $pkMatcher;

  // predefined
  private $hasDynamicParameter = false;
  private $parameterValues = array();
  private $fieldValues = array();
  private $relations = array();

  public function __construct(Factory $factory, Context $context, ImportExecutor $executor, $definition) {
    $this->factory = $factory;
    $this->context = $context;
    $this->executor = $executor;
    $this->transformations = new ImportTransformations($factory);
    $this->collection = $factory->getDictionary()->getCollection(Utils::extract('collection', $definition));
    $this->setupImportMode($definition);
    $this->setupParameters($definition);
    $this->setupFields($definition);
    $this->setupRelations($definition);
  }

  public function getCollectionId() {
    return $this->collection->getId();
  }

  public function init() {
    $this->valid = true;
    $this->result = null;
    $this->errors = array();
    $this->fullErrors = array();
    $this->conditionValues = null;
  }

  public function testMatching() {
    try {
      if ($this->valid) {
        $pk = $this->findPrimaryKey();
        $status = is_null($pk) ? 'not-matched' : 'matched';
        return array('status' => $status, 'conditions' => $this->getConditionValues());
      }
    } catch (\Exception $e) {
      $this->addError($e);
    }
    return false;
  }

  public function execute(Progress $progress) {
    if ($this->valid) {
      try {
        $params = $this->getParameterValues();
        $fields = $this->getFields($params);

        $modification = $this->factory->getModification($this->context);

        $pk = $this->findPrimaryKey();

        switch ($this->importMode) {
          case 'replace':
          case 'insert':
            $modification->addCreate($this->getCollectionId(), $fields);
            $this->result = 'created';
            break;
          case 'update';
            if ($pk) {
              $this->updateRecord($fields, $pk, $modification);
              $this->result = 'updated';
              break;
            } else {
              $this->result = 'not-found';
              return $this->wrapResult();
            }
          case 'updateOrInsert':
            if ($pk) {
              $this->updateRecord($fields, $pk, $modification);
              $this->result = 'updated';
            } else {
              $modification->addCreate($this->getCollectionId(), $fields);
              $this->result = 'created';
            }
            break;
          case 'insertNew':
            if ($pk) {
              $this->result = 'exists';
              return $this->wrapResult($pk);
            } else {
              $modification->addCreate($this->getCollectionId(), $fields);
              $this->result = 'created';
              break;
            }
        }

        $this->executor->execute($modification, $progress);
        if (! $pk) {
          $pk = $this->executor->getLastId();
        }

        foreach ($this->relations as $key => $def) {
          $type = $def['type'];
          $link = $def['link'];
          $linkKey = $def['linkKey'];
          $collection = $def['collection'];
          $definition = $def['def'];
          if ($link->canCreate()) {
            if ($type === 'association') {
              $this->associate($pk, $key, $linkKey, $definition, $collection, $params, $progress);
            } else {
              $this->addDependends($definition, $pk, $key, $link, $collection, $params, $progress);
            }
          } else {
            throw new \Exception("Can't associate with {$link->getFullId()}, link does not support creation");
          }
        }

        if ($this->valid) {
          return $this->wrapResult($pk);
        }
        return false;
      } catch (\Exception $e) {
        $this->addError($e);
      }
    }
    return false;
  }

  private function wrapResult($pk=null) {
    $ret = array('status' => $this->result);
    if ($pk) {
      $ret['key'] = $pk;
    }
    return $ret;
  }

  public function getErrors($full) {
    return $full ? $this->fullErrors : $this->errors;
  }

  public function getConditionValues() {
    return $this->conditionValues;
  }

  protected function addError(\Exception $e, $prefix=null) {
    $this->valid = false;
    $s = $e;
    if (! is_string($e)) {
      $s = self::getErrorMessage($e, false);
      $sf = self::getErrorMessage($e, true);
    }
    if ($prefix) {
      $s = "$prefix: $s";
      $sf = "$prefix: $sf";
    }
    $this->errors[] = $s;
    $this->fullErrors[] = $sf;
  }

  protected function getFactory() {
    return $this->factory;
  }

  protected function getCollection() {
    return $this->collection;
  }

  protected function getTransformation(Array $transformations, $field) {
    if ($field) {
      $transformations[] = $this->transformations->getFieldRestriction($field);
    }
    return $this->transformations->getChain($transformations);
  }

  private function setupParameters($definition) {
    $parameters = Utils::extract('parameters', $definition);
    $paramDefs = $this->factory->getParameters();
    foreach ($parameters as $param => $def) {
      $type = Utils::extract('type', $def);
      if (! isset($paramDefs[$param])) {
        throw new \Exception("Unknown parameter `$param`");
      }
      $paramDef = $paramDefs[$param];
      switch ($type) {
        case 'all':
          $this->parameterValues[$param] = $this->getAllPossibleParameterValues($param);
          break;
        case 'list':
          $this->parameterValues[$param] = Utils::extract('values', $def);
          break;
        default:
          $this->hasDynamicParameter = true;
          $this->setupDynamicParameter($param, $def, $paramDef);
      }
    }
  }

  private function getParameterValues() {
    if ($this->hasDynamicParameter) {
      return array_merge($this->parameterValues, $this->getDynamicParameterValues());
    }
    return $this->parameterValues;
  }

  private function setupFields($definition) {
    $fields = Utils::extract('fields', $definition);
    $reqParameters = array();
    foreach ($fields as $field => $def) {
      $type = Utils::extract('type', $def);
      $fieldDef = $this->collection->getField($field);
      $reqParameters = array_unique(array_merge($reqParameters, $fieldDef->getParameters()));
      switch ($type) {
        case 'constant':
          $value = isset($def['value']) ? $def['value'] : null;
          $this->fieldValues[$field] = Types::convertValue($fieldDef->getType(), $value);
          break;
        default:
          $this->setupField($field, $def, $fieldDef);
      }
    }
    $parameters = array_keys(Utils::extract('parameters', $definition));
    $diff = array_diff($reqParameters, $parameters);
    if (!! $diff) {
      throw new UserError("Import definition is missing parameters: " . join(", ", $diff));
    }
  }

  private function setupRelations($definition) {
    if (isset($definition['relations'])) {
      foreach ($definition['relations'] as $linkKey => $variants) {
        $link = $this->collection->getLink($linkKey);
        $collection = $link->getTarget();
        for ($i=0; $i<count($variants); $i++) {
          $def = $variants[$i];
          $variantKey = $linkKey . '_' . $i;
          $type = Utils::extract('type', $def);
          if ($type !== 'association' && $type !== 'dependent') {
            throw new \Exception('Invalid relation type: ' . $type);
          }
          $this->relations[$variantKey] = array(
            'type' => $type,
            'linkKey' => $linkKey,
            'link' => $link,
            'collection' => $collection,
            'def' => $def
          );
          $this->setupRelation($variantKey, $def, $link, $collection);
        }
      }
    }
  }

  private function getAllPossibleParameterValues($param) {
    $parameters = $this->factory->getParameters();
    if (isset($parameters[$param])) {
      if (isset($parameters[$param]['values'])) {
        return array_keys($parameters[$param]['values']);
      }
      throw new UserError("Parameter `$param` is not enum");
    }
    throw new UserError("Parameter definition for `$param` not found");
  }

  private function setupImportMode($definition) {
    $importMode = Utils::extract('importMode', $definition);
    $this->importMode = Utils::extract('type', $importMode);
    // validate import mode
    if (! in_array($this->importMode, array('insert', 'update', 'updateOrInsert', 'replace', 'insertNew'))) {
      throw new UserError("Invalid import mode: $this->importMode");
    }
    // set up record matching
    if (in_array($this->importMode, array('insertNew', 'update', 'updateOrInsert'))) {
      $cacheMissing = $this->importMode === 'update';
      $matchConditions = Utils::extract('condition', $importMode);
      $this->pkMatcher = new PrimaryKeyMatcher($this->factory, $this->context, $this->collection, $matchConditions, $cacheMissing);
      $this->setupRecordMatching($matchConditions);
    }
  }

  private function updateRecord($fields, $pks, $modification) {
    $contexts = array();

    $conds = array();
    $keys = $this->collection->getKeyFields();
    for ($i=0; $i<count($pks); $i++) {
      $key = $keys[$i];
      $value = $pks[$i];
      $type = $key->getType();
      $conds[] = array(
        'func' => 'equals',
        'type' => 'boolean',
        'args' => array(
          array(
            "func" => "variable",
            "type" => $type,
            "args" => array($type, $type, $key->getId())
          ),
          array(
            'func' => 'identity',
            'type' => $type,
            'args' => array(Types::convertValue($type, $value))
          )
        )
      );
    }

    foreach ($fields as $fld => $def) {
      if (is_array($def)) {
        foreach ($def as $valueVariant) {
          $value = $valueVariant['value'];
          unset($valueVariant['value']);
          ksort($valueVariant);
          $unique = '';
          foreach ($valueVariant as $key => $val) {
            $unique .= $key . '-' . $val . ',';
          }
          if (! isset($contexts[$unique])) {
            $contexts[$unique] = array(
              'context' => $this->getContext($valueVariant),
              'fields' => array()
            );
          }
          $contexts[$unique]['fields'][$fld] = $this->getUpdateField($fld, $value);
        }
      } else {
        if (! isset($contexts['-'])) {
          $contexts['-'] = array(
            'context' => $this->context,
            'fields' => array()
          );
        }
        $contexts['-']['fields'][$fld] = $this->getUpdateField($fld, $def);
      }
    }
    foreach ($contexts as $c) {
      $context = $c['context'];
      $fields = $c['fields'];
      $modification->addUpdate($this->getCollectionId(), $fields, $conds, $context);
    }
  }

  private function getUpdateField($fld, $value) {
    $field = $this->collection->getField($fld);
    return array(
      'func' => 'identity',
      'type' => $field->getType(),
      'args' => array($value)
    );
  }

  protected function getContext($params) {
    if ($params) {
      $c = $this->context;
      $newContext = $this->factory->getContext($c->getValue('executionSource'), $c->getValue('executionSourceId'));
      $newContext->setValues($params);
      return $newContext;
    }
    return $this->context;
  }

  private function getFields($parameters) {
    $fields = $this->getFieldValues($parameters);
    if ($this->fieldValues) {
      foreach ($this->fieldValues as $field => $value) {
        $fieldDef = $this->collection->getField($field);
        $req = $fieldDef->getParameters();
        if ($req) {
          $input = array();
          foreach ($req as $param) {
            $input[$param] = Utils::extract($param, $parameters);
          }
          $fields[$field] = Utils::cartesian($input, $value);
        } else {
          $fields[$field] = $value;
        }
      }
    }
    return $fields;
  }

  private function associate($pks, $variantKey, $linkKey, $def, $collection, $parameters, $progress) {
    $entries = $this->getRelationValues($variantKey, $parameters, $collection);
    if ($entries) {
      $modification = $this->factory->getModification($this->context);

      if ($pks && Utils::extract('importMode', $def) === 'replace') {
        $pk = Utils::zip($this->collection->getKeys(), $pks);
        $link = $this->collection->getLink($linkKey);
        $modification->deleteAssociation($this->collection, $link, array($pk));
      }

      foreach ($entries as $entry) {
        $modification->addAssociation($this->collection, $linkKey, $pks, $entry);
      }

      try {
        $this->executor->execute($modification, $progress);
      } catch (\Exception $e) {
        $msg = "Failed to associate `{$collection->getSingularName()}`";
        $this->addError($e, $msg);
      }
    }
  }

  private function addDependends($def, $pks, $linkKey, $link, $collection, $parameters, $progress) {
    $keys = array();
    $targetFields = $link->getTargetFields();
    for ($i=0; $i<count($pks); $i++) {
      $pk = $pks[$i];
      $ps = array();
      $field = $collection->getField($targetFields[$i]);
      foreach ($field->getParameters() as $param) {
        $ps[$param] = $parameters[$param];
      }
      $keys[$field->getId()] = $ps ? Utils::cartesian($ps, $pk) : $pk;
    }

    if ($pks && Utils::extract('importMode', $def) === 'replace') {
      $pk = Utils::zip($this->collection->getKeys(), $pks);
      $modification = $this->factory->getModification($this->context);
      $modification->addStatement(new DeleteDependent($this->factory, $this->factory->getConnection(), $this->collection, $link, array($pk)));
      $this->executor->execute($modification, $progress);
    }
    $entries = $this->getRelationValues($linkKey, $parameters, $collection);
    foreach ($entries as $entry) {
      try {
        $entry = array_merge($entry, $keys);
        $modification = $this->factory->getModification($this->context);
        $modification->addCreate($collection->getId(), $entry);
        $this->executor->execute($modification, $progress);
      } catch (\Exception $e) {
        $msg = "Failed to import `{$collection->getSingularName()}`";
        $this->addError($e, $msg);
      }
    }
  }

  private function findPrimaryKey() {
    if ($this->pkMatcher) {
      $conditionValues = $this->getRecordMatchingConditionInputValues();
      if ($conditionValues) {
        $this->conditionValues = $conditionValues;
        return $this->pkMatcher->getPrimaryKeys($conditionValues);
      }
    }
  }

  private static function getErrorMessage(\Exception $e, $full) {
    if ($full) {
      return $e->getMessage();
    }
    if (method_exists($e, 'getUserMessage')) {
      return $e->getUserMessage();
    }
    return "Unknown error";
  }

  protected abstract function setupDynamicParameter($param, $def, $paramDef);
  protected abstract function setupField($field, $def, $fieldDef);
  protected abstract function setupRelation($key, $def, $link, $collection);
  protected abstract function setupRecordMatching($condition);
  protected abstract function getDynamicParameterValues();
  protected abstract function getFieldValues($params);
  protected abstract function getRelationValues($key, $params, $collection);
  protected abstract function getRecordMatchingConditionInputValues();
}
