<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('ph_iconboxes.php');

$module = new PH_IconBoxes();
$action = Tools::getValue('action');

if ($action == 'updatePosition') {
    $ids = Tools::getValue('ids');
    if (is_array($ids)) {
        foreach ($ids as $pos => $id) {
        	$iconbox = new PrestaHomeIconBox($id);
        	$iconbox->position = $pos;
        	$iconbox->save();
        }
    }
}

$module->clearCache();