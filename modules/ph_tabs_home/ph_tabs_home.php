<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class PH_Tabs_Home extends Module
{
	private $_prefix;
    private $_fields_form = array();

	public function __construct()
	{
		$this->name = 'ph_tabs_home';
		$this->tab = 'front_office_features';
		$this->version = '1.0.2';
		$this->author = 'www.PrestaHome.com';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Tabs with products on homepage');
		$this->description = $this->l('Displays product with tabs on the homepage of your store');

		$this->_prefix = 'PH_TABS_HOME_';
	}

	public function install()
	{
		$this->_clearCache('*');
		$this->renderConfigurationForm();
		$this->batchUpdateConfigs();

		if (!parent::install()
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct')
			|| !$this->registerHook('categoryUpdate')
			|| !$this->registerHook('displayHome')
		)
			return false;

		return true;
	}

	public function uninstall()
	{
		$this->renderConfigurationForm();
    	$this->deleteConfigs();
    	$this->_clearCache('*');
		return parent::uninstall();
	}

	public function hookDisplayHome($params)
	{
		$this->smarty->assign(
			array(
				'bestsellers' => $this->getBestSellers($params),
				'new_products' => $this->getNewProducts($params),
				'specials' => $this->getSpecials($params),
				'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
			)
		);

		return $this->display(__FILE__, 'ph_tabs_home.tpl', $this->getCacheId('ph_tabs_home'));
	}

	protected function getBestSellers($params)
	{
		if (Configuration::get('PS_CATALOG_MODE') OR !Configuration::get($this->_prefix.'DISPLAY_BESTSELLERS'))
			return false;

		if (!($products = ProductSale::getBestSalesLight((int)$params['cookie']->id_lang, 0, Configuration::get($this->_prefix.'PRODUCT_NB'))))
			return false;

		$currency = new Currency($params['cookie']->id_currency);
		$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
		foreach ($products as &$row)
			$row['price'] = Tools::displayPrice(Product::getPriceStatic((int)$row['id_product'], $usetax), $currency);

		if($products && sizeof($products))
		{
			Hook::exec('actionProductListModifier', array(
            	'cat_products' => &$products,
        	));

        	shuffle($products);
		}

		return $products;
	}

	protected function getNewProducts($params)
	{
		if(!Configuration::get($this->_prefix.'DISPLAY_NEW_PRODUCTS'))
			return false;

		$products = Product::getNewProducts($this->context->language->id, 0, Configuration::get($this->_prefix.'PRODUCT_NB'));

		if($products && sizeof($products))
		{
			Hook::exec('actionProductListModifier', array(
            	'cat_products' => &$products,
        	));

        	shuffle($products);
		}
		return $products;
	}

	protected function getSpecials($params)
	{
		if(!Configuration::get($this->_prefix.'DISPLAY_SPECIALS'))
			return false;

		$products = Product::getPricesDrop($this->context->language->id, 0, Configuration::get($this->_prefix.'PRODUCT_NB'));

		if($products && sizeof($products))
		{
			Hook::exec('actionProductListModifier', array(
            	'cat_products' => &$products,
        	));

        	shuffle($products);
		}

		return $products;
	}

	public function getContent() 
	{
    	$this->_html = '<h2>'.$this->displayName.'</h2>';

    	if (Tools::isSubmit('save'.$this->name)) 
    	{
            $this->renderConfigurationForm();
            $this->batchUpdateConfigs();

            $this->_clearCache('ph_tabs_home.tpl');
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));

        }
        return $this->_html . $this->renderForm();
    }

    public function renderConfigurationForm() 
    {
    	if($this->_fields_form)
            return;

    	$fields_form = array(
            'form' => array(
	            'legend' => array(
	                'title' => $this->l('Settings'),
	                'icon' => 'icon-cogs'
	            ),

	            'input' => array(

	            	array(
	                	'type'  => 'text',
	                	'lang' 	=> true,
		                'label' => $this->l('Title of the blog'),
		                'name'  => $this->_prefix.'TITLE',
		                'default' => 'Our offer',
		                'validate' => 'isAnything',
	                ),

	                array(
	                	'type'  => 'text',
		                'label' => $this->l('Number of items in tab'),
		                'name'  => $this->_prefix.'PRODUCT_NB',
		                'desc'  => $this->l('The maximum number of products in each tab (default: 6).'),
		                'default' => '6',
		                'validate' => 'isUnsignedInt',
	                ),

	                array(
	                	'type'  => 'radio',
		                'label' => $this->l('Number of items in row'),
		                'name'  => $this->_prefix.'PRODUCTS_IN_ROW',
		                'default' => '6',
		                'values' => array(
		                	array(
								'id' => 'nb_2',
								'value' => 2,
								'label' => '2'
							),
							array(
								'id' => 'nb_3',
								'value' => 3,
								'label' => '3'
							),
							array(
								'id' => 'nb_4',
								'value' => 4,
								'label' => '4'
							),
							array(
								'id' => 'nb_6',
								'value' => 6,
								'label' => '6'
							)
						)
	                ),
	              
              		array(
		                'type' => 'switch',
		                'label' => $this->l('Display products in promotion?'),
		                'name' => $this->_prefix.'DISPLAY_SPECIALS',
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
		                'default' => '1'	
	              	),

	              	array(
		                'type' => 'switch',
		                'label' => $this->l('Display bestsellers?'),
		                'name' => $this->_prefix.'DISPLAY_BESTSELLERS',
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
		                'default' => '1'	
	              	),

	              	array(
		                'type' => 'switch',
		                'label' => $this->l('Display new products?'),
		                'name' => $this->_prefix.'DISPLAY_NEW_PRODUCTS',
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
		                'default' => '1'	
	              	),
	            ),

	            'submit' => array(
	            'title' => $this->l('Save'),
	            'class' => 'btn btn-default')
	        ),
		);

		$this->_fields_form[] = $fields_form;
    }

    protected function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $this->renderConfigurationForm();

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang)
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'save'.$this->name;
        $helper->toolbar_btn =  array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm($this->_fields_form);
    }

    public function getConfigFieldsValues($data = null) 
    {
        $fields_values = array();
        foreach ( $this->_fields_form as $k => $f ) 
        {
            foreach ( $f['form']['input'] as $i => $input ) 
            {
                if( isset($input['lang']) ) 
                {
                    foreach ( Language::getLanguages(false) as $lang ) 
                    {
                        $values = Tools::getValue( $input['name'].'_'.$lang['id_lang'], ( Configuration::hasKey($input['name']) ? Configuration::get($input['name'], $lang['id_lang']) : $input['default'] ) );
                        $fields_values[$input['name']][$lang['id_lang']] = $values;
                    }
                } 
                else 
                {
                    $values = Tools::getValue( $input['name'], ( Configuration::hasKey($input['name']) ? Configuration::get($input['name']) : $input['default'] ) );
                    $fields_values[$input['name']] = $values;
                }
            }
        }
        return $fields_values;
    }

    public function batchUpdateConfigs()
    {
        foreach ( $this->_fields_form as $k => $f ) 
        {
            foreach ( $f['form']['input'] as $i => $input ) 
            {
                if( isset($input['lang']) ) 
                {
                    $data = array();
                    foreach ( Language::getLanguages(false) as $lang ) 
                    {
                        $val = Tools::getValue( $input['name'].'_'.$lang['id_lang'], $input['default'] );
                        $data[$lang['id_lang']] = $val;
                    }
                    Configuration::updateValue( trim($input['name']), $data, true );
                }
                else 
                { 
                    $val = Tools::getValue( $input['name'], $input['default'] );
                    Configuration::updateValue( $input['name'], $val, true );
                }
            }
        }
    }

    public function deleteConfigs()
    {
        foreach ( $this->_fields_form as $k => $f ) 
        {
            foreach ( $f['form']['input'] as $i => $input ) 
            {
                Configuration::deleteByName($input['name']);
            }
        }

        return true;
    }

    public function hookAddProduct($params) {
        $this->_clearCache('*');
    }

    public function hookUpdateProduct($params) {
        $this->_clearCache('*');
    }

    public function hookDeleteProduct($params) {
        $this->_clearCache('*');
    }

    /**
     * Return value with every available language
     * @param  string Value
     * @return array
     */
	public static function prepareValueForLangs($value)
    {
        $output = array();

        foreach(Language::getLanguages(false) as $lang)
            $output[$lang['id_lang']] = $value;

        return $output;
    }
}
