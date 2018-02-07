<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_5_2_2($module)
{
    unset($module);

    $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'elegantaltinypngimagecompress ADD image_formats_to_compress text AFTER compress_generated_images';

    if (Db::getInstance()->execute($sql) == false) {
        //throw new Exception(Db::getInstance()->getMsgError());
        return false;
    }

    return true;
}
