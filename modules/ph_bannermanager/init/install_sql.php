<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_banner` (
            `id_prestahome_banner` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
            `access` TEXT NOT NULL,
            `columns` smallint(2) unsigned DEFAULT \'12\',
            `class` varchar(255) NOT NULL,
            `hook` varchar(255) NOT NULL,
            `new_window` tinyint(1) NOT NULL DEFAULT 0,
            `position` int(10) unsigned NOT NULL DEFAULT 0,
            `active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
            PRIMARY KEY (`id_prestahome_banner`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_banner_lang` (
            `id_prestahome_banner` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `url` text NOT NULL,
            `image` varchar(255) NOT NULL,
            PRIMARY KEY (`id_prestahome_banner`,`id_lang`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_banner_shop` (
            `id_prestahome_banner` int(11) unsigned NOT NULL,
            `id_shop` int(11) unsigned NOT NULL,
            PRIMARY KEY (`id_prestahome_banner`,`id_shop`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';