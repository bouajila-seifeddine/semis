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

class GetXmlPreviewService extends Service {

  public function __construct() {
    parent::__construct('get-xml');
  }

  public function process($factory, $request) {
    $xml = $this->getArrayParameter('parsed');
    $userParameters = $this->getArrayParameter('userParameters');
    $parameters = $this->getArrayParameter('parameterValues');
    $id = $this->getParameter('id', false);
    if (! $id) {
      $id = -1;
    }

    $context = $factory->getContext('app', $id);
    $context->setUserParameters($userParameters);
    $context->setValues($parameters);

    $xmlOutput = new XmlOutputInMemory();
    $executor = new XmlExecutor($factory);
    $progress = new Progress(true);
    $executor->buildXml($xml, $xmlOutput, 50, $context, $progress);
    return $xmlOutput->getXml();
  }

}
