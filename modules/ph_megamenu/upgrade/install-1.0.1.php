<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_1($object)
{
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD categories_columns smallint(2) unsigned DEFAULT 2 AFTER columns');

	return true;
}