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

class CsvFeedTask extends Task {

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
    header("Content-Type: text/csv");
    $filename = $this->getFilename('csv');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $factory = $this->getFactory();
    $executor = new ListExecutor($factory, false, false);
    try {
      $columns = $this->definition['columns'];
      $exportLabels = $context->getValue('task::exportColumnNames');
      $output = new CsvOutputStream(fopen('php://output', 'w'), $columns, $exportLabels, $this->getSeparator($context));
      if ($executor->buildList($this->definition, $output, null, null, $context, $progress)) {
        return $executor->getStats();
      }
      return false;
    } catch (\Exception $e) {
      $message = "There has been an error";
      $queries = array();

      if ($factory->debugMode()) {
        $message = $e->__toString();
        $queries = array_map(function($q) {
          return $q['sql'];
        }, $factory->getConnection()->getQueries());
      }
      echo "$message\n";
      if (count($queries) > 0) {
        echo "queries:\n";
        foreach($queries as $q) {
          echo $q . "\n\n";
        }
      }
      throw $e;
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
