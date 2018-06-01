<?php
/**
* Cookies Plus
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2018 idnovate.com
*  @license   See above
*/

class CookiesPlus extends Module
{
    public function __construct()
    {
        $this->name = 'cookiesplus';
        $this->tab = 'front_office_features';
        $this->version = '1.1.2';
        $this->author = 'idnovate';
        $this->module_key = '22c3b977fe9c819543a216a2fd948f22';
        $this->author_address = '0xd89bcCAeb29b2E6342a74Bc0e9C82718Ac702160';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Cookies - GDPR Cookie law (block before consent)');
        $this->description = $this->l('Make your store GDPR compliant using this module. This module lets you block the cookies until the customer gives his consent accepting the warning.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete the module and the related data?');

        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
            $this->local_path = _PS_MODULE_DIR_.$this->name.'/';
        }

        $this->warning = $this->getWarnings(false);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('displayNav')
            && $this->registerHook('displayNav2')
            && (version_compare(_PS_VERSION_, '1.5', '<') || $this->registerHook('displayMyAccountBlockfooter'))
            && $this->registerHook('customerAccount')
            && $this->registerHook('backOfficeHeader')
            && $this->setDefaultValues()
            && $this->installOverride()
            ;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        $fields = array_merge($this->getConfigValues(), $this->getLangConfigValues());

        foreach (array_keys($fields) as $key) {
            Configuration::deleteByName($key);
        }

