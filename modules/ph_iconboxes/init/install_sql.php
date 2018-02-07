<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_iconbox` (
            `id_prestahome_iconbox` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
            `access` TEXT NOT NULL,
            `columns` smallint(2) unsigned DEFAULT \'12\',
            `class` varchar(255) NOT NULL,
            `hook` varchar(255) NOT NULL,
            `icon` varchar(255) NOT NULL,
            `position` int(10) unsigned NOT NULL DEFAULT 0,
            `active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
            PRIMARY KEY (`id_prestahome_iconbox`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_iconbox_lang` (
            `id_prestahome_iconbox` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `url` text NOT NULL,
            PRIMARY KEY (`id_prestahome_iconbox`,`id_lang`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestahome_iconbox_shop` (
            `id_prestahome_iconbox` int(11) unsigned NOT NULL,
            `id_shop` int(11) unsigned NOT NULL,
            PRIMARY KEY (`id_prestahome_iconbox`,`id_shop`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';