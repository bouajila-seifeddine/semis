<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Pronimbo.
 *  @copyright Pronimbo. all rights reserved.
 *  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 */

$sql = array();


$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'paseocenter_pages` (
`id_paseocenter_pages` int(11) NOT NULL AUTO_INCREMENT,
`id_meta` int(11) DEFAULT \'0\',
`page` varchar(255) DEFAULT NULL,
PRIMARY KEY (`id_paseocenter_pages`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'paseocenter_metas` (
`id_entity` int(11) NOT NULL,
`type` int(11) NOT NULL,
`id_shop` int(11) NOT NULL,
`noindex` tinyint(1) DEFAULT \'0\',
`markup` tinyint(1) DEFAULT \'0\',
`nofollow` tinyint(1) DEFAULT \'0\',
`canonical` tinyint(1) DEFAULT \'0\',
`id_paseocenter_metas` int(11) NOT NULL AUTO_INCREMENT,
`twt_card` varchar(50) DEFAULT NULL,
`fb_object_type` varchar(50) DEFAULT NULL,
`og_image` int(1) DEFAULT NULL,
PRIMARY KEY (`id_paseocenter_metas`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'paseocenter_metas_lang` (
`id_paseocenter_metas` int(11) NOT NULL,
`id_lang` int(11) NOT NULL,
`canonical` varchar(150) DEFAULT NULL,
`og_meta_title` varchar(150) DEFAULT NULL,
`og_meta_description` varchar(350) DEFAULT NULL,
`og_video` varchar(200) DEFAULT NULL,
`scripts` text DEFAULT NULL,
PRIMARY KEY (`id_paseocenter_metas`,`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


foreach ($sql as $query) if (Db::getInstance()->execute($query) == false) return false;
