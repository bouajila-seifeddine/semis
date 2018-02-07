<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_11($object)
{
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD new_row tinyint(1) unsigned NOT NULL DEFAULT \'0\' AFTER hide_on_desktop');

	return true;
}