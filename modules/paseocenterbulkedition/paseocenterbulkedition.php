<?php
/**
*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
*
*  @author    Pronimbo.
*  @copyright Pronimbo. all rights reserved.
*  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
*/

if (!defined('_PS_VERSION_')) exit;
if (!class_exists('PaMeta'))
include_once(_PS_MODULE_DIR_.'paseocenterbulkedition'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'PaMeta.php');
if (!class_exists('PaPage'))
include_once(_PS_MODULE_DIR_.'paseocenterbulkedition'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'PaPage.php');

class PaSeoCenterBulkEdition extends Module
{
	protected $config_form = false;

	public function __construct()
	{
		$this->name = 'paseocenterbulkedition';
		$this->tab = 'administration';
		$this->version = '1.1.1';
		$this->author = 'Pronimbo';
		$this->need_instance = 0;
		$this->module_key = '908b87fe37d35116d5601872496fb8c7';
		/**
		 * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
		 */
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Seo Center: Bulk Edition');
		$this->description = $this->l('Get top positions on search engines editing massively your product, manufacturer, suppliers, cms, and other pages of your shop adding no index, no follow, markups, open graph customized metas, canonical customized url and more');
	}

	protected function createTabs()
	{
		$tab = Tab::getInstanceFromClassName('AdminPaSeoCenterParent');
		$tab1 = Tab::getInstanceFromClassName('AdminPaSeoCenterGeneral');
		$tab3 = Tab::getInstanceFromClassName('AdminPaSeoCenterBulkEdition');
		$tab3->module = $tab1->module = $tab->module = $this->name;
		$tab->class_name = 'AdminPaSeoCenterParent';
		$tab1->class_name = 'AdminPaSeoCenterGeneral';
		$tab3->class_name = 'AdminPaSeoCenterBulkEdition';
		$tab->id_parent = 0;
		$tab->position = 99;
		$tab1->position = 1;
		$tab3->position = 4;
		foreach (Language::getLanguages() as $lang)
		{
			$tab->name[$lang['id_lang']] = $this->l('Seo Center');
			$tab1->name[$lang['id_lang']] = $this->l('Seo Settings');
			$tab3->name[$lang['id_lang']] = $this->l('Bulk edition');
		}
		$res = $tab->save();
		$id_parent = $tab->id;
		$tab1->id_parent = $tab3->id_parent = $id_parent;
		$res = $res && $tab1->save() && $tab3->save();
		return $res;
	}

	protected function removeTab()
	{
		if (Module::isInstalled('paseocenter'))
			return true;

		$tab1 = Tab::getInstanceFromClassName('AdminPaSeoCenterGeneral');
		$tab3 = Tab::getInstanceFromClassName('AdminPaSeoCenterBulkEdition');
		$res = true;
		if (!Module::isInstalled('paseocenterredirections') && !Module::isInstalled('paseocentersitemaps'))
		{
			$tab = Tab::getInstanceFromClassName('AdminPaSeoCenterParent');
			$res = $tab->delete();
		}

		return $res && $tab1->delete() && $tab3->delete();

	}

	public function install()
	{
		if (!Module::isInstalled('paseocenter'))
		{
			include(dirname(__FILE__).'/sql/install.php');
			$res = parent::install() && $this->registerHook('footer') && $this->registerHook('displayHeader') && $this->registerHook('actionModuleInstallAfter') && $this->registerHook('actionAdminControllerSetMedia') && $this->registerHook('actionAdminModulesDeleteAfter') && $this->createTabs() && PaPage::fillTable(true);
		}
		else
		{
			$res = false;
			Context::getContext()->controller->errors[] = $this->l('Cannot install this module because, the Seo Center module is installed yet');
		}
		return $res;
	}

	public function uninstall()
	{
		$res = true;
		if (!Module::isInstalled('paseocenter'))
		{
			include(dirname(__FILE__).'/sql/uninstall.php');
			$res = $this->removeTab();
		}
		return $res && parent::uninstall();
	}

	public function hookActionAdminControllerSetMedia()
	{
		$this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/views/css/icon.css');
	}
	public function hookActionModuleInstallAfter($params)
	{
		$module = $params['object'];
		$files = Meta::getPages();
		foreach ($files as $file) if (strpos($file, 'module-'.$module->name.'-') !== false) DB::getInstance()->insert('paseocenter_pages', array(
			'id_meta' => 0,
			'page' => pSQL($file)
		));
	}

