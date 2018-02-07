<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_5_1_6($module)
{
    unset($module);
    
    $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress MODIFY COLUMN created_at DATETIME;';
    $sql .= 'ALTER TABLE ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress MODIFY COLUMN updated_at DATETIME;';
    $sql .= 'ALTER TABLE ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress_images MODIFY COLUMN modified_at DATETIME;';
    $sql .= 'ALTER TABLE ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress_images DROP FOREIGN KEY id_elegantaltinypngimagecompress;';

    if (Db::getInstance()->execute($sql) == false) {
        //throw new Exception(Db::getInstance()->getMsgError());
        return false;
    }

    return true;
}
