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

class XmlFeedTask extends Task {

  public function __construct($identity, Factory $factory, $xml, Array $requiredParameters, Array $userParameters, XmlOutput $output) {
    parent::__construct($factory, $identity);
    $this->xml = $xml;
    $this->output = $output;
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
    header("Content-Type: text/xml");
    $filename = $this->getFilename('xml');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $factory = $this->getFactory();
    $executor = new XmlExecutor($factory);
    try {
      if ($executor->buildXml($this->xml, $this->output, null, $context, $progress)) {
        return $executor->getStats();
      }
      return false;
    } catch (UserError $e) {
      $this->handleError($e, true);
      throw $e;
    } catch (\Exception $e) {
      $this->handleError($e, false);
      throw $e;
    }
  }

  private function handleError($e, $userError) {
    $factory = $this->getFactory();
    $message = $userError ? $e->getMessage() : "There has been an error";
    $queries = array();

    if ($factory->debugMode()) {
      $message = $e->__toString();
      $queries = array_map(function($q) {
        return $q['sql'];
      }, $factory->getConnection()->getQueries());
    }
    echo "<?xml version=\"1.0\" ?>\n";
    echo "<error>\n";
    echo "  <message>$message</message>\n";
    if (count($queries) > 0) {
      echo "  <queries>\n";
      foreach($queries as $q) {
        echo "    <query>" . htmlspecialchars($q). "</query>";
      }
      echo "  </queries>\n";
    }
    echo "</error>\n";
  }
}
