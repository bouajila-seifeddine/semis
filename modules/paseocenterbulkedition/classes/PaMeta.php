<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Prestashop Addons.
 * @copyright Prestashop Addons S.L. all rights reserved.
 * @license   http://www.prestashop-addons.com/licenses/license_en.pdf http://www.prestashop-addons.com/licenses/license_es.pdf https://www.prestashop-addons.com/licenses/license_fr.pdf
 */

if (!defined('_PS_VERSION_')) exit;
include_once('PaPage.php');

class PaMeta extends ObjectModel
{
	public static $definition = array(
		'table' => 'paseocenter_metas',
		'primary' => 'id_paseocenter_metas',
		'multilang' => true,
		'fields' => array(
			'id_entity' => array('type' => self::TYPE_INT),
			'type' => array('type' => self::TYPE_INT),
			'id_shop' => array('type' => self::TYPE_INT),
			'markup' => array('type' => self::TYPE_INT),
			'noindex' => array('type' => self::TYPE_INT),
			'twt_card' => array('type' => self::TYPE_STRING),
			'fb_object_type' => array('type' => self::TYPE_STRING),
			'og_image' => array('type' => self::TYPE_INT),
			'canonical' => array(
				'type' => self::TYPE_STRING,
				'lang' => true,
				'validate' => 'isGenericName',
			),
			'og_meta_title' => array(
				'type' => self::TYPE_STRING,
				'lang' => true,
				'validate' => 'isGenericName',
			),
			'og_meta_description' => array(
				'type' => self::TYPE_STRING,
				'lang' => true,
				'validate' => 'isGenericName',
			),
			'og_video' => array(
				'type' => self::TYPE_STRING,
				'lang' => true,
				'validate' => 'isGenericName',
			),
			'scripts' => array(
				'type' => self::TYPE_HTML,
				'lang' => true,

			),
			'nofollow' => array('type' => self::TYPE_INT),
		),
	);
	/** @var string Name */
	public $id_entity;
	public $type;
	public $id_shop;
	public $noindex;
	public $markup;
	public $canonical;
	public $meta_title;
	public $meta_description;
	public $meta_keywords;
	public $link_rewrite;
	public $twt_card;
	public $fb_object_type;
	public $og_image;
	public $og_meta_title;
	public $og_meta_description;
	public $og_video;
	public $nofollow;
	public $scripts;
	/**
	 * @see ObjectModel::$definition
	 */
	const ENTITY_PRODUCT = 1;
	const ENTITY_CATEGORY = 2;
	const ENTITY_MANUFACTURER = 3;
	const ENTITY_SUPPLIER = 4;
	const ENTITY_CMS = 5;
	const ENTITY_STORE = 6;
	const ENTITY_PAGE = 7;
	const ENTITY_CMS_CAT = 9;
	const ENTITY_HOME = 8;

	public function __construct($id = null, $id_lang = null, $id_shop = null)
	{
		$this->customConstruct($id, $id_lang, $id_shop);
		$this->loadMetas();
	}

	public function customConstruct($id = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($this->getPrimaryKeyByIDFormated($id), $id_lang, $id_shop);
		$params = self::getEntitiesByID($id);
		$this->type = $params['type'];
		$this->id_shop = $params['id_shop'];
		$this->id_entity = $params['id_entity'];
		$this->loadMetas();
	}

	public static function getEntitiesByID($id)
	{
		return array(
			'type' => (int)Tools::substr($id, 0, 1),
			'id_shop' => (int)Tools::substr($id, 1, 3),
			'id_entity' => (int)Tools::substr($id, 4),
		);
	}

	public function getPrimaryKeyByIDFormated($id)
	{
		if ((int)$id == 0) return 0;
		$params = self::getEntitiesByID($id);
		$this->type = $params['type'];
		$this->id_shop = $params['id_shop'];
		$this->id_entity = $params['id_entity'];
		$query = new DbQuery();
		$query->select(self::$definition['primary']);
		$query->from(self::$definition['table']);
		$query->where('id_entity = '.(int)$this->id_entity);
		$query->where('type = '.(int)$this->type);
		$query->where('id_shop = '.(int)$this->id_shop);
		return (int)DB::getInstance()->getValue($query);
	}

