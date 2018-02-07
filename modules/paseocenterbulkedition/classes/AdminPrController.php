<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Pronimbo.com
 * @copyright Pronimbo.com. all rights reserved.
 * @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 */

class AdminPrController extends ModuleAdminControllerCore
{
	public $module_list;
	const  PR_VERSION = '1.0.2';
	public function __construct()
	{
		$this->context = Context::getContext();
		$this->bootstrap = true;
		parent::__construct();
	}

	public function renderList()
	{
		$inputs = Hook::exec('action'.$this->context->controller->controller_name.'RenderFormInput', array(), null, true);
		if (!is_array($this->fields_list))
			$this->fields_list = array();
		if (is_array($inputs)) foreach ($inputs as $input)
			$this->fields_list = array_merge($this->fields_list, $input);
		if (!($this->fields_list && is_array($this->fields_list)))
			return false;
		$this->getList($this->context->language->id);
		if (isset($this->fields_list) && is_array($this->fields_list) && array_key_exists('active', $this->fields_list) && !empty($this->fields_list['active']))
		{
			$inputs = Hook::exec('action'.$this->context->controller->controller_name.'RenderListBulkAction', array(), null, true);
			if (!is_array($this->bulk_actions)) $this->bulk_actions = array();
			if (is_array($inputs)) foreach ($inputs as $input) $this->bulk_actions = array_merge($this->bulk_actions, $input);
		}
		$helper = new HelperList();
		if (!is_array($this->_list))
		{
			$this->displayWarning($this->l('Bad SQL query', 'Helper').'<br />'.htmlspecialchars($this->_list_error));
			return false;
		}
		$this->setHelperDisplay($helper);
		$helper->tpl_vars = $this->tpl_list_vars;
		$helper->tpl_delete_link_vars = $this->tpl_delete_link_vars;
		foreach ($this->actions_available as $action)
			if (!in_array($action, $this->actions) && isset($this->$action) && $this->$action)
				$this->actions[] = $action;

		$helper->module = $this->module;
		$helper->token = $this->token;
		$list = $helper->generateList($this->_list, $this->fields_list);
		return $list;
	}

	public function renderForm()
	{
		if (!($obj = $this->loadObject(true)))
			return;

		$fieldsets = Hook::exec('action'.$this->context->controller->controller_name.'RenderFormFieldsets', array(), null, true);
		if (!is_array($this->fields_form['form']['fieldsets'])) $this->fields_form['form']['fieldsets'] = array();
		if (is_array($fieldsets)) foreach ($fieldsets as $fieldsets) $this->fields_form['form']['fieldsets'] = array_merge($this->fields_form['form']['fieldsets'], $fieldsets);
		$inputs = Hook::exec('action'.$this->context->controller->controller_name.'RenderFormInput', array(), null, true);
		if (!is_array($this->fields_form['form']['input'])) $this->fields_form['form']['input'] = array();
		if (is_array($inputs)) foreach ($inputs as $input) $this->fields_form['form']['input'] = array_merge($this->fields_form['form']['input'], $input);
		$tabs = Hook::exec('action'.$this->context->controller->controller_name.'RenderFormTabs', array(), null, true);
		if (!is_array($this->fields_form['form']['tabs'])) $this->fields_form['form']['tabs'] = array();
		if (is_array($tabs)) foreach ($tabs as $tab) $this->fields_form['form']['tabs'] = array_merge($this->fields_form['form']['tabs'], $tab);

			/************************/
		$helper = new HelperForm();
		$helper->toolbar_btn = array('save' => array('desc' => $this->l('Save'), 'href' => AdminController::$currentIndex.'&configure='.$this->module->name.'&save'.$this->module->name.'&token='.$this->token,), 'back' => array('href' => AdminController::$currentIndex.'&configure='.$this->module->name.'&token='.$this->token, 'desc' => $this->l('Back to list')));
		$this->setHelperDisplay($helper);
		$helper->module = $this->module;
		$helper->fields_value = $this->getFormValues($obj);
		$helper->tpl_vars = $this->tpl_form_vars;
		$helper->name_controller = $this->controller_name;
		$languages = Language::getLanguages();
		if (version_compare(_PS_VERSION_, '1.6.0', '>='))
		{
			$helper = $this->helper;
			$helper->submit_action = ($this->submit_action) ? $this->submit_action : self::$currentIndex.((Tools::getValue($this->identifier)) ? '&update'.$this->table : '&add'.$this->table);
			$helper->tpl_vars['ps15'] = false;
		}
		else
		{
			$helper->tpl_vars['current_id_lang'] = $this->context->language->id;
			$helper->tpl_vars['ps15'] = true;
		}

		foreach ($languages as &$language)
			$language['is_default'] = ($language['id_lang'] == $this->context->language->id);
		$helper->languages = $languages;
		$helper->token = $this->token;
		$helper->default_form_language = $this->context->language->id;
		$helper->show_cancel_button = (isset($this->show_form_cancel_button)) ? $this->show_form_cancel_button : ($this->display == 'add' || $this->display == 'edit');
		$back = Tools::safeOutput(Tools::getValue('back', ''));
		if (empty($back)) $back = self::$currentIndex.'&token='.$this->token;
		if (!Validate::isCleanHtml($back)) die(Tools::displayError());
		$helper->back_url = $back;
		!is_null($this->base_tpl_form) ? $helper->base_tpl = $this->base_tpl_form : '';
		if ($this->tabAccess['view'])
		{
			if (Tools::getValue('back')) $helper->tpl_vars['back'] = Tools::safeOutput(Tools::getValue('back'));
			else
				$helper->tpl_vars['back'] = Tools::safeOutput(Tools::getValue(self::$currentIndex.'&token='.$this->token));
		}
		return $helper->generateForm(array($this->fields_form));
	}

