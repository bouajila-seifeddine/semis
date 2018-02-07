<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Pronimbo.com
 * @copyright Pronimbo.com. all rights reserved.
 * @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 */

include_once(_PS_MODULE_DIR_.'paseocenterbulkedition'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'AdminPrController.php');

class AdminPaSeoCenterGeneralController extends AdminPrController
{
	public static $options = array(
		'PA_SEO_PAG_NOFOLLOW',
		'PA_SEO_PAG_NOINDEX',
		'PA_SEO_NO_PAG_CANONICAL',
		'PA_SEO_MARKUP',
		'PA_SEO_CAPITALIZE_HOME_TITLE',
		'PA_SEO_CAPITALIZE_PROD_TITLE',
		'PA_SEO_CAPITALIZE_CAT_TITLE',
		'PA_SEO_CAPITALIZE_MAN_TITLE',
		'PA_SEO_CAPITALIZE_SUP_TITLE',
		'PA_SEO_CAPITALIZE_CMS_TITLE',
		'PA_SEO_HOME_TITLE_FORMAT',
		'PA_SEO_PROD_TITLE_FORMAT',
		'PA_SEO_CAT_TITLE_FORMAT',
		'PA_SEO_MAN_TITLE_FORMAT',
		'PA_SEO_SUP_TITLE_FORMAT',
		'PA_SEO_CMS_TITLE_FORMAT',
		'PA_SEO_PAGE_TITLE_FORMAT',
		'PA_SEO_HOME_DESC_FORMAT',
		'PA_SEO_PROD_DESC_FORMAT',
		'PA_SEO_CAT_DESC_FORMAT',
		'PA_SEO_MAN_DESC_FORMAT',
		'PA_SEO_SUP_DESC_FORMAT',
		'PA_SEO_CMS_DESC_FORMAT',
		'PA_SEO_PAGE_DESC_FORMAT',
		'PA_SEO_GA',
		'PA_SEO_GA_ACTIVE',
		'PA_SEO_GOOGLE_WEBMASTER',
		'PA_SEO_BING_WEBMASTER',
		'PA_SEO_PINT_WEBMASTER',
		'PA_SEO_CAPITALIZE_PAGE_TITLE',
		'PA_SEO_AUTO_OG_DESCRIPTION',
		'PA_SEO_OG_HOME_LOGO',
		'PA_SEO_OG_USE_DEFAULT_IMG',
		'PA_SEO_OG_DEAULT_IMG',
		'PA_SEO_OG_IMG_SIZE',
		'PA_SEO_OG_PRODUCT',
		'PA_SEO_OG_PRODUCT_TYPE',
		'PA_SEO_OG_CATEGORY',
		'PA_SEO_OG_CATEGORY_TYPE',
		'PA_SEO_OG_HOME',
		'PA_SEO_OG_HOME_TYPE',
		'PA_SEO_OG_CMS',
		'PA_SEO_OG_CMS_TYPE',
		'PA_SEO_OG_MAN',
		'PA_SEO_OG_MAN_TYPE',
		'PA_SEO_OG_SUP',
		'PA_SEO_OG_SUP_TYPE',
		'PA_SEO_OG_PAGE',
		'PA_SEO_OG_PAGE_TYPE',
		'PA_SEO_TWT_PROFILE',
		'PA_SEO_TW_SUP_TYPE',
		'PA_SEO_TW_SUP',
		'PA_SEO_TW_PAGE_TYPE',
		'PA_SEO_TW_PAGE',
		'PA_SEO_TW_PRODUCT',
		'PA_SEO_TW_PRODUCT_TYPE',
		'PA_SEO_TW_CATEGORY',
		'PA_SEO_TW_CATEGORY_TYPE',
		'PA_SEO_TW_HOME',
		'PA_SEO_TW_HOME_TYPE',
		'PA_SEO_TW_CMS',
		'PA_SEO_TW_CMS_TYPE',
		'PA_SEO_TW_MAN',
		'PA_SEO_TW_MAN_TYPE',
		'PA_SEO_TW_SUP',
		'PA_SEO_TW_SUP_TYPE',
		'PA_SEO_OG_ENABLED',
		'PA_SEO_SCRIPTS',
	);

	public function __construct()
	{
		$this->controller_name = 'AdminPaSeoCenterGeneral';
		parent::__construct();
		$this->context = Context::getContext();
		$this->bootstrap = true;
		$this->className = 'stdClass';
		$this->tpl_folder = 'controllers/general/';
		$this->table = 'paseocenter';
	}

	/**
	*	Not remove this function,
	*/
	public function processFilter()
	{

	}
	public function getBotsBlockerFields()
	{
		return array();
	}

	public function getRobotsFields()
	{
		return array(
			array(
				'type' => 'textarea',
				'label' => $this->l('robots.txt file'),
				'tab' => 'robots',
				'fieldset' => 'robots',
				'name' => 'PA_SEO_ROBOTS',
				'cols' => '15',
				'rows' => '20',
			)
		);
	}

