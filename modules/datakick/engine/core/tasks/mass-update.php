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

class MassUpdateTask extends Task {

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
    $def = $this->definition;

    $factory = $this->getFactory();
    $factory->getUser()->getPermissions()->checkEdit($def['type']);
    $modification = $factory->getModification($context);
    $modification->addUpdate($def['type'], $def['fields'], $def['conditions']);
    $modification->execute($progress);

    return array(
      'recordType' => $def['type'],
      'fields' => array_keys($def['fields']),
      'changes' => $modification->getStats()
    );
  }

}
