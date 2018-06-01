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

class ParameterProvider {
  private $factory;

  public function __construct($factory) {
    $this->factory = $factory;
  }

  public function getParameter($id, $definition) {
    if ($id === 'executionSource')
      return 'app';
    if ($id === 'executionSourceId')
      return -1;
    if ($id === 'timestamp')
      return new \DateTime();
    throw new \Exception("Don't know how to provide parameter '$id'");
  }

  public function deriveParameter($id, $definition, $dependencies) {
    if ($id === 'executionId') {
      return $this->getExecutionId($dependencies[0], $dependencies[1]);
    }
    throw new \Exception("Don't know how to derive parameter '$id' [" . implode(',', $dependencies) . "]");
  }

  private function getExecutionId($source, $sourceId) {
    $table = $this->factory->getServiceTable('executions');
    $connection = $this->factory->getConnection();
    return $connection->insert($table, array(
      'source' => $source,
      'source_id' => $sourceId,
      'user_id' => $this->factory->getUser()->getId()
    ));
  }

  public function getFactory() {
    return $this->factory;
  }

}
