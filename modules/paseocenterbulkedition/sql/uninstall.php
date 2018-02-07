<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Pronimbo.
 *  @copyright Pronimbo. all rights reserved.
 *  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 */

$sql = array();
if (!Module::isInstalled('paseocenter'))
{
	$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'paseocenter_metas';
	$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'paseocenter_metas_lang';
	$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'paseocenter_pages';

}
foreach ($sql as $query) if (Db::getInstance()->execute($query) == false) return false;
