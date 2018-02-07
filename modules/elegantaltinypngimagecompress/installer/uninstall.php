<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

/**
 * This file returns array of SQL queries that are required to be executed during module un-installation.
 */
$sql = array();

// Drop tables that are created during module installation. Note: order of queries is important here.
$sql[] = 'SET foreign_key_checks = 0';
$sql[] = 'UPDATE ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress_images SET id_elegantaltinypngimagecompress = 0';
$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress';
$sql[] = 'SET foreign_key_checks = 1';

return $sql;