	public function loadMetas()
	{
		switch ($this->type)
		{
			case self::ENTITY_PRODUCT:
				$item = new Product($this->id_entity, true, $this->id_lang, $this->id_shop);
				if ((string)$this->twt_card == '') $this->twt_card = Configuration::get('PA_SEO_TW_PRODUCT_TYPE');
				if ((string)$this->fb_object_type == '') $this->fb_object_type = Configuration::get('PA_SEO_OG_PRODUCT_TYPE');
				break;
			case self::ENTITY_CATEGORY:
				$item = new Category($this->id_entity, $this->id_lang, $this->id_shop);
				if ((string)$this->twt_card == '') $this->twt_card = Configuration::get('PA_SEO_TW_CATEGORY_TYPE');
				if ((string)$this->fb_object_type == '') $this->fb_object_type = Configuration::get('PA_SEO_OG_CATEGORY_TYPE');
				break;
			case self::ENTITY_CMS:
				$item = new CMS($this->id_entity, $this->id_lang, $this->id_shop);
				if ((string)$this->twt_card == '') $this->twt_card = Configuration::get('PA_SEO_TW_CMS_TYPE');
				if ((string)$this->fb_object_type == '') $this->fb_object_type = Configuration::get('PA_SEO_OG_CMS_TYPE');
				break;
			case self::ENTITY_SUPPLIER:
				$item = new Supplier($this->id_entity, $this->id_lang, $this->id_shop);
				if ((string)$this->twt_card == '') $this->twt_card = Configuration::get('PA_SEO_TW_SUPPLIER_TYPE');
				if ((string)$this->fb_object_type == '') $this->fb_object_type = Configuration::get('PA_SEO_OG_SUPPLIER_TYPE');
				break;
			case self::ENTITY_MANUFACTURER:
				$item = new Manufacturer($this->id_entity, $this->id_lang, $this->id_shop);
				if ((string)$this->twt_card == '') $this->twt_card = Configuration::get('PA_SEO_TW_MANUFACTURER_TYPE');
				if ((string)$this->fb_object_type == '') $this->fb_object_type = Configuration::get('PA_SEO_OG_MANUFACTURER_TYPE');
				break;
//			case self::ENTITY_CMS_CAT:
//				$item = new CMSCategory($this->id_entity, $this->id_lang, $this->id_shop);
//				if ((string)$this->twt_card == '') $this->twt_card = Configuration::get('PA_SEO_TW_CMS_CAT_TYPE');
//				if ((string)$this->fb_object_type == '') $this->fb_object_type = Configuration::get('PA_SEO_OG_CMS_CAT_TYPE');
//				break;
			default:
				$item = new PaPage($this->id_entity, $this->id_lang, $this->id_shop);
		}

		if ($this->id_lang)
		{
			$this->meta_title = ((string)$item->meta_title != '') ? $item->meta_title : (isset($item->name) ? $item->name : '');
			if ((string)$item->meta_description != '')
				$this->meta_description = $item->meta_description;
			else
				if (isset($item->description))
					$this->meta_description = Tools::substr(strip_tags($item->description), 0, 150);
				else
					$this->meta_description = '';
		}
		else
		{
			if (is_array($item->meta_title))
				$this->meta_title = $item->meta_title;
			else
				if (isset($item->name) && is_array($item->name))
					$this->meta_title = $item->name;
				else
					$this->meta_title = array();
			if ((is_array($item->meta_description)))
				$this->meta_description = $item->meta_description;
			else
				if (isset($item->description) && is_array($item->description))
					$this->meta_description = Tools::substr(strip_tags($item->description), 0, 150);
				else
					$this->meta_description = array();
		}
		$this->meta_keywords = $item->meta_keywords;
		$this->link_rewrite = $item->link_rewrite;
		$context = Context::getContext();
		$this->og_image = $context->link->getMediaLink(self::getOpenGraphImage(self::getFormattedID(), true, Configuration::get('PA_SEO_OG_IMG_SIZE')));
		if ($this->og_meta_title == null && Validate::isLoadedObject($item) && Configuration::get('PA_SEO_AUTO_OG_DESCRIPTION'))
			$this->og_meta_title = $item->meta_title;
		if ($this->og_meta_description == null && Validate::isLoadedObject($item) && Configuration::get('PA_SEO_AUTO_OG_DESCRIPTION'))
			$this->og_meta_description = $item->meta_description;
	}

	public function saveMetas()
	{
		switch ($this->type)
		{
			case self::ENTITY_PRODUCT:
				$item = new Product($this->id_entity, true, null, $this->id_shop);
				break;
			case self::ENTITY_CATEGORY:
				$item = new Category($this->id_entity, null, $this->id_shop);
				break;
			case self::ENTITY_CMS:
				$item = new CMS($this->id_entity, null, $this->id_shop);
				break;
			case self::ENTITY_SUPPLIER:
				$item = new Supplier($this->id_entity, null, $this->id_shop);
				break;
			case self::ENTITY_MANUFACTURER:
				$item = new Manufacturer($this->id_entity, null, $this->id_shop);
				break;
			default:
				$item = new PaPage($this->id_entity, null, $this->id_shop);
				break;
		}
		$item->meta_title = $this->meta_title;
		$item->meta_description = $this->meta_description;
		$item->meta_keywords = $this->meta_keywords;
		$item->link_rewrite = $this->link_rewrite;
		return $item->save();
	}

	public function add($autodate = true, $null_values = false, $full = true)
	{
		$res = parent::add($autodate, $null_values);
		if ($res && $full)
			$res = $this->saveMetas();
		return $res;
	}

	public function update($null_values = false, $full = true)
	{
		if (!Validate::isLoadedObject($this))
			$res = parent::add(false, $null_values, $full);
		else
		{
			$res = parent::update($null_values);
			if ($res && $full) $res = $this->saveMetas();
		}
		return $res;
	}

	public function save($null_value = false, $autodate = true, $full = true)
	{
		return ((int)$this->id == 0) ? $this->add($autodate, $null_value, $full) : $this->update($null_value, $full);
	}

	public function delete()
	{
		return false;
	}

	public static function getValidationRules($classname = __CLASS__)
	{
		$fields = array(
			'meta_description' => array(
				'type' => ObjectModel::TYPE_STRING,
				'lang' => true,
				'validate' => 'isGenericName',
				'size' => 255,
			),
			'meta_keywords' => array(
				'type' => ObjectModel::TYPE_STRING,
				'lang' => true,
				'validate' => 'isGenericName',
				'size' => 255
			),
			'meta_title' => array(
				'type' => ObjectModel::TYPE_STRING,
				'lang' => true,
				'validate' => 'isGenericName',
				'size' => 128
			),
			'link_rewrite' => array(
				'type' => ObjectModel::TYPE_STRING,
				'lang' => true,
				'validate' => 'isLinkRewrite',
				'size' => 128,
			),
		);
		$out = parent::getValidationRules($classname);
		$out['fields'] = array_merge(self::$definition['fields'], $fields);

		return $out;
	}

	public static function getStaticImgPath($id, $relative_path = false)
	{
		if (!$relative_path) $path = _PS_IMG_DIR_;
		else
			$path = _PS_IMG_;
		$vars = preg_split('//', $id, -1, PREG_SPLIT_NO_EMPTY);
		$path .= 'og/';

		if (!$relative_path) $path .= implode(DIRECTORY_SEPARATOR, $vars);
		else
			$path .= implode('/', $vars);

		if (!$relative_path && !is_dir($path))
			mkdir($path, 0777, true);

		return $path;
	}