	public function getHtAccessFields()
	{
		$header_info = _PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'templates';
		$header_info .= DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'_configure'.DIRECTORY_SEPARATOR;
		$header_info .= 'controllers'.DIRECTORY_SEPARATOR.'general'.DIRECTORY_SEPARATOR.'htaccess_info.tpl';

		return array(
			array(
				'type' => 'html',
				'tab' => 'htaccess',
				'fieldset' => 'htaccess',
				'label' =>  '',
				'html_content' => $this->context->smarty->fetch($header_info),
				'name' => 'PA_SEO_HTACCESS_INFO',
			),
			array(
				'type' => 'button',
				'tab' => 'htaccess',
				'fieldset' => 'htaccess',
				'id' => 'PA_SEO_HTACCESS_ENABLE',
				'href' => 'javascript:void(0)',
				'label' =>  '',
				'inner_label' => $this->l('Edit .htaccess'),
				'name' => 'PA_SEO_HTACCESS_ENABLE',
			),
			array(
				'type' => 'textarea',
				'tab' => 'htaccess',
				'fieldset' => 'htaccess',
				'label' => $this->l('.htaccess file'),
				'name' => 'PA_SEO_HTACCESS',
				'cols' => '15',
				'rows' => '20',
				'disabled' => true,
			),

			);
	}

	public function getScriptsFields()
	{
		$script_info = _PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.'views';
		$script_info .= DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'admin';
		$script_info .= DIRECTORY_SEPARATOR.'_configure'.DIRECTORY_SEPARATOR.'controllers';
		$script_info .= DIRECTORY_SEPARATOR.'general'.DIRECTORY_SEPARATOR.'scripts_info.tpl';

		return array(
			array(
				'type' => 'html',
				'tab' => 'script',
				'fieldset' => 'script',
				'label' =>  '',
				'html_content' => $this->context->smarty->fetch($script_info),
				'name' => 'PA_SEO_SCRIPTS_INFO',
			),
			array(
				'type' => 'textarea',
				'tab' => 'script',
				'fieldset' => 'script',
				'label' => $this->l('Scripts'),
				'name' => 'PA_SEO_SCRIPTS',
				'lang' => true,
				'cols' => '20',
				'rows' => '30',
			)
		);
	}

	public function getGeneralFields()
	{
		$type = 'switch';
		return array(
			'submitAdd' => array(
				'type' => 'hidden',
				'tab' => 'general',
				'fieldset' => 'general',
				'label' => ' ',
				'name' => 'submitAddpaseocenter',
			),
			'PA_SEO_PAG_NOFOLLOW' => array(
				'type' => $type,
				'tab' => 'general',
				'fieldset' => 'general',
				'label' => $this->l('No Follow for paginated URLs'),
				'name' => 'PA_SEO_PAG_NOFOLLOW',
				'is_bool' => true,
				'desc' => $this->l('This option will automatically match paginated as "No follow" on products page list'),
				'values' => array(
					array(
						'id' => 'PA_SEO_PAG_NOFOLLOW_on',
						'value' => true,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'PA_SEO_PAG_NOFOLLOW_off',
						'value' => false,
						'label' => $this->l('Disabled')
					)
				),
			),
			'PA_SEO_PAG_NOINDEX' => array(
				'type' => $type,
				'tab' => 'general',
				'fieldset' => 'general',
				'label' => $this->l('No Index for paginated URLs'),
				'name' => 'PA_SEO_PAG_NOINDEX',
				'is_bool' => true,
				'desc' => $this->l('This option will automatically match paginated as "No Index" on products search page list'),
				'values' => array(
					array(
						'id' => 'PA_SEO_PAG_NOINDEX_on',
						'value' => true,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'PA_SEO_PAG_NOINDEX_off',
						'value' => false,
						'label' => $this->l('Disabled')
					)
				),
			),
			'PA_SEO_NO_PAG_CANONICAL' => array(
				'type' => $type,
				'tab' => 'general',
				'fieldset' => 'general',
				'label' => $this->l('Canonical url on paginated URLs'),
				'name' => 'PA_SEO_NO_PAG_CANONICAL',
				'is_bool' => true,
				'desc' => $this->l('Checking this option will set the Canonical URL for all paginated content to the first page of pagination.'),
				'values' => array(
					array(
						'id' => 'PA_SEO_NO_PAG_CANONICAL_on',
						'value' => true,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'PA_SEO_NO_PAG_CANONICAL_off',
						'value' => false,
						'label' => $this->l('Disabled')
					)
				),
			),
			'PA_SEO_MARKUP' => array(
				'type' => $type,
				'tab' => 'general',
				'fieldset' => 'general',
				'label' => $this->l('Enable Schema.org Markup'),
				'name' => 'PA_SEO_MARKUP',
				'is_bool' => true,
				'values' => array(
					array(
						'id' => 'PA_SEO_NO_PAG_CANONICAL_on',
						'value' => true,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'PA_SEO_NO_PAG_CANONICAL_off',
						'value' => false,
						'label' => $this->l('Disabled')
					)
				),
			),
		);
	}

