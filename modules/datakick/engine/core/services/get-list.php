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

class GetListService extends Service {

  public function __construct() {
    parent::__construct('get-list');
  }

  public function process($factory, $request) {
    $definition = $this->getArrayParameter('definition');
    $parameters = $this->getArrayParameter('parameterValues');
    $userParameters = isset($definition['userParameters']) ? $definition['userParameters'] : array();
    $offset = (int)$this->getParameter('offset', false);
    $limit = (int)$this->getParameter('limit', false);

    $id = $this->getParameter('id', false);
    if (! $id) {
      $id = -1;
    }

    $context = $factory->getContext('app', $id);
    $context->setUserParameters($userParameters);
    $context->setValues($parameters);

    $listOutput = new ListOutputInMemory();
    $progress = new Progress(true);

    $executor = new ListExecutor($factory, true, true, false);
    $executor->buildList($definition, $listOutput, $offset, $limit, $context, $progress);
    return array(
      'list' => $listOutput->getList(),
      'count' => $listOutput->getCount(),
      'total' => $executor->getTotalCount(),
      'limit' => $executor->getLimit(),
      'offset' => $executor->getOffset()
    );
  }
}