	public function renderView()
	{
		return $this->renderForm();
	}

	public function renderOptions()
	{
		return parent::renderOptions();
	}

	public function getFormValues()
	{
		$out = array();
		if ($this->object instanceof ObjectModel) $out = get_object_vars($this->object);
		$forms_values = Hook::exec('action'.$this->controller_name.'GetFormValues', null, false, true);
		if (is_array($forms_values)) foreach ($forms_values as $form_values) $out = array_merge($out, $form_values);
		return $out;
	}

	public function setMedia()
	{
		parent::setMedia();
		if (version_compare(_PS_VERSION_, '1.6.0', '<'))
		{
			$css_path = $this->module->name.'/views/css/css15/';
			$js_path = $this->module->name.'/views/js/js15/';
			$css_dir = scandir(_PS_MODULE_DIR_.$css_path);
			foreach ($css_dir as $file)
				if (!in_array($file, array('.', '..')) && strpos($file, '.css') > -1 && file_exists(_PS_MODULE_DIR_.$css_path.$file))
					$this->addCSS(_MODULE_DIR_.$css_path.$file);

			$js_dir = scandir(_PS_MODULE_DIR_.$js_path);
			foreach ($js_dir as $file)
				if (!in_array($file, array('.', '..')) && strpos($file, '.js') > -1 && file_exists(_PS_MODULE_DIR_.$js_path.$file))
					$this->addJS(_MODULE_DIR_.$js_path.$file);
		}
		Hook::exec('action'.$this->controller_name.'SetMedia');
	}

	public function postProcess()
	{
		parent::postProcess();
		Hook::exec('action'.$this->controller_name.'PostProcess');
	}

	public function setHelperDisplay(Helper $helper)
	{
		if (version_compare(_PS_VERSION_, '1.6.0', '>='))
			return parent::setHelperDisplay($helper);

		if (empty($this->toolbar_title))
			$this->initToolbarTitle();
		if ($this->object && isset($this->object->id) && $this->object->id)
			$helper->id = $this->object->id;

		// @todo : move that in Helper
		$helper->title = $this->toolbar_title;
		$helper->toolbar_btn = $this->toolbar_btn;
		$helper->show_toolbar = $this->show_toolbar;
		$helper->toolbar_scroll = $this->toolbar_scroll;
		$helper->override_folder = $this->tpl_folder;
		$helper->actions = $this->actions;
		$helper->simple_header = $this->list_simple_header;
		$helper->bulk_actions = $this->bulk_actions;
		$helper->currentIndex = self::$currentIndex;
		$helper->className = $this->className;
		$helper->table = $this->table;
		$helper->name_controller = Tools::getValue('controller');
		$helper->orderBy = $this->_orderBy;
		$helper->orderWay = $this->_orderWay;
		$helper->listTotal = $this->_listTotal;
		$helper->shopLink = $this->shopLink;
		$helper->shopLinkType = $this->shopLinkType;
		$helper->identifier = $this->identifier;
		$helper->token = $this->token;
		$helper->languages = $this->_languages;
		$helper->specificConfirmDelete = $this->specificConfirmDelete;
		$helper->imageType = $this->imageType;
		$helper->no_link = $this->list_no_link;
		$helper->colorOnBackground = $this->colorOnBackground;
		$helper->ajax_params = (isset($this->ajax_params) ? $this->ajax_params : null);
		$helper->default_form_language = $this->default_form_language;
		$helper->allow_employee_form_lang = $this->allow_employee_form_lang;
		$helper->multiple_fieldsets = $this->multiple_fieldsets;
		$helper->row_hover = $this->row_hover;
		$helper->position_identifier = $this->position_identifier;

		$helper->list_skip_actions = $this->list_skip_actions;
	}
	public function initToolbar()
	{
		if (method_exists($this, 'initPageHeaderToolbar') && version_compare(_PS_VERSION_, '1.6.0', '<'))
		{
			$this->initPageHeaderToolbar();
			foreach ($this->page_header_toolbar_btn as $key => $btn)
				$this->toolbar_btn[$key] = $btn;
		}
		else
			parent::initToolbar();
	}
	public function initPageHeaderToolbar()
	{
		$this->page_header_toolbar_btn = array();
		if (method_exists('AdminController', 'initPageHeaderToolbar' ))
			parent::initPageHeaderToolbar();

	}
}

?>
