<?php
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_1()
{
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_iconbox_lang` ADD content TEXT AFTER title');
	return true;
}
