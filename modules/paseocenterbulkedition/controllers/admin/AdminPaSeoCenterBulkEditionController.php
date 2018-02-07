<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Pronimbo.com
 * @copyright Pronimbo.com. all rights reserved.
 * @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 */

include_once(_PS_MODULE_DIR_.'paseocenterbulkedition'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'AdminPrController.php');
include_once(_PS_MODULE_DIR_.'paseocenterbulkedition'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'PaMeta.php');

class AdminPaSeoCenterBulkEditionController extends AdminPrController
{
	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
		$this->bootstrap = true;
		$this->table = 'paseocenter';
		$this->controller_name = 'AdminPaSeoCenterBulkEdition';
		$this->className = 'PaMeta';
		$this->tpl_folder = 'controllers/bulkedition/';
		$this->list_simple_header = false;
		$this->fields_list = array(
			'image' => array(
				'title' => $this->l('Image'),
				'align' => 'center',
				'callback' => 'printOGImage',
				'orderby' => false,
				'filter' => false,
				'search' => false
			),
			'type_name' => array(
				'title' => $this->l('Type'),
				'type' => 'select',
				'list' => array(
					PaMeta::ENTITY_PRODUCT => $this->l('Product'),
					PaMeta::ENTITY_CATEGORY => $this->l('Category'),
					PaMeta::ENTITY_PAGE => $this->l('Front pages'),
					PaMeta::ENTITY_CMS => $this->l('CMS'),
//					PaMeta::ENTITY_CMS_CAT => $this->l('CMS Category'),
					PaMeta::ENTITY_MANUFACTURER => $this->l('Manufacturer'),
					PaMeta::ENTITY_SUPPLIER => $this->l('Supplier'),
				),
				'filter_type' => 'int',
				'filter_key' => 'a!type',
				'order_key' => 'a!type_name',
				'search' => true
			),
			'name' => array(
				'title' => $this->l('Name'),
				'type' => 'text',
				'search' => true
			),
			'meta_title' => array(
				'title' => $this->l('Meta Title'),
				'type' => 'text',
				'search' => true,
			),
			'canonical' => array(
				'title' => $this->l('Canonical'),
				'type' => 'text',
				'class' => 'fixed-width-xs',
				'align' => 'center',
				'filter_key' => 'a!canonical',
				'ajax' => true,
				'orderby' => false
			),
			'nofollow' => array(
				'title' => $this->l('No Follow'),
				'active' => 'nofollow',
				'type' => 'bool',
				'class' => 'fixed-width-xs',
				'align' => 'center',
				'filter_key' => 'a!nofollow',
				'ajax' => true,
				'orderby' => false
			),
			'markup' => array(
				'title' => $this->l('Markup'),
				'active' => 'markup',
				'type' => 'bool',
				'class' => 'fixed-width-xs',
				'align' => 'center',
				'filter_key' => 'a!markup',
				'ajax' => true,
				'orderby' => false
			),
			'noindex' => array(
				'title' => $this->l('No Index'),
				'active' => 'index',
				'type' => 'bool',
				'class' => 'fixed-width-xs',
				'align' => 'center',
				'filter_key' => 'a!noindex',
				'ajax' => true,
				'orderby' => false
			),
		);
		$this->_defaultOrderBy = 'id';
		$this->identifier = 'id';
		$this->bulk_actions = array(
			'changeCanonicalUrl' => array(
				'text' => $this->l('Change canonical URL'),
				'icon' => 'icon-link',
			),
			'activateNoFollow' => array(
				'text' => $this->l('Set No follow'),
				'icon' => 'icon-check',
				'confirm' => $this->l('Match as "No Follow" the selected items?')
			),
			'deactivateNoFollow' => array(
				'text' => $this->l('Remove No follow'),
				'icon' => 'icon-remove',
				'confirm' => $this->l('Remove "No Follow" the selected items?')
			),
			'activateNoIndex' => array(
				'text' => $this->l('Set No index'),
				'icon' => 'icon-check',
				'confirm' => $this->l('Match as "No Index" the selected items?')
			),
			'deactivateNoIndex' => array(
				'text' => $this->l('Remove No index'),
				'icon' => 'icon-remove',
				'confirm' => $this->l('Remove "No Index" the selected items?')
			),
			'activateMarkup' => array(
				'text' => $this->l('Add markup Schema.org'),
				'icon' => 'icon-check',
				'confirm' => $this->l('Add markup "Schema.org" the selected items?')
			),
			'deactivateMarkup' => array(
				'text' => $this->l('Remove markup Schema.org'),
				'icon' => 'icon-remove',
				'confirm' => $this->l('Remove markup "Schema.org" the selected items?')
			),
		);
		$this->addRowAction('edit');
	}

	public function printOGImage($id)
	{
		$image_path = PaMeta::getOpenGraphImage($id, false);
		$dest_path = $this->table.'_mini_'.$id.'_'.$this->context->shop->id.'.'.$this->imageType;
		$image = ImageManagerCore::thumbnail($image_path, $dest_path, 45, $this->imageType, true, true);
		return $image;
	}

	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		if (!isset($this->list_id)) $this->list_id = $this->table;
		/* Manage default params values */
		$use_limit = true;
		if ($limit === false) $use_limit = false;
		elseif (empty($limit))
		{
			if (version_compare(_PS_VERSION_, '1.6.0', '>'))
			{
				if (isset($this->context->cookie->{$this->list_id.'_pagination'}) && $this->context->cookie->{$this->list_id.'_pagination'})
					$limit = $this->context->cookie->{$this->list_id.'_pagination'};
				else
					$limit = $this->_default_pagination;
			}
			else
			{
				if (isset($this->context->cookie->{$this->table.'_pagination'}) && $this->context->cookie->{$this->table.'_pagination'})
					$limit = $this->context->cookie->{$this->table.'_pagination'};
				else
					$limit = $this->_pagination[1];
			}
		}
		if (empty($order_by))
		{
			if ($this->context->cookie->{$this->list_id.'Orderby'})
				$order_by = $this->context->cookie->{$this->list_id.'Orderby'};
			elseif ($this->_orderBy)
				$order_by = $this->_orderBy;
			else
				$order_by = $this->_defaultOrderBy;
		}
		if (empty($order_way))
		{
			if ($this->context->cookie->{$this->list_id.'Orderway'})
				$order_way = $this->context->cookie->{$this->list_id.'Orderway'};
			elseif ($this->_orderWay)
				$order_way = $this->_orderWay;
			else
				$order_way = $this->_defaultOrderWay;
		}
		if (version_compare(_PS_VERSION_, '1.6.0', '>'))
		{
			$limit = (int)Tools::getValue($this->list_id.'_pagination', $limit);
			if (in_array($limit, $this->_pagination) && $limit != $this->_default_pagination) $this->context->cookie->{$this->list_id.'_pagination'} = $limit;
			else
				unset($this->context->cookie->{$this->list_id.'_pagination'});
		}
		else
		{
			$limit = (int)Tools::getValue('pagination', $limit);
			$this->context->cookie->{$this->table.'_pagination'} = $limit;
		}
		$order_by_valid = !Validate::isOrderBy($order_by);
		$order_way_valid = !Validate::isOrderBy($order_way);

		/* Check params validity */
		if ($order_by_valid || $order_way_valid || !is_numeric($start) || !is_numeric($limit) || !Validate::isUnsignedId($id_lang))
			throw new PrestaShopException('get list params is not valid');
		if (!isset($this->fields_list[$order_by]['order_key']) && isset($this->fields_list[$order_by]['filter_key']))
			$this->fields_list[$order_by]['order_key'] = $this->fields_list[$order_by]['filter_key'];
		if (isset($this->fields_list[$order_by]) && isset($this->fields_list[$order_by]['order_key']))
			$order_by = $this->fields_list[$order_by]['order_key'];
		/* Determine offset from current page */
		$start = 0;
		if ((int)Tools::getValue('submitFilter'.$this->list_id))
			$start = ((int)Tools::getValue('submitFilter'.$this->list_id) - 1) * $limit;
		elseif (empty($start) && isset($this->context->cookie->{$this->list_id.'_start'}) && Tools::isSubmit('export'.$this->table))
			$start = $this->context->cookie->{$this->list_id.'_start'};
		// Either save or reset the offset in the cookie
		if ($start)
			$this->context->cookie->{$this->list_id.'_start'} = $start;
		elseif (isset($this->context->cookie->{$this->list_id.'_start'}))
			unset($this->context->cookie->{$this->list_id.'_start'});
		/* Cache */
		$this->_lang = (int)$id_lang;
		$this->_orderBy = $order_by;
		if (preg_match('/[.!]/', $order_by))
		{
			$order_by_split = preg_split('/[.!]/', $order_by);
			$order_by = bqSQL($order_by_split[0]).'.`'.bqSQL($order_by_split[1]).'`';
		}
		elseif ($order_by) $order_by = '`'.bqSQL($order_by).'`';
		$this->_orderWay = Tools::strtoupper($order_way);
		// Add SQL shop restriction
		$select_shop = $join_shop = $where_shop = '';
		if ($this->shopLinkType)
			$where_shop = Shop::addSqlRestriction($this->shopShareDatas, 'a', $this->shopLinkType);

		if ($this->multishop_context && Shop::isTableAssociated($this->table) && !empty($this->className))
			if (Shop::getContext() != Shop::CONTEXT_ALL || !$this->context->employee->isSuperAdmin())
			{
				$test_join = !preg_match('#`?'.preg_quote(_DB_PREFIX_.$this->table.'_shop').'`? *sa#', $this->_join);
				if (Shop::isFeatureActive() && $test_join && Shop::isTableAssociated($this->table))
					$this->_where .= ' AND a.'.$this->identifier.' IN (
						SELECT sa.'.$this->identifier.'
						FROM `'._DB_PREFIX_.$this->table.'_shop` sa
						WHERE sa.id_shop IN ('.implode(', ', Shop::getContextListShopID()).')
					)';
			}

			$this->_listsql = '
			SELECT SQL_CALC_FOUND_ROWS ';
			$this->_listsql = '(SELECT * FROM (SELECT DISTINCT CONCAT('.PaMeta::ENTITY_PRODUCT.',
			LPAD(b.id_shop,3,\'0\'),c.id_product) as id,
            CONCAT('.PaMeta::ENTITY_PRODUCT.',LPAD(b.id_shop,3,\'0\'),c.id_product) as image, 
            b.id_shop as id_shop, c.name,c.meta_title, c.meta_description,c.meta_keywords
            , '.PaMeta::ENTITY_PRODUCT.' as type , \''.$this->l('Product').'\' as type_name, 
            e.noindex, el.canonical, e.markup , e.nofollow   
            FROM '._DB_PREFIX_.'product_lang c
            INNER JOIN '._DB_PREFIX_.'product_shop b ON b.id_product = c.id_product  AND c.id_lang = '.(int)$this->context->language->id.'
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas e ON e.id_entity = c.id_product AND
            e.type = '.PaMeta::ENTITY_PRODUCT.'  AND b.id_shop = e.id_shop
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas_lang el ON e.id_paseocenter_metas = el.id_paseocenter_metas AND
            el.id_lang = '.(int)$this->context->language->id.') as a
            WHERE  1 '.(isset($this->_where) ? $this->_where.' ' : '').($this->deleted ? 'AND a.`deleted` = 0 ' : '').
			(isset($this->_filter) ? $this->_filter : '').$where_shop;

			$this->_listsql .= ') UNION ALL (';

			$this->_listsql .= 'SELECT * FROM (SELECT DISTINCT
            CONCAT('.PaMeta::ENTITY_CMS.',LPAD(b.id_shop,3,\'0\'),c.id_cms) as id, 
            CONCAT('.PaMeta::ENTITY_CMS.',LPAD(b.id_shop,3,\'0\'),c.id_cms) as image, 
            b.id_shop as id_shop, c.meta_title as name, c.meta_title, c.meta_description,
            c.meta_keywords, '.PaMeta::ENTITY_CMS.' as type, \''.$this->l('CMS').'\' as type_name ,  
            e.noindex, el.canonical, e.markup , e.nofollow
            FROM '._DB_PREFIX_.'cms_lang c
            INNER JOIN '._DB_PREFIX_.'cms_shop b ON c.id_cms = b.id_cms AND c.id_lang = '.(int)$this->context->language->id.'
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas e ON e.id_entity = c.id_cms AND
            e.type = '.PaMeta::ENTITY_CMS.'  AND b.id_shop = e.id_shop
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas_lang el ON  e.id_paseocenter_metas = el.id_paseocenter_metas AND
            el.id_lang = '.(int)$this->context->language->id.'
            ) a WHERE 1 '.(isset($this->_where) ? $this->_where.' ' : '').(isset($this->_filter) ? $this->_filter : '').$where_shop;

			$this->_listsql .= ') UNION ALL (';

			$this->_listsql .= $sqlpage = 'SELECT * FROM (SELECT DISTINCT
            CONCAT('.PaMeta::ENTITY_PAGE.',LPAD(IF(ISNULL(d.id_shop), '.(int)$this->context->shop->id.', d.id_shop),3,\'0\'),
            c.id_paseocenter_pages) as id,
            CONCAT('.PaMeta::ENTITY_PAGE.',LPAD(IF(ISNULL(d.id_shop), '.(int)$this->context->shop->id.', d.id_shop),3,\'0\'),
            c.id_paseocenter_pages) as image,
            IF(ISNULL(d.id_shop), '.(int)$this->context->shop->id.', d.id_shop) as id_shop, c.page as name, d.title as meta_title,
            d.description as meta_description,
            d.keywords as meta_keywords, '.PaMeta::ENTITY_PAGE.' as type, \''.$this->l('Front page').'\' as type_name ,  
            e.noindex, el.canonical, e.markup , e.nofollow 
            FROM '._DB_PREFIX_.'paseocenter_pages c
            LEFT JOIN '._DB_PREFIX_.'meta_lang d ON c.id_meta = d.id_meta AND d.id_lang = '.(int)$this->context->language->id.' AND
            d.id_shop IN ('.(int)$this->context->shop->id.', 0)
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas e ON e.id_entity = c.id_paseocenter_pages AND e.type = '.PaMeta::ENTITY_PAGE.'  AND
            d.id_shop = e.id_shop
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas_lang el ON  e.id_paseocenter_metas = el.id_paseocenter_metas AND
            el.id_lang = '.(int)$this->context->language->id.') a
            WHERE 1 '.(isset($this->_where) ? $this->_where.' ' : '').(isset($this->_filter) ? $this->_filter : '').$where_shop;

			$this->_listsql .= ') UNION ALL (';

			$this->_listsql .= 'SELECT * FROM (SELECT DISTINCT
            CONCAT('.PaMeta::ENTITY_CATEGORY.', LPAD(b.id_shop,3,\'0\'),c.id_category) as id, 
            CONCAT('.PaMeta::ENTITY_CATEGORY.', LPAD(b.id_shop,3,\'0\'),c.id_category) as image, 
            b.id_shop as id_shop, c.name, c.meta_title, c.meta_description,c.meta_keywords,
            '.PaMeta::ENTITY_CATEGORY.' as type, \''.$this->l('Category').'\' as type_name , 
            e.noindex, el.canonical, e.markup , e.nofollow FROM '._DB_PREFIX_.'category_lang c
            INNER JOIN '._DB_PREFIX_.'category_shop b ON c.id_category = b.id_category   AND c.id_lang = '.(int)$this->context->language->id.'
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas e ON e.id_entity = c.id_category AND e.type = '.PaMeta::ENTITY_CATEGORY.'  AND
            b.id_shop = e.id_shop
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas_lang el ON  e.id_paseocenter_metas = el.id_paseocenter_metas AND
            el.id_lang = '.(int)$this->context->language->id.'
            ) a WHERE 1 '.(isset($this->_where) ? $this->_where.' ' : '').(isset($this->_filter) ? $this->_filter : '').$where_shop;

			$this->_listsql .= ') UNION ALL (';

			$this->_listsql .= 'SELECT * FROM (SELECT DISTINCT
            CONCAT('.PaMeta::ENTITY_MANUFACTURER.',LPAD(b.id_shop,3,\'0\'),b.id_manufacturer) as id, 
            CONCAT('.PaMeta::ENTITY_MANUFACTURER.',LPAD(b.id_shop,3,\'0\'),b.id_manufacturer) as image, 
            b.id_shop as id_shop, c.name, d.meta_title, d.meta_description,d.meta_keywords,
            '.PaMeta::ENTITY_MANUFACTURER.' as type, \''.$this->l('Manufacturer').'\' as type_name , 
            e.noindex, el.canonical, e.markup , e.nofollow FROM '._DB_PREFIX_.'manufacturer_lang d
            INNER JOIN '._DB_PREFIX_.'manufacturer c ON c.id_manufacturer = d.id_manufacturer   AND
            d.id_lang = '.(int)$this->context->language->id.'
            INNER JOIN '._DB_PREFIX_.'manufacturer_shop b ON d.id_manufacturer = b.id_manufacturer
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas e ON e.id_entity = c.id_manufacturer
            AND e.type = '.PaMeta::ENTITY_MANUFACTURER.'  AND b.id_shop = e.id_shop
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas_lang el ON  e.id_paseocenter_metas = el.id_paseocenter_metas AND
            el.id_lang = '.(int)$this->context->language->id.'
            ) a WHERE 1 '.(isset($this->_where) ? $this->_where.' ' : '').
			(isset($this->_filter) ? $this->_filter : '').$where_shop;

			$this->_listsql .= ') UNION ALL (';

			$this->_listsql .= 'SELECT * FROM (SELECT DISTINCT
            CONCAT( '.PaMeta::ENTITY_SUPPLIER.' ,LPAD(b.id_shop,3,\'0\'),d.id_supplier) as id, 
            CONCAT( '.PaMeta::ENTITY_SUPPLIER.' ,LPAD(b.id_shop,3,\'0\'),d.id_supplier) as image, 
            b.id_shop as id_shop, c.name, d.meta_title, d.meta_description,d.meta_keywords,
            '.PaMeta::ENTITY_SUPPLIER.' as type, \''.$this->l('Supplier').'\' as type_name ,  
            e.noindex, el.canonical, e.markup , e.nofollow FROM '._DB_PREFIX_.'supplier_lang d
            INNER JOIN '._DB_PREFIX_.'supplier c ON c.id_supplier = d.id_supplier AND
            d.id_lang = '.(int)$this->context->language->id.'
            INNER JOIN '._DB_PREFIX_.'supplier_shop b ON d.id_supplier = b.id_supplier
			LEFT JOIN '._DB_PREFIX_.'paseocenter_metas e ON e.id_entity = c.id_supplier AND
			e.type = '.PaMeta::ENTITY_SUPPLIER.'  AND b.id_shop = e.id_shop
            LEFT JOIN '._DB_PREFIX_.'paseocenter_metas_lang el ON  e.id_paseocenter_metas = el.id_paseocenter_metas AND
            el.id_lang = '.(int)$this->context->language->id.') a
            WHERE 1 '.(isset($this->_where) ? $this->_where.' ' : '').(isset($this->_filter) ? $this->_filter : '').$where_shop.')';
			$order_by = ((str_replace('`', '', $order_by) == $this->identifier) ? '' : '').$order_by;
			$order_way = pSQL($order_way).($this->_tmpTableFilter ? ') tmpTable WHERE 1 '.$this->_tmpTableFilter : '');
			$limit = (($use_limit === true) ? ' LIMIT '.(int)$start.','.(int)$limit : '');
			$this->_listsql .= ' ORDER BY '.$order_by.' '.$order_way.$limit;
			$this->_list = Db::getInstance()->executeS($this->_listsql, true, false);

			if ($this->_list === false)
				$this->_list_error = Db::getInstance()->getMsgError();

			$this->_listTotal = Db::getInstance()->getValue('SELECT FOUND_ROWS() AS `'._DB_PREFIX_.$this->table.'`', false);

	}

	protected function processBulkActivatenofollow()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$this->boxes = Tools::getValue('paseocenterBox', array());
			if (!empty($this->boxes))
			{
				$res = true;
				foreach ($this->boxes as $item)
				{
					$object = new PaMeta($item);
					$object->nofollow = 1;
					if (!$object->save(true, true, false))
						$res &= false;
					else
						$res &= true;
				}
				if ($res) $this->confirmations[] = $this->l('"No follow" actived successfully');
				else
					$this->errors[] = $this->l('An error has ocurrer while try change "No follow" status on some pages');
			}
			else
				$this->errors[] = Tools::displayError('You must select one item almost.');
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to delete this.');
	}

	protected function processBulkDeactivatenofollow()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$this->boxes = Tools::getValue('paseocenterBox', array());
			if (!empty($this->boxes))
			{
				$res = true;
				foreach ($this->boxes as $item)
				{
					$object = new PaMeta($item);
					$object->nofollow = 0;
					$res &= $object->save(true, true, false);
				}
				if ($res) $this->confirmation[] = $this->l('"No follow" deactived successfully');
				else
					$this->errors[] = $this->l('An error has ocurrer while try change "No follow" status on some pages');
			}
			else
				$this->errors[] = Tools::displayError('You must select one item almost.');
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to delete this.');
	}

	protected function processBulkActivatenoindex()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$this->boxes = Tools::getValue('paseocenterBox', array());
			if (!empty($this->boxes))
			{
				$res = true;
				foreach ($this->boxes as $item)
				{
					$object = new PaMeta($item);
					$object->noindex = 1;
					if (!$object->save(true, true, false))
						$res &= false;
					else
						$res &= true;
				}
				if ($res) $this->confirmations[] = $this->l('Noindex actived successfully');
				else
					$this->errors[] = $this->l('An error has ocurrer while try change no index status on some pages');
			}
			else
				$this->errors[] = Tools::displayError('You must select one item almost.');
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to delete this.');
	}

	protected function processBulkDeactivatenoindex()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$this->boxes = Tools::getValue('paseocenterBox', array());
			if (!empty($this->boxes))
			{
				$res = true;
				foreach ($this->boxes as $item)
				{
					$object = new PaMeta($item);
					$object->noindex = 0;
					$res &= $object->save(true, true, false);
				}
				if ($res) $this->confirmation[] = $this->l('"No index" deactived successfully');
				else
					$this->errors[] = $this->l('An error has ocurrer while try change "No index" status on some pages');
			}
			else
				$this->errors[] = Tools::displayError('You must select one item almost.');
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to delete this.');
	}

	protected function processBulkActivatemarkup()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$this->boxes = Tools::getValue('paseocenterBox', array());
			if (!empty($this->boxes))
			{
				$res = true;
				foreach ($this->boxes as $item)
				{
					$object = new PaMeta($item);
					$object->markup = 1;
					if (!$object->save(true, true, false)) $res &= false;
					else
						$res &= true;
				}
				if ($res) $this->confirmations[] = $this->l('Markup "Schema.org" actived successfully');
				else
					$this->errors[] = $this->l('An error has ocurrer while try change the markup "Schema.org" status on some pages');
			}
			else
				$this->errors[] = Tools::displayError('You must select one item almost.');
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to delete this.');
	}

	protected function processBulkDeactivatemarkup()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$this->boxes = Tools::getValue('paseocenterBox', array());
			if (!empty($this->boxes))
			{
				$res = true;
				foreach ($this->boxes as $item)
				{
					$object = new PaMeta($item);
					$object->markup = 0;
					$res &= $object->save(true, true, false);
				}
				if ($res) $this->confirmation[] = $this->l('Markup "Schema.org" deactived successfully');
				else
					$this->errors[] = $this->l('An error has ocurrer while try change "Markup.org" status on some pages');
			}
			else
				$this->errors[] = Tools::displayError('You must select one item almost.');
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to delete this.');
	}

	protected function processBulkChangecanonicalurl()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$this->boxes = Tools::getValue('paseocenterBox', array());
			if (!empty($this->boxes))
			{
				if (Tools::getIsset('acepted'))
				{
					$res = true;
					foreach ($this->boxes as $meta)
					{

						$meta = new PaMeta($meta);
						$languages  = Language::getLanguages();
						if (count($languages) > 1)
						foreach ($languages as $lang)
							$meta->canonical[$lang['id_lang']] = trim(Tools::getValue('canonical_url_'.$lang['id_lang'], ''));
						else
							$meta->canonical[$this->context->language->id] = trim(Tools::getValue('canonical_url', ''));

						$res &= $meta->save(true, true, false);
					}
					if ($res) $this->confirmations[] = $this->l('Canonical URL has changed successfully');
					else
						$this->errors[] = $this->l('An error ocurred while try change canonical url');
				}
				else
				{
					$params = array(
						'items' => $this->boxes,
						'changeCanonicalURL' => true,
						'action' => $this->context->link->getAdminLink('AdminPaSeoCenterBulkEdition', true).'&submitBulkchangeCanonicalUrlpaseocenter',
						'languages' => Language::getLanguages(),
						'defaultFormLanguage' => $this->context->language->id,
					);
					$this->context->smarty->assign($params);
				}
			}
			else
				$this->errors[] = Tools::displayError('You must select one item almost.');
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to delete this.');
	}

	public function ajaxProcessIndexpaseocenter()
	{
		$out = array();
		if (Tools::getValue('id'))
		{
			$meta = new PaMeta((int)Tools::getValue('id'));
			$meta->noindex = (int)!$meta->noindex;
			if ($meta->save(true, true, false))
			{
				$out['success'] = 1;
				$out['text'] = $this->l('Noindex has been changed successfully');
			}
			else
			{
				$out['success'] = 0;
				$out['text'] = $this->l('An error ocurred while try change noindex status');
			}
		}
		else
		{
			$out['success'] = 0;
			$out['text'] = $this->l('ID is needed');
		}
		die(Tools::jsonEncode($out));
	}

	public function ajaxProcessNofollowpaseocenter()
	{
		$out = array();
		if (Tools::getValue('id'))
		{
			$meta = new PaMeta((int)Tools::getValue('id'));
			$meta->nofollow = (int)!$meta->nofollow;
			if ($meta->save(true, true, false))
			{
				$out['success'] = 1;
				$out['text'] = $this->l('"No follow" has been changed successfully');
			}
			else
			{
				$out['success'] = 0;
				$out['text'] = $this->l('An error ocurred while try change "No follow" status');
			}
		}
		else
		{
			$out['success'] = 0;
			$out['text'] = $this->l('ID is needed');
		}
		die(Tools::jsonEncode($out));
	}
	public function ajaxProcessMarkuppaseocenter()
	{
		$out = array();
		if (Tools::getValue('id'))
		{
			$meta = new PaMeta((int)Tools::getValue('id'));
			$meta->markup = (int)!$meta->markup;
			if ($meta->save(true, true, false))
			{
				$out['success'] = 1;
				$out['text'] = $this->l('Markup "Schema.org" has been changed successfully');
			}
			else
			{
				$out['success'] = 0;
				$out['text'] = $this->l('An error ocurred while try change de markup "Schema.org" status');
			}
		}
		else
		{
			$out['success'] = 0;
			$out['text'] = $this->l('ID is needed');
		}
		die(Tools::jsonEncode($out));
	}
	public function processIndex()
	{
		$out = array();
		if (Tools::getValue('id'))
		{
			$meta = new PaMeta((int)Tools::getValue('id'));
			$meta->noindex = (int)!$meta->noindex;
			if ($meta->save(true, true, false))
			{
				$out['success'] = 1;
				$out['text'] = $this->l('Noindex has been changed successfully');
			}
			else
			{
				$out['success'] = 0;
				$out['text'] = $this->l('An error ocurred while try change noindex status');
			}
		}
		else
		{
			$out['success'] = 0;
			$out['text'] = $this->l('ID is needed');
		}
		die(Tools::jsonEncode($out));
	}

	public function processNofollow()
	{
		$out = array();
		if (Tools::getValue('id'))
		{
			$meta = new PaMeta((int)Tools::getValue('id'));
			$meta->nofollow = (int)!$meta->nofollow;
			if ($meta->save(true, true, false))
			{
				$out['success'] = 1;
				$out['text'] = $this->l('"No follow" has been changed successfully');
			}
			else
			{
				$out['success'] = 0;
				$out['text'] = $this->l('An error ocurred while try change "No follow" status');
			}
		}
		else
		{
			$out['success'] = 0;
			$out['text'] = $this->l('ID is needed');
		}
		die(Tools::jsonEncode($out));
	}
	public function processMarkup()
	{
		$out = array();
		if (Tools::getValue('id'))
		{
			$meta = new PaMeta((int)Tools::getValue('id'));
			$meta->markup = (int)!$meta->markup;
			if ($meta->save(true, true, false))
			{
				$out['success'] = 1;
				$out['text'] = $this->l('Markup "Schema.org" has been changed successfully');
			}
			else
			{
				$out['success'] = 0;
				$out['text'] = $this->l('An error ocurred while try change de markup "Schema.org" status');
			}
		}
		else
		{
			$out['success'] = 0;
			$out['text'] = $this->l('ID is needed');
		}
		die(Tools::jsonEncode($out));
	}

	protected function loadObject($opt = false)
	{
		if (!isset($this->className) || empty($this->className))
			return true;
		$id = $this->getFormatedID();
		if ($id && Validate::isUnsignedId($id))
		{
			if (!$this->object) $this->object = new $this->className($id);
			return $this->object;
		}
		elseif ($opt)
		{
			if (!$this->object) $this->object = new $this->className();
			return $this->object;
		}
		else
		{
			$this->errors[] = Tools::displayError('The object cannot be loaded (the identifier is missing or invalid)');
			return false;
		}
	}

	public function copyFromPost(&$object, $table)
	{
		parent::copyFromPost($object, $table);
		foreach (Language::getLanguages(true, $object->id_shop) as $lang)
		{
			$object->meta_title[$lang['id_lang']] = Tools::getValue('meta_title_'.(int)$lang['id_lang'], '');
			$object->meta_description[$lang['id_lang']] = Tools::getValue('meta_description_'.(int)$lang['id_lang'], '');
			$object->meta_keywords[$lang['id_lang']] = Tools::getValue('meta_keywords_'.(int)$lang['id_lang'], '');
			$object->link_rewrite[$lang['id_lang']] = Tools::getValue('link_rewrite_'.(int)$lang['id_lang'], '');
		}
		$object->type = (int)Tools::getValue('type', 0);
		$object->id_shop = (int)Tools::getValue('id_shop', 0);
		$object->id_entity = (int)Tools::getValue('id_entity', 0);
		return $object;
	}
	protected function postImage($id)
	{
		$this->imageType = 'jpg';
		$vars = preg_split('//', $id, -1, PREG_SPLIT_NO_EMPTY);
		$this->fieldImageSettings = array(
			'name' => 'og_image',
			'dir' => 'og/'.implode('/', $vars)
		);
		if (!is_dir(PaMeta::getStaticImgPath($this->getFormatedID())))
			mkdir(PaMeta::getStaticImgPath($this->getFormatedID()), 0777, true);
		$ret = parent::postImage($id);
		if ($ret)
		{
			$images_types = array();
			$images_types[] = (array)new ImageTypeCore(Configuration::get('PA_SEO_OG_IMG_SIZE'));
			foreach ($images_types as $image_type)
			{
				$source_path = PaMeta::getStaticImgPath($this->getFormatedID()).'/'.$this->getFormatedID().'.jpg';
				$dest_path = PaMeta::getStaticImgPath($this->getFormatedID()).'/'.$this->getFormatedID().'-'.Tools::stripslashes($image_type['name']).'.jpg';
				ImageManager::resize($source_path, $dest_path, (int)$image_type['width'], (int)$image_type['height']);
			}

		}
		return $ret;
	}
	public function getFacebookObjectType()
	{
		$fields = array(
			array(
				'id' => 'product',
				'name' => $this->l('Product'),
			),
			array(
				'id' => 'website',
				'name' => $this->l('Website')
			)
		);
		usort($fields, array($this, 'azOrder'));
		return $fields;
	}

	protected function azOrder($a, $b)
	{
		return strcmp($a['name'], $b['name']);
	}



	public function getFormatedID()
	{
		$type = (int)Tools::getValue('type', Tools::substr(Tools::getValue('id', 0), 0, 1));
		$id_entity = (int)Tools::getValue('id_entity', Tools::substr(Tools::getValue('id', 0), 4));
		$id_shop = (int)Tools::getValue('id_shop', Tools::substr(Tools::getValue('id', 0), 1, 3));
		$id = $type.str_pad($id_shop, 3, '0', STR_PAD_LEFT).$id_entity;
		return $id;
	}

	public function processUpdate()
	{
		/* Checking fields validity */
		$this->validateRules();
		if (empty($this->errors))
		{
			$id = $this->getFormatedID();
			/* Object update */
			if (isset($id) && !empty($id))
			{
				$object = new $this->className($id);
				if (Validate::isLoadedObject($object))
				{
					/* Specific to objects which must not be deleted */
					if ($this->deleted && $this->beforeDelete($object))
					{
						// Create new one with old objet values
						$object_new = $object->duplicateObject();
						if (Validate::isLoadedObject($object_new))
						{
							// Update old object to deleted
							$object->deleted = 1;
							$object->update();
							// Update new object with post values
							$this->copyFromPost($object_new, $this->table);
							$result = $object_new->update();
							if (Validate::isLoadedObject($object_new))
								$this->afterDelete($object_new, $object->id);
						}
					}
					else
					{
						$this->copyFromPost($object, $this->table);
						$result = $object->update();
						$this->afterUpdate($object);
					}
					if ($object->id) $this->updateAssoShop($object->id);
					if (!$result)
					{
						$msg = Tools::displayError('An error occurred while updating an object.').' <b>'.$this->table.'</b> ('.Db::getInstance()->getMsgError().')';
						$this->errors[] = $msg;
					}
					elseif ($this->postImage($this->getFormatedID()) && !count($this->errors) && $this->_redirect)
					{
						$path = _PS_IMG_DIR_.'tmp'.DIRECTORY_SEPARATOR.$this->table.'_mini_';
						$path .= $this->getFormatedID().'_'.$this->context->shop->id.'.'.$this->imageType;
						if (file_exists($path))
							unlink($path);

						$current_uri = self::$currentIndex.'&'.$this->identifier.'='.$this->getFormatedID().'&conf=4';
						if ($back = Tools::getValue('back'))
							$this->redirect_after = urldecode($back).'&conf=4';
						if (Tools::getValue('stay_here') == 'on' || Tools::getValue('stay_here') == 'true' || Tools::getValue('stay_here') == '1')
							$this->redirect_after = $current_uri.'&updatepaseocenter&token='.$this->token;
						if (Tools::isSubmit('submitAdd'.$this->table.'AndStay'))
							$this->redirect_after = $current_uri.'&update'.$this->table.'&token='.$this->token;
						if (empty($this->redirect_after) && $this->redirect_after !== false)
							$this->redirect_after = self::$currentIndex.'&conf=4&token='.$this->token;
					}
					$msg = sprintf($this->l('%s modification', 'AdminTab', false, false), $this->className);
					if (class_exists('PrestaShopLogger'))
						PrestaShopLogger::addLog($msg, 1, null, $this->className, (int)$object->id, true, (int)$this->context->employee->id);
				}
				else
				{
					$msg = Tools::displayError('An error occurred while updating an object.').' <b>';
					$msg .= $this->table.'</b> '.Tools::displayError('(cannot load object)');
					$this->errors[] = $msg;
				}
			}
		}
		$this->errors = array_unique($this->errors);
		if (!empty($this->errors))
		{
			$this->display = 'edit';
			return false;
		}
		if (isset($object))
			return $object;

		return false;
	}
	public function getFormValues()
	{
		$out = parent::getFormValues();
		$out['submitAdd'.$this->table] = '1';
		if (version_compare(_PS_VERSION_, '1.6.0', '<'))
		{
			$id = Tools::getValue('id', 0);
			$path = PaMeta::getOpenGraphImage($id);
			$custom_image = PaMeta::getStaticImgPath($id).'/'.$id.'.jpg';
			if (file_exists($custom_image))
			{
				$delete_url = $this->context->link->getAdminLink('AdminPaSeoCenterBulkEdition', true).'&action=deleteOGImage&id='.Tools::getValue('id', 0);
				$og_image_size = filesize($custom_image);
				if ($og_image_size)
					$og_image_size = round((int)$og_image_size / 1024, 2);
				$path = $custom_image;
			}
			else
				$og_image_size = $delete_url = false;

			$og_image_url = ImageManager::thumbnail($path, 'paseocenter_mini_form_'.$id.'.'.$this->imageType, 350, $this->imageType, true, true);

			$out['og_image'] = array('image' => $og_image_url);
		}
		return $out;
	}
	public function renderList()
	{
		unset($this->toolbar_btn['new']);
		return parent::renderList();
	}

	public function renderForm()
	{
		$type = 'switch';
		$id = Tools::getValue('id', 0);
		$path = PaMeta::getOpenGraphImage($id);
		$custom_image = PaMeta::getStaticImgPath($id).'/'.$id.'.jpg';
		if (file_exists($custom_image))
		{
			$delete_url = $this->context->link->getAdminLink('AdminPaSeoCenterBulkEdition', true).'&action=deleteOGImage&id='.Tools::getValue('id', 0);
			$og_image_size = filesize($custom_image);
			if ($og_image_size)
				$og_image_size = round((int)$og_image_size / 1024, 2);
			$path = $custom_image;
		}
		else
			$og_image_size = $delete_url = false;
		$og_image_url = ImageManager::thumbnail($path, 'paseocenter_mini_form_'.$id.'.'.$this->imageType, 350, $this->imageType, true, true);

		$this->fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Meta information: '),
					'icon' => 'icon-bookmark',
				),
				'tabs' => array(
					'general' => $this->l('General'),
					'open_graph' => $this->l('Social setting'),
					'scripts' => $this->l('Scripts'),
					'meta_generator' => $this->l('Meta generator'),
				),
				'fieldsets' => array(),
				'input' => array(
					'group0' => array(
						'type' => 'group-buttons',
						'tab' => 'general',
						'label' => '',
						'name' => 'group0',
						'buttons' => array(
							'markup' => array(
								'type' => $type,
								'tab' => 'general',
								'label' => $this->l('Markup'),
								'name' => 'markup',
								'is_bool' => true,
								'hint' => $this->l('Include markup "Schema.org" format'),
								'values' => array(
									array(
										'id' => 'markup_on',
										'value' => true,
										'label' => $this->l('Enabled')
									),
									array(
										'id' => 'markup_off',
										'value' => false,
										'label' => $this->l('Disabled')
									)
								),
							),
						),
					),
					'group1' => array(
						'type' => 'group-buttons',
						'tab' => 'general',
						'label' => '',
						'name' => 'group1',
						'buttons' => array(
							'noindex' => array(
								'type' => $type,
								'tab' => 'general',
								'label' => $this->l('No index'),
								'name' => 'noindex',
								'is_bool' => true,
								'hint' => $this->l('Mark as no index'),
								'values' => array(
									array(
										'id' => 'noindex_on',
										'value' => true,
										'label' => $this->l('Enabled')
									),
									array(
										'id' => 'noindex_off',
										'value' => false,
										'label' => $this->l('Disabled')
									)
								),
							),
							'nofollow' => array(
								'type' => $type,
								'tab' => 'general',
								'label' => $this->l('No Follow'),
								'name' => 'nofollow',
								'is_bool' => true,
								'hint' => $this->l('Include "No follow" metatag'),
								'values' => array(
									array(
										'id' => 'nofollow_on',
										'value' => true,
										'label' => $this->l('Enabled')
									),
									array(
										'id' => 'nofollow_off',
										'value' => false,
										'label' => $this->l('Disabled')
									)
								),
							),
						),
					),
					'canonical' => array(
						'type' => 'text',
						'tab' => 'general',
						'col' => '4',
						'lang' => true,
						'label' => $this->l('Canonical'),
						'name' => 'canonical',
						'hint' => $this->l('Set the canonical url to this element'),
						'desc' => $this->l('If you leave blank this field, Canonical URL will be the normal element URL'),
					),
					'link_rewrite' => array(
						'type' => 'text',
						'tab' => 'general',
						'col' => '4',
						'lang' => true,
						'label' => $this->l('Friendly URL'),
						'name' => 'link_rewrite',
						'hint' => $this->l('Set the Friendly URL'),
					),
					'id_shop' => array(
						'type' => 'hidden',
						'name' => 'id_shop',
					),
					'id' => array(
						'type' => 'hidden',
						'name' => 'id',
					),
					'id_entity' => array(
						'type' => 'hidden',
						'name' => 'id_entity',
					),
					'type' => array(
						'type' => 'hidden',
						'name' => 'type',
					),
					'meta_title' => array(
						'type' => 'text',
						'tab' => 'general',
						'lang' => true,
						'col' => '4',
						'label' => $this->l('Meta title'),
						'name' => 'meta_title',
						'hint' => $this->l('Set the Meta title'),
					),
					'meta_keywords' => array(
						'type' => 'text',
						'tab' => 'general',
						'col' => '4',
						'lang' => true,
						'label' => $this->l('Meta keywords'),
						'name' => 'meta_keywords',
						'hint' => $this->l('Set the Meta keywords'),
					),
					'meta_description' => array(
						'type' => 'textarea',
						'tab' => 'general',
						'col' => '4',
						'lang' => true,
						'label' => $this->l('Meta description'),
						'name' => 'meta_description',
						'hint' => $this->l('Set the Meta description'),
						'rows' => '5',
						'cols' => '15',
					),
					'og_image' => array(
						'name' => 'og_image',
						'tab' => 'open_graph',
						'label' => $this->l('OG Image'),
						'type' => 'file',
						'display_image' => true,
						'image' => (($og_image_url != '') ? $og_image_url : false),
					),
					'twt_card' => array(
						'name' => 'twt_card',
						'label' => $this->l('Twitter Card'),
						'tab' => 'open_graph',
						'type' => 'select',
						'options' => array(
							'query' => array(
								array(
									'id' => 'summary',
									'name' => $this->l('Summary')
								),
								array(
									'id' => 'summary_large_image',
									'name' => $this->l('Summary with images')
								),
								array(
									'id' => 'photo',
									'name' => $this->l('Photo')
								),
							),
							'id' => 'id',
							'name' => 'name',
						),
					),
					'fb_object_type' => array(
						'name' => 'fb_object_type',
						'tab' => 'open_graph',
						'label' => $this->l('Facebook object type'),
						'type' => 'select',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
					'og_meta_title' => array(
						'name' => 'og_meta_title',
						'col' => '4',
						'label' => $this->l('OG Meta title'),
						'tab' => 'open_graph',
						'type' => 'text',
						'lang' => true
					),
					'og_video' => array(
						'name' => 'og_video',
						'label' => $this->l('OG Video'),
						'tab' => 'open_graph',
						'col' => '4',
						'type' => 'text',
						'lang' => true
					),
					'og_meta_description' => array(
						'name' => 'og_meta_description',
						'label' => $this->l('OG Meta description'),
						'col' => '4',
						'tab' => 'open_graph',
						'type' => 'textarea',
						'lang' => true
					),
					'scripts' => array(
						'name' => 'scripts',
						'label' => $this->l('Scripts'),
						'col' => '4',
						'tab' => 'scripts',
						'lang' => true,
						'type' => 'textarea',
						'rows' => '15',
						'cols' => '20',
					),
					'submitAdd' => array(
						'name' => 'submitAdd'.$this->table,
						'tab' => 'scripts',
						'type' => 'hidden',
					),
				),
			),
		);
		if ($delete_url) $this->fields_form['form']['input']['og_image']['delete_url'] = $delete_url;
		if ($og_image_size) $this->fields_form['form']['input']['og_image']['size'] = $og_image_size;
		return parent::renderForm();
	}

	public function initPageHeaderToolbar()
	{
		parent::initPageHeaderToolbar();
		if ($this->display == 'edit' || $this->display == 'add')
		{
			$this->page_header_toolbar_btn['back'] = array(
				'short' => 'Back',
				'href' => $this->context->link->getAdminLink('AdminPaSeoCenterBulkedition').'&token='.$this->token,
				'icon' => 'process-icon-back',
				'desc' => $this->l('Back to list'),
			);
			$this->page_header_toolbar_btn['save'] = array(
				'short' => 'Save',
				'href' => '#',
				'icon' => 'process-icon-save',
				'desc' => $this->l('Save'),
			);
			$this->page_header_toolbar_btn['save-and-stay'] = array(
				'short' => 'Save And Stay',
				'href' => '#',
				'icon' => 'process-icon-save',
				'desc' => $this->l('Save And Stay'),
			);
		}
	}

	public function processDeleteogimage()
	{
		$id = Tools::getValue('id');
		$path = PaMeta::getStaticImgPath($id);
		$dir = scandir($path);
		if ($dir) foreach ($dir as $file)
		{
			if (file_exists($path.DIRECTORY_SEPARATOR.$file))
				unlink($path.DIRECTORY_SEPARATOR.$file);
		}
		if (file_exists(_PS_IMG_DIR_.'tmp'.DIRECTORY_SEPARATOR.'paseocenter_mini_'.$id.'.jpg'))
			unlink(_PS_IMG_DIR_.'tmp'.DIRECTORY_SEPARATOR.'paseocenter_mini_'.$id.'.jpg');

		if (file_exists(_PS_IMG_DIR_.'tmp'.DIRECTORY_SEPARATOR.'paseocenter_mini_product_'.$id.'.jpg'))
			unlink(_PS_IMG_DIR_.'tmp'.DIRECTORY_SEPARATOR.'paseocenter_mini_product_'.$id.'.jpg');

		$uri = $this->context->link->getAdminLink('AdminPaSeoCenterBulkEdition');
		$this->redirect_after = $uri.'&update'.$this->table.'&id='.$id.'&conf=4&token'.$this->token;
	}
}
