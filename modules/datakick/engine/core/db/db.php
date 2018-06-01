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

require_once(dirname(__FILE__).'/statements/statement-builder.php');
require_once(dirname(__FILE__).'/statements/statement.php');
require_once(dirname(__FILE__).'/statements/base.php');
require_once(dirname(__FILE__).'/statements/callback.php');
require_once(dirname(__FILE__).'/statements/insert-missing.php');
require_once(dirname(__FILE__).'/statements/insert.php');
require_once(dirname(__FILE__).'/statements/update.php');
require_once(dirname(__FILE__).'/statements/create-association.php');
require_once(dirname(__FILE__).'/statements/set-virtual-field.php');
require_once(dirname(__FILE__).'/statements/delete.php');
require_once(dirname(__FILE__).'/statements/delete-dependent.php');
require_once(dirname(__FILE__).'/statements/delete-condition.php');
require_once(dirname(__FILE__).'/statements/delete-association.php');
require_once(dirname(__FILE__).'/statements/delete-record.php');
require_once(dirname(__FILE__).'/statements/component.php');
require_once(dirname(__FILE__).'/statements/batch.php');
require_once(dirname(__FILE__).'/statements/reset-auto-increment.php');
require_once(dirname(__FILE__).'/statements/query.php');
require_once(dirname(__FILE__).'/temp-table.php');
require_once(dirname(__FILE__).'/modification.php');
