<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_13($object)
{
	// Configurations
	Configuration::updateGlobalValue('PH_MM_USE_SLIDE_EFFECT', false);

	return true;
}