	public static function getOpenGraphImage($id, $relative_path = false, $type = false)
	{
		$params = self::getEntitiesByID($id);
		$path = false;
		switch ($params['type'])
		{
			case self::ENTITY_PRODUCT:
				$cover = Product::getCover($params['id_entity'], Context::getContext());
				$img = new Image($cover['id_image']);
				if (Validate::isLoadedObject($img))
					$path = 'p/'.$img->getImgPath().'.'.$img->image_format;
				break;
			case self::ENTITY_CATEGORY:
				$path = 'c/'.$params['id_entity'].'.jpg';
				break;
			case self::ENTITY_MANUFACTURER:
				$path = 'm/'.$params['id_entity'].'.jpg';
				break;
			case self::ENTITY_SUPPLIER:
				$path = 's/'.$params['id_entity'].'.jpg';
				break;
		}
		if (file_exists(self::getStaticImgPath($id, false, $type).'/'.$id.'.jpg'))
			$url = self::getStaticImgPath($id, $relative_path, $type).'/'.$id.'.jpg';
		elseif ($path && file_exists(_PS_IMG_DIR_.$path))
			$url = (($relative_path) ? _PS_IMG_ : _PS_IMG_DIR_).$path;
		elseif (Configuration::get('PA_SEO_OG_HOME_LOGO'))
			$url = (($relative_path) ? _PS_IMG_ : _PS_IMG_DIR_).Configuration::get('PS_LOGO');
		elseif (Configuration::get('PA_SEO_OG_USE_DEFAULT_IMG'))
			$url = (($relative_path) ? _MODULE_DIR_ : _PS_MODULE_DIR_).'paseocenterbulkedition/views/img/og/default.jpg';
		else
			$url = '';
		return $url;
	}

	public static function getMarkup()
	{
		$context = Context::getContext();
		switch ($context->controller->php_self)
		{
			case 'index':
				$out = self::getIndexMarkup();
				break;
			case 'product':
				$out = self::getProductMarkup();
				break;
			case 'category':
				$out = self::getCategoryMarkup();
				break;
			case 'manufacturer':
				$out = self::getIndexMarkup();
				break;
			case 'cms':
				$out = self::getIndexMarkup();
				break;
			case 'supplier':
				$out = self::getIndexMarkup();
				break;
			default:
				return false;
		}
		return Tools::jsonEncode($out);
	}

	public static function getCategoryMarkup()
	{
		$category = new Category((int)Tools::getValue('id_category'), Context::getContext()->language->id);
		$metas = Meta::getCategoryMetas($category->id, Context::getContext()->language->id, 'category');
		$markup = array(
			'@context' => 'http://schema.org/',
			'@type' => 'Thing',
			'name' => self::parseMetaTitle($metas['meta_title'], self::ENTITY_CATEGORY),
			'description' => self::parseMetaTitle($metas['meta_description'], self::ENTITY_CATEGORY),
			'image' => Context::getContext()->link->getMediaLink(self::getOpenGraphImage($category->id, true, self::ENTITY_CATEGORY)),
			'url' => Context::getContext()->link->getCategoryLink($category->id, null, Context::getContext()->language->id),

		);
		return $markup;
	}

	public static function getIndexMarkup()
	{
		$currencies = Currency::getCurrencies();
		$currency = array();
		foreach ($currencies as $curr) $currency[] = $curr['iso_code'];
		$metas = Meta::getHomeMetas(Context::getContext()->language->id, 'index');
		$metas = self::getFormatedMetas($metas, 'index');
		$context = Context::getContext();
		$logo = $context->link->getMediaLink(self::getOpenGraphImage(self::getFormattedID(), true, Configuration::get('PA_SEO_OG_IMG_SIZE')));
		$markup = array(
			'@context' => 'http://schema.org/',
			'@type' => 'ShoppingCenter',
			'currenciesAccepted' => implode(',', $currency),
			'email' => Configuration::get('PS_SHOP_EMAIL'),
			'brand' => Configuration::get('PS_SHOP_NAME'),
			'description' => $metas['meta_description'],
			'name' => self::parseMetaTitle($metas['title'], self::ENTITY_HOME),
			'url' => self::getCanonicalURl($context->language->id),
			'logo' => $logo,
		);
		return $markup;
	}

	public static function getCanonicalURl($id_lang, $type = null, $id_shop = null, $id_entity = null)
	{
		$meta = new PaMeta(self::getFormattedID($type, $id_shop, $id_entity), $id_lang, $id_shop);
		if (!$meta->canonical)
		{
			$link = new Link();
			switch ($meta->type)
			{
				case self::ENTITY_PRODUCT:
					$object = new Product($meta->id_entity, false, $id_lang, $meta->id_shop);
					$meta->canonical = $link->getProductLink($object);
					break;
				case self::ENTITY_CATEGORY:
					$object = new Category($meta->id_entity, $id_lang, $meta->id_shop);
					$meta->canonical = $link->getCategoryLink($object);
					break;
				case self::ENTITY_CMS:
					$object = new CMS($meta->id_entity, $id_lang, $meta->id_shop);
					$meta->canonical = $link->getCMSLink($object);
					break;
//				case self::ENTITY_CMS_CAT:
//					$meta->canonical = $link->getCMSCategoryLink(new CMSCategory($meta->id_entity, $id_lang, $meta->id_shop));
//					break;
				case self::ENTITY_MANUFACTURER:
					$object = new Manufacturer($meta->id_entity, $id_lang, $meta->id_shop);
					$meta->canonical = $link->getManufacturerLink($object);
					break;
				case self::ENTITY_SUPPLIER:
					$object = new Supplier($meta->id_entity, $id_lang, $meta->id_shop);
					$meta->canonical = $link->getSupplierLink($object);
					break;
				default:
					if (Context::getContext()->controller->controller_type == 'fc')
					{
						$controller = Context::getContext()->controller->controller_type;
						$args = explode('-', $controller);
						if (isset($args[1]))
							$module = $args[1];
						else
							$module = null;

						if ((isset($args[2])))
							$module_controller = $args[2];
						else
							$module_controller = null;

						$meta->canonical = $link->getModuleLink($module, $module_controller, null, $id_lang, $meta->id_shop);
					}
					elseif (Context::getContext()->controller->controller_type == 'front')
						$meta->canonical = $link->getPageLink(Context::getContext()->controller->php_self, null, $id_lang, null, null, $meta->id_shop);
					else
						$meta->canonical = '';
			}
		}
		return $meta->canonical;
	}

