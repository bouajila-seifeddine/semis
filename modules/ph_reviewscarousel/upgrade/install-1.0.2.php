<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_2($object)
{
	Configuration::updateGlobalValue('PH_REVIEWSCAROUSEL_ITEMS_COLUMN', '2');
	return true;
}