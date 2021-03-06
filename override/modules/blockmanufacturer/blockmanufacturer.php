<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2015 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
/**
 * @author PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
 * @license You only can use module, nothing more!
 */
if (!defined('_PS_VERSION_'))
	exit;
class BlockManufacturerOverride extends BlockManufacturer
{
	public function __construct()
	{
		parent::__construct();
		if($this->id && !$this->isRegisteredInHook('displayAfterContent'))
			$this->registerHook('displayAfterContent');
	}
	public function install()
	{
		$this->registerHook('displayAfterContent');
		return parent::install();
	}
	public function hookDisplayAfterContent()
	{
		$manufacturers = Manufacturer::getManufacturers();
			foreach ($manufacturers as &$manufacturer)
			{
				$manufacturer['image'] = $this->context->language->iso_code.'-default';
				if (file_exists(_PS_MANU_IMG_DIR_.$manufacturer['id_manufacturer'].'-'.ImageType::getFormatedName('medium').'.jpg'))
					$manufacturer['image'] = $manufacturer['id_manufacturer'];
			}
			$this->smarty->assign(array(
				'manufacturers' => $manufacturers,
				'text_list' => Configuration::get('MANUFACTURER_DISPLAY_TEXT'),
				'text_list_nb' => Configuration::get('MANUFACTURER_DISPLAY_TEXT_NB'),
				'form_list' => Configuration::get('MANUFACTURER_DISPLAY_FORM'),
				'display_link_manufacturer' => Configuration::get('PS_DISPLAY_SUPPLIERS'),
			));
		return $this->display(__FILE__, 'blockmanufacturer-content.tpl');
	}
}