	public static function getFormattedID($type = null, $id_shop = null, $id_entity = null)
	{
		$controller_type = isset(Context::getContext()->controller->php_self) ? Context::getContext()->controller->php_self : Context::getContext()->controller->controller_type;
		if (!$type)
		{
			switch ($controller_type)
			{
				case 'product':
					$type = self::ENTITY_PRODUCT;
					break;
				case 'category':
					$type = self::ENTITY_CATEGORY;
					break;
				case 'cms':
					$type = self::ENTITY_CMS;
					break;
//				case 'cms_category':
//					$type = self::ENTITY_CMS_CAT;
//					break;
				case 'manufacturer':
					$type = self::ENTITY_MANUFACTURER;
					break;
				case 'supplier':
					$type = self::ENTITY_SUPPLIER;
					break;
				case 'store':
					$type = self::ENTITY_STORE;
					break;
				default:
					$type = self::ENTITY_PAGE;
					break;
			}
		}
		if (!$id_entity)
		{
			switch ($id_entity)
			{
				case self::ENTITY_PRODUCT:
					$id_entity = Tools::getValue('id_product');
					break;
				case self::ENTITY_CATEGORY:
					$id_entity = Tools::getValue('id_category');
					break;
				case self::ENTITY_CMS:
					$id_entity = Tools::getValue('id_cms');
					break;
//				case self::ENTITY_CMS_CAT:
//					$id_entity = Tools::getValue('id_cms_category');
//					break;
				case self::ENTITY_SUPPLIER:
					$id_entity = Tools::getValue('id_supplier');
					break;
				case self::ENTITY_MANUFACTURER:
					$id_entity = Tools::getValue('id_manufacturer');
					break;
				default:
					$id_entity = PaPage::getIdByPageName($controller_type);
			}
		}
		if (!$id_shop) $id_shop = Context::getContext()->shop->id;
		return $type.str_pad(((!$id_shop) ? Context::getContext()->shop->id : $id_shop), 3, 0, STR_PAD_LEFT).$id_entity;
	}

	public static function getCMSMarkup()
	{
		$metas = Meta::getCmsMetas(Tools::getValue('id_cms', 0), Context::getContext()->language->id, Tools::getValue('page_name', ''));
		$img = Context::getContext()->link->getMediaLink(self::getOpenGraphImage(self::getFormattedID(), true, Configuration::get('PA_SEO_OG_IMG_SIZE')));
		$markup = array(
			'@context' => 'http://schema.org/',
			'@type' => 'Article',
			'author' => Configuration::get('PS_SHOP_NAME'),
			'creator' => Configuration::get('PS_SHOP_NAME'),
			'about' => self::parseMetaTitle($metas['meta_title'], self::ENTITY_CMS),
			'name' => self::parseMetaTitle($metas['meta_title'], self::ENTITY_CMS),
			'description' => $metas['meta_description'],
			'articleBody' => $metas['meta_description'],
			'url' => self::getCanonicalURl(Context::getContext()->language->id),
			'image' => $img,
		);
		return $markup;
	}

	public static function getPageMarkup()
	{
		$metas = Meta::getHomeMetas(Context::getContext()->language->id, Context::getContext()->controller->php_self);
		$markup = array(
			'@context' => 'http://schema.org/',
			'@type' => 'WebSite',
			'brand' => Configuration::get('PS_SHOP_NAME'),
			'description' => $metas['meta_description'],
			'name' => self::parseMetaTitle($metas['meta_title'], self::ENTITY_PAGE),
			'url' => Context::getContext()->link->getPageLink('index'),
			'logo' => Context::getContext()->link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO')),
		);
		return $markup;
	}

	public static function getManufacturerMarkup()
	{
		$context = Context::getContext();
		$metas = Meta::getManufacturerMetas((int)Tools::getValue('id_manufacturer'), $context->language->id, 'manufacturer');
		$id_cms_category = Tools::getValue('id_cms_category', 0);
		$product_list = Manufacturer::getProducts($id_cms_category, $context->language->id, 1, Configuration::get('PS_PRODUCTS_PER_PAGE'));
		$img = $context->link->getMediaLink(self::getOpenGraphImage(self::getFormattedID(), true, Configuration::get('PA_SEO_OG_IMG_SIZE')));
		$markup = array(
			'@context' => 'http://schema.org/',
			'@type' => array(
				'ItemList',
				'Product'
			),
			'name' => self::parseMetaTitle($metas['meta_title'], self::ENTITY_MANUFACTURER),
			'author' => Configuration::get('PS_SHOP_NAME'),
			'creator' => Configuration::get('PS_SHOP_NAME'),
			'about' => $metas['meta_description'],
			'url' => self::getCanonicalURl(Context::getContext()->language->id),
			'image' => $img,
			'listItemOrder' => 'http://schema.org/ItemListOrderAscending',
		);
		if ($product_list)
			foreach ($product_list as $product)
				$markup['itemListElement'][] = self::getProductMarkup($product['id_product']);
		return $markup;
	}

	public static function getSupplierMarkup()
	{
		$currencies = Currency::getCurrencies();
		$currency = array();
		foreach ($currencies as $curr) $currency[] = $curr['iso_code'];
		$metas = Meta::getSupplierMetas((int)Tools::getValue('id_supplier'), Context::getContext()->language->id, 'supplier');
		$product_list = Supplier::getProducts((int)Tools::getValue('id_supplier'), Context::getContext()->language->id, 0, 10);
		$img = Context::getContext()->link->getMediaLink(self::getOpenGraphImage(self::getFormattedID(), true, Configuration::get('PA_SEO_OG_IMG_SIZE')));
		$markup = array(
			'@context' => 'http://schema.org/',
			'@type' => 'ShoppingCenter',
			'currenciesAccepted' => implode(',', $currency),
			'brand' => Configuration::get('PS_SHOP_NAME'),
			'name' => self::parseMetaTitle($metas['meta_title'], self::ENTITY_SUPPLIER),
			'email' => Configuration::get('PS_SHOP_EMAIL'),
			'description' => $metas['meta_description'],
			'url' => self::getCanonicalURl(Context::getContext()->language->id),
			'image' => $img,
			'listItemOrder' => 'http://schema.org/ItemListOrderAscending',
		);
		if ($product_list)
			foreach ($product_list as $product)
				$markup['itemListElement'][] = self::getProductMarkup($product['id_product']);
		return $markup;
	}

