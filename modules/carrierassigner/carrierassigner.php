<?php

// TODO PRO version:
// - csv upload
// - carrier by name?
// - assign to subcategories too
// - clear assigned carriers

if (!defined('_PS_VERSION_'))
	exit;

class carrierAssigner extends Module
{

	protected $_errors = array();
	protected $_html = '';

	public function __construct()
	{
		$this->name = 'carrierassigner';
		$this->tab = 'administration';
		$this->version = '1.0';
		$this->author = 'Nemo';
		$this->need_instance = 0;
		
		$this->bootstrap = true;

	 	parent::__construct();

		$this->displayName = $this->l('Carrier Assigner');
		$this->description = $this->l('Assign carriers to products in bulk.');
	}
	
	public function install()
	{
		if (!parent::install())
			return false;
		return true;
	}
	
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

	private function _installConfig()
	{
		foreach ($this->_config as $keyname => $value) {
			Configuration::updateValue($keyname, $value);
		}
		return true;
	}


	private function _eraseConfig()
	{
		foreach ($this->_config as $keyname => $value) {
			Configuration::deleteByName($keyname);
		}
		return true;
	}

	


	public function getContent()
	{
		$this->_postProcess();
		$this->_displayForm();

		return	$this->_html;
	}
	
	private function _displayForm()
	{
		$this->_html .= '<form action="" >
			<div class="panel">
				<a title="'.$this->l('Prestashop Modules at NemoPS').'" target="_blank" href="http://store.nemops.com"><img src="'.$this->_path.'/assets/generic_ad.jpg" alt="'.$this->l('Prestashop Modules at NemoPS').'"></a>
				<a title="'.$this->l('PrestaShop Modules Course').'" target="_blank" href="http://nemops.com/prestashop-modules-course/"><img src="'.$this->_path.'/assets/course_ad.jpg" alt="'.$this->l('PrestaShop Modules Course').'"></a>
				<ins data-revive-zoneid="10" data-revive-id="27f1a68d9b3c239bbbd38cc09b79d453"></ins>
				<script async src="http://dh42.com/openx/www/delivery/asyncjs.php"></script>

			</div>			
		</form>';
		$this->_html .= $this->_generateForm();
		// With Template
		$this->context->smarty->assign(array(
			'variable'=> 1
		));
		$this->_html .= $this->display(__FILE__, 'backoffice.tpl');
	}

