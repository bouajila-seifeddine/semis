<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

/**
 * This file returns array of SQL queries that are required to be executed during module installation.
 */
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress (
    id_elegantaltinypngimagecompress int(11) unsigned NOT NULL AUTO_INCREMENT,
    image_group varchar(255) NOT NULL,
    custom_dir varchar(255),
    compress_original_images tinyint(1) unsigned NOT NULL DEFAULT \'1\',
    compress_generated_images tinyint(1) unsigned NOT NULL DEFAULT \'1\',
    image_formats_to_compress text,
    images_count int(11) unsigned NOT NULL,
    images_size_before bigint(20) unsigned NOT NULL,
    images_size_after bigint(20) unsigned NOT NULL,
    status tinyint(1) unsigned NOT NULL DEFAULT \'0\',
    created_at DATETIME,
    updated_at DATETIME,
    PRIMARY KEY  (id_elegantaltinypngimagecompress) 
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress_images (
    id_elegantaltinypngimagecompress_images int(11) unsigned NOT NULL AUTO_INCREMENT,
    id_elegantaltinypngimagecompress int(11) unsigned NOT NULL, 
    status tinyint(1) unsigned NOT NULL DEFAULT \'0\',
    image_path varchar(255) NOT NULL,
    image_size_before int(11) unsigned NOT NULL,
    image_size_after int(11) unsigned NOT NULL,
    modified_at DATETIME,
    PRIMARY KEY (id_elegantaltinypngimagecompress_images) 
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

return $sql;
