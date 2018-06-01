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

class ImportValidateMatchingService extends Service {

  public function __construct() {
    parent::__construct('import-validate-matching');
  }

  public function process($factory, $request) {
    $datasource = (int)$this->getParameter('datasource');
    $collection = $this->getParameter('collection');
    $condition = $this->getArrayParameter('condition');
    $root = $this->getParameter('rootNode');

    $manager = new ImportManager($factory);
    $filename = $manager->getDatasourceLocalFile($datasource);
    $matcher = new XmlImportValidateMatching($factory, $collection, $root, $condition);

    return $matcher->run($filename, new Progress(true));
  }
}
