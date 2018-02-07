<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_2($object)
{
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD display_title tinyint(1) unsigned DEFAULT 1 AFTER logged');

	return true;
}