	private function _generateForm()
	{
		$inputs = array();



		$inputs[] = array(
			'type' => 'radio',
			'label' => $this->l('Method'),
			'name' => 'assign_method',
			'desc' => $this->l('Choose which method to use to assign products below'),
			'values' => array(
				array(
					'id' => 'method_input',
					'value' => 0,
					'label' => $this->l('Text input')
				),
				array(
					'id' => 'method_category',
					'value' => 1,
					'label' => $this->l('By Category Selection')
				)
			),
		);


		$inputs[] = array(
			'type' => 'text',
			'label' => $this->l('Products IDs'),
			'name' => 'product_ids',
			'desc' => $this->l('Add the product ids you want to assign the carrier to, separated by comma. Leave blank if you choose to assign by category')
			);

         $inputs[] = array(
			'type' => 'categories',
			'label' => $this->l('By category'),
			'name' => 'assign_categories',
			'desc' => $this->l('Select the category of which products will be bound to the chosen carrier'),
			'tree' => array(
			    'root_category' => 1,
			    'id' => 'id_category',
			    'name' => 'name_category',
			    'selected_categories' => array(),
				)
			);

		
		$carriers = Carrier::getCarriers($this->context->language->id); // only get active ones
		$inputs[] = array(
			'type' => 'select',
			'label' => $this->l('Carrier'),
			'name' => 'chosen_carrier',
			'desc' => 'Select the carrier you want to bind to these products',
			'options' => array(
				'query' => $carriers,
				'id' => 'id_reference',
				'name' => 'name'
			),
		);
		

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
					),
				'description' => $this->l('Use this form to bind products to any chosen carrier. If any of them is bound already, the module will just ignore it.'),
				'input' => $inputs,
				'submit' => array(
					'title' => $this->l('Assign'),
					'class' => 'btn btn-default pull-right',
					'name' => 'submitUpdate'
					)
				)
			);



		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper = new HelperForm();
		$helper->default_form_language = $lang->id;
		// $helper->submit_action = 'submitUpdate';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules',false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array('assign_method' => 0, 'product_ids' => '', 'chosen_carrier' => '0' )
		);
		return $helper->generateForm(array($fields_form));
	}


	private function _postProcess()
	{
		if (Tools::isSubmit('submitUpdate')) // handles the basic config update
		{
			// Check what's the chosen method
			if(Tools::getValue('assign_method') == 0) // direct input
			{
				// check the input field
				$ids = Tools::getValue('product_ids');
				// regex to check if they are ok
				if(!preg_match("/^[0-9,]+$/", $ids))
					$this->_errors[] = $this->l('Invalid product IDs string');
				else {
					$ids_array = explode(',', $ids);
					$this->assignCarrier($ids_array);
					// TODO convert this to a function
					
				}
			} else { // assign by category
				$id_category = Tools::getValue('assign_categories');
				if(!$id_category)
					$this->_errors[] = $this->l('Error: You must select a category');
				else {
					$category = new Category((int)$id_category);
					$products = $category->getProductsWs();
					if(!$products)
						$this->_errors[] = Tools::displayError('No product found in the chosen category');
					else {
						foreach ($products as $product => $idpr)
							$ids_array[] = $idpr['id'];
						$this->assignCarrier($ids_array);
					}
					// TODONEXT pass products to the previous query, convert it to function
				}
			}

			// Error handling
			if ($this->_errors) {
				$this->_html .= $this->displayError(implode($this->_errors, '<br />'));
			} else $this->_html .= $this->displayConfirmation($this->l('Settings Updated!'));
		}
	}

	public function getConfigFull()
	{
		// join lang and normal config
		$config = $this->getConfig();
		$config_lang = $this->getConfigLang();
		return array_merge($config, $config_lang);
	}

	public function getConfig()
	{
		$config_keys = array_keys($this->_config);
		return Configuration::getMultiple($config_keys);
	}

	public function getConfigLang($id_lang = false)
	{
		if(!$id_lang)
		{
			foreach ($this->_config_lang as $key => $value)
			{
				$results[$key] = Configuration::getInt($key);
			}
			return $results;
		} else {
			$config_keys = array_keys($this->_config_lang);
			return Configuration::getMultiple($config_keys, $id_lang);
		}	
	}

	public function assignCarrier($ids_array)
	{
		$query_length = 0;
		Db::getInstance(_PS_USE_SQL_SLAVE_)->query('START TRANSACTION;');
		foreach ($ids_array as $id) {
			// update each product, but do it bulk without autocommit
			$clause = 'INSERT IGNORE INTO '._DB_PREFIX_.'product_carrier (id_product, id_carrier_reference, id_shop) VALUES ('.$id.','.(int)Tools::getValue('chosen_carrier').','.$this->context->shop->id.');';
			$query_length++;


			$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->query($clause);

			// if no rows were affected, we have an error, display it

			if(!$res)
			{
				$this->_errors[] = Tools::displayError('Error on updating the carrier on product #'.$id.': ').mysql_error();
				break;
			}
				
			if($query_length == 500)
			{
				Db::getInstance()->query('COMMIT');
				$query_length = 0;
			}
		
		} // end foreach id
		if(!Db::getInstance()->query('COMMIT'))	
			$this->_errors[] = Tools::displayError('Error: ').mysql_error();
	}



	public function hookDisplayLeftColumn($params)
	{

		$this->context->smarty->assign(array(
			'value1' => TRUE,
			'value2' => TRUE
		));


		return $this->display(__FILE__, 'carrierassigner.tpl');
	}
	
	public function hookDisplayRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}

	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'views/css/carrierassigner.css', 'all');
		$this->context->controller->addJS($this->_path.'views/js/carrierassigner.js', 'all');

	}

}