        return true;
    }

    public function setDefaultValues()
    {
        Configuration::updateValue('C_P_EXPIRY', '365');
        Configuration::updateValue('C_P_BOTS', 'Teoma|alexa|froogle|Gigabot|inktomi|looksmart|URL_Spider_SQL|Firefly|NationalDirectory|AskJeeves|TECNOSEEK|InfoSeek|WebFindBot|girafabot|crawler|www.galaxy.com|Googlebot|Scooter|TechnoratiSnoop|Rankivabot|Mediapartners-Google| Sogouwebspider|WebAltaCrawler|TweetmemeBot|Butterfly|Twitturls|Me.dium|Twiceler');

        $modulesIds = array();
        $modulesThird = array('ganalytics', 'blockfacebook');
        $modules =  Module::getModulesOnDisk();
        foreach ($modules as $module) {
            if (in_array($module->name, $modulesThird) && !$module->not_on_disk) {
                $modulesIds[] = $module->id;
            }
        }
        Configuration::updateValue('C_P_MODULES_VALUES', Tools::jsonEncode($modulesIds));

        $fields = array();

        //Initialize multilang configuration values
        $translations = array();

        // English
        $translations['C_P_TEXT_BASIC']['en'] = 'This store asks you to accept cookies for performance, social media and advertising purposes. Social media and advertising cookies of third parties are used to offer you social media functionalities and personalized ads. To get more information or amend your preferences, press the "Advanced settings" button or visit "Cookie preferences" at the bottom of the website. To get more information about these cookies and the processing of your personal data, press the "More information" button. Do you accept these cookies and the processing of personal data involved?';
        $translations['C_P_TEXT_REQUIRED']['en'] = '<p><strong>Strictly necessary cookies</strong><br />These are Cookies that are necessary for the functioning of the Online Services. For example, they are used to enable the operation of the Online Services, enable access to secure areas of the Online Services, remember items placed in a shopping basket or cart during a session, secure the Online Services and for the administration of the Online Services (e.g., load balancing, fraud prevention). Without these Cookies, the Online Services would not function properly and this store may be unable to provide certain services.</p>';
        $translations['C_P_TEXT_3RDPARTY']['en'] = '<p><strong>Third party cookies</strong><br />This store may use third parties who use their own Cookies to store and/or access data relating to your use of, and interaction with, the Online Services. The Online Services may contain content from third parties (such as Google Maps, YouTube, ShareThis, etc.) and plugins from social media sites (like Facebook, Twitter, Linkedin, etc.). When you connect to these services, the third parties may store and/or access data using Cookies over which this store does not have control. If you are logged in to a social media website while visiting the Online Services the social media plugins may allow the social media website to receive information that you visited the Online Services and link it to your social media account. This store does not control the Cookies used by these third party services or their policies or practices. Please review those third parties\' cookie, privacy and data sharing statements.</p>';
        $translations['C_P_TEXT_REJECT']['en'] = 'If you don\'t accept strictly necessary cookies you can\'t continue in this store and we are going to redirect you to another site. Are you sure?';

        //Spanish
        $translations['C_P_TEXT_BASIC']['es'] = 'Esta tienda te pide que aceptes cookies para fines de rendimiento, redes sociales y publicidad. Las redes sociales y las cookies publicitarias de terceros se utilizan para ofrecerte funciones de redes sociales y anuncios personalizados. Para obtener más información o modificar tus preferencias, presiona el botón "Configuración avanzada" o visita "Preferencias de cookies" en la parte inferior del sitio web. Para obtener más información sobre estas cookies y el procesamiento de tus datos personales, presiona el botón "Más información". ¿Aceptas estas cookies y el procesamiento de datos personales involucrados?';
        $translations['C_P_TEXT_REQUIRED']['es'] = '<p><strong>Cookies obligatorias</strong><br />Se trata de aquellas Cookies que son necesarias para el funcionamiento de los Servicios en línea. Por ejemplo, se utilizan para permitir el funcionamiento de los Servicios en línea, hacer posible el acceso a las áreas protegidas de los Servicios en línea y recordar los elementos colocados en la cesta o el carrito de la compra durante la sesión, así como proteger y administrar los Servicios en línea (por ejemplo, equilibrado de carga o prevención del fraude). Sin estas Cookies, los Servicios en línea no funcionarían correctamente y/o es posible que esta tienda no pudiese prestar determinados servicios.</p>';
        $translations['C_P_TEXT_3RDPARTY']['es'] = '<p><strong>Cookies de terceros</strong><br />Esta tienda puede recurrir a terceros que utilicen sus propias Cookies para almacenar los datos relativos a cómo utilizas los Servicios en línea e interaccionas con ellos, así como para obtener acceso a tales datos. Los Servicios en línea pueden incluir contenido de terceros (tales como Google Maps, YouTube, ShareThis), y complementos de sitios de redes sociales, (como Facebook, LinkedIn, etc.). Cuando te conectas a estos servicios, otros terceros pueden utilizar Cookies para almacenar datos (y obtener acceso a ellos) sobre los que esta tienda no ejerce control alguno; esto incluye Cookies funcionales, de selección o publicidad de esos terceros. Si tienes una sesión iniciada en un sitio web de una red social mientras visitas los Servicios en línea, los complementos de estas redes podrían permitir que ese sitio reciba información sobre el hecho de que has visitado los Servicios en línea y enlazar dicha información a la cuenta de la red social en cuestión. Esta tienda no controla las Cookies que estos servicios de terceros utilizan ni tampoco sus políticas o prácticas al respecto. Es importante que consultes las declaraciones de esos terceros relativas a cookies, privacidad o intercambio de datos.</p>';
        $translations['C_P_TEXT_REJECT']['es'] = 'Si no aceptas las cookies obligatorias no puedes continuar en esta tienda y te redirigiremos a otra web. ¿Está seguro?';

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                $languageCode = $lang['iso_code'];
            } else {
                $languageCode = strtok($lang['language_code'], '-');
            }

            $fields['C_P_TEXT_BASIC'][$lang['id_lang']] = isset($translations['C_P_TEXT_BASIC'][$languageCode]) ? $translations['C_P_TEXT_BASIC'][$languageCode] : $translations['C_P_TEXT_BASIC']['en'];
            $fields['C_P_TEXT_REQUIRED'][$lang['id_lang']] = isset($translations['C_P_TEXT_REQUIRED'][$languageCode]) ? $translations['C_P_TEXT_REQUIRED'][$languageCode] : $translations['C_P_TEXT_REQUIRED']['en'];
            $fields['C_P_TEXT_3RDPARTY'][$lang['id_lang']] = isset($translations['C_P_TEXT_3RDPARTY'][$languageCode]) ? $translations['C_P_TEXT_3RDPARTY'][$languageCode] : $translations['C_P_TEXT_3RDPARTY']['en'];
            $fields['C_P_TEXT_REJECT'][$lang['id_lang']] = isset($translations['C_P_TEXT_REJECT'][$languageCode]) ? $translations['C_P_TEXT_REJECT'][$languageCode] : $translations['C_P_TEXT_REJECT']['en'];

            $fields['C_P_REJECT_URL'][$lang['id_lang']] = 'https://www.google.com/';
        }

        Configuration::updateValue('C_P_TEXT_BASIC', $fields['C_P_TEXT_BASIC'], true);
        Configuration::updateValue('C_P_TEXT_REQUIRED', $fields['C_P_TEXT_REQUIRED'], true);
        Configuration::updateValue('C_P_TEXT_3RDPARTY', $fields['C_P_TEXT_3RDPARTY'], true);
        Configuration::updateValue('C_P_TEXT_REJECT', $fields['C_P_TEXT_REJECT'], true);
        Configuration::updateValue('C_P_REJECT_URL', $fields['C_P_REJECT_URL'], true);

        return true;
    }

    public function installOverride()
    {
        if (_PS_VERSION_ > '1.5') {
            return true;
        }

        $errors = array();

        // Make sure the environment is OK
        if (!is_dir(dirname(__FILE__).'/../../override/classes/')) {
            mkdir(dirname(__FILE__).'/../../override/classes/', 0777, true);
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Cookie.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Cookie.php') == md5_file(dirname(__FILE__).'/override/classes/Cookie.php')) {
                $errors[] = '/override/classes/Tools.php';
            }
        }

        if (!copy(dirname(__FILE__).'/override/classes/Cookie.php', dirname(__FILE__).'/../../override/classes/Cookie.php')) {
            $errors[] = '/override/classes/Cookie.php';
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Hook.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Hook.php') == md5_file(dirname(__FILE__).'/override/classes/Hook.php')) {
                $errors[] = '/override/classes/FrontController.php';
            }
        }

        if (!copy(dirname(__FILE__).'/override/classes/Hook.php', dirname(__FILE__).'/../../override/classes/Hook.php')) {
            $errors[] = '/override/classes/Hook.php';
        }

        if (count($errors)) {
            die('<div class="conf warn">
                                <img src="../img/admin/warn2.png" alt="" title="" />'.
                $this->l('The module was successfully installed (').
                '<a href="?tab=AdminModules&configure=cookiesplus&token='.Tools::getAdminTokenLite('AdminModules').'&tab_module=front_office_features&module_name=cookiesplus" style="color: blue;">'.$this->l('configure').'</a>'.
                $this->l(') but the following file already exist. Please, merge the file manually.').'<br />'.
                implode('<br />', $errors).
                '</div>');
        }

        return true;
    }

    public function getContent()
    {
        $this->context->controller->addCSS($this->_path.'views/css/cookiesplus.admin.css');

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->controller->addJS($this->_path.'views/js/cookiesplus.admin.js');
            $this->context->controller->addJS($this->_path.'views/js/tabs.js');
        }

        $html = '';
        if (((bool)Tools::isSubmit('submitCookiesPlusModule')) == true) {
            $html .= $this->postProcess();
        }

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $html . $this->renderForm14();
        } else {
            return $html . $this->renderForm();
        }
    }

    protected function renderForm()
    {
        $html = '';

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCookiesPlusModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $html .= $helper->generateForm($this->getConfigForm());

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->smarty->assign(array(
                'this_path'     => $this->_path,
                'support_id'    => '21644'
            ));

            $available_lang_codes = array('en', 'es', 'fr', 'it', 'de');
            $default_lang_code = 'en';
            $template_iso_suffix = in_array(strtok($this->context->language->language_code, '-'), $available_lang_codes) ? strtok($this->context->language->language_code, '-') : $default_lang_code;
            $html .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/company/information_'.$template_iso_suffix.'.tpl');
        }

        return $html;
    }

    protected function renderForm14()
    {
        $helper = new Helper();

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => Language::getLanguages(false),
            'id_language' => $this->context->language->id,
            'THEME_LANG_DIR' => _PS_IMG_.'l/'
        );

        return $helper->generateForm($this->getConfigForm());
    }

    protected function getConfigForm()
    {
        $fields = array();

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Module settings'),
                'icon' => 'icon-cogs',
            ),
            'input' => array(
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Enable module'),
                    'desc'  => $this->l(''),
                    'name' => 'C_P_ENABLE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'C_P_ENABLE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'C_P_ENABLE_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                ),
                array(
                    'col' => 2,
                    'type' => 'text',
                    'label' => $this->l('Cookie lifetime'),
                    'hint' => $this->l('Cookie consent will be stored during this time (or until customer delete cookies)'),
                    'suffix' => 'days',
                    'name' => 'C_P_EXPIRY',
                    'class' => 't',
                    'required' => true,
                ),
                array(
                    'col' => 8,
                    'type' => 'textarea',
                    'label' => $this->l('Exclude module for these user agents (SEO)'),
                    'desc' => $this->l('Separate each user agent with a "|" (pipe) character'),
                    'name' => 'C_P_BOTS',
                    'class' => 't',
                ),
                array(
                    'col' => 8,
                    'type' => 'textarea',
                    'label' => $this->l('Exclude module for these IPs'),
                    'desc' => $this->l('Separate each IP with a "|" (pipe) character'),
                    'name' => 'C_P_IPS',
                    'class' => 't',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $cms = CMS::listCms($this->context->language->id);
        $dummy = array(
            'id_cms' => 0,
            'meta_title' => '-'
        );

        array_unshift($cms, $dummy);

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Basic configuration'),
                'icon' => 'icon-pencil',
            ),
            'input' => array(
                array(
                    'cols' => 90,
                    'rows' => 5,
                    'type' => 'textarea',
                    'label' => $this->l('Cookies description'),
                    'name' => 'C_P_TEXT_BASIC',
                    'lang' => true,
                    'required' => true,
                    'autoload_rte' => true,
                    'class' => 't',
                    //'autoload_rte' => version_compare(_PS_VERSION_, '1.6', '>=') ? '' : true,
                    //'class' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'apc_tiny' : '',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Display a link to cookies policy CMS'),
                    'name' => 'C_P_CMS_PAGE',
                    'class' => 't',
                    'options' => array(
                        'query' => $cms,
                        'id' => 'id_cms',
                        'name' => 'meta_title'
                    ),
                ),
                array(
                    'col' => 9,
                    'type' => 'html',
                    'label' => 'Preview',
                    'name' => '<img style="max-width: auto;" src="'.$this->_path.'views/img/basic.png">',
                    'class' => 't',
                    'lang' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced configuration'),
                'icon' => 'icon-pencil',
            ),
            'input' => array(
                array(
                    'cols' => 90,
                    'rows' => 5,
                    'type' => 'textarea',
                    'label' => $this->l('Strictly necessary cookies description'),
                    'name' => 'C_P_TEXT_REQUIRED',
                    'lang' => true,
                    'required' => true,
                    'autoload_rte' => true,
                    'class' => 't',
                    //'autoload_rte' => version_compare(_PS_VERSION_, '1.6', '>=') ? '' : true,
                    //'class' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'apc_tiny' : '',
                ),

                array(
                    'cols' => 90,
                    'rows' => 5,
                    'type' => 'textarea',
                    'label' => $this->l('3rd party cookies description'),
                    'name' => 'C_P_TEXT_3RDPARTY',
                    'lang' => true,
                    'required' => false,
                    'class' => 't',
                    'autoload_rte' => true,
                    //'autoload_rte' => version_compare(_PS_VERSION_, '1.6', '>=') ? '' : true,
                    //'class' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'apc_tiny' : '',
                ),
                array(
                    'cols' => 90,
                    'rows' => 5,
                    'type' => 'textarea',
                    'label' => $this->l('Text when user reject installing cookies'),
                    'name' => 'C_P_TEXT_REJECT',
                    'lang' => true,
                    'required' => true,
                    'class' => 't',
                    'autoload_rte' => true,
                    //'autoload_rte' => version_compare(_PS_VERSION_, '1.6', '>=') ? '' : true,
                    //'class' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'apc_tiny' : '',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Redirect customer to this URL when essential cookies are not accepted'),
                    'name' => 'C_P_REJECT_URL',
                    'lang' => true,
                    'required' => true,
                    'class' => 't',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Display a link to cookies policy CMS'),
                    'name' => 'C_P_CMS_PAGE_ADV',
                    'class' => 't',
                    'options' => array(
                        'query' => $cms,
                        'id' => 'id_cms',
                        'name' => 'meta_title'
                    ),
                ),
                array(
                    'type' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'switch' : 'radio',
                    'label' => $this->l('Accept cookies default value'),
                    'name' => 'C_P_DEFAULT_VALUE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'C_P_DEFAULT_VALUE_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'C_P_DEFAULT_VALUE_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'col' => 9,
                    'type' => 'html',
                    'label' => 'Preview',
                    'name' => '<img style="max-width: auto;" src="'.$this->_path.'views/img/advanced.png">',
                    'class' => 't',
                    'lang' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Stricly necessary cookies modules'),
                'icon' => 'icon-certificate',
            ),
            'input' => array(
                array(
                    'col' => 9,
                    'type' => 'html',
                    'label' => '',
                    'name' => '<div class="alert alert-warning">'.$this->l('Select the modules that install cookies. The selected modules will not be executed until customer accepts strictly necessary cookies').'</div>',
                    'class' => 't',
                    'lang' => true,
                ),
                array(
                    'col' => 9,
                    'type' => 'free',
                    'label' => $this->l('Modules blocked'),
                    'name' => 'C_P_MODULES_NEC',
                    'class' => 't',
                    'lang' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('3rd party cookies modules'),
                'icon' => 'icon-certificate',
            ),
            'input' => array(
                array(
                    'col' => 9,
                    'type' => 'html',
                    'label' => '',
                    'name' => '<div class="alert alert-warning">'.$this->l('Select the modules that install cookies. The selected modules will not be executed until customer accepts 3rd party cookies').'</div>',
                    'class' => 't',
                    'lang' => true,
                ),
                array(
                    'col' => 9,
                    'type' => 'free',
                    'label' => $this->l('Modules blocked'),
                    'name' => 'C_P_MODULES',
                    'class' => 't',
                    'lang' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        return $fields;
    }

    protected function getConfigFormValues()
    {
        $fields = array_merge($this->getConfigValues(), $this->getLangConfigValues());

        return $fields;
    }

    protected function postProcess()
    {
        $html = '';
        $errors = array();

        if (!Tools::getValue('C_P_EXPIRY')) {
            $errors[] = $this->l('You have to introduce the cookie expiry time');
        } elseif (!Validate::isUnsignedInt(Tools::getValue('C_P_EXPIRY'))
            || Tools::getValue('C_P_EXPIRY') <= 0) {
            $errors[] = $this->l('You have to introduce a correct value for cookie expiry time');
        }

        if (Tools::getValue('C_P_REJECT_URL')
            && !Validate::isUrl(Tools::getValue('C_P_REJECT_URL'))) {
            $errors[] = $this->l('You have to introduce a correct URL');
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $html .= $this->displayError($error);
            }
        } else {
            $fields = array_merge($this->getConfigValues(), $this->getLangConfigValues());

            foreach (array_keys($fields) as $key) {
                if ($key == 'C_P_MODULES_VALUES') {
                    Configuration::updateValue($key, Tools::jsonEncode(Tools::getValue('C_P_MODULES_VALUES')));
                } elseif ($key == 'C_P_MODULES_NEC_VALUES') {
                    Configuration::updateValue($key, Tools::jsonEncode(Tools::getValue('C_P_MODULES_NEC_VALUES')));
                } else {
                    Configuration::updateValue($key, $fields[$key], true);
                }
            }

            $html .= $this->displayConfirmation($this->l('Configuration saved successfully.'));
        }

        return $html;
    }

    protected function getLangConfigValues()
    {
        $fields = array();

        $configFields = array('C_P_TEXT_BASIC', 'C_P_TEXT_REQUIRED', 'C_P_TEXT_3RDPARTY', 'C_P_TEXT_REJECT', 'C_P_REJECT_URL');

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            foreach ($configFields as $field) {
                $fields[$field][$lang['id_lang']] = Tools::getValue(
                    $field.'_'.$lang['id_lang'],
                    Configuration::get($field, $lang['id_lang'])
                );
            }
        }

        return $fields;
    }

    protected function getConfigValues()
    {
        $fields = array();

        $configFields = array('C_P_ENABLE', 'C_P_EXPIRY', 'C_P_BOTS', 'C_P_IPS', 'C_P_CMS_PAGE', 'C_P_CMS_PAGE_ADV', 'C_P_DEFAULT_VALUE');

        foreach ($configFields as $field) {
            $fields[$field] = Tools::getValue($field, Configuration::get($field));
        }

        $fields['C_P_MODULES_VALUES'] = Configuration::get('C_P_MODULES_VALUES');
        $fields['C_P_MODULES_NEC_VALUES'] = Configuration::get('C_P_MODULES_NEC_VALUES');

        $this->context->smarty->assign(array(
            'allModules' => $this->getModuleList('necessary'),
            'fieldName' => 'C_P_MODULES_NEC_VALUES'
        ));

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $templateName = 'configure_modules_14.tpl';
        } else {
            $templateName = 'configure_modules.tpl';
        }

        $fields['C_P_MODULES_NEC'] =
            $this->context->smarty->fetch($this->local_path.'views/templates/admin/'.$templateName);

        $this->context->smarty->assign(array(
            'allModules' => $this->getModuleList('third'),
            'fieldName' => 'C_P_MODULES_VALUES'
        ));
        $fields['C_P_MODULES'] =
            $this->context->smarty->fetch($this->local_path.'views/templates/admin/'.$templateName);


        return $fields;
    }

    public function hookHeader()
    {
        if (isset($this->context->controller->cms->id)
            && ($this->context->controller->cms->id == Configuration::get('C_P_CMS_PAGE')
                || $this->context->controller->cms->id == Configuration::get('C_P_CMS_PAGE_ADV'))) {
            return;
        }

        if (Configuration::get('C_P_ENABLE')) {
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                Tools::addJS(_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js');
                Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css');
            } else {
                $this->context->controller->addJqueryPlugin('fancybox');
            }

            $this->context->controller->addJS($this->_path.'views/js/cookiesplus.js');

            $this->context->smarty->assign(array(
                'C_P_COOKIE_VALUE'  => $this->context->cookie->psnotice,
                'C_P_TEXT_BASIC'    => Configuration::get('C_P_TEXT_BASIC', $this->context->cookie->id_lang),
                'C_P_TEXT_REQUIRED' => Configuration::get('C_P_TEXT_REQUIRED', $this->context->cookie->id_lang),
                'C_P_TEXT_3RDPARTY' => Configuration::get('C_P_TEXT_3RDPARTY', $this->context->cookie->id_lang),
                'C_P_TEXT_REJECT'   => Configuration::get('C_P_TEXT_REJECT', $this->context->cookie->id_lang),
                'C_P_REJECT_URL'    => Configuration::get('C_P_REJECT_URL', $this->context->cookie->id_lang),
                'C_P_CMS_PAGE'      => Configuration::get('C_P_CMS_PAGE'),
                'C_P_CMS_PAGE_ADV'  => Configuration::get('C_P_CMS_PAGE_ADV'),
                'C_P_DEFAULT_VALUE' => Configuration::get('C_P_DEFAULT_VALUE'),
                'C_P_VERSION'       => Tools::substr(_PS_VERSION_, 0, 3)
            ));

            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                return $this->display(__FILE__, '/views/templates/hook/header_14.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, '/views/templates/hook/header_15.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->display(__FILE__, 'header_16.tpl');
            } else {
                return $this->display(__FILE__, 'header_17.tpl');
            }
        }
    }

    public function hookCustomerAccount($params)
    {
        /* PS 1.4 only */
        global $smarty;
        return $this->display(__FILE__, 'views/templates/hook/customer_account_14.tpl');
    }

    public function hookDisplayMyAccountBlock()
    {
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            return $this->hookDisplayMyAccountBlockFooter();
        }
    }

    public function hookDisplayMyAccountBlockFooter()
    {
        if (Configuration::get('C_P_ENABLE')) {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, 'footer_15.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->context->smarty->fetch($this->local_path.'views/templates/hook/footer_16.tpl');
            } else {
                return $this->context->smarty->fetch($this->local_path.'views/templates/hook/footer_17.tpl');
            }
        }
    }

    public function hookDisplayCustomerAccount()
    {
        if (Configuration::get('C_P_ENABLE')) {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, 'customer_account_15.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->display(__FILE__, 'customer_account_16.tpl');
            } else {
                return $this->display(__FILE__, 'customer_account_17.tpl');
            }
        }
    }

    public function hookDisplayNav()
    {
        if (Configuration::get('C_P_ENABLE')) {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, 'nav_16.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->display(__FILE__, 'nav_16.tpl');
            } else {
                return $this->display(__FILE__, 'nav_17.tpl');
            }
        }
    }

    public function hookDisplayNav2()
    {
        return $this->hookDisplayNav();
    }

    public static function updateCookie($modules)
    {
        if (!Configuration::get('C_P_ENABLE')) {
            return $modules;
        }

        //Exclude admin calls
        if (defined('_PS_ADMIN_DIR_')) {
            return $modules;
        }
        if (is_object(Context::getContext()->controller)
            && Context::getContext()->controller->controller_type == 'admin') {
            return $modules;
        }

        //Exclude .map extensions
        if (pathinfo(parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")['path'], PATHINFO_EXTENSION) == 'map') {
            return $modules;
        }

        //Validate user agent
        if (self::allowedUserAgent()) {
            return $modules;
        }

        //Validate IP
        if (self::allowedIP()) {
            return $modules;
        }

        $path = trim(Context::getContext()->shop->physical_uri, '/\\').'/';
        if ($path{0} != '/') {
            $path = '/'.$path;
        }
        $path = rawurlencode($path);
        $path = str_replace('%2F', '/', $path);
        $path = str_replace('%7E', '~', $path);

        if ((isset(Context::getContext()->cookie->psnoticeexiry)
            && Context::getContext()->cookie->psnoticeexiry
            && time() >= Context::getContext()->cookie->psnoticeexiry)
            || Tools::isSubmit('remove')
            || Tools::isSubmit('removeAndRedirect')
            || (!isset(Context::getContext()->cookie->psnotice)
                && !Tools::isSubmit('save')
                && !Tools::isSubmit('save-basic'))
            || (Tools::isSubmit('save')
                && !Tools::getValue('thirdparty')
                && !Tools::getValue('essential'))) {
            Context::getContext()->cookie->psnotice = null;

            foreach (array_keys($_COOKIE) as $cookieName) {
                if ($cookieName == 'PHPSESSID') {
                    continue;
                }
                setcookie($cookieName, '', time()-3600, $path);
                setcookie($cookieName, '', time()-3600, '/');
                setcookie($cookieName, '', time()-3600, '');
                setcookie($cookieName, '', time()-3600, $path, '.'.$_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '/', '.'.$_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '', '.'.$_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, $path, $_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '/', $_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '', $_SERVER['HTTP_HOST']);
                unset($_COOKIE[$cookieName]);
            }

            if (Tools::isSubmit('removeAndRedirect')
                && $url = Configuration::get('C_P_REJECT_URL', Context::getContext()->cookie->id_lang)) {
                if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                    $url = "https://".$url;
                }

                Tools::redirectLink($url);
            }
        } elseif (Tools::isSubmit('save')) {
            if (Tools::getValue('thirdparty')) {
                Context::getContext()->cookie->psnotice = 2;
            } elseif (Tools::getValue('essential')) {
                Context::getContext()->cookie->psnotice = 1;
            }
            Context::getContext()->cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        } elseif (Tools::isSubmit('save-basic')) {
            Context::getContext()->cookie->psnotice = 2;
            Context::getContext()->cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        }

        if (!isset(Context::getContext()->cookie->psnotice)) {
            $blockedModulesId = Configuration::get('C_P_MODULES_NEC_VALUES') ?
                Tools::jsonDecode(Configuration::get('C_P_MODULES_NEC_VALUES')) : array();

            if (is_array($modules) && is_array($blockedModulesId)) {
                foreach ($modules as $key => $module) {
                    if (in_array($module['id_module'], $blockedModulesId)) {
                        unset($modules[$key]);
                    }
                }
            }
        }

        if (!isset(Context::getContext()->cookie->psnotice)
            || Context::getContext()->cookie->psnotice != '2') {
            $blockedModulesId = Configuration::get('C_P_MODULES_VALUES') ?
                Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();

            if (is_array($modules) && is_array($blockedModulesId)) {
                foreach ($modules as $key => $module) {
                    if (in_array($module['id_module'], $blockedModulesId)) {
                        unset($modules[$key]);
                    }
                }
            }
        }

        return $modules;
    }

    public static function updateCookie14($hook_name, $hookArgs = array(), $id_module = null)
    {
        /* PS 1.4 only */
        global $cookie;

        $path = trim(__PS_BASE_URI__, '/\\').'/';
        if ($path{0} != '/') {
            $path = '/'.$path;
        }
        $path = rawurlencode($path);
        $path = str_replace('%2F', '/', $path);
        $path = str_replace('%7E', '~', $path);

        if ((isset(Context::getContext()->cookie->psnoticeexiry)
            && Context::getContext()->cookie->psnoticeexiry
            && time() >= Context::getContext()->cookie->psnoticeexiry)
            || Tools::isSubmit('remove')
            || Tools::isSubmit('removeAndRedirect')
            || (!isset(Context::getContext()->cookie->psnotice)
                && !Tools::isSubmit('save')
                && !Tools::isSubmit('save-basic'))
            || (Tools::isSubmit('save')
                && !Tools::getValue('thirdparty')
                && !Tools::getValue('essential'))) {
            foreach (array_keys($_COOKIE) as $cookieName) {
                setcookie($cookieName, '', time()-3600, $path);
                setcookie($cookieName, '', time()-3600, '/');
                setcookie($cookieName, '', time()-3600, '');
                setcookie($cookieName, '', time()-3600, $path, '.'.$_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '/', '.'.$_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '', '.'.$_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, $path, $_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '/', $_SERVER['HTTP_HOST']);
                setcookie($cookieName, '', time()-3600, '', $_SERVER['HTTP_HOST']);
                unset($_COOKIE[$cookieName]);
            }
            Context::getContext()->cookie->psnotice = null;

            if (Tools::isSubmit('removeAndRedirect')
                && $url = Configuration::get('C_P_REJECT_URL', Context::getContext()->cookie->id_lang)) {
                if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                    $url = "https://".$url;
                }

                Tools::redirectLink($url);
            }
        } elseif (Tools::isSubmit('save')) {
            if (Tools::getValue('thirdparty')) {
                Context::getContext()->cookie->psnotice = 2;
            } elseif (Tools::getValue('essential')) {
                Context::getContext()->cookie->psnotice = 1;
            }
            Context::getContext()->cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        } elseif (Tools::isSubmit('save-basic')) {
            Context::getContext()->cookie->psnotice = 2;
            Context::getContext()->cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        }

        if ((!empty($id_module) && !Validate::isUnsignedId($id_module)) || !Validate::isHookName($hook_name)) {
            die(Tools::displayError());
        }

        global $cart, $cookie;
        $live_edit = false;
        if (!isset($hookArgs['cookie']) || !$hookArgs['cookie']) {
            $hookArgs['cookie'] = $cookie;
        }
        if (!isset($hookArgs['cart']) || !$hookArgs['cart']) {
            $hookArgs['cart'] = $cart;
        }
        $hook_name = Tools::strtolower($hook_name);

        if (!isset(self::$_hookModulesCache)) {
            $db = Db::getInstance(_PS_USE_SQL_SLAVE_);
            $result = $db->ExecuteS('
            SELECT h.`name` as hook, m.`id_module`, h.`id_hook`, m.`name` as module, h.`live_edit`
            FROM `'._DB_PREFIX_.'module` m
            LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`
            LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
            AND m.`active` = 1
            ORDER BY hm.`position`', false);
            self::$_hookModulesCache = array();

            if ($result) {
                while ($row = $db->nextRow()) {
                    $row['hook'] = Tools::strtolower($row['hook']);
                    if (!isset(self::$_hookModulesCache[$row['hook']])) {
                        self::$_hookModulesCache[$row['hook']] = array();
                    }

                    self::$_hookModulesCache[$row['hook']][] = array('id_hook' => $row['id_hook'], 'module' => $row['module'], 'id_module' => $row['id_module'], 'live_edit' => $row['live_edit']);
                }
            }
        }

        if (!isset(self::$_hookModulesCache[$hook_name])) {
            return;
        }

        $altern = 0;
        $output = '';
        foreach (self::$_hookModulesCache[$hook_name] as $array) {
            if (!isset(Context::getContext()->cookie->psnotice)) {
                $blockedModulesId = Configuration::get('C_P_MODULES_NEC_VALUES') ?
                    Tools::jsonDecode(Configuration::get('C_P_MODULES_NEC_VALUES')) : array();

                if (is_array($blockedModulesId) && in_array($array['id_module'], $blockedModulesId)) {
                    continue;
                }
            }

            if (!isset(Context::getContext()->cookie->psnotice)
                || Context::getContext()->cookie->psnotice != '2') {
                $blockedModulesId = Configuration::get('C_P_MODULES_VALUES') ?
                    Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();
                if (is_array($blockedModulesId) && in_array($array['id_module'], $blockedModulesId)) {
                    continue;
                }
            }

            if ($id_module && $id_module != $array['id_module']) {
                continue;
            }

            if (!($moduleInstance = Module::getInstanceByName($array['module']))) {
                continue;
            }

            $exceptions = $moduleInstance->getExceptions((int)$array['id_hook'], (int)$array['id_module']);
            foreach ($exceptions as $exception) {
                if (strstr(basename($_SERVER['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'], $exception['file_name'])) {
                    continue 2;
                }
            }

            if (is_callable(array($moduleInstance, 'hook'.$hook_name))) {
                $hookArgs['altern'] = ++$altern;

                $display = call_user_func(array($moduleInstance, 'hook'.$hook_name), $hookArgs);
                if ($array['live_edit'] && ((Tools::isSubmit('live_edit') && $ad = Tools::getValue('ad') && (Tools::getValue('liveToken') == sha1(Tools::getValue('ad')._COOKIE_KEY_))))) {
                    $live_edit = true;
                    $output .= '<script type="text/javascript"> modules_list.push(\''.$moduleInstance->name.'\');</script>
                                <div id="hook_'.$array['id_hook'].'_module_'.$moduleInstance->id.'_moduleName_'.$moduleInstance->name.'"
                                class="dndModule" style="border: 1px dotted red;'.(!Tools::strlen($display) ? 'height:50px;' : '').'">
                                <span><img src="'.$moduleInstance->_path.'/logo.gif">'
                                .$moduleInstance->displayName.'<span style="float:right">
                                <a href="#" id="'.$array['id_hook'].'_'.$moduleInstance->id.'" class="moveModule">
                                    <img src="'._PS_ADMIN_IMG_.'arrow_out.png"></a>
                                <a href="#" id="'.$array['id_hook'].'_'.$moduleInstance->id.'" class="unregisterHook">
                                    <img src="'._PS_ADMIN_IMG_.'delete.gif"></span></a>
                                </span>'.$display.'</div>';
                } else {
                    $output .= $display;
                }
            }
        }

        return ($live_edit ? '<script type="text/javascript">hooks_list.push(\''.$hook_name.'\'); </script><!--<div id="add_'.$hook_name.'" class="add_module_live_edit">
                <a class="exclusive" href="#">Add a module</a></div>--><div id="'.$hook_name.'" class="dndHook" style="min-height:50px">' : '').$output.($live_edit ? '</div>' : '');
    }

    public static function hookExecPayment14()
    {
        global $cart, $cookie;
        $hookArgs = array('cookie' => $cookie, 'cart' => $cart);
        $id_customer = (int)($cookie->id_customer);
        $billing = new Address((int)($cart->id_address_invoice));
        $output = '';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
        SELECT DISTINCT h.`id_hook`, m.`name`, hm.`position`
        FROM `'._DB_PREFIX_.'module_country` mc
        LEFT JOIN `'._DB_PREFIX_.'module` m ON m.`id_module` = mc.`id_module`
        INNER JOIN `'._DB_PREFIX_.'module_group` mg ON (m.`id_module` = mg.`id_module`)
        INNER JOIN `'._DB_PREFIX_.'customer_group` cg on (cg.`id_group` = mg.`id_group` AND cg.`id_customer` = '.(int)($id_customer).')
        LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`
        LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
        WHERE h.`name` = \'payment\'
        AND mc.id_country = '.(int)($billing->id_country).'
        AND m.`active` = 1
        ORDER BY hm.`position`, m.`name` DESC');
        if ($result) {
            foreach ($result as $k => $module) {
                if (!isset(Context::getContext()->cookie->psnotice)) {
                    $blockedModulesId = Configuration::get('C_P_MODULES_NEC_VALUES') ?
                        Tools::jsonDecode(Configuration::get('C_P_MODULES_NEC_VALUES')) : array();

                    if (is_array($blockedModulesId) && in_array($module['id_module'], $blockedModulesId)) {
                        continue;
                    }
                }

                if (!isset(Context::getContext()->cookie->psnotice)
                    || Context::getContext()->cookie->psnotice != '2') {
                    $blockedModulesId = Configuration::get('C_P_MODULES_VALUES') ?
                        Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();
                    if (is_array($blockedModulesId) && in_array($module['id_module'], $blockedModulesId)) {
                        continue;
                    }
                }

                if (($moduleInstance = Module::getInstanceByName($module['name'])) && is_callable(array($moduleInstance, 'hookpayment'))) {
                    if (!$moduleInstance->currencies || ($moduleInstance->currencies && sizeof(Currency::checkPaymentCurrencies($moduleInstance->id)))) {
                        $output .= call_user_func(array($moduleInstance, 'hookpayment'), $hookArgs);
                    }
                }
            }
        }
        return $output;
    }

    public static function writeCookie()
    {

        if (!Configuration::get('C_P_ENABLE')) {
            return true;
        }

        if (defined('_PS_ADMIN_DIR_')) {
            return true;
        }
        if (version_compare(_PS_VERSION_, '1.5', '>=')) {
            if (is_object(Context::getContext()->controller)
                && Context::getContext()->controller->controller_type == 'admin') {
                return true;
            }
        }

        if (isset(Context::getContext()->cookie->psnotice)
            && Context::getContext()->cookie->psnotice) {
            return true;
        }

        //Validate user agent
        if (self::allowedUserAgent()) {
            return true;
        }

        //Validate IP
        if (self::allowedIP()) {
            return $modules;
        }

        return false;
    }

    public function hookBackOfficeHeader()
    {
        if (version_compare(_PS_VERSION_, '1.6', '<')
            && Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/cookiesplus.admin.js');
        }
    }

    public function getModuleList($type)
    {
        $modules =  Module::getModulesOnDisk();
        foreach ($modules as $key => $module) {
            if ($module->id == 0) {
                unset($modules[$key]);
            }

            if ($module->name == $this->name) {
                unset($modules[$key]);
            }

            if ($type == 'necessary') {
                $modules_blocked = Configuration::get('C_P_MODULES_NEC_VALUES') ?
                    Tools::jsonDecode(Configuration::get('C_P_MODULES_NEC_VALUES')) : array();
            } else {
                $modules_blocked = Configuration::get('C_P_MODULES_VALUES') ?
                    Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();
            }

            if ($modules_blocked) {
                if (in_array($module->id, $modules_blocked)) {
                    $module->checked = true;
                }
            }
        }

        return $modules;
    }

    protected static function allowedUserAgent()
    {
        if (preg_match('/'.Configuration::get('C_P_BOTS').'/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }

    protected static function allowedIP()
    {
        if (in_array(Tools::getRemoteAddr(), explode('|', Configuration::get('C_P_IPS')))) {
            return true;
        }
    }

    public function getWarnings($getAll = true)
    {
        $warning = array();

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE')) {
                $warning[] = $this->l('You have to enable non PrestaShop modules at ADVANCED PARAMETERS - PERFORMANCE');
            }

            if (Configuration::get('PS_DISABLE_OVERRIDES')) {
                $warning[] = $this->l('You have to enable overrides at ADVANCED PARAMETERS - PERFORMANCE');
            }
        }

        if (count($warning) && !$getAll) {
            return $warning[0];
        }

        return $warning;
    }
}
