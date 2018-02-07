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

if(file_exists(_PS_MODULE_DIR_ . 'ph_iconboxes/models/PrestaHomeIconBox.php'))
	require_once _PS_MODULE_DIR_ . 'ph_iconboxes/models/PrestaHomeIconBox.php';

class PH_IconBoxes extends Module {
	
	public function __construct() {
		$this->name = 'ph_iconboxes';
		$this->tab = 'advertising_marketing';
		$this->version = '1.0.2';
		$this->author = 'www.PrestaHome.com';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Icon Boxes');
		$this->description = $this->l('Add block with icon and text');

		if($this->id)
		{
			if(!$this->isRegisteredInHook('displayTopColumn'))
				$this->registerHook('displayTopColumn');

			if(!$this->isRegisteredInHook('displayAfterContent'))
				$this->registerHook('displayAfterContent');

			if(!$this->isRegisteredInHook('displayBeforeFooter'))
				$this->registerHook('displayBeforeFooter');

			if(!$this->isRegisteredInHook('displayAfterFooter'))
				$this->registerHook('displayAfterFooter');
		}
	}

	public function install()
	{
		if (Shop::isFeatureActive()){
			Shop::setContext(Shop::CONTEXT_ALL);
		}

		if (!parent::install() ||
			!$this->prepareModuleSettings() ||
			!$this->registerHook('displayTopColumn') ||
			!$this->registerHook('displayHome') ||
			!$this->registerHook('displayAfterContent') ||
			!$this->registerHook('displayBeforeFooter') ||
			!$this->registerHook('displayAfterFooter') ||
			!$this->registerHook('actionShopDataDuplication'))
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall()) {
			return false;
		}

		// Database
		$sql = array();
		include (dirname(__file__) . '/init/uninstall_sql.php');
		foreach ($sql as $s) {
			if (!Db::getInstance()->Execute($s)) {
				return false;
			}
		}
	 
		// For theme developers - you're welcome!
		if(file_exists(_PS_MODULE_DIR_.'ph_iconboxes/init/my-uninstall.php'))
			include_once _PS_MODULE_DIR_.'ph_iconboxes/init/my-uninstall.php';

