<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('ph_bannermanager.php');

$module = new PH_BannerManager();
$action = Tools::getValue('action');

if ($action == 'updatePosition') {
    $ids = Tools::getValue('ids');
    if (is_array($ids)) {
        foreach ($ids as $pos => $id) {
        	$banner = new PrestaHomeBanner($id);
        	$banner->position = $pos;
        	$banner->save();
        }
    }
}

$module->clearCache();