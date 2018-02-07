<?php
/**
* @author    PrestaHome Team <support@prestahome.com>
* @copyright  Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/

if (!defined('_PS_VERSION_'))
	exit;

if(file_exists(_PS_MODULE_DIR_ . 'ph_bannermanager/models/PrestaHomeBanner.php'))
	require_once _PS_MODULE_DIR_ . 'ph_bannermanager/models/PrestaHomeBanner.php';

class PH_BannerManager extends Module {
	
	public function __construct() {
		$this->name = 'ph_bannermanager';
		$this->tab = 'advertising_marketing';
		$this->version = '1.0.3';
		$this->author = 'www.PrestaHome.com';

		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Banner Manager');
		$this->description = $this->l('Manager banners in your store');

		if($this->id)
		{
			if(!$this->isRegisteredInHook('displayBeforeContent'))
				$this->registerHook('displayBeforeContent');

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
			!$this->registerHook('displayBeforeContent') ||
			!$this->registerHook('displayAfterContent') ||
			!$this->registerHook('displayTopColumn') ||
			!$this->registerHook('displayBeforeFooter') ||
			!$this->registerHook('displayAfterFooter') ||
			!$this->registerHook('displayHeader') ||
			!$this->registerHook('displayHome') ||
			!$this->registerHook('displayLeftColumn') ||
			!$this->registerHook('displayLeftColumnProduct') ||
			!$this->registerHook('displayRightColumn') ||
			!$this->registerHook('displayRightColumnProduct') ||
			!$this->registerHook('displayFooter') ||
			!$this->registerHook('displayFooterProduct') ||
			!$this->registerHook('displayMaintenance') ||
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
		if(file_exists(_PS_MODULE_DIR_.'ph_bannermanager/init/my-uninstall.php'))
			include_once _PS_MODULE_DIR_.'ph_bannermanager/init/my-uninstall.php';

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
		Shop::addTableAssociation('prestahome_banner', array('type' => 'shop'));
		Shop::setContext(Shop::CONTEXT_ALL);

		/**
		
			For theme developers - you're welcome!

		**/
		if(file_exists(_PS_MODULE_DIR_.'ph_bannermanager/init/my-install.php'))
			include_once _PS_MODULE_DIR_.'ph_bannermanager/init/my-install.php';

		return true;
	}

	public function getContent() {
		return  $this->configHeader().
				$this->postProcess().
				$this->renderForm().
				$this->renderBannerLists();
	}
	
	public function configHeader() {
		$this->context->controller->addCss($this->_path.'views/css/configure.css');
		$this->context->controller->addJqueryUI('ui.sortable');
		$this->context->controller->addJs($this->_path.'views/js/configure.js');
	}
	
	protected function renderBannerLists() {
		$output = '';
		$hooks = $this->getDisplayHookList();
		foreach ($hooks as $hook) {
		    $banners = PrestaHomeBanner::getByHook($hook['name'] , $this->context->language->id, $this->context->shop->id);
		    foreach($banners as &$banner)
		    	$banner['class'] = '';

		    if (!empty($banners)) {
		        $output .= $this->renderList($banners, $hook);
		    }
		}
		return $output;
	}
	
	protected function renderList($banners, $hook) {
		$fields_list = array(
			'id_prestahome_banner' => array(
				'title' => $this->l(''),
				'type' => 'movable'
			),
			'image' => array(
				'title' => $this->l('Image'),
				'type' => 'image',
				'image_baseurl' => PrestaHomeBanner::getRelativeImagePath($this->context->language->iso_code),
				'class' => 'field'
			),
			'title' => array(
				'title' => $this->l('Title'),
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
			'new_window' => array(
				'title' => $this->l('New window'),
				'type' => 'bool',
				'align' => 'center',
				'active' => 'new_window',
				'class' => 'field target'
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
		$helper->identifier = 'id_prestahome_banner';
		$helper->table = $this->name;
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->module = $this;
		$helper->title = $this->l($hook['name']);
		$helper->list_id = $helper->title;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
		
		return $helper->generateList($banners, $fields_list);
	}
	
	protected function renderForm() {
		$isEdit = false;
		if (Tools::isSubmit('update'.$this->name)) {
			$isEdit = true;
		}

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => ($isEdit ? $this->l('Edit banner') : $this->l('Add new banner')),
					'icon' => ($isEdit ? 'icon-edit' : 'icon-camera')
				),
				'input' => array(
					array(
						'type' => 'file_lang',
						'label' => $this->l('Upload Image'),
						'name' => 'image_upload',
						'lang' => true,
						'required' => true
					),
					array(
						'label' => $this->l('Title'),
						'type'  => 'text',
						'lang'  => true,
						'name'  => 'title',
						'required' => true
					),
					array(
						'label' => $this->l('Link'),
						'type'  => 'text',
						'lang'  => true,
						'name'  => 'url'
					),
					array(
						'type' => 'switch',
						'label' => $this->l('New window'),
						'name' => 'new_window',
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
						'type' => 'select',
						'label' => $this->l('Hook'),
						'desc' => $this->l('Choose on what hook to display the ad'),
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
		   	'image_baseurl' => PrestaHomeBanner::getRelativeImagePath()
		);

		$helper->submit_action = ($isEdit ? 'submitEditBanner' : 'submitAddBanner');

		if ($isEdit) {
			$helper->tpl_vars['id_prestahome_banner'] = Tools::getValue('id_prestahome_banner');
			$helper->show_cancel_button = true;
			$helper->back_url = $this->getBackUrl();
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_prestahome_banner');
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
		$images = array();
		
		if (Tools::isSubmit('submitAddBanner')) {
			
			foreach($languages as $lang) {
				$title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
				$url[$lang['id_lang']] = Tools::getValue('url_' . $lang['id_lang']);
				$images[$lang['id_lang']] = $this->upload_image($_FILES['image_upload_'.$lang['id_lang']], PrestaHomeBanner::getServerImagePath($lang['iso_code'], false));
			}
			
			$output = '';
			foreach ($images as &$error) {
				if (is_array($error)) {
					$output .= $this->displayError($error[0]);
					$error = '';
				}
			}
			if($output !== '') return $output;
			
			$banner = new PrestaHomeBanner();
			$banner->hook = Tools::getValue('hook');
			$banner->new_window = Tools::getValue('new_window');
			$banner->active = Tools::getValue('active');
			$banner->class = Tools::getValue('class');
			$banner->columns = Tools::getValue('columns');
			$banner->title = $title;
			$banner->url = $url;
			$banner->image = $images;

			if (!$banner->add() OR !$banner->associateTo(Tools::getValue('checkBoxShopAsso_module', Shop::getCompleteListOfShopsID())))
				return $this->displayError($this->l('An error occured while creating banner'));

			$this->clearCache();
			return $this->displayConfirmation($this->l('Ad created succesfully'));
		}
		
		if (Tools::isSubmit('submitEditBanner')) {
			foreach($languages as $lang) 
			{
				$title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
				$url[$lang['id_lang']] = Tools::getValue('url_' . $lang['id_lang']);
				$images[$lang['id_lang']] = $this->upload_image($_FILES['image_upload_'.$lang['id_lang']], PrestaHomeBanner::getServerImagePath($lang['iso_code'], false));
			}

			$banner = new PrestaHomeBanner((int)Tools::getValue('id_prestahome_banner'));
			$banner->hook = Tools::getValue('hook');
			$banner->new_window = Tools::getValue('new_window');
			$banner->active = Tools::getValue('active');
			$banner->class = Tools::getValue('class');
			$banner->columns = Tools::getValue('columns');
			$banner->title = $title;
			$banner->url = $url;

			foreach($languages as $lang) 
			{
				if(!empty($images[$lang['id_lang']]))
					$banner->image[$lang['id_lang']] = $images[$lang['id_lang']];
				else
					$banner->image[$lang['id_lang']] = $banner->image[$lang['id_lang']];
			}

			if (!$banner->update() OR !$banner->associateTo(Tools::getValue('checkBoxShopAsso_module', Shop::getCompleteListOfShopsID())))
				return $this->displayError($this->l('An error occured while updating banner target'));

			$this->clearCache();

			return $this->displayConfirmation($this->l('Ad edited succesfully'));
		}
		
		if (Tools::isSubmit('delete'.$this->name)) {

			$banner = new PrestaHomeBanner((int)Tools::getValue('id_prestahome_banner'));

			if(!$banner->delete())
				return $this->displayError($this->l('Could not delete banner'));

			$this->clearCache();
			return $this->displayConfirmation($this->l('Ad removed'));
		}
		
		if (Tools::isSubmit('new_window'.$this->name)) {
			$banner = new PrestaHomeBanner((int)Tools::getValue('id_prestahome_banner'));
			$banner->new_window = !$banner->new_window;

			if (!$banner->save())
				return $this->displayError($this->l('An error occured while updating banner target'));

			$this->clearCache();
		}
		
		if (Tools::isSubmit('status'.$this->name)) {
			$banner = new PrestaHomeBanner((int)Tools::getValue('id_prestahome_banner'));
			$banner->active = !$banner->active;
			if (!$banner->save())
				return $this->displayError($this->l('An error occured while updating banner status'));

			$this->clearCache();
		}
	}
	
	protected function _postValidate() {
		$errors = array();
		
		if (
				Tools::isSubmit('new_window'.$this->name) || 
				Tools::isSubmit('status'.$this->name) ||
				Tools::isSubmit('delete'.$this->name) ||
				Tools::isSubmit('submitEditBanner')
		) {
			// validate id_prestahome_banner
			if (!Validate::isUnsignedInt( Tools::getValue('id_prestahome_banner') )) {
				$errors[] = 'Invalid banner id';
			}
		}
		
		if (
				Tools::isSubmit('submitAddBanner') ||
				Tools::isSubmit('submitEditBanner')
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
			if (!$valid) $errors[] = 'Enter a title';
		}
		
		if (Tools::isSubmit('submitAddBanner')) {
			// validate choose file
			$valid = false;
			foreach ($_FILES as $file) {
				if (!empty($file['tmp_name'])) {
					$valid = true;
					break;
				}
			}
			if (!$valid) $errors[] = 'Upload an image';
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
			$fields['id_prestahome_banner'] = (int)Tools::getValue('id_prestahome_banner');
			$banner = new PrestaHomeBanner((int)Tools::getValue('id_prestahome_banner'), null, $this->context->shop->id);
			foreach($languages as $lang) {
				$fields['title'][$lang['id_lang']] = $banner->title[$lang['id_lang']];
				$fields['url'][$lang['id_lang']] = $banner->url[$lang['id_lang']];
				$fields['image'][$lang['id_lang']] = $banner->image[$lang['id_lang']];
			}

			$fields['new_window'] = $banner->new_window;
			$fields['hook'] = $banner->hook;
			$fields['class'] = $banner->class;
			$fields['columns'] = $banner->columns;
			$fields['active'] = $banner->active;
		} 
		else 
		{
			foreach($languages as $lang) 
			{
				$fields['title'][$lang['id_lang']] = '';
				$fields['url'][$lang['id_lang']] = '';
			}

			$fields['new_window'] = true;
			$fields['hook'] = 'displayHome';
			$fields['class'] = '';
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

	/**
	 * Upload an image
	 * @param array $file
	 * @param string $path upload directory
	 * @param string $name change file name
	 * @param boolean $encrypt encrypt file name
	 * @param boolean $mkpath create path if not exists
	 * @return string|array error|filename
	 */
	private function upload_image($file, $path, $name=null, $encrypt=true, $mkpath = true) {
		$type = Tools::strtolower(Tools::substr(strrchr($file['name'], '.'), 1));
		$imagesize = @getimagesize($file['tmp_name']);
		if (isset($file) &&
			isset($file['tmp_name']) &&
			!empty($file['tmp_name']) &&
			!empty($imagesize) &&
			in_array(Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
					'jpg',
					'gif',
					'jpeg',
					'png'
			)) &&
			in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
		){
			if (!is_dir($path) && $mkpath) { // create path if not exists
				mkdir($path, 0777, true);
			}
			$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'BSK');
			$salt = sha1(microtime());
			if (is_string($name)) { // fixed name
				$encrypt = false;
				$file['name'] = $name.'.'.$type;
			}
			if ($error = ImageManager::validateUpload($file))
				return array($error);
			elseif (!$temp_name || !move_uploaded_file($file['tmp_name'], $temp_name))
				return array('Cannot move temp image file');
			elseif (!ImageManager::resize($temp_name, $path.DIRECTORY_SEPARATOR.($encrypt ? Tools::encrypt($file['name'].$salt).'.'.$type : $file['name']), null, null, $type))
				return array('An error occurred during the image upload process.');
			if (isset($temp_name))
				@unlink($temp_name);
			
			return ($encrypt ? Tools::encrypt($file['name'].$salt).'.'.$type : $file['name']);
		}
	}
	

	public function hookHeader() {}
	
	protected function _prepareHook($hook_name, $custom_tpl = false) {
		$banners = PrestaHomeBanner::getByHook($hook_name, $this->context->language->id, $this->context->shop->id, true);
		if (!empty($banners)) 
		{
			$this->smarty->assign(array(
				'banners' => $banners,
				'hook_name' => $hook_name,
				'image_path' => PrestaHomeBanner::getRelativeImagePath($this->context->language->iso_code)
			));

			if($custom_tpl)
				$tpl = $custom_tpl;
			else
				$tpl = 'hook';

			return $this->display(__FILE__, $tpl.'.tpl');
		}
	}
	
	public function hookDisplayBanner() {
		return $this->_prepareHook('displayBanner');
	}

	public function hookDisplayHome() {
		return $this->_prepareHook('displayHome');
	}

	public function hookDisplayLeftColumn() {
		return $this->_prepareHook('displayLeftColumn');
	}

	public function hookDisplayLeftColumnProduct() {
		return $this->_prepareHook('displayLeftColumnProduct');
	}
	
	public function hookDisplayRightColumn() {
		return $this->_prepareHook('displayRightColumn');
	}
	
	public function hookDisplayRightColumnProduct() {
		return $this->_prepareHook('displayRightColumnProduct');
	}
	
	public function hookDisplayFooterProduct() {
		return $this->_prepareHook('displayFooterProduct');
	}
	
	public function hookDisplayFooter() {
		return $this->_prepareHook('displayFooter');
	}
	
	public function hookDisplayMaintenance() {
		return $this->_prepareHook('displayMaintenance');
	}

	public function hookDisplayTopColumn() {
		return $this->_prepareHook('displayTopColumn', 'top_column');
	}

	public function hookDisplayBeforeContent() {
		return $this->_prepareHook('displayBeforeContent', 'before_content');
	}
	
	public function clearCache() {
		$this->_clearCache('hook.tpl', $this->getCacheId());
	}
}
