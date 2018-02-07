<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

defined('_ELEGANTALTINYPNGIMAGECOMPRESS_DIR_') ? null : define('_ELEGANTALTINYPNGIMAGECOMPRESS_DIR_', _PS_MODULE_DIR_ . 'elegantaltinypngimagecompress');

require_once(_ELEGANTALTINYPNGIMAGECOMPRESS_DIR_ . '/classes/ElegantalTinyPngImageCompressTools.php');
require_once(_ELEGANTALTINYPNGIMAGECOMPRESS_DIR_ . '/classes/ElegantalTinyPngImageCompressModule.php');
require_once(_ELEGANTALTINYPNGIMAGECOMPRESS_DIR_ . '/classes/ElegantalTinyPngImageCompressObjectModel.php');
require_once(_ELEGANTALTINYPNGIMAGECOMPRESS_DIR_ . '/classes/ElegantalTinyPngImageCompressClass.php');
require_once(_ELEGANTALTINYPNGIMAGECOMPRESS_DIR_ . '/classes/ElegantalTinyPngImageCompressImagesClass.php');