	public static function getProductMarkup($id_product = null)
	{
		if (!$id_product)
			$id_product = Tools::getValue('id_product', 0);
		$product = new Product($id_product, true, Context::getContext()->language->id, Context::getContext()->shop->id);
		$cover = Product::getCover($product->id);
		$img = new Image($cover['id_image']);
		$link = new Link();
		$image_link = $link->getImageLink($product->meta_title, $img->id);
		$category = new CategoryCore($product->id_category_default, Context::getContext()->language->id);
		$markup = array(
			'@context' => 'http://schema.org',
			'@type' => 'Product',
			'name' => self::parseMetaTitle(($product->meta_title) ? $product->meta_title : $product->name, self::ENTITY_PRODUCT),
			'brand' => Configuration::get('PS_SHOP_NAME'),
			'logo' => Context::getContext()->link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO')),
			'image' => $image_link,
			'category' => array(
				'@context' => 'http://schema.org',
				'@type' => 'Thing',
				'url' => $link->getCategoryLink($category->id, null, Context::getContext()->language->id),
				'name' => $category->name,
			),
		);
		if ($product->id_manufacturer > 0)
			$markup['manufacturer'] = Manufacturer::getNameById($product->id_manufacturer);
		if ($product->width > 0)
			$markup['width'] = $product->width;
		if ($product->depth > 0)
			$markup['depth'] = $product->depth;
		if ($product->height > 0)
			$markup['height'] = $product->height;
		if ($product->reference != '')
			$markup['sku'] = $product->reference;
		if ($product->ean13 != '')
			$markup['gtin13'] = $product->ean13;

		$currency = Currency::getCurrent();
		$markup['offers'] = array(
			'price' => Tools::ps_round($product->getPrice()),
			'priceCurrency' => $currency->iso_code,
		);
		return $markup;
	}

	public static function parseMetaTitle($title)
	{
		return $title;
	}