	public function hookActionAdminModulesDeleteAfter()
	{
		$module_name = Tools::getValue('module_name', '');
		$query = new DBQuery();
		$query->select('id_paseocenter_pages');
		$query->from('paseocenter_pages');
		$query->where('page like \'module-'.pSQL($module_name).'-%');
		$id_pages = DB::getInstance()->execute($query);
		DB::getInstance()->delete('paseocenter_pages', 'page like \'module-'.pSQL($module_name).'-%');
		if ($id_pages)
		{
			$p = array();
			foreach ($id_pages as $page) $p[] = (int)$page['id_paseocenter_pages'];
			DB::getInstance()->delete('paseocenter_metas ', ' id_entity IN ('.implode(',', $p).') AND type = '.(int)PaMeta::ENTITY_PAGE);
		}
	}


	public function hookHeader()
	{
		$meta = new PaMeta(self::getFormatedID(), $this->context->language->id);
		if ((Configuration::get('PA_SEO_MARKUP') && (int)$meta->id == 0) || (int)$meta->markup)
		{
			$markup = PaMeta::getMarkup();
			if ($markup)
				$this->context->smarty->assign('markup', $markup);
		}
		$metas = array();

		if (Configuration::get('PA_SEO_GOOGLE_WEBMASTER') && Configuration::get('PA_SEO_GOOGLE_WEBMASTER') != '')
			$metas[] = array(
			'name' => 'google-site-verification',
			'content' => Configuration::get('PA_SEO_GOOGLE_WEBMASTER'),
		);
		if (Configuration::get('PA_SEO_BING_WEBMASTER') && Configuration::get('PA_SEO_BING_WEBMASTER') != '') $metas[] = array(
			'name' => 'msvalidate.01',
			'content' => Configuration::get('PA_SEO_BING_WEBMASTER'),
		);
		if (Configuration::get('PA_SEO_PINT_WEBMASTER') && Configuration::get('PA_SEO_PINT_WEBMASTER') != '') $metas[] = array(
			'name' => 'p:domain_verify',
			'content' => Configuration::get('PA_SEO_PINT_WEBMASTER'),
		);
		if (Configuration::get('PA_SEO_OG_ENABLED'))
		{
			if (self::checkFacebookOGActive() || self::checkTwitterOGActive() || Configuration::get('PA_SEO_OG_ENABLED'))
			{
				if ($meta->og_meta_description != '')
					$meta_desc = $meta->og_meta_description;
				elseif (Configuration::get('PA_SEO_AUTO_OG_DESCRIPTION'))
					$meta_desc = $meta->meta_description;
				else
					$meta_desc = $meta->meta_description;

				if ($meta->og_meta_title != '')
					$meta_title = $meta->og_meta_title;
				elseif (Configuration::get('PA_SEO_AUTO_OG_DESCRIPTION'))
					$meta_title = $meta->meta_title;
				else
					$meta_title = $meta->meta_title;

				$og_metas = array(
					'meta_title' => $meta_title,
					'meta_description' => $meta_desc,
				);

			}
			if (self::checkFacebookOGActive() || Configuration::get('PA_SEO_OG_ENABLED'))
			{
				$metas[] = array(
					'property' => 'og:title',
					'content' => $og_metas['meta_title'],
				);
				$metas[] = array(
					'property' => 'og:image',
					'content' => Context::getContext()->link->getMediaLink(PaMeta::getOpenGraphImage(self::getFormatedID(), true)),
				);
				$metas[] = array(
					'property' => 'og:site_name',
					'content' => Configuration::get('PS_SHOP_NAME'),
				);
				$metas[] = array(
					'property' => 'og:description',
					'content' => $og_metas['meta_description'],
				);

				if (self::checkFacebookOGActive())
				{
					$metas[] = array(
						'property' => 'og:type',
						'content' => $meta->fb_object_type,
					);
				}
				$metas[] = array(
					'property' => 'og:url',
					'content' => PaMeta::getCanonicalURl($this->context->language->id, $meta->type, $meta->id_shop, $meta->id_entity),
				);
			}

			if (self::checkTwitterOGActive())
			{
				$metas[] = array(
					'name' => 'twitter:card',
					'content' => $meta->twt_card,
				);
				$metas[] = array(
					'name' => 'twitter:title',
					'content' => $og_metas['meta_title'],
				);
				$metas[] = array(
					'name' => 'twitter:description',
					'content' => $og_metas['meta_description']
				);
				$metas[] = array(
					'name' => 'twitter:creator',
					'content' => Configuration::get('PA_SEO_TWT_PROFILE')
				);
			}

		}
		$params = array();
		$params['metas'] = $metas;
		$params['ga'] = Configuration::get('PA_SEO_GA_ACTIVE');
		$params['ga_code'] = Configuration::get('PA_SEO_GA');
		$params['pacanonical'] = PaMeta::getCanonicalURl($this->context->language->id, $meta->type, $meta->id_shop, $meta->id_entity);
		$this->context->smarty->assign($params);
		$this->context->smarty->assign('global_script', Configuration::get('PA_SEO_SCRIPTS', $this->context->language->id));
		$this->context->smarty->assign('page_script', $meta->scripts);
		$meta = new PaMeta(self::getFormatedID(), $this->context->language->id);
		$this->context->smarty->assign('canonical', false);
		if (Tools::getValue('p', 0) > 1 && Configuration::get('PA_SEO_PAG_NOINDEX') || $meta->noindex)
			$this->context->smarty->assign('nobots', true);

		if (Tools::getValue('p', 0) > 1 && Configuration::get('PA_SEO_PAG_NOFOLLOW') || $meta->nofollow)
			$this->context->smarty->assign('nofollow', true);

		return $this->display(__FILE__, 'header.tpl');
	}

