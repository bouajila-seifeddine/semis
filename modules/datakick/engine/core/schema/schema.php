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

require_once(dirname(__FILE__) . '/cron.php');
require_once(dirname(__FILE__) . '/custom-field.php');
require_once(dirname(__FILE__) . '/endpoint.php');
require_once(dirname(__FILE__) . '/executions.php');
require_once(dirname(__FILE__) . '/list.php');
require_once(dirname(__FILE__) . '/mass-update.php');
require_once(dirname(__FILE__) . '/place.php');
require_once(dirname(__FILE__) . '/schedule.php');
require_once(dirname(__FILE__) . '/xml-template.php');
require_once(dirname(__FILE__) . '/import-datasource.php');
require_once(dirname(__FILE__) . '/import-definition.php');

class CoreSchemaLoader extends SchemaLoader {
  public function load() {
    $this->loadSchema(new Schema\Core\Cron());
    $this->loadSchema(new Schema\Core\CustomField());
    $this->loadSchema(new Schema\Core\Endpoint());
    $this->loadSchema(new Schema\Core\Executions());
    $this->loadSchema(new Schema\Core\ListSchema());
    $this->loadSchema(new Schema\Core\MassUpdate());
    $this->loadSchema(new Schema\Core\Place());
    $this->loadSchema(new Schema\Core\Schedule());
    $this->loadSchema(new Schema\Core\XmlTemplate());
    $this->loadSchema(new Schema\Core\ImportDatasources());
    $this->loadSchema(new Schema\Core\ImportDefinitionSchema());
  }
}
