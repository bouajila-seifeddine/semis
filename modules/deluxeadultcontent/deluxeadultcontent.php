<?php
/**
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* @author    Innovadeluxe SL
* @copyright 2016 Innovadeluxe SL

* @license   INNOVADELUXE
*/

if (!class_exists('WebBotChecker')) {
	require_once "libraries/webbotcheccker.php";
}

class DeluxeAdultContent extends Module
{
	public function __construct()
	{
		$this->name = 'deluxeadultcontent';
		$this->tab = 'front_office_features';
		$this->version = '1.5.0';
		$this->author = 'innovadeluxe';
		$this->module_key = '326314166b215a5626c68ef0e97acd79';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Deluxe Adult Content');
		$this->description = $this->l('Configurable warning message about adult content in your site');
	}

	public function install()
	{
		$lang_examples = Language::getLanguages(false);
		$text_acontent_example = array();
		$acontent_redirect_example = array();
		$button_accept = array();
		$button_cancel = array();

		foreach ($lang_examples as $lang_example) {
			$acontent_redirect_example[$lang_example['id_lang']] = '#';
			$text_acontent_example[$lang_example['id_lang']] = urlencode( '
				This store contains material for adults.
			');
			$button_accept[$lang_example['id_lang']] = 'Accept';
			$button_cancel[$lang_example['id_lang']] = 'No, thanks';
		}


		Configuration::updateValue(Tools::strtoupper($this->name).'_COLOR', '#000');
		Configuration::updateValue(Tools::strtoupper($this->name).'_OPACITY', '1');
		Configuration::updateValue(Tools::strtoupper($this->name).'_REDIRECT', $acontent_redirect_example);
		Configuration::updateValue(Tools::strtoupper($this->name).'_TEXT', $text_acontent_example);
		Configuration::updateValue(Tools::strtoupper($this->name).'_BUTT_ACCEPT', $button_accept);
		Configuration::updateValue(Tools::strtoupper($this->name).'_NOACCEP', $button_cancel);

		return parent::install() && $this->registerHook('displayHeader') &&
														$this->registerHook('backOfficeHeader');
	}

	public function uninstall()
	{
		Configuration::deleteByName(Tools::strtoupper($this->name).'_COLOR');
		Configuration::deleteByName(Tools::strtoupper($this->name).'_OPACITY');
		Configuration::deleteByName(Tools::strtoupper($this->name).'_REDIRECT');
		Configuration::deleteByName(Tools::strtoupper($this->name).'_TEXT');
		Configuration::deleteByName(Tools::strtoupper($this->name).'_BUTT_ACCEPT');
		Configuration::deleteByName(Tools::strtoupper($this->name).'_NOACCEP');
		return parent::uninstall();
	}

	public function hookDisplayHeader($params)
	{
				$bot =  new WebBotChecker();
				if ((bool)$bot->isThatBot() == true){			
					return false;
				}

				$this->context->controller->addCSS($this->_path.'views/css/'.$this->name.'.css', 'all');
				$this->context->controller->addJS ( $this->_path . 'views/js/' . $this->name . '.js', 'all' );

				$cookie_acontent_path = trim(__PS_BASE_URI__, '/\\').'/';
				if ($cookie_acontent_path{0} != '/') { 
					$cookie_acontent_path = '/'.$cookie_acontent_path;
				}
				
				$cookie_acontent_path = rawurlencode($cookie_acontent_path);
				$cookie_acontent_path = str_replace('%2F', '/', $cookie_acontent_path);
				$cookie_acontent_path = str_replace('%7E', '~', $cookie_acontent_path);
				$config = $this->getConfigFieldsValues();
				$active_lang = $this->context->language->id;

				$this->smarty->assign(array(
					'deluxeadultcontent_CookiesUrl' => Configuration::get(Tools::strtoupper($this->name.'_COOKIES_URL'), $active_lang),
					'deluxeadultcontent_CookiesUrlTitle' => Configuration::get(Tools::strtoupper($this->name.'_COOKIES_URL_TITLE'), $active_lang),
					'deluxeadultcontent_Redirect' => Configuration::get(Tools::strtoupper($this->name.'_REDIRECT'), $active_lang),
					'deluxeadultcontent_CookiesText' => Configuration::get(Tools::strtoupper($this->name.'_TEXT'), $active_lang),
					'deluxeadultcontent_CookieName' => 'DELUXEADULTCONTENTWarningCheck',
					'deluxeadultcontent_CookiePath' => $cookie_acontent_path,
					'deluxeadultcontent_CookieDomain' => $this->getDomain(),
					'deluxeadultcontent_txtBtnAccept' => Configuration::get(Tools::strtoupper($this->name.'_BUTT_ACCEPT'), $active_lang),
					'deluxeadultcontent_txtBtnCancel' => Configuration::get(Tools::strtoupper($this->name.'_NOACCEP'), $active_lang),
					'deluxeadultcontent_ajaxUrl' => Tools::getHttpHost(true)._MODULE_DIR_.'deluxeadultcontent/cookie_ajax.php',
					'deluxeadultcontent_opacity' => Configuration::get(Tools::strtoupper($this->name.'_OPACITY')),
					'deluxeadultcontent_color' => Configuration::get(Tools::strtoupper($this->name.'_COLOR')),
				));

				return $this->display(__FILE__, 'views/templates/front/deluxeadultcontent.tpl');
	}

	public function hookBackOfficeHeader()
	{
			if (Tools::getValue('configure') == $this->name) {
					$this->context->controller->addCSS($this->_path.'views/css/back.css');
			}
	}

	public function getContent()
	{
		$output = $this->innovaTitle();
		$output .= $this->postProcess() . $this->renderform();
		return $output;

	}

	public function postProcess()
	{
		$languages = Language::getLanguages(false);


		if (Tools::isSubmit('submitSettings'))
		{
			$acontent_cookies_url = array();
			$acontent_cookies_redirect = array();
			$acontent_cookies_text = array();
			$button_accept = array();
			$button_cancel = array();

			foreach ($languages as $language)
			{
				$acontent_cookies_url[$language['id_lang']] = Tools::getValue('DELUXEADULTCONTENT_COOKIES_URL_'.$language['id_lang']);
				$acontent_cookies_redirect[$language['id_lang']] = Tools::getValue('DELUXEADULTCONTENT_REDIRECT_'.$language['id_lang']);
				$acontent_cookies_text[$language['id_lang']] = urlencode(Tools::getValue('DELUXEADULTCONTENT_TEXT_'.$language['id_lang']));
				$button_accept[$language['id_lang']] = Tools::getValue('DELUXEADULTCONTENT_BUTT_ACCEPT_'.$language['id_lang']);;
				$button_cancel[$language['id_lang']] = Tools::getValue('DELUXEADULTCONTENT_NOACCEP_'.$language['id_lang']);;
			}

			Configuration::updateValue('DELUXEADULTCONTENT_REDIRECT', $acontent_cookies_redirect);
			Configuration::updateValue('DELUXEADULTCONTENT_TEXT', $acontent_cookies_text);
			Configuration::updateValue('DELUXEADULTCONTENT_BUTT_ACCEPT', $button_accept);
			Configuration::updateValue('DELUXEADULTCONTENT_NOACCEP', $button_cancel);
			Configuration::updateValue('DELUXEADULTCONTENT_COLOR', Tools::getValue('DELUXEADULTCONTENT_COLOR'));
			Configuration::updateValue('DELUXEADULTCONTENT_OPACITY', Tools::getValue('DELUXEADULTCONTENT_OPACITY'));

			return $this->displayConfirmation($this->l('The settings have been updated.'));
		}

		return '';
	}


	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'tinymce' => true,
				'legend' => array(
					'title' => $this->l('Options'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Redirection'),
						'name' => Tools::strtoupper($this->name).'_REDIRECT',
						'lang' => true,
						'desc' => $this->l('Where to redirect if the customer is not accepting this site')
					),
					 array(
						'type' => 'textarea',
						'label' => $this->l('Warning text'),
						'name' => Tools::strtoupper($this->name).'_TEXT',
						'lang' => true,
						'autoload_rte' => true,
						'desc' => $this->l('Text to show in the adult content warning contaniner'),
						'cols' => 60,
						'rows' => 30
					),
					array(
						'type' => 'text',
						'label' => $this->l('Accept text'),
						'name' => Tools::strtoupper($this->name).'_BUTT_ACCEPT',
						'lang' => true,
						'desc' => $this->l('Text to show in the accept button.')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Not accept text'),
						'name' => Tools::strtoupper($this->name).'_NOACCEP',
						'lang' => true,
						'desc' => $this->l('Text to show in the not accept button.')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Background opacity'),
						'name' => Tools::strtoupper($this->name).'_OPACITY',
						'desc' => $this->l('0 = Transparent; 1 = Opaque; Default: 0.8')
					),
					array(
						'type' => 'color',
						'label' => $this->l('Background color'),
						'name' => Tools::strtoupper($this->name).'_COLOR',
						'desc' => $this->l('')
					),
				),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default')
			),
		);



		return $this->helperCreator('submitSettings', $fields_form);
	}

	private function helperCreator($submitAction, $fields_form)
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language) $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->languages = $languages;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = $submitAction;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
				'fields_value' => $this->getConfigFieldsValues(),
				'languages' => $this->context->controller->getLanguages(),
				'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		$languages = Language::getLanguages(false);
		$acontent_cookies_url = array();
		$acontent_cookies_url_title = array();
		$acontent_cookies_text = array();
		$button_accept = array();
		$button_cancel = array();
		$acontent_cookies_redirect = array();

		foreach ($languages as $language) {
			$acontent_cookies_url[$language['id_lang']] = Configuration::get('DELUXEADULTCONTENT_COOKIES_URL', $language['id_lang']);
			$acontent_cookies_url_title[$language['id_lang']] = Configuration::get('DELUXEADULTCONTENT_COOKIES_URL_TITLE', $language['id_lang']);
			$acontent_cookies_redirect[$language['id_lang']] = Configuration::get('DELUXEADULTCONTENT_REDIRECT', $language['id_lang']);
			$acontent_cookies_text[$language['id_lang']] = urldecode(Configuration::get('DELUXEADULTCONTENT_TEXT', $language['id_lang']));
			$button_accept[$language['id_lang']] = Configuration::get('DELUXEADULTCONTENT_BUTT_ACCEPT', $language['id_lang']);
			$button_cancel[$language['id_lang']] = Configuration::get('DELUXEADULTCONTENT_NOACCEP', $language['id_lang']);
		}

		return array(
			'DELUXEADULTCONTENT_REDIRECT' => Tools::getValue('DELUXEADULTCONTENT_REDIRECT', $acontent_cookies_redirect),
			'DELUXEADULTCONTENT_TEXT' => Tools::getValue('DELUXEADULTCONTENT_TEXT', $acontent_cookies_text),
			'DELUXEADULTCONTENT_BUTT_ACCEPT' => Tools::getValue('DELUXEADULTCONTENT_BUTT_ACCEPT', $button_accept),
			'DELUXEADULTCONTENT_NOACCEP' => Tools::getValue('DELUXEADULTCONTENT_NOACCEP', $button_cancel),
			'DELUXEADULTCONTENT_COLOR' => Tools::getValue('DELUXEADULTCONTENT_COLOR', Configuration::get('DELUXEADULTCONTENT_COLOR')),
			'DELUXEADULTCONTENT_OPACITY' => Tools::getValue('DELUXEADULTCONTENT_OPACITY', Configuration::get('DELUXEADULTCONTENT_OPACITY')),
		);
	}

	// Custom --------->

	public function innovaTitle()
	{
			$suggested_modules = array(
					'en' => array(
							'texto_soporte' => 'support',
							'texto_ayuda' => 'help',
							'texto_opinion' => 'opinion',
							'texto_modulos_interesar' => 'More modules could be interesting for you to improve your shop',
							'texto_todos_modulos' => 'Review all our modules',
							'nombre_modulo1' => 'Data privacy extended (data protection law) - LOPD',
							'nombre_modulo2' => 'EU Cookies Law warning',
							'nombre_modulo3' => 'Refunds manager',
					),
					'es' => array(
							'texto_soporte' => 'soporte',
							'texto_ayuda' => 'ayuda',
							'texto_opinion' => 'opinion',
							'texto_modulos_interesar' => 'Más módulos que te pueden interesar para potenciar tu tienda',
							'texto_todos_modulos' => 'consulta todos nuestros módulos',
							'nombre_modulo1' => 'Cumplimiento Ley de protección de datos - LOPD',
							'nombre_modulo2' => 'Aviso cumplimiento de la ley de Cookies',
							'nombre_modulo3' => 'Gestor de devoluciones',
					),
					'de' => array(
							'texto_soporte' => 'Support',
							'texto_ayuda' => 'Helfen',
							'texto_opinion' => 'Kommentar',
							'texto_modulos_interesar' => 'Weitere Module, die von Interesse sein könnte, Ihr Geschäft zu verbessern',
							'texto_todos_modulos' => 'Alle unsere Module',
							'nombre_modulo1' => 'Einhaltung der Datenschutzgesetze - BDSG',
							'nombre_modulo2' => 'Hinweise zur Erfüllung der Cookie-Richtlinien',
							'nombre_modulo3' => 'Refunds manager',
					),
					'fr' => array(
							'texto_soporte' => 'Soutien',
							'texto_ayuda' => 'Aider',
							'texto_opinion' => 'Commentaire',
							'texto_modulos_interesar' => 'Plus de modules qui pourraient être d\'intérêt pour améliorer votre magasin',
							'texto_todos_modulos' => 'Voir tous nos modules',
							'nombre_modulo1' => 'Conformité à la Loi sur la protection des données LOPD',
							'nombre_modulo2' => 'Avertissement Légal de Cookies',
							'nombre_modulo3' => 'Refunds manager',
					),
					'it' => array(
							'texto_soporte' => 'Asistenza',
							'texto_ayuda' => 'Aiutare',
							'texto_opinion' => 'Commento',
							'texto_modulos_interesar' => 'Altri moduli che possono essere di interesse per migliorare il vostro negozio',
							'texto_todos_modulos' => 'Vedi tutti i nostri moduli',
							'nombre_modulo1' => 'Codice in materia di Protezione dei Dati Personali',
							'nombre_modulo2' => 'Avviso sul rispetto della Legge dei Cookies Europea',
							'nombre_modulo3' => 'Refunds manager',
					),
					'id_modulo_actual' => '18008',
					'id_modulo1' => '8123',
					'id_modulo2' => '8296',
					'id_modulo3' => '21870'
			);

			$iso_code_selected = '';
			$shop_iso_code_lang = $this->context->language->iso_code;
			switch ($shop_iso_code_lang) {
					case 'en':
					case 'es':
					case 'de':
					case 'fr':
					case 'it':
							$iso_code_selected = $shop_iso_code_lang;
							break;

					default:
							$iso_code_selected = 'en';
							break;
			}
			$this->smarty->assign(array(
			 'module_dir' => $this->_path,
			 'module_name' => $this->displayName,
			 'module_description' => $this->description,
			 'link_iso' => $iso_code_selected,
			 'suggested_modules' => $suggested_modules,
			));

			return $this->display(__FILE__, 'views/templates/admin/banner/banner.tpl') .
			$this->display(__FILE__, 'views/templates/admin/innova-title.tpl');
	}


	protected function getDomain()
	{
		$r = '!(?:(\w+)://)?(?:(\w+)\:(\w+)@)?([^/:]+)?(?:\:(\d*))?([^#?]+)?(?:\?([^#]+))?(?:#(.+$))?!i';
		preg_match ($r, Tools::getHttpHost(false, false), $out);
		if (preg_match('/^(((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]{1}[0-9]|[1-9]).)'.
		 '{1}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]).)'.
		 '{2}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]){1}))$/', $out[4])) return false;
		if (!strstr(Tools::getHttpHost(false, false), '.')) return false;
		$domain = $out[4];

		return $domain;
	}

}
