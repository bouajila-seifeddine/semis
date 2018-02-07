<?php
/*
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
* @author    PrestaHome Team <support@prestahome.com>
* @copyright  Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
require_once _PS_MODULE_DIR_ . 'ph_megamenu/ph_megamenu.php';

class AdminPrestaHomeMegaMenuController extends ModuleAdminController
{

	public $is_16;
	protected $position_identifier = 'id_prestahome_megamenu';
	private $spacer_size = '5';

	public function __construct()
	{
		$this->table = 'prestahome_megamenu';
		$this->className = 'PrestaHomeMegaMenu';
		$this->lang = true;

		$this->bootstrap = true;

		$this->_where = 'AND a.`id_parent` = 0';
		$this->_orderBy = 'position';

		$this->is_16 = (bool)(version_compare(_PS_VERSION_, '1.6.0', '>=') === true);
		
		$this->bulk_actions = array(
				'delete' => array(
					'text' => $this->l('Delete selected'), 
					'confirm' => $this->l('Delete selected items?'
					)
				),
				'enableSelection' => array('text' => $this->l('Enable selection')),
				'disableSelection' => array('text' => $this->l('Disable selection'))
			);

		$this->fields_list = array(
			'id_prestahome_megamenu' => array(
				'title' => $this->l('ID'), 
				'align' => 'center', 
				'class' => 'fixed-width-xs',
			),

			'title' => array(
				'title' => $this->l('Title'), 
				'width' => 'auto',
				'filter_key' => 'b!title',
			),

			'type' => array(
				'title' => $this->l('Type'), 
				'width' => 'auto',
				'callback' => 'getMegaMenuType',
			),

			'active' => array(
				'title' => $this->l('Enabled'),
				'class' => 'fixed-width-xs',
				'align' => 'center',
				'active' => 'status',
				'type' => 'bool',
				'orderby' => false
			),

			'position' => array(
				'title' => $this->l('Position'),
				'align' => 'center', 
				'class' => 'fixed-width-md',
				'filter_key' => 'a!position',
				'position' => 'position'
			)
		);

		$this->addRowActionSkipList('details', PrestaHomeMegaMenu::getMenusWithoutChildrens());
		//$this->addRowActionSkipList('edit', PrestaHomeMegaMenu::getNewRows());

		parent::__construct();

	}

	public function getMegaMenuType($type)
	{
		if($type == 0)
			return $this->l('Default (custom dropdown)');

		if($type == 1)
			return $this->l('Mega menu');

		if($type == 2)
			return $this->l('Categories dropdown');

		if($type == 4)
			return $this->l('Mega Categories from parent');

		if($type == 8)
			return $this->l('Custom Mega Categories');

		if($type == 5)
			return $this->l('Custom HTML');

		if($type == 6)
			return $this->l('Product(s)');
	}

	public function init()
	{
		parent::init();

		Shop::addTableAssociation($this->table, array('type' => 'shop'));
	}

	/**

	"Details" view for PrestaShop 1.6

	**/

	public function renderDetails()
	{
		if (($id = Tools::getValue('id_prestahome_megamenu')))
		{
			$this->lang = false;
			$this->list_id = 'details';
			$this->addRowAction('edit');
			$this->addRowAction('delete');
			$this->toolbar_btn = array();
			$megamenu = $this->loadObject($id);
			$this->toolbar_title = $megamenu->title[$this->context->employee->id_lang];

			$this->_select = 'b.*';
			$this->_join = 'LEFT JOIN `'._DB_PREFIX_.'prestahome_megamenu_lang` b ON (b.`id_prestahome_megamenu` = a.`id_prestahome_megamenu` AND b.`id_lang` = '.$this->context->language->id.')';
			$this->_where = 'AND a.`id_parent` = '.(int)$id;
			$this->_orderBy = 'position';

			$this->fields_list = array(
				'id_prestahome_megamenu' => array(
					'title' => $this->l('ID'), 
					'align' => 'center', 
					'class' => 'fixed-width-xs',
				),

				'title' => array(
					'title' => $this->l('Title'), 
					'width' => 'auto',
					'filter_key' => 'b!title',
				),

				'columns' => array(
					'title' => $this->l('Columns'), 
					'class' => 'fixed-width-xs',
					'align' => 'center',
				),

				'type' => array(
					'title' => $this->l('Type'), 
					'width' => 'auto',
					'callback' => 'getMegaMenuType',
				),

				'new_row' => array(
					'title' => $this->l('New row?'),
					'class' => 'fixed-width-xs',
					'align' => 'center',
					'active' => 'new_row',
					'type' => 'bool',
					'orderby' => false
				),

				'active' => array(
					'title' => $this->l('Enabled'),
					'class' => 'fixed-width-xs',
					'align' => 'center',
					'active' => 'status',
					'type' => 'bool',
					'orderby' => false
				),

				'position' => array(
					'title' => $this->l('Position'),
					'class' => 'fixed-width-md',
					'filter_key' => 'a!position',
					'position' => 'position'
				)
			);

			if($megamenu->type == 0)
			{
				unset($this->fields_list['columns']);
			}
		
			self::$currentIndex = self::$currentIndex.'&details'.$this->table;
			$this->processFilter();
			return parent::renderList();
		}
	}

	public function renderList()
	{
		$this->addRowAction('details');
		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->_where = 'AND a.`id_parent` = 0';
		$this->_orderBy = 'position';

		if (Shop::getContext() == Shop::CONTEXT_SHOP)
			$this->_join = ' LEFT JOIN `'._DB_PREFIX_.'prestahome_megamenu_shop` sa ON (a.`id_prestahome_megamenu` = sa.`id_prestahome_megamenu` AND sa.id_shop = '.(int)$this->context->shop->id.') ';

		if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive())
			$this->_where .= ' AND sa.`id_shop` = '.(int)Context::getContext()->shop->id;

		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
			unset($this->fields_list['position']);

		return parent::renderList();
	}

	public function initFormToolBar()
	{
		if ($this->display == 'details' || $this->display == 'add' || $this->display == 'edit')
		{
			$this->toolbar_btn['back'] = array(
				'href' => Context::getContext()->link->getAdminLink('AdminPrestaHomeMegaMenu'),
				'desc' => $this->l('Back to list'),
			);
		}

		if($this->display == 'edit')
		{
			$megamenu = $this->loadObject((int)Tools::getValue('id_prestahome_megamenu'));
			if($megamenu->id_parent > 0)
			{
				$this->toolbar_btn['back'] = array(
					'href' => Context::getContext()->link->getAdminLink('AdminPrestaHomeMegaMenu'),
					'desc' => $this->l('Back to list'),
				);
			}
		}

		if(!isset($this->display))
		{
			 $this->toolbar_btn['new_menu'] = array(
				'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token,
				'desc' => $this->l('Add new menu', null, null, false),
			);
		}
	}

	public function initPageHeaderToolbar()
	{
		$this->page_header_toolbar_title = $this->l('Menus');

		if ($this->display == 'details' || $this->display == 'add' || $this->display == 'edit')
		{
			$this->page_header_toolbar_btn['back_to_list'] = array(
				'href' => Context::getContext()->link->getAdminLink('AdminPrestaHomeMegaMenu'),
				'desc' => $this->l('Back to list', null, null, false),
				'icon' => 'process-icon-back'
			);
		}

		if($this->display == 'edit')
		{
			$megamenu = $this->loadObject((int)Tools::getValue('id_prestahome_megamenu'));
			if($megamenu->id_parent > 0)
			{
				$this->page_header_toolbar_btn['back_to_list'] = array(
					'href' => Context::getContext()->link->getAdminLink('AdminPrestaHomeMegaMenu').'&id_prestahome_megamenu='.$megamenu->id_parent.'&details'.$this->table,
					'desc' => $this->l('Back to list', null, null, false),
					'icon' => 'process-icon-back'
				);
			}
		}

		if($this->display == 'add' && ($id_parent = Tools::getValue('id_parent', 0)))
		{
			$this->page_header_toolbar_btn['back_to_list'] = array(
				'href' => Context::getContext()->link->getAdminLink('AdminPrestaHomeMegaMenu').'&id_prestahome_megamenu='.$id_parent.'&details'.$this->table,
				'desc' => $this->l('Back to list', null, null, false),
				'icon' => 'process-icon-back'
			);
		}

		if(!isset($this->display))
		{
			$this->page_header_toolbar_btn['new_menu'] = array(
				'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token,
				'desc' => $this->l('Add new menu', null, null, false),
				'icon' => 'process-icon-new'
			);
		}

		if($this->display == 'details')
		{
			$megamenu = $this->loadObject((int)Tools::getValue('id_prestahome_megamenu'));

			if($megamenu->type == 0)
			{
			   $this->page_header_toolbar_btn['new_menu'] = array(
					'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token.'&id_parent='.(int)Tools::getValue('id_prestahome_megamenu'),
					'desc' => $this->l('Add new menu', null, null, false),
					'icon' => 'process-icon-new'
				); 
			}

			if($megamenu->type == 1)
			{
				// $this->page_header_toolbar_btn['new_row'] = array(
				// 	'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token.'&id_parent='.(int)Tools::getValue('id_prestahome_megamenu').'&type=7',
				// 	'desc' => $this->l('New row', null, null, false),
				// 	'icon' => 'process-icon-new'
				// ); 

				$this->page_header_toolbar_btn['new_mega_categories'] = array(
					'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token.'&id_parent='.(int)Tools::getValue('id_prestahome_megamenu').'&type=4',
					'desc' => $this->l('Mega Categories from parent', null, null, false),
					'icon' => 'process-icon-new'
				); 

				// $this->page_header_toolbar_btn['new_custom_mega_categories'] = array(
				// 	'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token.'&id_parent='.(int)Tools::getValue('id_prestahome_megamenu').'&type=8',
				// 	'desc' => $this->l('Custom Mega Categories', null, null, false),
				// 	'icon' => 'process-icon-new'
				// ); 

				$this->page_header_toolbar_btn['new_mega_custom_html'] = array(
					'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token.'&id_parent='.(int)Tools::getValue('id_prestahome_megamenu').'&type=5',
					'desc' => $this->l('Custom HTML', null, null, false),
					'icon' => 'process-icon-new'
				); 

				$this->page_header_toolbar_btn['new_mega_product'] = array(
					'href' => self::$currentIndex.'&addprestahome_megamenu&token='.$this->token.'&id_parent='.(int)Tools::getValue('id_prestahome_megamenu').'&type=6',
					'desc' => $this->l('Product(s)', null, null, false),
					'icon' => 'process-icon-new'
				); 
			}
			
		}

		$this->page_header_toolbar_btn['go_to_menu_settings'] = array(
            'href' => Context::getContext()->link->getAdminLink('AdminPrestaHomeMegaMenuSettings'),
            'desc' => $this->l('Go to Menu Settings', null, null, false),
            'icon' => 'process-icon-cogs',
        );
			
		
		parent::initPageHeaderToolbar();
	}

	public function initProcess()
	{
		if (Tools::getIsset('details'.$this->table))
		{
			$this->list_id = 'details';


			if(Tools::getIsset('submitReset'.$this->list_id))
				$this->processResetFilters();
		}
		else
			$this->list_id = 'tab';

		return parent::initProcess();
	}

	public function setMedia()
	{
		parent::setMedia();
		$this->context->controller->addJS(_MODULE_DIR_. 'ph_megamenu/js/admin.js');
		$this->context->controller->addCSS(_MODULE_DIR_. 'ph_megamenu/css/ph_megamenu_admin.css');
		$this->context->controller->addJS(_MODULE_DIR_.'ph_megamenu/js/select2/select2.min.js');
		$this->context->controller->addJS(_MODULE_DIR_.'ph_megamenu/js/select2/select2_locale_'.$this->context->language->iso_code.'.js');
		$this->context->controller->addCSS(_MODULE_DIR_.'ph_megamenu/js/select2/select2.css', 'all');
	}

	public function initToolbarTitle()
	{
		$bread_extended = array_unique($this->breadcrumbs);

		$object = $this->loadObject(true);

		switch ($this->display)
		{
			case 'edit':
				$bread_extended[] = $object->id_parent == 0 ? $this->l('Edit menu') : $this->l('Edit sub-menu');
				break;

			case 'add':
				$bread_extended[] = $this->l('Add new');
				break;

			case 'view':
				$bread_extended[] = $this->l('View');
				break;
		}
		$this->toolbar_title = $bread_extended;
	
		if (Tools::isSubmit('submitFilter'))
		{
			$filter = '';
			foreach ($this->fields_list AS $field => $t)
			{
				if (isset($t['filter_key']))
					$field = $t['filter_key'];
				if ($val = Tools::getValue($this->table.'Filter_'.$field))
				{
					if(!is_array($val) && !empty($val))
						$filter .= ($filter ?  ', ' : $this->l(' filter by ')).$t['title'].' : ';
		
					if (isset($t['type']) && $t['type'] == 'bool')
						$filter .= ((bool)$val) ? $this->l('yes') : $this->l('no');
					elseif(is_string($val))
						$filter .= htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
					elseif(is_array($val))
					{
						$tmp = '';
						foreach($val as $v)
							if(is_string($v) && !empty($v))
								$tmp .= ' - '.htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
						if(Tools::strlen($tmp))
						{
							$tmp = ltrim($tmp, ' - ');
							$filter .= ($filter ?  ', ' : $this->l(' filter by ')).$t['title'].' : ';                           
							$filter .= $tmp;
						}
					}
				}
			}
			if ($filter)
				$this->toolbar_title[] = $filter;
		}   
	}

	public function renderForm()
	{
		$this->initFormToolbar();
		if (!$this->loadObject(true))
			return;

		$informations = '<ol>';
		$informations .= '<li>'.$this->l('Mega Menu - Remember - even if you create new row mega menu will have a width of first columns, for eg. if you use 6 columns and make new row, if you want to have this row on 50% width you need to choose width of 3 columns instead of 6 columns').'</li>';
		$informations .= '<li>'.$this->l('Mega Products - Each product has a width of '.Configuration::get('PH_MM_PRODUCT_WIDTH').' columns - you can change this in mega menu configuration').'</li>';
		$informations .= '</ul>';
		$this->displayInformation($informations);


		$obj = $this->loadObject(true);
		$id_parent = Tools::getValue('id_parent', $obj->id_parent);
		$type = Tools::getValue('type', $obj->type);

		$columns = array();
		// ToDo: Przemyslec ten mechanizm
		// if($items[0]['type'] == '7')
		// {
		// 	$available_columns = 12;
		// }
		// else
		// {
			
		//}

		// if($id_parent > 0 && $this->display == 'edit')
		// {
		// 	$second_sql = 'SELECT type, columns, id_prestahome_megamenu FROM `'._DB_PREFIX_.'prestahome_megamenu` WHERE id_parent = '.$id_parent.' AND id_prestahome_megamenu <= '.$obj->id.' ORDER BY id_prestahome_megamenu DESC';
		// 	$items = DB::getInstance()->executeS($second_sql);

		// 	$used_columns = 0;
		// 	foreach($items as $item)
		// 	{
		// 		if($item['type'] == '7')
		// 		{
		// 			$used_columns -= $used_columns;
		// 		}
		// 		else
		// 		{
		// 			$used_columns += $item['columns'];
		// 		}
		// 	}

		// 	$available_columns = 12-$used_columns;

		// 	if($available_columns == 0)
		// 	{
		// 		for ($i = 1; $i <= $obj->columns; $i++) {
		// 			$columns[$i]['label'] = $i.' '.$this->l('column(s)');
		// 			$columns[$i]['value'] = $i;
		// 		}
		// 	}
		// 	else
		// 	{
		// 		for ($i = 1; $i <= ($obj->columns+$available_columns); $i++) {
		// 			$columns[$i]['label'] = $i.' '.$this->l('column(s)');
		// 			$columns[$i]['value'] = $i;
		// 		}
		// 	}
		// }

		// if($id_parent > 0 && $this->display == 'add' && $type != 0 && $type != 7)
		// {
		// 	if($available_columns == 0)
		// 	{
		// 		$columns[0]['label'] = 0;
		// 		$columns[0]['value'] = 0;

		// 		$this->errors = $this->l('You reach maximum number of columns in this tab (12), you can add new row.');
		// 		return;
		// 	}
		// 	else
		// 	{
		// 		for ($i = 1; $i <= $available_columns; $i++) {
		// 			$columns[$i]['label'] = $i.' '.$this->l('column(s)');
		// 			$columns[$i]['value'] = $i;
		// 		}
		// 	}
		// }
		for ($i = 1; $i <= 12; $i++) {
			$columns[$i]['label'] = $i.' '.$this->l('column(s)');
			$columns[$i]['value'] = $i;
		}

		$this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
		$this->tpl_form_vars['languages'] = $this->_languages;
		$this->tpl_form_vars['prestahome_megamenu'] = $obj;
		$this->tpl_form_vars['is_16'] = $this->is_16;

		/**


            LINKS ACCESS
            

        **/
        if (isset($obj->id) && $obj->access)
        {
            $groupAccess = unserialize($obj->access);

            foreach ($groupAccess as $groupAccessID => $value)
            {
                $groupBox = 'groupBox_'.$groupAccessID;
                $this->fields_value[$groupBox] = $value;
            }
        } 
        else 
        {
            $groups = Group::getGroups($this->context->language->id);
            $preselected = array(
                Configuration::get('PS_UNIDENTIFIED_GROUP'),
                Configuration::get('PS_GUEST_GROUP'),
                Configuration::get('PS_CUSTOMER_GROUP')
            );
            foreach ($groups as $group){
                $this->fields_value['groupBox_'.$group['id_group']] = (in_array($group['id_group'], $preselected));
            }
        }

		$i = 0;

		// Base tab
		if($id_parent == 0)
		{
			$selected_cat = array(($obj->id_category_parent > 0 ? $obj->id_category_parent : 2));
			$root_category = Category::getRootCategory();
			$root_category = array('id_category' => $root_category->id, 'name' => $root_category->name);

			if($this->is_16)
			{
				$categories_select = array(
					'type'  => 'categories',
					'form_group_class' => 'categories',
					'label' => $this->l('Parent category'),
					'name'  => 'id_category_parent',
					'tree'  => array(
						'id'                  => 'categories-tree',
						'selected_categories' => $selected_cat,
						'disabled_categories' => null,
						'root_category' => Context::getContext()->shop->getCategory()
					)
				);
			}
			else
			{
				$categories_select = array(
					'type' => 'categories',
					'label' => $this->l('Parent category'),
					'name' => 'id_category_parent',
					'values' => array(
						'trads' => array(
							 'Root' => $root_category,
							 'selected' => $this->l('Selected'),
							 'Collapse All' => $this->l('Collapse All'),
							 'Expand All' => $this->l('Expand All')
						),
						'selected_cat' => $selected_cat,
						'input_name' => 'id_category_parent',
						'use_radio' => true,
						'use_search' => false,
						'disabled_categories' => array(4),
						'top_category' => Category::getTopCategory(),
						'use_context' => true,
					)
				);
			}

			$this->fields_form[$i]['form'] = array(
				'legend' => array(
					'title' => $this->l('Options'),
				),
				'input' => array(

					array(
						'type' => 'text',
						'label' => $this->l('Title:'),
						'name' => 'title',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Display block title?'),
						'desc' => $this->l('This option is usefull when you want to use icon font for eg home link and make title unvisible, leaves only icon'),
						'name' => 'display_title',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'display_title_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'display_title_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Open in new window?'),
						'name' => 'new_window',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'new_window_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'new_window_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Move to right?'),
						'name' => 'align',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'align_on',
								'value' => 1,
							),
							array(
								'id' => 'align_off',
								'value' => 0,
							)
						),
					),

					array(
						'type' => 'text',
						'label' => $this->l('Icon:'),
						'name' => 'icon',
						'desc' => $this->l('FontAwesome icon, eg fa-home, fa-envelope, fa-phone etc. List of available icons: http://fontawesome.io/icons/'),
						'required' => false,
						'lang' => false,
					),

					array(
						'type' => 'text',
						'label' => $this->l('URL:'),
						'name' => 'url',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'text',
						'label' => $this->l('Additional CSS class:'),
						'name' => 'class',
						'required' => false,
					),

					array(
						'type' => 'radio',
						'label' => $this->l('Type:'),
						'name' => 'type',
						'required' => true,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'default',
								'value' => 0,
								'label' => $this->l('Default')
							),
							array(
								'id' => 'megamenu',
								'value' => 1,
								'label' => $this->l('Mega menu')
							),
							array(
								'id' => 'dropdown_categories',
								'value' => 2,
								'label' => $this->l('Categories dropdown')
							),
						),
					),

					$categories_select,

				),
				'submit' => array(
					'title' => $this->l('Save'),
					'stay' => true,
					'class' => 'button'
				)
			);
			$i++;

			if($this->display == 'add')
			{
				$this->fields_value['label_bg'] = Configuration::get('PH_MM_DEFAULT_LABEL_BG');
				$this->fields_value['label_color'] = Configuration::get('PH_MM_DEFAULT_LABEL_COLOR');
			}

			$this->fields_form[$i]['form'] = array(
				'legend' => array(
					'title' => $this->l('Menu label'),
				),
				'input' => array(

					array(
						'type' => 'text',
						'label' => $this->l('Text:'),
						'name' => 'label_text',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'color',
						'label' => $this->l('Background:'),
						'name' => 'label_bg',
						'required' => false,
					),

					array(
						'type' => 'color',
						'label' => $this->l('Color:'),
						'name' => 'label_color',
						'required' => false,
					),

				),
				'submit' => array(
					'title' => $this->l('Save'),
					'stay' => true,
					'class' => 'button'
				)
			);
			$i++;
		}

		// Options for mega menu
		if($id_parent == 0 && $type == 1 && $this->display == 'edit')
		{
			$this->fields_form[$i]['form'] = array(
				'legend' => array(
					'title' => $this->l('Mega Menu - Options'),
				),
				'input' => array(

					array(
						'type' => 'file',
						'label' => $this->l('Background image:'),
						'name' => 'background_img',
						'required' => false,
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'stay' => true,
					'class' => 'button'
				)
			);
			$i++;
		}

		// New dropdown
		if($id_parent != 0 && $type == 0)
		{
			$this->fields_form[$i]['form'] = array(
				'legend' => array(
					'title' => $this->l('Menu - Options'),
				),
				'input' => array(

					array(
						'type' => 'hidden',
						'name' => 'id_parent',
						'value' => $id_parent,
					),

					array(
						'type' => 'text',
						'label' => $this->l('Title:'),
						'name' => 'title',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'text',
						'label' => $this->l('Icon:'),
						'name' => 'icon',
						'desc' => $this->l('FontAwesome icon, eg fa-home, fa-envelope, fa-phone etc. List of available icons: http://fontawesome.io/icons/'),
						'required' => false,
						'lang' => false,
					),

					array(
						'type' => 'text',
						'label' => $this->l('URL:'),
						'name' => 'url',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'text',
						'label' => $this->l('Additional CSS class:'),
						'name' => 'class',
						'required' => false,
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'stay' => true,
					'class' => 'button'
				)
			);
			$i++;
		}

		// Mega Categories
		if($id_parent > 0 && $type == 4 || $id_parent > 0 && $type == 8)
		{
			$selected_cat = array(($obj->id_category_parent > 0 ? $obj->id_category_parent : 2));

			if($type == 8)
			{
				$categories_select = array(
					'type'  => 'categories',
					'form_group_class' => 'categories',
					'label' => $this->l('Parent category'),
					'name'  => 'id_category_parent',
					'tree'  => array(
						'id'                  => 'categories-tree',
						'selected_categories' => $selected_cat,
						'selected_cat_ids' => implode(',', array_keys($selected_cat)),
						'disabled_categories' => null,
						'use_checkbox' => true,
						'root_category' => Context::getContext()->shop->id_category
					)
				);
			}
			else if($type == 4)
			{
				$categories_select = array(
					'type'  => 'categories',
					'form_group_class' => 'categories',
					'label' => $this->l('Parent category'),
					'name'  => 'id_category_parent',
					'tree'  => array(
						'id'                  => 'categories-tree',
						'selected_categories' => $selected_cat,
						'disabled_categories' => null,
						'root_category' => Context::getContext()->shop->getCategory()
					)
				);
			}

			$this->fields_form[$i]['form'] = array(
				'legend' => array(
					'title' => $this->l('Mega Categories from parent - Content'),
				),
				'input' => array(

					array(
						'type' => 'text',
						'label' => $this->l('Title (for back-office only):'),
						'name' => 'title',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Display main category title?'),
						'name' => 'display_title',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'display_title_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'display_title_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),

					array(
						'type' => 'text',
						'label' => $this->l('Additional CSS class:'),
						'name' => 'class',
						'required' => false,
					),

					array(
						'type' => 'hidden',
						'name' => 'type',
						'value' => $type,
					),

					array(
						'type' => 'hidden',
						'name' => 'id_parent',
						'value' => $id_parent,
					),

					$categories_select,

					array(
						'type' => 'select',
						'label' => $this->l('Columns:'),
						'name' => 'columns',
						'required' => true,
						'options' => array(
							'id' => 'value',
							'query' => $columns,
							'name' => 'label'
						)
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Start as a new row?'),
						'name' => 'new_row',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'new_row_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'new_row_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'stay' => true,
					'class' => 'button'
				)
			);
			$i++;
		}

		// Custom HTML
		if($id_parent > 0 && $type == 5)
		{
			$this->fields_form[$i]['form'] = array(
				'legend' => array(
					'title' => $this->l('Custom HTML'),
				),
				'input' => array(

					array(
						'type' => 'text',
						'label' => $this->l('Title:'),
						'name' => 'title',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Display block title?'),
						'desc' => $this->l('This option is usefull when you want to use icon font for eg home link and make title unvisible, leaves only icon'),
						'name' => 'display_title',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'display_title_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'display_title_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),

					array(
						'type' => 'text',
						'label' => $this->l('Icon:'),
						'name' => 'icon',
						'desc' => $this->l('FontAwesome icon, eg fa-home, fa-envelope, fa-phone etc. List of available icons: http://fontawesome.io/icons/'),
						'required' => false,
						'lang' => false,
					),

					array(
						'type' => 'text',
						'label' => $this->l('URL:'),
						'name' => 'url',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'hidden',
						'name' => 'type',
						'value' => $type,
					),

					array(
						'type' => 'hidden',
						'name' => 'id_parent',
						'value' => $id_parent,
					),

					array(
						'type' => 'textarea',
						'autoload_rte' => true,
						'label' => $this->l('Content:'),
						'name' => 'content',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'select',
						'label' => $this->l('Columns:'),
						'name' => 'columns',
						'required' => true,
						'options' => array(
							'id' => 'value',
							'query' => $columns,
							'name' => 'label'
						)
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Start as a new row?'),
						'name' => 'new_row',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'new_row_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'new_row_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				   
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'stay' => true,
					'class' => 'button'
				)
			);
			$i++;
		}

		// Product
		if($id_parent > 0 && $type == 6)
		{

			$sql = 'SELECT p.`id_product`, `reference`, pl.name
			FROM `'._DB_PREFIX_.'product` p
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int)Context::getContext()->language->id.')';

			$products = Db::getInstance()->executeS($sql);

			$this->fields_form[$i]['form'] = array(
				'legend' => array(
					'title' => $this->l('Product(s)'),
				),
				'input' => array(

					array(
						'type' => 'hidden',
						'name' => 'type',
						'value' => $type,
					),

					array(
						'type' => 'hidden',
						'name' => 'id_parent',
						'value' => $id_parent,
					),

					array(
						'type' => 'text',
						'label' => $this->l('Title:'),
						'name' => 'title',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Display block title?'),
						'desc' => $this->l('This option is usefull when you want to use icon font for eg home link and make title unvisible, leaves only icon'),
						'name' => 'display_title',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'display_title_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'display_title_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),

					array(
						'type' => 'text',
						'label' => $this->l('Icon:'),
						'name' => 'icon',
						'desc' => $this->l('FontAwesome icon, eg fa-home, fa-envelope, fa-phone etc. List of available icons: http://fontawesome.io/icons/'),
						'required' => false,
						'lang' => false,
					),

					array(
						'type' => 'text',
						'label' => $this->l('URL:'),
						'name' => 'url',
						'required' => false,
						'lang' => true,
					),

					array(
						'type' => 'select',
						'label' => $this->l('Product:'),
						'name' => 'id_product[]',
						'id' => 'select_product',
						'multiple' => true,
						'required' => true,
						'options' => array(
							'id' => 'id_product',
							'query' => $products,
							'name' => 'name',
						)
					),

					array(
						'type' => 'select',
						'label' => $this->l('Columns:'),
						'name' => 'columns',
						'required' => true,
						'options' => array(
							'id' => 'value',
							'query' => $columns,
							'name' => 'label'
						)
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Start as a new row?'),
						'name' => 'new_row',
						'required' => false,
						'class' => 't',
						'is_bool' => false,
						'values' => array(
							array(
								'id' => 'new_row_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'new_row_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				   
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'stay' => true,
					'class' => 'button'
				)
			);

			$this->fields_value['id_product[]'] = explode(',', $obj->id_product);

			$i++;
		}

		$unidentified = new Group(Configuration::get('PS_UNIDENTIFIED_GROUP'));
        $guest = new Group(Configuration::get('PS_GUEST_GROUP'));
        $default = new Group(Configuration::get('PS_CUSTOMER_GROUP'));

        $unidentified_group_information = sprintf($this->l('%s - All people without a valid customer account.'), '<b>'.$unidentified->name[$this->context->language->id].'</b>');
        $guest_group_information = sprintf($this->l('%s - Customer who placed an order with the guest checkout.'), '<b>'.$guest->name[$this->context->language->id].'</b>');
        $default_group_information = sprintf($this->l('%s - All people who have created an account on this site.'), '<b>'.$default->name[$this->context->language->id].'</b>');

		$this->fields_form[$i]['form'] = array(
			'legend' => array(
				'title' => $this->l('Availability'),
			),
			'input' => array(
			   array(
					'type' => 'switch',
					'label' => $this->l('Displayed:'),
					'name' => 'active',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
				),
			   	array(
					'type' => 'switch',
					'label' => $this->l('Disable on mobile:'),
					'name' => 'hide_on_mobile',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'hide_on_mobile_on',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'hide_on_mobile_off',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Disable on desktop:'),
					'name' => 'hide_on_desktop',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'hide_on_desktop_on',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'hide_on_desktop_off',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
				),
			   	array(
                    'type' => 'group',
                    'label' => $this->l('Group access'),
                    'name' => 'groupBox',
                    'values' => Group::getGroups(Context::getContext()->language->id),
                    'info_introduction' => $this->l('You now have three default customer groups.'),
                    'unidentified' => $unidentified_group_information,
                    'guest' => $guest_group_information,
                    'customer' => $default_group_information,
                    'hint' => $this->l('Mark all of the customer groups which you would like to have access to this category.')
                )

			),
			'submit' => array(
				'title' => $this->l('Save'),
				'stay' => true,
				'class' => 'button'
			),
		);
		$i++;


		if (Shop::isFeatureActive())
			$this->fields_form[$i]['form'] = array(
			'legend' => array(
				'title' => $this->l('Shop association:')
			),      
			'input' => array(   
				array(
					'type' => 'shop',
					'label' => $this->l('Shop association:'),
					'name' => 'checkBoxShopAsso',
				),
			
			)
		);
		$i++;

		$this->multiple_fieldsets = true;

		return parent::renderForm();
	}

	public function assignGroupsToLinks()
    {
        $groups = Group::getGroups($this->context->language->id);
        $groupBox = Tools::getValue('groupBox', array());
        $access = array();
        
        if (!$groupBox)
        {
            foreach ($groups as $group)
            {
                $access[$group['id_group']] = false;
            }
        } 
        else 
        {
            foreach ($groups as $group)
            {
                $access[$group['id_group']] = in_array($group['id_group'], $groupBox);
            }
        }
        
        $access = serialize($access);
        $_POST['access'] = $access;
    }

	public function postProcess()
	{
		$this->assignGroupsToLinks();
		
		$moduleInstance = new ph_megamenu();
		$moduleInstance->clearMenuCache('ph_megamenu.tpl');
		
		if(Tools::getValue('id_product', 0))
		{
			$_POST['id_product'] = join(Tools::getValue('id_product'), ',');
		}

		if (($id_prestahome_megamenu = (int)Tools::getValue('id_prestahome_megamenu')) && ($direction = Tools::getValue('move')) && Validate::isLoadedObject($megamenu = new PrestaHomeMegaMenu($id_prestahome_megamenu)))
		{
			if ($megamenu->move($direction))
				Tools::redirectAdmin(self::$currentIndex.'&token='.$this->token);
		}
		elseif (Tools::getValue('position') && !Tools::isSubmit('submitAdd'.$this->table))
		{
			if ($this->tabAccess['edit'] !== '1')
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
			elseif (!Validate::isLoadedObject($object = new PrestaHomeMegaMenu((int)Tools::getValue($this->identifier))))
				$this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').
					' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
			if (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
				$this->errors[] = Tools::displayError('Failed to update the position.');
			else
				Tools::redirectAdmin(self::$currentIndex.'&conf=5&token='.Tools::getAdminTokenLite('AdminTabs'));
		}
		elseif (Tools::isSubmit('submitAddaprestahome_megamenu') && Tools::getValue('id_prestahome_megamenu') === Tools::getValue('id_parent'))
			$this->errors[] = Tools::displayError('You can\'t put this menu inside itself. ');
		else
		{
			// Temporary add the position depend of the selection of the parent category
			if (!Tools::isSubmit('id_prestahome_megamenu')) // @todo Review
				$_POST['position'] = PrestaHomeMegaMenu::getNbMenus(Tools::getValue('id_parent'));
		}
		
		if (!count($this->errors))
			parent::postProcess();
		
	}

	public function ajaxProcessUpdatePositions()
	{
		$id_prestahome_megamenu = (int)(Tools::getValue('id'));
		$positions = Tools::getValue('prestahome_megamenu');

		$moduleInstance = new ph_megamenu();
		$moduleInstance->clearMenuCache('ph_megamenu.tpl');

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			$id_prestahome_megamenu = (int)$pos[2];

			if ((int)$id_prestahome_megamenu > 0)
			{
				if ($MegaMenu = new PrestaHomeMegaMenu($id_prestahome_megamenu))
				{
					$MegaMenu->position = $position+1;
					if($MegaMenu->update())
						echo 'ok position '.(int)$position.' for category '.(int)$MegaMenu->id.'\r\n';
				}
				else
				{
					echo '{"hasError" : true, "errors" : "This category ('.(int)$id_prestahome_megamenu.') cant be loaded"}';
				}

			}
		}
	}

	protected function afterDelete($object, $oldId)
	{
		$moduleInstance = new ph_megamenu();
		$moduleInstance->clearMenuCache('ph_megamenu.tpl');
		return true;
	}

	protected function afterAdd($object)
	{
		$moduleInstance = new ph_megamenu();
		$moduleInstance->clearMenuCache('ph_megamenu.tpl');

		return true;
	}

	protected function afterUpdate($object)
	{
		$moduleInstance = new ph_megamenu();
		$moduleInstance->clearMenuCache('ph_megamenu.tpl');
		return true;
	}

}
