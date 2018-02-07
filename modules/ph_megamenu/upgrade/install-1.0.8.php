<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_8($object)
{
	/**
        
        NEW STYLE OF MENU ACCESS

    **/

	Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'prestahome_megamenu` ADD access TEXT NOT NULL AFTER logged');

	// Make sure that links for logged only are properly assigned to new access system:
	$links = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'prestahome_megamenu`');
	$groups = Group::getGroups(Context::getContext()->language->id);

	foreach($links as $link)
	{
		$access = array();

		if($link['logged'] == 1)
		{
			foreach ($groups as $group){
				if($group['id_group'] == Configuration::get('PS_UNIDENTIFIED_GROUP') || $group['id_group'] == Configuration::get('PS_GUEST_GROUP'))
	            	$access[$group['id_group']] = false;
	            else
	            	$access[$group['id_group']] = true;
	        }
		}
		else
		{
			foreach ($groups as $group){
	            $access[$group['id_group']] = true;
	        }
			
		}
		
		Db::getInstance()->update('prestahome_megamenu', array('access' => pSQL(serialize($access))), 'id_prestahome_megamenu = '.(int)$link['id_prestahome_megamenu']);
	}

	return true;
}