	public function getTitleFields()
	{
		$format_field_info = _PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'templates';
		$format_field_info .= DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'_configure'.DIRECTORY_SEPARATOR.'controllers';
		$format_field_info .= DIRECTORY_SEPARATOR.'general'.DIRECTORY_SEPARATOR.'format_title_info.tpl';
		$type = 'switch';
		return array(
			'PA_SEO_TITLE_FORMAT_INFO' => array(
				'type' => 'html',
				'fieldset' => 'format',
				'tab' => 'title',
				'col' => '12',
				'name' => 'PA_SEO_TITLE_FORMAT_INFO',
				'html_content' => $this->context->smarty->fetch($format_field_info),

			),

			'buttons' => array(
				'type' => 'group-buttons',
				'tab' => 'title',
				'fieldset' => 'format',
				'label' => $this->l('Home page'),
				'name' => 'buttons1',
				'buttons' => array(
					'PA_SEO_CAPITALIZE_HOME_TITLE' => array(
						'type' => $type,
						'tab' => 'title',
						'label' => $this->l('Capitalize'),
						'name' => 'PA_SEO_CAPITALIZE_HOME_TITLE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_CAPITALIZE_HOME_TITLE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_CAPITALIZE_HOME_TITLE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_HOME_TITLE_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Title format'),
						'name' => 'PA_SEO_HOME_TITLE_FORMAT',
					),
					'PA_SEO_HOME_DESC_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Description format'),
						'name' => 'PA_SEO_HOME_DESC_FORMAT',
					),

				),
			),
			'buttons1' => array(
				'type' => 'group-buttons',
				'tab' => 'title',
				'fieldset' => 'format',
				'label' => $this->l('Products page'),
				'name' => 'buttons1',
				'buttons' => array(
					'PA_SEO_CAPITALIZE_PROD_TITLE' => array(
						'type' => $type,
						'tab' => 'title',
						'label' => $this->l('Capitalize'),
						'name' => 'PA_SEO_CAPITALIZE_PROD_TITLE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_CAPITALIZE_PROD_TITLE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_CAPITALIZE_PROD_TITLE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_PROD_TITLE_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Title format'),
						'name' => 'PA_SEO_PROD_TITLE_FORMAT',
					),
					'PA_SEO_PROD_DESC_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Description format'),
						'name' => 'PA_SEO_PROD_DESC_FORMAT',
					),

				),
			),
			'buttons2' => array(
				'type' => 'group-buttons',
				'tab' => 'title',
				'fieldset' => 'format',
				'label' => $this->l('Category page'),
				'name' => 'buttons1',
				'buttons' => array(
					'PA_SEO_CAPITALIZE_CAT_TITLE' => array(
						'type' => $type,
						'tab' => 'title',
						'label' => $this->l('Capitalize'),
						'name' => 'PA_SEO_CAPITALIZE_CAT_TITLE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_CAPITALIZE_CAT_TITLE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_CAPITALIZE_CAT_TITLE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_CAT_TITLE_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Title format'),
						'name' => 'PA_SEO_CAT_TITLE_FORMAT',
					),
					'PA_SEO_CAT_DESC_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Description format'),
						'name' => 'PA_SEO_CAT_DESC_FORMAT',
					),

				),
			),
			'buttons8' => array(
				'type' => 'group-buttons',
				'tab' => 'title',
				'fieldset' => 'format',
				'label' => $this->l('CMS page'),
				'name' => 'buttons2',
				'buttons' => array(
					'PA_SEO_CAPITALIZE_CMS_TITLE' => array(
						'type' => $type,
						'tab' => 'title',
						'label' => $this->l('Capitalize'),
						'name' => 'PA_SEO_CAPITALIZE_CMS_TITLE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_CAPITALIZE_CMS_TITLE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_CAPITALIZE_CMS_TITLE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_CMS_TITLE_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Title format'),
						'name' => 'PA_SEO_CMS_TITLE_FORMAT',
					),
					'PA_SEO_CMS_DESC_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Description format'),
						'name' => 'PA_SEO_CMS_DESC_FORMAT',
					),

				),
			),
			'buttons4' => array(
				'type' => 'group-buttons',
				'tab' => 'title',
				'fieldset' => 'format',
				'label' => $this->l('Manufacturer page'),
				'name' => 'buttons4',
				'buttons' => array(
					'PA_SEO_CAPITALIZE_MAN_TITLE' => array(
						'type' => $type,
						'tab' => 'title',
						'label' => $this->l('Capitalize'),
						'name' => 'PA_SEO_CAPITALIZE_MAN_TITLE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_CAPITALIZE_MAN_TITLE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_CAPITALIZE_MAN_TITLE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_MAN_TITLE_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Title format'),
						'name' => 'PA_SEO_MAN_TITLE_FORMAT',
					),
					'PA_SEO_MAN_DESC_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Description format'),
						'name' => 'PA_SEO_MAN_DESC_FORMAT',
					),

				),
			),
			'buttons5' => array(
				'type' => 'group-buttons',
				'tab' => 'title',
				'fieldset' => 'format',
				'label' => $this->l('Supplier page'),
				'name' => 'buttons5',
				'buttons' => array(
					'PA_SEO_CAPITALIZE_SUP_TITLE' => array(
						'type' => $type,
						'tab' => 'title',
						'label' => $this->l('Capitalize'),
						'name' => 'PA_SEO_CAPITALIZE_SUP_TITLE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_CAPITALIZE_SUP_TITLE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_CAPITALIZE_SUP_TITLE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_SUP_TITLE_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Title format'),
						'name' => 'PA_SEO_SUP_TITLE_FORMAT',
					),
					'PA_SEO_SUP_DESC_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Description format'),
						'name' => 'PA_SEO_SUP_DESC_FORMAT',
					),

				),
			),
			'buttons6' => array(
				'type' => 'group-buttons',
				'tab' => 'title',
				'fieldset' => 'format',
				'label' => $this->l('Other page'),
				'name' => 'buttons6',
				'buttons' => array(
					'PA_SEO_CAPITALIZE_PAGE_TITLE' => array(
						'type' => $type,
						'tab' => 'title',
						'label' => $this->l('Capitalize'),
						'name' => 'PA_SEO_CAPITALIZE_PAGE_TITLE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_CAPITALIZE_PAGE_TITLE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_CAPITALIZE_PAGE_TITLE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_PAGE_TITLE_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Title format'),
						'name' => 'PA_SEO_PAGE_TITLE_FORMAT',
					),
					'PA_SEO_PAGE_DESC_FORMAT' => array(
						'type' => 'text',
						'tab' => 'title',
						'label' => $this->l('Description format'),
						'name' => 'PA_SEO_PAGE_DESC_FORMAT',
					),

				),
			),

		);
	}

	public function getWebmasterFields()
	{
		$type = 'switch';
		return array(
			'PA_SEO_GA_ACTIVE' => array(
				'type' => $type,
				'tab' => 'general',
				'prefix' => 'UA',
				'fieldset' => 'analytics',
				'label' => $this->l('Google Analytics'),
				'col' => '3',
				'name' => 'PA_SEO_GA_ACTIVE',
				'hint' => $this->l('Active analytics script.'),
				'is_bool' => true,
				'values' => array(
					array(
						'id' => 'PA_SEO_GA_ACTIVE_on',
						'value' => true,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'PA_SEO_GA_ACTIVE_off',
						'value' => false,
						'label' => $this->l('Disabled')
					)
				),
			),
			'PA_SEO_GA' => array(
				'type' => 'text',
				'tab' => 'general',
				'prefix' => 'UA',
				'fieldset' => 'analytics',
				'label' => $this->l('UA Code'),
				'col' => '3',
				'name' => 'PA_SEO_GA',
				'hint' => $this->l('Enter your analytics ID UA-XXXXXX.'),
			),
			'PA_SEO_GOOGLE_WEBMASTER' => array(
				'type' => 'text',
				'tab' => 'general',
				'fieldset' => 'webmaster',
				'label' => $this->l('Google verification code'),
				'col' => '4',
				'name' => 'PA_SEO_GOOGLE_WEBMASTER',
				'hint' => $this->l('Enter your verification code here to verify your site with Google Webmaster Tools.'),
			),
			'PA_SEO_BING_WEBMASTER' => array(
				'type' => 'text',
				'tab' => 'general',
				'fieldset' => 'webmaster',
				'col' => '4',
				'label' => $this->l('Bing verification code'),
				'name' => 'PA_SEO_BING_WEBMASTER',
				'hint' => $this->l('Enter your verification code here to verify your site with Bing Webmaster Tools.'),
			),
			'PA_SEO_PINT_WEBMASTER' => array(
				'type' => 'text',
				'tab' => 'general',
				'fieldset' => 'webmaster',
				'col' => '4',
				'label' => $this->l('Pinterest verification code'),
				'name' => 'PA_SEO_PINT_WEBMASTER',
				'hint' => $this->l('Enter your verification code here to verify your site with Pinterest.'),
			),
		);
	}

	public function renderForm()
	{
		$this->fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs',
				),
				'tabs' => array(
					'general' => $this->l('General'),
					'title' => $this->l('Title Settings'),
					'open_graph' => $this->l('Open Graph Settings'),
					'google' => $this->l('Google Settings'),
					'facebook' => $this->l('Facebook Settings'),
					'twitter' => $this->l('Twitter Settings'),
					'script' => $this->l('Scripts'),
					'robots' => $this->l('Robots.txt'),
					'htaccess' => $this->l('.htaccess'),
				),
				'fieldsets' => array(
					'analytics' => array(
						'label' => $this->l('Google Analytics'),
						'tab' => 'general'
					),
					'webmaster' => array(
						'label' => $this->l('Webmasters settings'),
						'tab' => 'general'
					),
					'general' => array(
						'label' => $this->l('Pagination and Markups settings'),
						'tab' => 'general'
					),
					'format' => array(
						'label' => $this->l('Format title and descriptions'),
						'tab' => 'title'
					),
					'robots' => array(
						'label' => $this->l('robots.txt file'),
						'tab' => 'robots'
					),
					'htaccess' => array(
						'label' => $this->l('.htaccess file'),
						'tab' => 'htaccess'
					),
					'script' => array(
						'label' => $this->l('Scripts'),
						'tab' => 'script'
					),
					'fb_pages' => array(
						'label' => $this->l('Facebook Tags on pages'),
						'tab' => 'facebook'
					),
					'tw_author' => array(
						'label' => $this->l('Twitter Profile'),
						'tab' => 'twitter'
					),
					'tw_pages' => array(
						'label' => $this->l('Twitter Tags on pages'),
						'tab' => 'twitter'
					),
				),
				'input' => array_merge(
					array(),
					$this->getGeneralFields(),
					$this->getTitleFields(),
					$this->getWebmasterFields(),
					$this->getBotsBlockerFields(),
					$this->getRobotsFields(),
					$this->getHtAccessFields(),
					$this->getOpenGraphGeneralFields(),
					$this->getFacebookFields(),
					$this->getTwitterFields(),
					$this->getScriptsFields()),
			),
		);
		return parent::renderForm();
	}

	public function renderList()
	{
		return $this->renderForm();
	}

	public function getFormValues()
	{
		$values = Configuration::getMultiple(self::$options);

		foreach (LanguageCore::getLanguages() as $lang)
			$values['PA_SEO_SCRIPTS'][$lang['id_lang']] = Configuration::get('PA_SEO_SCRIPTS', $lang['id_lang']);

		/*Robots.txt load*/
		$values['PA_SEO_ROBOTS'] = Tools::file_get_contents(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'robots.txt');
		$values['PA_SEO_HTACCESS'] = Tools::file_get_contents(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'.htaccess');

		$values['submitAdd'.$this->table] = '1';

		return $values;
	}

	public function processAdd()
	{
		foreach (self::$options as $option)
			Configuration::updateValue($option, Tools::getValue($option));

		$script = array();
		foreach (LanguageCore::getLanguages() as $lang)
			$script[$lang['id_lang']] = Tools::getValue('PA_SEO_SCRIPTS_'.$lang['id_lang'], '');

		ConfigurationCore::updateValue('PA_SEO_SCRIPTS', $script, true);

		if (count($_FILES))
			$this->postImage(1);
		if (Tools::getValue('PA_SEO_ROBOTS'))
			file_put_contents(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'robots.txt', Tools::getValue('PA_SEO_ROBOTS'));
		if (Tools::getValue('PA_SEO_HTACCESS'))
			file_put_contents(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'.htaccess', Tools::getValue('PA_SEO_HTACCESS'));
	}

	public function getFacebookFields()
	{
		$type = 'switch';
		return array(
			'facebook1' => array(
				'type' => 'group-buttons',
				'fieldset' => 'fb_pages',
				'name' => 'facebook1',
				'label' => $this->l('Product Page'),
				'tab' => 'facebook',
				'buttons' => array(
					'PA_SEO_OG_PRODUCT' => array(
						'type' => $type,
						'tab' => 'facebook',
						'name' => 'PA_SEO_OG_PRODUCT',
						'label' =>  ' ',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_PRODUCT_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_PRODUCT_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_OG_PRODUCT_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_PRODUCT_TYPE',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'facebook2' => array(
				'type' => 'group-buttons',
				'fieldset' => 'fb_pages',
				'name' => 'facebook2',
				'label' => $this->l('Category Page'),
				'tab' => 'facebook',
				'buttons' => array(
					'PA_SEO_OG_CATEGORY' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_CATEGORY',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_CATEGORY_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_CATEGORY_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_OG_CATEGORY_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_CATEGORY_TYPE',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'facebook3' => array(
				'type' => 'group-buttons',
				'fieldset' => 'fb_pages',
				'name' => 'facebook3',
				'label' => $this->l('Home Page'),
				'tab' => 'facebook',
				'buttons' => array(
					'PA_SEO_OG_HOME' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_HOME',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_HOME_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_HOME_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_OG_HOME_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_HOME_TYPE',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'facebook4' => array(
				'type' => 'group-buttons',
				'fieldset' => 'fb_pages',
				'name' => 'facebook4',
				'label' => $this->l('CMS Page'),
				'tab' => 'facebook',
				'buttons' => array(
					'PA_SEO_OG_CMS' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_CMS',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_CMS_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_CMS_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_OG_CMS_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_CMS_TYPE',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'facebook5' => array(
				'type' => 'group-buttons',
				'fieldset' => 'fb_pages',
				'name' => 'facebook5',
				'label' => $this->l('Manufacturer Page'),
				'tab' => 'facebook',
				'buttons' => array(
					'PA_SEO_OG_MAN' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_MAN',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_MAN_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_MAN_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_OG_MAN_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_MAN_TYPE',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'facebook6' => array(
				'type' => 'group-buttons',
				'fieldset' => 'fb_pages',
				'name' => 'facebook6',
				'label' => $this->l('Supplier Page'),
				'tab' => 'facebook',
				'buttons' => array(
					'PA_SEO_OG_SUP' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_SUP',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_SUP_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_SUP_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_OG_SUP_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_SUP_TYPE',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),

			'facebook8' => array(
				'type' => 'group-buttons',
				'fieldset' => 'fb_pages',
				'name' => 'facebook8',
				'label' => $this->l('Other Pages'),
				'tab' => 'facebook',
				'buttons' => array(
					array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_PAGE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_PAGE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_PAGE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_OG_PAGE_TYPE',
						'options' => array(
							'query' => $this->getFacebookObjectType(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
		);
	}

	public function getTwitterCards()
	{
		return array(
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
		);
	}

	public function getTwitterFields()
	{
		$type = 'switch';
		return array(
			'PA_SEO_TWT_PROFILE' => array(
				'type' => 'text',
				'fieldset' => 'tw_author',
				'col' => '4',
				'prefix' => '<i class="icon-twitter"></i>',
				'tab' => 'twitter',
				'label' => $this->l('Profile Twitter Username'),
				'name' => 'PA_SEO_TWT_PROFILE',
				'desc' => $this->l('Profile Username is your Twitter page.'),
			),
			'twitter1' => array(
				'type' => 'group-buttons',
				'fieldset' => 'tw_pages',
				'name' => 'twitter1',
				'label' => $this->l('Product Page'),
				'tab' => 'twitter',
				'buttons' => array(
					'PA_SEO_TW_PRODUCT' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_PRODUCT',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_TW_PRODUCT_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_TW_PRODUCT_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_TW_PRODUCT_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_PRODUCT_TYPE',
						'options' => array(
							'query' => $this->getTwitterCards(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'twitter2' => array(
				'type' => 'group-buttons',
				'fieldset' => 'tw_pages',
				'name' => 'twitter2',
				'label' => $this->l('Category Page'),
				'tab' => 'twitter',
				'buttons' => array(
					'PA_SEO_TW_CATEGORY' => array(
						'type' => $type,
						'label' =>  ' ',
						'tab' => 'facebook',
						'name' => 'PA_SEO_TW_CATEGORY',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_TW__CATEGORY_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_TW_CATEGORY_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_TW_CATEGORY_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_CATEGORY_TYPE',
						'options' => array(
							'query' => $this->getTwitterCards(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'twitter3' => array(
				'type' => 'group-buttons',
				'fieldset' => 'tw_pages',
				'name' => 'twitter3',
				'label' => $this->l('Home Page'),
				'tab' => 'twitter',
				'buttons' => array(
					'PA_SEO_TW_HOME' => array(
						'type' => $type,
						'tab' => 'facebook',
						'name' => 'PA_SEO_TW_HOME',
						'label' =>  ' ',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_TW_HOME_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_TW_HOME_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_TW_HOME_TYPE' => array(
						'type' => 'select',
						'label' =>  ' ',
						'tab' => 'general',
						'name' => 'PA_SEO_TW_HOME_TYPE',
						'options' => array(
							'query' => $this->getTwitterCards(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'twitter4' => array(
				'type' => 'group-buttons',
				'fieldset' => 'tw_pages',
				'name' => 'twitter4',
				'label' => $this->l('CMS Page'),
				'tab' => 'twitter',
				'buttons' => array(
					'PA_SEO_TW_CMS' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_CMS',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_TW_CMS_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_TW_CMS_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_TW_CMS_TYPE' => array(
						'type' => 'select',
						'label' =>  ' ',
						'tab' => 'general',
						'name' => 'PA_SEO_TW_CMS_TYPE',
						'options' => array(
							'query' => $this->getTwitterCards(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'twitter5' => array(
				'type' => 'group-buttons',
				'fieldset' => 'tw_pages',
				'name' => 'twitter5',
				'label' => $this->l('Manufacturer Page'),
				'tab' => 'twitter',
				'buttons' => array(
					'PA_SEO_TW_MAN' => array(
						'type' => $type,
						'label' =>  ' ',
						'tab' => 'facebook',
						'name' => 'PA_SEO_TW_MAN',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_TW_MAN_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_TW_MAN_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_TW_MAN_TYPE' => array(
						'type' => 'select',
						'label' =>  ' ',
						'tab' => 'general',
						'name' => 'PA_SEO_TW_MAN_TYPE',
						'options' => array(
							'query' => $this->getTwitterCards(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'twitter6' => array(
				'type' => 'group-buttons',
				'fieldset' => 'tw_pages',
				'name' => 'twitter6',
				'label' => $this->l('Supplier Page'),
				'tab' => 'twitter',
				'buttons' => array(
					'PA_SEO_TW_SUP' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_SUP',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_TW_SUP_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_TW_SUP_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_TW_SUP_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_SUP_TYPE',
						'options' => array(
							'query' => $this->getTwitterCards(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
			'twitter8' => array(
				'type' => 'group-buttons',
				'fieldset' => 'tw_pages',
				'name' => 'twitter8',
				'label' => $this->l('Other Pages'),
				'tab' => 'twitter',
				'buttons' => array(
					'PA_SEO_TW_SUP' => array(
						'type' => $type,
						'tab' => 'facebook',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_PAGE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_TW_PAGE_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_TW_PAGE_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_TW_SUP_TYPE' => array(
						'type' => 'select',
						'tab' => 'general',
						'label' =>  ' ',
						'name' => 'PA_SEO_TW_PAGE_TYPE',
						'options' => array(
							'query' => $this->getTwitterCards(),
							'id' => 'id',
							'name' => 'name',
						),
					),
				),
			),
		);
	}

	public function getOpenGraphGeneralFields()
	{
		$type = 'switch';
		$image_default = _PS_MODULE_DIR_.$this->module->name.'/views/img/og/default.jpg';
		$og_default_image_url = ImageManager::thumbnail($image_default, 'paseocenter_mini_default.'.$this->imageType, 150, $this->imageType, true, true);
		$og_default_image_size = file_exists($image_default) ? filesize($image_default) / 1000 : false;
		return array(
			'group1' => array(
				'type' => 'group-buttons',
				'name' => 'group1',
				'label' => '',
				'tab' => 'open_graph',
				'buttons' => array(
					'PA_SEO_OG_ENABLED' => array(
						'type' => $type,
						'tab' => 'open_graph',
						'label' => $this->l('Enable Open Graph Metas'),
						'name' => 'PA_SEO_OG_ENABLED',
						'hint' => $this->l('Enable Open Graph markups.'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_ENABLED_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_ENABLED_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_AUTO_OG_DESCRIPTION' => array(
						'type' => $type,
						'tab' => 'open_graph',
						'label' => $this->l('Autogenerate OG content'),
						'name' => 'PA_SEO_AUTO_OG_DESCRIPTION',
						'hint' => $this->l('Check this and your Open Graph descriptions will be auto-generated from your content.'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_AUTO_OG_DESCRIPTION_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_AUTO_OG_DESCRIPTION_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
				),
			),
			'group2' => array(
				'type' => 'group-buttons',
				'tab' => 'open_graph',
				'label' => ' ',
				'name' => 'group2',
				'buttons' => array(
					'PA_SEO_OG_HOME_LOGO' => array(
						'type' => $type,
						'tab' => 'open_graph',
						'label' => $this->l('Use logo as Home OG Image'),
						'name' => 'PA_SEO_OG_HOME_LOGO',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_HOME_LOGO_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_HOME_LOGO_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					'PA_SEO_OG_USE_DEFAULT_IMG' => array(
						'type' => $type,
						'tab' => 'open_graph',
						'label' => $this->l('Use OG Image Default'),
						'name' => 'PA_SEO_OG_USE_DEFAULT_IMG',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'PA_SEO_OG_USE_DEFAULT_IMG_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'PA_SEO_OG_USE_DEFAULT_IMG_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
				),
			),
			'PA_SEO_OG_IMG_SIZE' => array(
				'type' => 'select',
				'tab' => 'open_graph',
				'label' => $this->l('Default OG Image image size'),
				'name' => 'PA_SEO_OG_IMG_SIZE',
				'hint' => $this->l('Select the Image Open Graph size.'),
				'options' => array(
					'query' => ImageTypeCore::getImagesTypes(),
					'id' => 'id_image_type',
					'name' => 'name',
				),
			),
			'PA_SEO_OG_DEAULT_IMG' => array(
				'tab' => 'open_graph',
				'label' => $this->l('Default OG Image when image not found'),
				'name' => 'PA_SEO_OG_DEAULT_IMG',
				'type' => 'file',
				'display_image' => true,
				'image' => $og_default_image_url ? $og_default_image_url : false,
				'size' => $og_default_image_size,
				'hint' => $this->l('Upload a default open graph image from your computer.'),
			),
		);
	}

	protected function postImage($id)
	{
		$this->imageType = 'jpg';
		$this->fieldImageSettings = array();
		$this->fieldImageSettings[] = array(
			'image_name' => 'default',
			'name' => 'PA_SEO_OG_DEAULT_IMG',
			'dir' => '../modules/paseocenter/views/img/og'
		);
		$this->fieldImageSettings[] = array(
			'image_name' => 'home',
			'name' => 'PA_SEO_OG_HOME_IMG',
			'dir' => '../modules/paseocenter/views/img/og'
		);
		if (isset($this->fieldImageSettings['name']) && isset($this->fieldImageSettings['dir']))
			return $this->uploadImage($id, $this->fieldImageSettings['name'], $this->fieldImageSettings['dir'].'/');
		elseif (!empty($this->fieldImageSettings))
			foreach ($this->fieldImageSettings as $image)
				if (isset($image['name']) && isset($image['dir']))
				{
					$this->uploadImage($image['image_name'], $image['name'], $image['dir'].'/');
					$image_type = new ImageTypeCore(Configuration::get('PA_SEO_OG_IMG_SIZE'));
					$source_path = _PS_MODULE_DIR_.$this->module->name.'/views/img/og/'.$image['image_name'].'.jpg';
					$dest_path = _PS_MODULE_DIR_.$this->module->name.'/views/img/og/'.$image['image_name'].'-'.Tools::stripslashes($image_type->name).'.jpg';
					ImageManager::resize($source_path, $dest_path, (int)$image_type->width, (int)$image_type->height);
				}
		$files = scandir(_PS_IMG_DIR_.'tmp');
		foreach ($files as $file)
		{
			if (strpos($file, 'paseocenter_mini_') !== false)
			{
				$path = _PS_IMG_DIR_.'tmp'.DIRECTORY_SEPARATOR.$file;
				if (file_exists($path))
					unlink($path);
			}
		}
		return !count($this->errors) ? true : false;
	}

	protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
	{
		if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
		{
			// Check image validity
			$max_size = isset($this->max_image_size) ? $this->max_image_size : 0;
			if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size)))
				$this->errors[] = $error;

			$tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
			if (!$tmp_name) return false;
			if (!move_uploaded_file($_FILES[$name]['tmp_name'], $tmp_name)) return false;
			// Evaluate the memory required to resize the image: if it's too much, you can't resize it.
			if (!ImageManager::checkImageMemoryLimit($tmp_name))
				$this->errors[] = Tools::displayError('
				Due to memory limit restrictions, this image cannot be loaded.
				Please increase your memory_limit value via your server\'s configuration settings.
				');
			// Copy new image
			$dest_path = _PS_IMG_DIR_.$dir.$id.'.'.$this->imageType;
			$resize = !ImageManager::resize($tmp_name, $dest_path, (int)$width, (int)$height, ($ext ? $ext : $this->imageType));
			if (empty($this->errors) && $resize)
				$this->errors[] = Tools::displayError('An error occurred while uploading the image.');
			if (count($this->errors)) return false;
			if ($this->afterImageUpload())
			{
				unlink($tmp_name);
				return true;
			}
			return false;
		}
		return true;
	}

	public function getFacebookObjectType()
	{
		$fields = array(
//			array(
//				'id' => 'activity',
//				'name' => $this->l('Activity', 'all_in_one_seo_pack'),
//			),
//			array(
//				'id' => 'sport',
//				'name' => $this->l('Sport'),
//			),
//			array(
//				'id' => 'bar',
//				'name' => $this->l('Bar'),
//			),
//			array(
//				'id' => 'company',
//				'name' => $this->l('Company'),
//			),
//			array(
//				'id' => 'cafe',
//				'name' => $this->l('Cafe'),
//			),
//			array(
//				'id' => 'hotel',
//				'name' => $this->l('Hotel'),
//			),
//			array(
//				'id' => 'restaurant',
//				'name' => $this->l('Restaurant'),
//			),
//			array(
//				'id' => 'cause',
//				'name' => $this->l('Cause'),
//			),
//			array(
//				'id' => 'sports_league',
//				'name' => $this->l('Sports League'),
//			),
//			array(
//				'id' => 'sports_team',
//				'name' => $this->l('Sports Team'),
//			),
//			array(
//				'id' => 'band',
//				'name' => $this->l('Band'),
//			),
//			array(
//				'id' => 'government',
//				'name' => $this->l('Government'),
//			),
//			array(
//				'id' => 'non_profit',
//				'name' => $this->l('Non Profit'),
//			),
//			array(
//				'id' => 'school',
//				'name' => $this->l('School'),
//			),
//			array(
//				'id' => 'university',
//				'name' => $this->l('University'),
//			),
//			array(
//				'id' => 'actor',
//				'name' => $this->l('Actor'),
//			),
//			array(
//				'id' => 'athlete',
//				'name' => $this->l('Athlete'),
//			),
//			array(
//				'id' => 'author',
//				'name' => $this->l('Author'),
//			),
//			array(
//				'id' => 'director',
//				'name' => $this->l('Director'),
//			),
//			array(
//				'id' => 'musician',
//				'name' => $this->l('Musician'),
//			),
//			array(
//				'id' => 'politician',
//				'name' => $this->l('Politician'),
//			),
//			array(
//				'id' => 'profile',
//				'name' => $this->l('Profile'),
//			),
//			array(
//				'id' => 'public_figure',
//				'name' => $this->l('Public Figure'),
//			),
//			array(
//				'id' => 'city',
//				'name' => $this->l('City'),
//			),
//			array(
//				'id' => 'country',
//				'name' => $this->l('Country'),
//			),
//			array(
//				'id' => 'landmark',
//				'name' => $this->l('Landmark'),
//			),
//			array(
//				'id' => 'state_province',
//				'name' => $this->l('State Province'),
//			),
//			array(
//				'id' => 'album',
//				'name' => $this->l('Album'),
//			),
//			array(
//				'id' => 'book',
//				'name' => $this->l('Book'),
//			),
//			array(
//				'id' => 'drink',
//				'name' => $this->l('Drink'),
//			),
//			array(
//				'id' => 'food',
//				'name' => $this->l('Food'),
//			),
//			array(
//				'id' => 'game',
//				'name' => $this->l('Game'),
//			),
//			array(
//				'id' => 'movie',
//				'name' => $this->l('Movie'),
//			),
			array(
				'id' => 'product',
				'name' => $this->l('Product'),
			),
//			array(
//				'id' => 'song',
//				'name' => $this->l('Song'),
//			),
//			array(
//				'id' => 'tv_show',
//				'name' => $this->l('TV Show'),
//			),
//			array(
//				'id' => 'episode',
//				'name' => $this->l('Episode'),
//			),
//			array(
//				'id' => 'article',
//				'name' => $this->l('Article'),
//			),
//			array(
//				'id' => 'blog',
//				'name' => $this->l('Blog'),
//			),
			array(
				'id' => 'website',
				'name' => $this->l('Website')
			)
		);
		usort($fields, array(
			$this,
			'AzOrder'
		));
		return $fields;
	}

	public function AZOrder($a, $b)
	{
		return strcmp($a['id'], $b['id']);
	}

	public function initPageHeaderToolbar()
	{
		parent::initPageHeaderToolbar();
		$this->page_header_toolbar_btn['save-and-stay'] = array(
			'short' => 'Save',
			'href' => '#',
			'icon' => 'process-icon-save',
			'desc' => $this->l('Save'),
		);
	}
}