		return true;
	}

	public function prepareModuleSettings()
	{
		// Database
		$sql = array();
		include (dirname(__file__) . '/init/install_sql.php');
		foreach ($sql as $s) {
			if (!Db::getInstance()->Execute($s)) {
				return false;
			}
		}
		// Demo content
		Shop::addTableAssociation('prestahome_iconbox', array('type' => 'shop'));
		Shop::setContext(Shop::CONTEXT_ALL);

		/**
		
			For theme developers - you're welcome!

		**/
		if(file_exists(_PS_MODULE_DIR_.'ph_iconboxes/init/my-install.php'))
			include_once _PS_MODULE_DIR_.'ph_iconboxes/init/my-install.php';

		return true;
	}

	public function getContent() {
		return  $this->configHeader().
				$this->postProcess().
				$this->renderForm().
				$this->renderIconBoxLists();
	}
	
	public function configHeader() {
		$this->context->controller->addCss($this->_path.'views/css/configure.css');
		$this->context->controller->addJqueryUI('ui.sortable');
		$this->context->controller->addJs($this->_path.'views/js/configure.js');
	}
	
	protected function renderIconBoxLists() {
		$output = '';
		$hooks = $this->getDisplayHookList();
		foreach ($hooks as $hook) {
		    $icon_boxes = PrestaHomeIconBox::getByHook($hook['name'] , $this->context->language->id, $this->context->shop->id);
		    if (!empty($icon_boxes)) {
		        $output .= $this->renderList($icon_boxes, $hook);
		    }
		}
		return $output;
	}
	
	protected function renderList($icon_boxes, $hook) {
		$fields_list = array(
			'id_prestahome_iconbox' => array(
				'title' => $this->l(''),
				'type' => 'movable'
			),
			'title' => array(
				'title' => $this->l('Title'),
				'type' => 'text',
				'class' => 'field'
			),
			'icon' => array(
				'title' => $this->l('Icon'),
				'type' => 'text',
				'class' => 'field'
			),
			'columns' => array(
				'title' => $this->l('Columns'),
				'type' => 'text',
				'class' => 'field'
			),
			'url' => array(
				'title' => $this->l('Link'),
				'type' => 'link',
				'class' => 'field link'
			),
			'active' => array(
				'title' => $this->l('Active'),
				'type' => 'bool',
				'align' => 'center',
				'active' => 'status',
				'class' => 'field'
			)
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->identifier = 'id_prestahome_iconbox';
		$helper->table = $this->name;
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->module = $this;
		$helper->title = $this->l($hook['name']);
		$helper->list_id = $helper->title;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
		
		return $helper->generateList($icon_boxes, $fields_list);
	}
	
	protected function renderForm() {
		$isEdit = false;
		if (Tools::isSubmit('update'.$this->name)) {
			$isEdit = true;
		}

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => ($isEdit ? $this->l('Edit icon box') : $this->l('Add new icon box')),
					'icon' => ($isEdit ? 'icon-edit' : 'icon-camera')
				),
				'input' => array(
					array(
						'label' => $this->l('Title'),
						'type'  => 'textarea',
						'lang'  => true,
						'name'  => 'title',
						'required' => true
					),
					array(
						'label' => $this->l('Content'),
						'type'  => 'textarea',
						'lang'  => true,
						'name'  => 'content',
						'required' => false
					),
					array(
						'label' => $this->l('Icon'),
						'type'  => 'text',
						'name'  => 'icon',
						'required' => true,
						'desc' => $this->l('Please use one of these icons:').' <a href="http://fontawesome.io/icons/#web-application" class="_blank">FontAwesome</a> - '.$this->l('use name of the icon, for eg. delivery or calendar-o. There is no need to use "fa" prefix.')
					),
					array(
						'label' => $this->l('Link'),
						'type'  => 'text',
						'lang'  => true,
						'name'  => 'url'
					),
					array(
						'type' => 'select',
						'label' => $this->l('Hook'),
						'desc' => $this->l('Choose on what hook to display icon box'),
						'name' => 'hook',
						'options' => array(
							'query' => $this->getDisplayHookList(),
							'id' => 'name',
							'name' => 'desc'
						),
						'class' => 'selectHook',
						'required' => true
					),
					array(
						'type' => 'select',
						'label' => $this->l('Size'),
						'name' => 'columns',
						'options' => array(
							'query' => array(
								array(
									'name' => $this->l('1 (width of 12 columns)'),
									'value' => '12',
								),
								array(
									'name' => $this->l('1/2 (width of 6 columns)'),
									'value' => '6',
								),
								array(
									'name' => $this->l('1/3 (width of 4 columns)'),
									'value' => '4',
								),
								array(
									'name' => $this->l('1/4 (width of 3 columns)'),
									'value' => '3',
								),
								array(
									'name' => $this->l('1/6 (width of 2 columns)'),
									'value' => '2',
								),
							),
							'id' => 'value',
							'name' => 'name'
						),
						'required' => true
					),
					array(
						'label' => $this->l('Additional class(es)'),
						'type'  => 'text',
						'name'  => 'class'
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Active'),
						'name' => 'active',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'shop',
						'label' => $this->l('Shop association:'),
						'name' => 'checkBoxShopAsso',
					),

				),
				'submit' => array(
					'title' => $this->l('Save'),
					'class' => 'button pull-right'
				)
			)
		);
		
		
		$helper = new HelperForm();
		$helper->table = $this->table;
		$lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$language = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
			'fields_value' => $this->getFieldsValues($isEdit),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
		);

		$helper->submit_action = ($isEdit ? 'submitEditIconBox' : 'submitAddIconBox');

		if ($isEdit) {
			$helper->tpl_vars['id_prestahome_iconbox'] = Tools::getValue('id_prestahome_iconbox');
			$helper->show_cancel_button = true;
			$helper->back_url = $this->getBackUrl();
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_prestahome_iconbox');
		}

		$helper->override_folder = '/';
		
		return $helper->generateForm(array($fields_form));
	}
	
	protected function postProcess() {
		$validation = $this->_postValidate();
		if (is_string($validation)) {
			return $validation;
		}

		$languages = Language::getLanguages(false);

		$title = array(); 
		$url = array(); 
		
		if (Tools::isSubmit('submitAddIconBox')) {
			
			foreach($languages as $lang) {
				$title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
				$content[$lang['id_lang']] = Tools::getValue('content_' . $lang['id_lang']);
				$url[$lang['id_lang']] = Tools::getValue('url_' . $lang['id_lang']);
			}
			
			$icon_box = new PrestaHomeIconBox();
			$icon_box->hook = Tools::getValue('hook');
			$icon_box->active = Tools::getValue('active');
			$icon_box->icon = Tools::getValue('icon');
			$icon_box->class = Tools::getValue('class');
			$icon_box->columns = Tools::getValue('columns');
			$icon_box->title = $title;
			$icon_box->url = $url;

			if (!$icon_box->add() OR !$icon_box->associateTo(Tools::getValue('checkBoxShopAsso_module', Shop::getCompleteListOfShopsID())))
				return $this->displayError($this->l('An error occured while creating icon box'));

			$this->clearCache();
			return $this->displayConfirmation($this->l('Icon box added succesfully'));
		}
		
		if (Tools::isSubmit('submitEditIconBox')) {
			foreach($languages as $lang) 
			{
				$title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
				$content[$lang['id_lang']] = Tools::getValue('content_' . $lang['id_lang']);
				$url[$lang['id_lang']] = Tools::getValue('url_' . $lang['id_lang']);
			}

			$icon_box = new PrestaHomeIconBox((int)Tools::getValue('id_prestahome_iconbox'));
			$icon_box->hook = Tools::getValue('hook');
			$icon_box->active = Tools::getValue('active');
			$icon_box->icon = Tools::getValue('icon');
			$icon_box->class = Tools::getValue('class');
			$icon_box->columns = Tools::getValue('columns');
			$icon_box->title = $title;
			$icon_box->content = $content;
			$icon_box->url = $url;

			if (!$icon_box->update() OR !$icon_box->associateTo(Tools::getValue('checkBoxShopAsso_module', Shop::getCompleteListOfShopsID())))
				return $this->displayError($this->l('An error occured while updating icon box'));

			$this->clearCache();

			return $this->displayConfirmation($this->l('Icon box edited succesfully'));
		}
		
		if (Tools::isSubmit('delete'.$this->name)) {

			$icon_box = new PrestaHomeIconBox((int)Tools::getValue('id_prestahome_iconbox'));

			if(!$icon_box->delete())
				return $this->displayError($this->l('Could not delete icon box'));

			$this->clearCache();
			return $this->displayConfirmation($this->l('Icon box removed succesfully'));
		}
		
		if (Tools::isSubmit('status'.$this->name)) {
			$icon_box = new PrestaHomeIconBox((int)Tools::getValue('id_prestahome_iconbox'));
			$icon_box->active = !$icon_box->active;
			if (!$icon_box->save())
				return $this->displayError($this->l('An error occured while updating icon box status'));

			$this->clearCache();
			return $this->displayConfirmation($this->l('Icon box status changed succesfully'));
		}
	}
	
	protected function _postValidate() {
		$errors = array();
		
		if (
				Tools::isSubmit('status'.$this->name) ||
				Tools::isSubmit('delete'.$this->name) ||
				Tools::isSubmit('submitEditIconBox')
		) {
			// validate id_prestahome_iconbox
			if (!Validate::isUnsignedInt( Tools::getValue('id_prestahome_iconbox') )) {
				$errors[] = $this->l('Invalid ID');
			}
		}
		
		if (
				Tools::isSubmit('submitAddIconBox') ||
				Tools::isSubmit('submitEditIconBox')
		) {
			// validate title
			$languages = Language::getLanguages(false);
			$valid = false;
			foreach($languages as $lang) {
				if (Tools::getValue('title_'.$lang['id_lang']) != '') {
					$valid = true;
					break;
				}
			}
			if (!$valid) $errors[] = $this->l('Please enter the title of the box');
		}
		
		if (!empty($errors)) {
			return $this->displayError(implode('<br>', $errors));
		} else {
			return true;
		}
		
	}
	
	protected function getFieldsValues($isEdit) 
	{
		$languages = Language::getLanguages(false);
		$fields = array();
		
		if ($isEdit) 
		{
			$fields['id_prestahome_iconbox'] = (int)Tools::getValue('id_prestahome_iconbox');
			$icon_box = new PrestaHomeIconBox((int)Tools::getValue('id_prestahome_iconbox'), null, $this->context->shop->id);
			foreach($languages as $lang) {
				$fields['title'][$lang['id_lang']] = $icon_box->title[$lang['id_lang']];
				$fields['content'][$lang['id_lang']] = $icon_box->content[$lang['id_lang']];
				$fields['url'][$lang['id_lang']] = $icon_box->url[$lang['id_lang']];
			}

			$fields['hook'] = $icon_box->hook;
			$fields['class'] = $icon_box->class;
			$fields['columns'] = $icon_box->columns;
			$fields['active'] = $icon_box->active;
			$fields['icon'] = $icon_box->icon;
		} 
		else 
		{
			foreach($languages as $lang) 
			{
				$fields['title'][$lang['id_lang']] = '';
				$fields['content'][$lang['id_lang']] = '';
				$fields['url'][$lang['id_lang']] = '';
			}

			$fields['hook'] = 'displayTopColumn';
			$fields['class'] = '';
			$fields['icon'] = '';
			$fields['columns'] = '1';
			$fields['active'] = true;
		}
		
		return $fields;
	}
	
	protected function getBackUrl() 
	{
		$current_index = AdminController::$currentIndex;
		$token = Tools::getAdminTokenLite('AdminModules');

		$back = Tools::safeOutput(Tools::getValue('back', ''));

		if (!isset($back) || empty($back)) {
			$back = $current_index . '&amp;configure=' . $this->name . '&token=' . $token;
		}
		
		return $back;
	}
	
	
	protected function getDisplayHookList() 
	{
		$exclude_hook = array('displayHeader', 'actionShopDataDuplication');
		$hook_list = $this->getRegisteredHookList();
		foreach ($hook_list as $key => $hook) {
			foreach ($exclude_hook as $exclude) {
				if ($hook['name'] == $exclude) {
					unset($hook_list[$key]);
				}
			}
		}
		return $hook_list;
	}
	
   	private function getRegisteredHookList($id_shop = null) 
   	{
		$id_shop = Context::getContext()->shop->id;

	   	$sql = '
		   SELECT h.id_hook, h.name, h.description, CONCAT(h.name, \' - \', h.description) AS `desc`
		   FROM `'._DB_PREFIX_.'hook` AS h
		   JOIN `'._DB_PREFIX_.'hook_module` AS hm ON h.id_hook = hm.id_hook
		   WHERE hm.id_module = '.(int)($this->id).' AND hm.id_shop = '.(int)$id_shop;
	   	$result = Db::getInstance()->executeS($sql);

	   	return $result;
    }
	
	public function hookHeader() {}
	
	protected function _prepareHook($hook_name, $custom_tpl = false) 
	{
		$icon_boxes = PrestaHomeIconBox::getByHook($hook_name, $this->context->language->id, $this->context->shop->id, true);
		if (!empty($icon_boxes)) 
		{
			$this->smarty->assign(array(
				'icon_boxes' => $icon_boxes,
				'hook_name' => $hook_name,
			));

			if($custom_tpl)
				$tpl = $custom_tpl;
			else
				$tpl = 'hook';

			return $this->display(__FILE__, $tpl.'.tpl');
		}
		return;
	}
	
	public function hookDisplayIconBox() 
	{
		return $this->_prepareHook('displayIconBox');
	}

	public function hookDisplayHome() 
	{
		return $this->_prepareHook('displayHome');
	}

	public function hookDisplayTopColumn() 
	{
		return $this->_prepareHook('displayTopColumn');
	}

	public function hookDisplayBeforeContent() 
	{
		return $this->_prepareHook('displayBeforeContent');
	}

	public function hookDisplayAfterContent() 
	{
		return $this->_prepareHook('displayAfterContent');
	}

	public function hookDisplayAfterFooter() 
	{
		return $this->_prepareHook('displayAfterFooter');
	}

	public function hookDisplayBeforeFooter() 
	{
		return $this->_prepareHook('displayBeforeFooter');
	}

	public function clearCache() 
	{
		$this->_clearCache('hook.tpl', $this->getCacheId());
	}
}