	public static function find($id_lang, $type, $id_shop, $expr)
	{
		$db = Db::getInstance(_PS_USE_SQL_SLAVE_);
		$sql = '';
		switch ($type)
		{
			case self::ENTITY_PRODUCT :
				$sql = 'SELECT * FROM (SELECT DISTINCT CONCAT('.self::ENTITY_PRODUCT.',LPAD(b.id_shop,3,\'0\'),c.id_product) as id,
                    b.id_shop as id_shop, c.name,c.meta_title, c.meta_description,c.meta_keywords
                    , '.PaMeta::ENTITY_PRODUCT.' as type
                    FROM '._DB_PREFIX_.'product_lang c
                    INNER JOIN '._DB_PREFIX_.'product_shop b ON b.id_product = c.id_product
                    AND c.id_lang = '.(int)$id_lang.' AND b.id_shop = '.(int)$id_shop.'
                   ) as a  WHERE  1 ';
				break;
			case self::ENTITY_CATEGORY:
				$sql .= 'SELECT * FROM (SELECT DISTINCT CONCAT('.PaMeta::ENTITY_CATEGORY.', LPAD(b.id_shop,3,\'0\'),c.id_category) as id,
                    b.id_shop as id_shop, c.name, c.meta_title, c.meta_description,
                    '.PaMeta::ENTITY_CATEGORY.' as type,
                    INNER JOIN '._DB_PREFIX_.'category_shop b ON c.id_category = b.id_category
                    AND c.id_lang = '.(int)$id_lang.' AND b.id_shop = '.(int)$id_shop.'
                    ) a WHERE 1 ';
				break;
			case self::ENTITY_CMS :
				$sql .= 'SELECT * FROM (SELECT DISTINCT CONCAT('.PaMeta::ENTITY_CMS.',LPAD(b.id_shop,3,\'0\'),c.id_cms) as id,
                    b.id_shop as id_shop, c.meta_title as name, c.meta_title, c.meta_description,
                    FROM '._DB_PREFIX_.'cms_lang c
                    INNER JOIN '._DB_PREFIX_.'cms_shop b ON c.id_cms = b.id_cms
                    AND c.id_lang = '.(int)$id_lang.' AND b.id_shop = '.(int)$id_shop.'
                    ) a WHERE 1 ';
				break;
			case self::ENTITY_STORE :
				$sql = '';
				break;
			case self::ENTITY_SUPPLIER:
				$sql .= 'SELECT * FROM (SELECT DISTINCT CONCAT( '.PaMeta::ENTITY_SUPPLIER.' ,LPAD(b.id_shop,3,\'0\'),d.id_supplier) as id,
                    b.id_shop as id_shop, c.name, d.meta_title, d.meta_description,d.meta_keywords,
                    '.PaMeta::ENTITY_SUPPLIER.' as type
                    INNER JOIN '._DB_PREFIX_.'supplier c ON c.id_supplier = d.id_supplier
                    AND d.id_lang = '.(int)$id_lang.' AND b.id_shop = '.(int)$id_shop.'
                    INNER JOIN '._DB_PREFIX_.'supplier_shop b ON d.id_supplier = b.id_supplier
                    ) a WHERE 1 ';
				break;
			case self::ENTITY_MANUFACTURER :
				$sql .= 'SELECT * FROM (SELECT DISTINCT CONCAT('.PaMeta::ENTITY_MANUFACTURER.',LPAD(b.id_shop,3,\'0\'),b.id_manufacturer) as id,
                    b.id_shop as id_shop, c.name, d.meta_title, d.meta_description,d.meta_keywords,
                    '.PaMeta::ENTITY_MANUFACTURER.' as type INNER JOIN '._DB_PREFIX_.'manufacturer c ON c.id_manufacturer = d.id_manufacturer
                    AND d.id_lang = '.(int)$id_lang.' AND b.id_shop = '.(int)$id_shop.'
                    INNER JOIN '._DB_PREFIX_.'manufacturer_shop b ON d.id_manufacturer = b.id_manufacturer
                    ) a WHERE 1 ';
				break;
			case self::ENTITY_STORE:
				$sql = '';
				break;
			case self::ENTITY_PAGE:
				$sql = '';
				break;
			case self::ENTITY_CMS_CAT:
				$sql .= 'SELECT * FROM (SELECT DISTINCT CONCAT('.PaMeta::ENTITY_CMS_CAT.',LPAD(b.id_shop,3,\'0\'),c.id_cms_category) as id,
                    b.id_shop as id_shop, c.meta_title as name, c.meta_title, c.meta_description,
                    c.meta_keywords, '.PaMeta::ENTITY_CMS_CAT.' as type
                    FROM '._DB_PREFIX_.'cms_category_lang c
                    INNER JOIN '._DB_PREFIX_.'cms_category_shop b ON  c.id_cms_category = b.id_cms_category
                    AND c.id_lang = '.(int)$id_lang.' AND b.id_shop = '.(int)$id_shop.'
                    ) a WHERE 1 ';
				break;
		}
		$sql .= ' AND (name like \'%'.pSQL($expr).'%\' OR meta_description like \'%'.pSQL($expr).'%\' OR meta_title like \'%'.pSQL($expr).'%\')';
		return $db->executeS($sql);
	}

	public static function getFormatedMetas($metas, $page_name)
	{
		if (!isset($metas['meta_description']) || $metas['meta_description'] == '')
			$metas['meta_description'] = (isset($metas['description']) && $metas['description']) ? $metas['description'] : '';

		if (!isset($metas['meta_keywords']) || $metas['meta_keywords'] == '')
			$metas['meta_keywords'] = (isset($metas['keywords']) && $metas['keywords']) ? $metas['keywords'] : '';
		switch ($page_name)
		{
			case 'product':
				$format = (string)Configuration::get('PA_SEO_PROD_TITLE_FORMAT');
				$format_desc = (string)Configuration::get('PA_SEO_PROD_DESC_FORMAT');
				$capitalize = (int)Configuration::get('PA_SEO_CAPITALIZE_PROD_TITLE');
				self::getProducTagFormatted($format, $format_desc);
				break;
			case 'index':
				$format = (string)Configuration::get('PA_SEO_HOME_TITLE_FORMAT');
				$format_desc = (string)Configuration::get('PA_SEO_HOME_DESC_FORMAT');
				$capitalize = (int)Configuration::get('PA_SEO_CAPITALIZE_HOME_TITLE');
				break;
			case 'category':
				$format = (string)Configuration::get('PA_SEO_CAT_TITLE_FORMAT');
				$format_desc = (string)Configuration::get('PA_SEO_CAT_DESC_FORMAT');
				$capitalize = (int)Configuration::get('PA_SEO_CAPITALIZE_CAT_TITLE');
				self::getCategoryTagFormatted($format, $format_desc);
				break;

			case 'manufacturer':
				if (Tools::getValue('id_manufacturer'))
				{
					$format = (string)Configuration::get('PA_SEO_MAN_TITLE_FORMAT');
					$format_desc = (string)Configuration::get('PA_SEO_MAN_DESC_FORMAT');
					$capitalize = (int)Configuration::get('PA_SEO_CAPITALIZE_MAN_TITLE');
					self::getManufacturerTagFormatted($format, $format_desc);
						break;
				}

			case 'supplier':
				if (Tools::getValue('id_supplier'))
				{
					$format = (string)Configuration::get('PA_SEO_SUP_TITLE_FORMAT');
					$format_desc = (string)Configuration::get('PA_SEO_SUP_DESC_FORMAT');
					$capitalize = (int)Configuration::get('PA_SEO_CAPITALIZE_SUP_TITLE');
					self::getSupplierTagFormatted($format, $format_desc);
					break;
				}

			case 'cms':
				if (Tools::getValue('id_cms'))
				{
					$format = (string)Configuration::get('PA_SEO_CMS_TITLE_FORMAT');
					$format_desc = (string)Configuration::get('PA_SEO_CMS_DESC_FORMAT');
					$capitalize = (int)Configuration::get('PA_SEO_CAPITALIZE_CMS_TITLE');
					self::getCMSTagFormatted($format, $format_desc);
					break;
				}

			default:
				$format = (string)Configuration::get('PA_SEO_PAGE_TITLE_FORMAT');
				$format_desc = (string)Configuration::get('PA_SEO_PAGE_DESC_FORMAT');
				$capitalize = (int)Configuration::get('PA_SEO_CAPITALIZE_PAGE_TITLE');
		}

		self::getCommonsTagFormatted($metas, $format, $format_desc);

		if ($capitalize)
			$format = Tools::ucfirst($format);

		$metas['meta_title'] = ($format != null ? $format : (isset($metas['meta_title']) ? $metas['meta_title'] : ''));
		$metas['meta_description'] = ($format_desc != null ? $format_desc : (isset($metas['meta_description']) ? $metas['meta_description'] : ''));
		$metas['title'] = ($format != null ? $format : (isset($metas['title']) ? $metas['title'] : (isset($metas['name']) ? $metas['name'] : '')));
		return $metas;
	}

	public static function getCommonsTagFormatted($metas, &$format, &$format_desc)
	{
		if (Tools::strpos($format, '%shop_name%') !== false)
			$format = Tools::str_replace_once('%shop_name%', Configuration::get('PS_SHOP_NAME'), $format);

		if (Tools::strpos($format_desc, '%shop_name%') !== false)
			$format_desc = Tools::str_replace_once('%shop_name%', Configuration::get('PS_SHOP_NAME'), $format_desc);

		if (Tools::strpos($format, '%meta_title%') !== false)
			if (isset($metas['meta_title']))
				$format = Tools::str_replace_once('%meta_title%', $metas['meta_title'], $format);
			else
				$format = Tools::str_replace_once('%meta_title%', $metas['title'], $format);

		if (Tools::strpos($format, '%meta_title%') !== false)
			if (isset($metas['meta_title']))
				$format = Tools::str_replace_once('%meta_title%', $metas['meta_title'], $format);
			else
				$format = Tools::str_replace_once('%meta_title%', $metas['title'], $format);

		if (Tools::strpos($format_desc, '%meta_title%') !== false)
			if (isset($metas['meta_title']))
				$format_desc = Tools::str_replace_once('%meta_title%', $metas['meta_title'], $format_desc);
			else
				$format_desc = Tools::str_replace_once('%meta_title%', $metas['title'], $format_desc);

		if (Tools::strpos($format, '%meta_description%') !== false && isset($metas['meta_description']))
			$format = Tools::str_replace_once('%meta_description%', $metas['meta_description'], $format);

		if (Tools::strpos($format_desc, '%meta_description%') !== false && isset($metas['meta_description']))
			$format_desc = Tools::str_replace_once('%meta_description%', $metas['meta_description'], $format_desc);

		if (Tools::strpos($format, '%meta_keywords%') !== false && isset($metas['meta_keywords']))
			$format = Tools::str_replace_once('%meta_keywords%', $metas['meta_keywords'], $format);

		if (Tools::strpos($format_desc, '%meta_keywords%') !== false && isset($metas['meta_keywords']))
			$format_desc = Tools::str_replace_once('%meta_keywords%', $metas['meta_keywords'], $format_desc);
	}

	public static function getProducTagFormatted(&$format, &$format_desc)
	{
		$product = new Product(Tools::getValue('id_product'), true, Context::getContext()->language->id);

		if (!Validate::isLoadedObject($product))
			return;
		$product_category =	Tools::strpos($format, '%product_category%');
		$product_category_desc = Tools::strpos($format_desc, '%product_category%');
		$category_breadcrumb = Tools::strpos($format_desc, '%category_breadcrumb%');
		$category_breadcrumb_desc = Tools::strpos($format, '%category_breadcrumb%');

		if (($product_category !== false || $product_category_desc !== false) || $category_breadcrumb !== false || $category_breadcrumb_desc !== false)
		{
				$category = new Category ($product->id_category_default, Context::getContext()->language->id);
				if (Validate::isLoadedObject($category))
				{
					if (Tools::strpos($format, '%product_category%') !== false || Tools::strpos($format_desc, '%product_category%') !== false)
					{
						$format = Tools::str_replace_once('%product_category%', $category->name, $format);
						$format_desc = Tools::str_replace_once('%product_category%', $category->name, $format_desc);
					}

					if (Tools::strpos($format, '%category_breadcrumb%') !== false || Tools::strpos($format_desc, '%category_breadcrumb%') !== false)
					{
						$parents = $category->getParentsCategories(Context::getContext()->language->id);
						$breadcrumb = array();
						$home_cat = Configuration::get('PS_HOME_CATEGORY');
						if ($parents)
							foreach ($parents as $parent)
								if ($parent['id_category'] != $home_cat)
									$breadcrumb[] = $parent['name'];

						$breadcrumb = implode(' > ', array_reverse($breadcrumb));

						$format = Tools::str_replace_once('%category_breadcrumb%', $breadcrumb, $format);
						$format_desc = Tools::str_replace_once('%category_breadcrumb%', $breadcrumb, $format_desc);
					}
				}
		}

		if (Tools::strpos($format, '%product_name%') !== false || Tools::strpos($format_desc, '%product_name%') !== false)
		{
			$format = Tools::str_replace_once('%product_name%', $product->name, $format);
			$format_desc = Tools::str_replace_once('%product_name%', $product->name, $format_desc);
		}

		if (Tools::strpos($format, '%product_description_short%') !== false || Tools::strpos($format_desc, '%product_description_short%') !== false)
		{
			$format = Tools::str_replace_once('%product_description_short%', strip_tags($product->description_short), $format);
			$format_desc = Tools::str_replace_once('%product_description_short%', strip_tags($product->description_short), $format_desc);
		}

		if (Tools::strpos($format, '%product_description%') !== false || Tools::strpos($format_desc, '%product_description%') !== false)
		{
			$format = Tools::str_replace_once('%product_description%', strip_tags($product->description), $format);
			$format_desc = Tools::str_replace_once('%product_description%', strip_tags($product->description), $format_desc);
		}

		if (Tools::strpos($format, '%price%') !== false || Tools::strpos($format_desc, '%price%') !== false)
		{
			$price = $product->getPrice(false);
			$format = Tools::str_replace_once('%price%', Tools::displayPrice($price), $format);
			$format_desc = Tools::str_replace_once('%price%', Tools::displayPrice($price), $format_desc);
		}

		if (Tools::strpos($format, '%price_with_tax%') !== false || Tools::strpos($format_desc, '%price_with_tax%') !== false)
		{
			$price = $product->getPrice(true);
			$format = Tools::str_replace_once('%price_with_tax%', Tools::displayPrice($price), $format);
			$format_desc = Tools::str_replace_once('%price_with_tax%', Tools::displayPrice($price), $format_desc);
		}

		if (Tools::strpos($format, '%product_id%') !== false || Tools::strpos($format_desc, '%product_id%') !== false)
		{
			$format = Tools::str_replace_once('%product_id%', $product->id, $format);
			$format_desc = Tools::str_replace_once('%product_id%', $product->id, $format_desc);
		}

	}
	public static function getCategoryTagFormatted(&$format, &$format_desc)
	{
		$id_category = Tools::getValue('id_category', Configuration::get('PS_HOME_CATEGORY'));
		$category = new Category($id_category, Context::getContext()->language->id);
		if (!Validate::isLoadedObject($category))
			return;

		if (Tools::strpos($format, '%category_breacrumb%') !== false || Tools::strpos($format_desc, '%category_breacrumb%') !== false)
		{
			$parents = $category->getParentsCategories(Context::getContext()->language->id);
			$breadcrumb = array();
			$home_cat = Configuration::get('PS_HOME_CATEGORY');
			if ($parents)
				foreach ($parents as $parent)
					if ($parent['id_category'] != $home_cat)
						$breadcrumb[] = $parent['name'];

			$breadcrumb = implode(' > ', array_reverse($breadcrumb));
			$breadcrumb .= ' > '.$category->name;

			$format = Tools::str_replace_once('%category_breacrumb%', $breadcrumb, $format);
			$format_desc = Tools::str_replace_once('%category_breacrumb%', $breadcrumb, $format_desc);
		}

		if (Tools::strpos($format, '%category_name%') !== false || Tools::strpos($format_desc, '%category_name%') !== false)
		{
			$format = Tools::str_replace_once('%category_name%', $category->name, $format);
			$format_desc = Tools::str_replace_once('%category_name%', $category->name, $format_desc);
		}

		if (Tools::strpos($format, '%category_description%') !== false || Tools::strpos($format_desc, '%category_description%') !== false)
		{
			$format = Tools::str_replace_once('%category_description%', strip_tags($category->description), $format);
			$format_desc = Tools::str_replace_once('%category_description%', strip_tags($category->description), $format_desc);
		}

		if (Tools::strpos($format, '%category_id%') !== false || Tools::strpos($format_desc, '%category_id%') !== false)
		{
			$format = Tools::str_replace_once('%category_id%', $category->id, $format);
			$format_desc = Tools::str_replace_once('%category_id%', $category->id, $format_desc);
		}

		if (Tools::strpos($format, '%page_number%') !== false || Tools::strpos($format_desc, '%page_number%') !== false)
		{
			$format = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format);
			$format_desc = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format_desc);
		}

	}
	public static function getSupplierTagFormatted(&$format, &$format_desc)
	{
		$supplier = new Supplier(Tools::getValue('id_supplier', 0), Context::getContext()->language->id);
		if (!Validate::isLoadedObject($supplier))
			return;

		if (Tools::strpos($format, '%supplier_name%') !== false || Tools::strpos($format_desc, '%supplier_name%') !== false)
		{
			$format = Tools::str_replace_once('%supplier_name%', $supplier->name, $format);
			$format_desc = Tools::str_replace_once('%supplier_name%', $supplier->name, $format_desc);
		}

		if (Tools::strpos($format, '%supplier_description%') !== false || Tools::strpos($format_desc, '%supplier_description%') !== false)
		{
			$format = Tools::str_replace_once('%supplier_description%', strip_tags($supplier->description), $format);
			$format_desc = Tools::str_replace_once('%supplier_description%', strip_tags($supplier->description), $format_desc);
		}

		if (Tools::strpos($format, '%supplier_id%') !== false || Tools::strpos($format_desc, '%supplier_id%') !== false)
		{
			$format = Tools::str_replace_once('%supplier_id%', $supplier->id, $format);
			$format_desc = Tools::str_replace_once('%supplier_id%', $supplier->id, $format_desc);
		}

		if (Tools::strpos($format, '%page_number%') !== false || Tools::strpos($format_desc, '%page_number%') !== false)
		{
			$format = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format);
			$format_desc = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format_desc);
		}

	}
	public static function getManufacturerTagFormatted(&$format, &$format_desc)
	{
		$manufacturer = new Manufacturer(Tools::getValue('id_manufacturer'), Context::getContext()->language->id);
		if (!Validate::isLoadedObject($manufacturer))
			return;

		if (Tools::strpos($format, '%manufacturer_name%') !== false || Tools::strpos($format_desc, '%manufacturer_name%') !== false)
		{
			$format = Tools::str_replace_once('%manufacturer_name%', $manufacturer->name, $format);
			$format_desc = Tools::str_replace_once('%manufacturer_name%', $manufacturer->name, $format_desc);
		}

		if (Tools::strpos($format, '%manufacturer_description%') !== false || Tools::strpos($format_desc, '%manufacturer_description%') !== false)
		{
			$format = Tools::str_replace_once('%manufacturer_description%', strip_tags($manufacturer->description), $format);
			$format_desc = Tools::str_replace_once('%manufacturer_description%', strip_tags($manufacturer->description), $format_desc);
		}

		if (Tools::strpos($format, '%manufacturer_id%') !== false || Tools::strpos($format_desc, '%manufacturer_id%') !== false)
		{
			$format = Tools::str_replace_once('%manufacturer_id%', $manufacturer->id, $format);
			$format_desc = Tools::str_replace_once('%manufacturer_id%', $manufacturer->id, $format_desc);
		}

		if (Tools::strpos($format, '%page_number%') !== false || Tools::strpos($format_desc, '%page_number%') !== false)
		{
			$format = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format);
			$format_desc = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format_desc);
		}

	}
	public static function getCMSTagFormatted(&$format, &$format_desc)
	{
		$cms = new CMS(Tools::getValue('id_cms'), Context::getContext()->language->id);
		if (!Validate::isLoadedObject($cms))
			return;

		if (Tools::strpos($format, '%cms_name%') !== false || Tools::strpos($format_desc, '%cms_name%') !== false)
		{
			$format = Tools::str_replace_once('%cms_name%', $cms->name, $format);
			$format_desc = Tools::str_replace_once('%cms_name%', $cms->name, $format_desc);
		}

		if (Tools::strpos($format, '%cms_id%') !== false || Tools::strpos($format_desc, '%cms_id%') !== false)
		{
			$format = Tools::str_replace_once('%cms_id%', $cms->id, $format);
			$format_desc = Tools::str_replace_once('%cms_id%', $cms->id, $format_desc);
		}

		if (Tools::strpos($format, '%page_number%') !== false || Tools::strpos($format_desc, '%page_number%') !== false)
		{
			$format = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format);
			$format_desc = Tools::str_replace_once('%page_number%', Tools::getValue('p', '1'), $format_desc);
		}

	}
}