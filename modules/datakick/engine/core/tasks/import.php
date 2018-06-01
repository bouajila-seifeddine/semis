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

class ImportTask extends Task {
  private $manager;
  private $datasourceId;
  private $definitions;
  private $rec;
  private $definition;

  public function __construct($identity, Factory $factory) {
    parent::__construct($factory, $identity);
    $this->manager = new ImportManager($factory);
    $this->rec = Utils::extract('record', $identity);
    $this->datasourceId = Utils::extract('id', $this->rec);
    $fileType = Utils::extract('fileType', $this->rec);
    $rawStructure = Utils::extract('structure', $this->rec);
    $structure = $this->manager->getStructure($fileType, json_decode($rawStructure, true));
    $this->definitions = $this->manager->getMatchingImportDefinitions($structure);
  }

  public function getUserParameters() {
    $definition = array(
      'type' => 'number',
      'description' => "Import Definition",
      'values' => $this->definitions
    );
    if (count($this->definitions)) {
      $keys = array_keys($this->definitions);
      $definition['default'] = $keys[0];
    }

    return array(
      'task::definitionId' => $definition
    );
  }

  public function getRequiredParameters() {
    return array_keys($this->getUserParameters());
  }

  public function isResumable() {
    return true;
  }

  public function doExecute(Context $context, Progress $progress, $executionId, $resumeState) {
    $definitionId = $context->getValue('task::definitionId');
    if (! isset($this->definitions[$definitionId])) {
      throw new UserError("Import definition $definitionId not found or is not compatible with datasource {$this->datasourceId}");
    }
    $this->definition = $this->manager->getImportDefinition($definitionId);
    $input = $this->getImportFile($executionId);
    $importer = $this->manager->getImporter($definitionId, $resumeState);
    $this->initialize($importer, $input, $progress, $definitionId);
    return $importer->run($input, $progress);
  }

  private static function isInitStage($stage) {
    return ($stage === 'init' || $stage === 'file' || $stage === 'count');
  }

  private function initialize($importer, $input, $progress, $definitionId) {
    $state = $importer->getState();
    $stage = $importer->getStage();
    if (self::isInitStage($stage)) {
      $cnt = 0;
      $progress->setProgress(-1, $cnt, $state);
      while (self::isInitStage($stage)) {
        $result = null;
        if ($stage === 'file') {
          $this->prepareFile($input);
          $result = $input;
        } else if ($stage === 'count') {
          $result = $this->countRows($input, $definitionId, $progress);
          $importer->setTotal($result);
        }
        $cnt++;
        $state = $importer->nextStage($result);
        $stage = $importer->getStage();
        $progress->setProgress(-1, $cnt, $state);
      }
    }
    $this->checkFile($input);
  }

  private function countRows($input, $definitionId, $progress) {
    return $this->manager->countRows($input, $definitionId, $progress);
  }

  private function checkFile($input) {
    if (! is_file($input)) {
      throw new UserError("Input file not found: $input");
    }
  }

  public function cleanup($executionId) {
    @unlink($this->getImportFile($executionId));
  }

  private function getImportFile($executionId) {
    $tmpDir = $this->getFactory()->getTempDirectory();
    return "{$tmpDir}/import-{$executionId}";
  }

  private function prepareFile($input) {
    if (! is_file($input)) {
      $source = $this->manager->getFile($this->datasourceId);
      @copy($source, $input);
    }
    return $input;
  }

  protected function transformResultForTracking($result) {
    $result['source'] = array(
      'type' => $this->rec['sourceType'],
      'source' => $this->rec['source']
    );
    $result['definition'] = $this->definition;
    if (isset($result['entries'])) {
      $entries = $result['entries'];
      unset($result['entries']);
      $statuses = array();
      $failures = array();
      foreach ($entries as $entry) {
        $status = $entry['status'];
        if (! isset($statuses[$status])) {
          $statuses[$status] = 1;
        } else {
          $statuses[$status]++;
        }
        if ($status === 'failed') {
          if (count($failures) < 20) {
            $failures = array_unique(array_merge($failures, $entry['fullErrors']));
          }
        }
      }
      $result['statuses'] = $statuses;
      $result['failures'] = $failures;
    }
    return $result;
  }

  protected function getErrorFromResult($result) {
    if ($result && isset($result['entries'])) {
      $failures = 0;
      foreach ($result['entries'] as $entry) {
        if ($entry['status'] === 'failed') {
          $failures++;
        }
      }
      if ($failures) {
        return "Failed to process $failures input records";
      }
    }
    return null;
  }

}
