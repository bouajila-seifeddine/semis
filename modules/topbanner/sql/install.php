<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'topbanner` (
    `id_banner` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`height` int(4) NOT NULL,
	`background` varchar(10) NOT NULL,
	`type` int(11) NOT NULL,
	`subtype` int(11) NOT NULL,
	`cartrule` int(11) NOT NULL,
	`timer` int(11) NOT NULL,
	`timer_left_text` varchar(255) NOT NULL,
	`timer_right_text` varchar(255) NOT NULL,
	`timer_background` varchar(10) NOT NULL,
	`timer_text_color` varchar(10) NOT NULL,
	`text` varchar(255) NOT NULL,
	`text_carrier_empty` varchar(255) NOT NULL,
	`text_carrier_between` varchar(255) NOT NULL,
	`text_carrier_full` varchar(255) NOT NULL,
	`text_size` int(4) NOT NULL,
	`text_font` int(2) NOT NULL,
	`text_color` varchar(10) NOT NULL,
	`cta` int(1) NOT NULL,
	`cta_text` varchar(255) NOT NULL,
	`cta_link` varchar(255) NOT NULL,
	`cta_text_color` varchar(10) NOT NULL,
	`cta_background` varchar(10) NOT NULL,
	`status` int(1) NOT NULL DEFAULT "0",
    PRIMARY KEY  (`id_banner`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'topbanner_lang` (
  `id_banner` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `id_shop` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  UNIQUE KEY `id_banner` (`id_banner`,`id_lang`,`id_shop`,`name`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
