<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
/**
* @author    PrestaHome Team <support@prestahome.com>
* @copyright  Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_megamenu` (
            `id_prestahome_megamenu` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_parent` int(10) unsigned NOT NULL DEFAULT 0,
            `position` int(10) unsigned NOT NULL DEFAULT 0,
            `type` tinyint(2) unsigned DEFAULT \'1\',
            `new_window` tinyint(1) NOT NULL DEFAULT 0,
            `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
            `access` TEXT NOT NULL,
            `display_title` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
            `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
            `hide_on_desktop` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
            `new_row` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
            `align` tinyint(1) unsigned DEFAULT NULL,
            `columns` smallint(2) unsigned DEFAULT \'4\',
            `class` varchar(50) NOT NULL,
            `icon` varchar(50) NOT NULL,
            `background_img` varchar(255) NOT NULL,
            `background_size` varchar(255) NOT NULL,
            `background_repeat` varchar(255) NOT NULL,
            `background_attachment` varchar(255) NOT NULL,
            `background_position` varchar(255) NOT NULL,
            `label_color` varchar(10) NOT NULL,
            `label_bg` varchar(10) NOT NULL,
            `label_position` varchar(10) NOT NULL,
            `id_category_parent` int(10) unsigned NOT NULL DEFAULT 0,
            `id_cms_category_parent` int(10) unsigned NOT NULL DEFAULT 0,
            `id_product` TEXT NOT NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_prestahome_megamenu`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_megamenu_lang` (
            `id_prestahome_megamenu` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `url` text NOT NULL,
            `label_text` varchar(255) NOT NULL,
            `content` longtext,
            `content_before` longtext,
            `content_after` longtext,
            PRIMARY KEY (`id_prestahome_megamenu`,`id_lang`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_megamenu_shop` (
            `id_prestahome_megamenu` int(11) unsigned NOT NULL,
            `id_shop` int(11) unsigned NOT NULL,
            PRIMARY KEY (`id_prestahome_megamenu`,`id_shop`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';