	public function hookFooter()
	{

	}

	public function hookDisplayHeader()
	{
		$metas = Meta::getMetaTags($this->context->language->id, $this->context->controller->php_self);
		$metas  = PaMeta::getFormatedMetas($metas, $this->context->controller->php_self);
		$this->context->smarty->assign($metas);
		return $this->hookHeader();
	}
	public static function checkTwitterOGActive()
	{
		$context = Context::getContext();
		$controller = $context->controller;
		switch ($controller->php_self)
		{
			case 'product':
				$res = (bool)Configuration::get('PA_SEO_TW_PRODUCT');
				break;
			case 'category':
				$res = (bool)Configuration::get('PA_SEO_TW_CATEGORY');
				break;
			case 'index':
				$res = (bool)Configuration::get('PA_SEO_TW_HOME');
				break;
			case 'cms':
				$res = (bool)Configuration::get('PA_SEO_TW_CMS');
				break;
			case 'cms_category':
				$res = (bool)Configuration::get('PA_SEO_TW_CMS_CAT');
				break;
			case 'manufacturer':
				$res = (bool)Configuration::get('PA_SEO_TW_MAN');
				break;
			case 'supplier':
				$res = (bool)Configuration::get('PA_SEO_TW_SUP');
				break;
			default:
				$res = (bool)Configuration::get('PA_SEO_TW_PAGE');
		}
		return $res;

	}

	public static function checkFacebookOGActive()
	{
		$context = Context::getContext();
		$controller = $context->controller;
		switch ($controller->php_self)
		{
			case 'product':
				$res = (int)Configuration::get('PA_SEO_OG_PRODUCT');
				break;
			case 'category':
				$res = (int)Configuration::get('PA_SEO_OG_CATEGORY');
				break;
			case 'cms':
				$res = (int)Configuration::get('PA_SEO_OG_CMS');
				break;
			case 'index':
				$res = (int)Configuration::get('PA_SEO_OG_HOME');
				break;
//			case 'cms_category':
//				$res = (int)Configuration::get('PA_SEO_OG_CMS_CAT');
//				break;
			case 'manufacturer':
				$res = (int)Configuration::get('PA_SEO_OG_MAN');
				break;
			case 'supplier':
				$res = (int)Configuration::get('PA_SEO_OG_SUP');
				break;
			default:
				$res = (int)Configuration::get('PA_SEO_OG_PAGE');
		}
		return $res;

	}

	public static function getFormatedID()
	{
		$context = Context::getContext();
		$controller = $context->controller;
		if (!in_array($controller->controller_type, array('admin', 'adminmodule')))
		{
			switch ($controller->php_self)
			{
				case 'product':
					$entity = PaMeta::ENTITY_PRODUCT;
					$id_entity = Tools::getValue('id_product');
					break;
				case 'category':
					$entity = PaMeta::ENTITY_CATEGORY;
					$id_entity = Tools::getValue('id_category');
					break;
				case 'cms':
					$entity = PaMeta::ENTITY_CMS;
					$id_entity = Tools::getValue('id_cms');
					break;
				case 'cms_category':
					$entity = PaMeta::ENTITY_CMS_CAT;
					$id_entity = Tools::getValue('id_cms_category');
					break;
				case 'manufacturer':
					$entity = PaMeta::ENTITY_MANUFACTURER;
					$id_entity = Tools::getValue('id_manufacturer');
					break;
				case 'supplier':
					$entity = PaMeta::ENTITY_SUPPLIER;
					$id_entity = Tools::getValue('id_supplier');
					break;
				default:
					$entity = PaMeta::ENTITY_PAGE;
					$id_entity = PaPage::getIdByPageName($controller->php_self);
			}
			return $entity.str_pad($context->shop->id, 3, 0, STR_PAD_LEFT).$id_entity;
		}
		else
			return 0;
	}
}
