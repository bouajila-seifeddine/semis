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

class CsvFileTask extends Task {

  public function __construct($identity, Factory $factory, $definition, Array $requiredParameters, Array $userParameters) {
    parent::__construct($factory, $identity);
    $this->definition = $definition;
    $this->requiredParameters = $requiredParameters;
    $this->userParameters = $userParameters;
  }

  public function getRequiredParameters() {
    return $this->requiredParameters;
  }

  public function getUserParameters() {
    return $this->userParameters;
  }

  public function doExecute(Context $context, Progress $progress, $executionId, $resumeState) {
    $factory = $this->getFactory();
    $place = $factory->getPlaces()->load($context->getValue('task::placeId'), $this->getTaskInfo($context, $executionId));
    $outputPath = $this->parametrize($context->getValue('task::outputPath'), $context);
    $executor = new ListExecutor($factory, false, false);
    $writeUri = $place->getWriteUri($outputPath);
    if ($writeUri) {
      $this->executeDirect($executor, $writeUri, $context, $progress, $place, $outputPath);
    } else {
      $this->executeIndirect($executor, $context, $progress, $place, $outputPath);
    }
    $place->finalize();
    return array(
      'place' => array(
        'id' => $place->getId(),
        'name' => $place->getName(),
        'type' => $place->getType(),
      ),
      'file' => $outputPath,
      'stats' => $executor->getStats()
    );
  }

  private function parametrize($path, $context) {
    $ts = $context->getValue('timestamp');
    $id = $context->getValue('executionId');
    $path = str_replace("{id}", $id, $path);
    $path = str_replace("{date}", $ts->format('Y-m-d'), $path);
    $path = str_replace("{time}", $ts->format('H-i-s'), $path);
    $path = str_replace("{year}", $ts->format('Y'), $path);
    $path = str_replace("{month}", $ts->format('m'), $path);
    $path = str_replace("{day}", $ts->format('d'), $path);
    $path = str_replace("{hour}", $ts->format('H'), $path);
    $path = str_replace("{minute}", $ts->format('i'), $path);
    $path = str_replace("{second}", $ts->format('s'), $path);
    return $path;
  }

  private function executeDirect($executor, $uri, $context, $progress, $place, $outputPath) {
    $permError = $place->getPermissionError($outputPath);
    if ($permError) {
      throw new UserError($permError);
    }

    $file = fopen($uri, 'w');
    if ($file === false) {
      throw new UserError("Failed to open uri $uri");
    }

    $columns = $this->definition['columns'];
    $exportLabels = $context->getValue('task::exportColumnNames');
    $output = new CsvOutputStream($file, $columns, $exportLabels, $this->getSeparator($context));
    $executor->buildList($this->definition, $output, null, null, $context, $progress);
  }

  private function executeIndirect($executor, $context, $progress, $place, $outputPath) {
    $tmpFile = tempnam(sys_get_temp_dir(), 'csv-');
    $exception = null;
    try {
      $columns = $this->definition['columns'];
      $exportLabels = $context->getValue('task::exportColumnNames');
      $output = new CsvOutputStream(fopen($tmpFile, 'w'), $columns, $exportLabels, $this->getSeparator($context));
      $ret = $executor->buildList($this->definition, $output, null, null, $context, $progress);
      if ($ret) {
        if (! $place->saveFile($tmpFile, $outputPath)) {
          throw new UserError($place->getError());
        }
      }
    } catch (\Exception $e) {
      $exception = $e;
    }
    unlink($tmpFile);
    if ($exception) {
      throw $exception;
    }
  }

  private function getSeparator($context) {
    $separator = $context->getValue('task::separator');
    if ($separator == 'semicolon')
      return ';';
    if ($separator == 'space')
      return ' ';
    if ($separator == 'tab')
      return "\t";
    return ',';
  }

}
