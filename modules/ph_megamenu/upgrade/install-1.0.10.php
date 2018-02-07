<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_10($object)
{
	// Manage nb products in row
	// Module settings
	// More design control over the mega menu
	// Possibility to hide menu on mobile or desktop

	// New settings tab
	$settings_tab = new Tab();

	foreach (Language::getLanguages(false) as $lang)
		$settings_tab->name[$lang['id_lang']] = $object->l('Mega Menu - Settings');

	$settings_tab->class_name = 'AdminPrestaHomeMegaMenuSettings';
	$settings_tab->id_parent = -1;
	$settings_tab->module = $object->name;
	$settings_tab->add();

	// DB Changes
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` DROP COLUMN logged');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` DROP COLUMN categories_columns');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD hide_on_mobile tinyint(1) unsigned NOT NULL DEFAULT \'0\' AFTER display_title');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD hide_on_desktop tinyint(1) unsigned NOT NULL DEFAULT \'0\' AFTER hide_on_mobile');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD background_img varchar(255) NOT NULL AFTER icon');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD background_size varchar(255) NOT NULL AFTER background_img');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD background_repeat varchar(255) NOT NULL AFTER background_size');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD background_attachment varchar(255) NOT NULL AFTER background_repeat');
	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD background_position varchar(255) NOT NULL AFTER background_attachment');

	// Configurations
	Configuration::updateGlobalValue('PH_MM_CATEGORIES_SORTBY', 'position');
	Configuration::updateGlobalValue('PH_MM_PRODUCT_WIDTH', '3');
	Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_TITLE', true);
	Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_SECOND_IMAGE', true);
	Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_PRICE', true);
	Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_ADD2CART', true);
	Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_VIEW', true);
	Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_QUICK_VIEW', true);


	return true;
}