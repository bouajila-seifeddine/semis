<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf https://www.lineagrafica.es/licenses/license_es.pdf https://www.lineagrafica.es/licenses/license_fr.pdf
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class LGCookiesLaw extends Module
{
    public $bootstrap;

    public function __construct()
    {
        $this->name = 'lgcookieslaw';
        $this->tab = 'front_office_features';
        $this->version = '1.4.5';
        $this->author = 'Línea Gráfica';
        $this->need_instance = 0;
        $this->module_key = '56c109696b8e3185bc40d38d855f7332';
        if (substr_count(_PS_VERSION_, '1.6') > 0) {
            $this->bootstrap = true;
        } else {
            $this->bootstrap = false;
        }

        parent::__construct();

        $this->displayName = $this->l('EU Cookie Law (Notification Banner + Cookie Blocker)');
        $this->description = $this->l('Display a responsive and custom warning banner and disable cookies when users enter your website until you obtain their consent.');

        /* Backward compatibility */
        if (_PS_VERSION_ < '1.5') {
            require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
        }
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('top') || !$this->registerHook('header')) {
            return false;
        }

        Configuration::updateValue('PS_LGCOOKIES_TIMELIFE', '31536000');
        Configuration::updateValue('PS_LGCOOKIES_NAME', '__lglaw');
        Configuration::updateValue('PS_LGCOOKIES_DIVCOLOR', '#000000');
        Configuration::updateValue('PS_LGCOOKIES_POSITION', '2');
        Configuration::updateValue('PS_LGCOOKIES_OPACITY', '0.8');
        Configuration::updateValue('PS_LGCOOKIES_TESTMODE', '1');

        Configuration::updateValue('PS_LGCOOKIES_SHADOWCOLOR', '#000000');
        Configuration::updateValue('PS_LGCOOKIES_FONTCOLOR', '#FFFFFF');
        Configuration::updateValue('PS_LGCOOKIES_CMS', '1');
        Configuration::updateValue('PS_LGCOOKIES_NAVIGATION_BTN', '5');
        Configuration::updateValue('PS_LGCOOKIES_CMS_TARGET', '1');
        Configuration::updateValue('PS_LGCOOKIES_POSITION', '1');
        Configuration::updateValue('PS_LGCOOKIES_SHOW_CLOSE', '1');
        Configuration::updateValue('PS_LGCOOKIES_BTN1_FONT_COLOR', '#FFFFFF');
        Configuration::updateValue('PS_LGCOOKIES_BTN1_BG_COLOR', '#8BC954');
        Configuration::updateValue('PS_LGCOOKIES_BTN2_FONT_COLOR', '#FFFFFF');
        Configuration::updateValue('PS_LGCOOKIES_BTN2_BG_COLOR', '#5BC0DE');

        Configuration::updateValue('PS_LGCOOKIES_NAVIGATION', '0');
        Configuration::updateValue('PS_LGCOOKIES_IPTESTMODE', ''.$_SERVER['REMOTE_ADDR'].'');
        Configuration::updateValue('PS_LGCOOKIES_BOTS', 'Teoma,alexa,froogle,Gigabot,inktomi,looksmart,URL_Spider_SQL,Firefly,NationalDirectory,AskJeeves,TECNOSEEK,InfoSeek,WebFindBot,girafabot,crawler,www.galaxy.com,Googlebot,Scooter,Slurp,bing,msnbot,appie,FAST,WebBug,Spade,ZyBorg,rabaz,Baiduspider,Feedfetcher-Google,TechnoratiSnoop,Rankivabot,Mediapartners-Google,Sogouwebspider,WebAltaCrawler,TweetmemeBot,Butterfly,Twitturls,Me.dium,Twiceler');
        // db tables
        $query = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcookieslaw` (
				`id_module` int(11) NOT NULL,
				UNIQUE KEY `id_module` (`id_module`)
				 ) ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb');
        if (!Db::getInstance()->Execute($query)) {
            return false;
        }
        $query = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcookieslaw_lang` (
				  `id_lang` int(11) NOT NULL,
				  `button1` text NOT NULL,
				  `button2` text NOT NULL,
				  `content` text NOT NULL,
				  UNIQUE KEY `id_lang` (`id_lang`)
				) ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb').' CHARSET=utf8';
        if (!Db::getInstance()->Execute($query)) {
            return false;
        }
        // main langs, english by default
        $languages = Language::getLanguages();
        foreach ($languages as $language) {
            if ($language['iso_code'] == 'en') {
                Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'lgcookieslaw_lang VALUES ('.(int)$language['id_lang'].', \''.pSQL('I accept').'\', \''.pSQL('More information').'\', \''.pSQL('<p><span style="font-family: tahoma, arial, helvetica, sans-serif;">Our webstore uses cookies to offer a better user experience and we recommend you to accept their use to fully enjoy your navigation.</span></p>', 'html').'\')');
            } elseif ($language['iso_code'] == 'es') {
                Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'lgcookieslaw_lang VALUES ('.(int)$language['id_lang'].', \''.pSQL('Acepto').'\', \''.pSQL('Más información').'\', \''.pSQL('<p><span style="font-family: tahoma, arial, helvetica, sans-serif;">Nuestra tienda usa cookies para mejorar la experiencia de usuario y le recomendamos aceptar su uso para aprovechar plenamente la navegación.</span></p>', 'html').'\')');
            } elseif ($language['iso_code'] == 'fr') {
                Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'lgcookieslaw_lang VALUES ('.(int)$language['id_lang'].', \''.pSQL('J\'accepte').'\', \''.pSQL('Plus d\'informations').'\', \''.pSQL('<p><span style="font-family: tahoma, arial, helvetica, sans-serif;">Notre boutique utilise des cookies pour améliorer l\'expérience utilisateur et nous vous recommandons d\'accepter leur utilisation pour profiter pleinement de votre navigation.</span></p>', 'html').'\')');
            } elseif ($language['iso_code'] == 'it') {
                Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'lgcookieslaw_lang VALUES ('.(int)$language['id_lang'].', \''.pSQL('Accetto').'\', \''.pSQL('Piú info').'\', \''.pSQL('<p><span style="font-family: tahoma, arial, helvetica, sans-serif;">Il nostro negozio online fa uso di cookies per migliorare l\'esperienza dell\'utente e raccomandiamo di accettarne l\'utilizzo per sfruttare a pieno la navigazione.</span></p>', 'html').'\')');
            } else {
                Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'lgcookieslaw_lang VALUES ('.(int)$language['id_lang'].', \''.pSQL('I accept').'\', \''.pSQL('More information').'\', \''.pSQL('<p><span style="font-family: tahoma, arial, helvetica, sans-serif;">Our webstore uses cookies to offer a better user experience and we recommend you to accept their use to fully enjoy your navigation.</span></p>', 'html').'\')');
            }
        }

        return true;
    }

    public function uninstall()
    {
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lgcookieslaw`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lgcookieslaw_lang`');
        return parent::uninstall();
    }

    private function cleanBots($bots)
    {
        $bots = str_replace(' ', '', $bots);
        return $bots;
    }

    private function getCMSList()
    {
        $cms = Db::getInstance()->ExecuteS(
            'SELECT * '.
            'FROM '._DB_PREFIX_.'cms_lang '.
            'WHERE id_lang = '.(int)(Configuration::get('PS_LANG_DEFAULT'))
        );
        return $cms;
    }

    private function isBot($agente)
    {
        $bots = Configuration::get('PS_LGCOOKIES_BOTS');
        $botlist = explode(',', $bots);
        foreach ($botlist as $bot) {
            if (strpos($agente, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    private function getModuleList()
    {
        $modules = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'module');
        foreach ($modules as &$module) {
            $module['checked'] = (int)$this->checkModule($module['id_module']);
        }

        return $modules;
    }

    private function checkModule($id_module)
    {
        $checkmodule = Db::getInstance()->getValue(
            'SELECT id_module '.
            'FROM '._DB_PREFIX_.'lgcookieslaw '.
            'WHERE id_module = '.(int)$id_module
        );
        if ($checkmodule) {
            return true;
        } else {
            return false;
        }
    }

    private function getContentLang($id_lang, $field)
    {
        $content = Db::getInstance()->getValue(
            'SELECT '.$field.' '.
            'FROM '._DB_PREFIX_.'lgcookieslaw_lang '.
            'WHERE id_lang = '.(int)$id_lang
        );
        return $content;
    }

    private function formatBootstrap($text)
    {
        $text = str_replace('<fieldset>', '<div class="panel">', $text);
        $text = str_replace(
            '<fieldset style="background:#DFF2BF;color:#4F8A10;border:1px solid #4F8A10;">',
            '<div class="panel"  style="background:#DFF2BF;color:#4F8A10;border:1px solid #4F8A10;">',
            $text
        );
        $text = str_replace('</fieldset>', '</div>', $text);
        $text = str_replace('<legend>', '<h3>', $text);
        $text = str_replace('</legend>', '</h3>', $text);
        return $text;
    }

    public function installOverrides()
    {
        $path = _PS_MODULE_DIR_.$this->name.
            DIRECTORY_SEPARATOR.'override'.
            DIRECTORY_SEPARATOR.'classes'.
            DIRECTORY_SEPARATOR;
        if (version_compare(_PS_VERSION_, '1.6.0.10', '>')) {
            copy($path.'Hook16011.php', $path.'Hook.php');
        } else if (version_compare(_PS_VERSION_, '1.6.0.5', '>')) {
            copy($path.'Hook16010.php', $path.'Hook.php');
        } else {
            copy($path.'Hook15.php', $path.'Hook.php');
        }
        return parent::installOverrides();
    }

    private function getP()
    {
        $default_lang = $this->context->language->id;
        $lang = Language::getIsoById($default_lang);
        $pl = array('es', 'fr', 'it');
        if (!in_array($lang, $pl)) {
            $lang = 'en';
        }

        $this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/views/css/publi/style.css');
        $base = (Tools::usingSecureMode() ? 'https://'.$this->context->shop->domain_ssl : 'http://'.$this->context->shop->domain);
        if (version_compare(_PS_VERSION_, '1.5.0', '>')) {
            $uri = $base.$this->context->shop->getBaseURI();
        } else {
            $uri = (Tools::usingSecureMode() ? 'https://'._PS_SHOP_DOMAIN_SSL_DOMAIN_ : 'http://'._PS_SHOP_DOMAIN_).__PS_BASE_URI__;
        }

        $path = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'publi'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.'index.php';
        $object = Tools::file_get_contents($path);
        $object = str_replace('src="/modules/', 'src="'.$uri.'modules/', $object);

        return $object;
    }

    private function warningA()
    {
        if (!file_exists(_PS_ROOT_DIR_.'/override/classes/Hook.php')) {
            $warningA = $this->displayError($this->l('The Hook.php override is missing. Please reset the module or copy the override manually on your FTP.'));
            return $warningA;
        }
    }

    private function warningB()
    {
        if ((int)Configuration::get('PS_DISABLE_OVERRIDES') > 0) {
            $warningB = $this->displayError($this->l('The overrides are currently disabled on your store. Please change the configuration').' <a href="index.php?tab=AdminPerformance&token='.Tools::getAdminTokenLite('AdminPerformance').'" target="_blank" style="color:#FF0000;">'.$this->l('here').'</a>');
            return $warningB;
        }
    }

    private function warningC()
    {
        if ((int)Configuration::get('PS_DISABLE_NON_NATIVE_MODULE') > 0) {
            $warningC = $this->displayError($this->l('Non PrestaShop modules are currently disabled on your store. Please change the configuration').' <a href="index.php?tab=AdminPerformance&token='.Tools::getAdminTokenLite('AdminPerformance').'" target="_blank" style="color:#FF0000;">'.$this->l('here').'</a>');
            return $warningC;
        }
    }

    private function warningD()
    {
        if ((int)Configuration::get('PS_LGCOOKIES_TESTMODE') > 0) {
            $warningD = $this->displayError($this->l('The preview mode of the module is enabled. Don\'t forget to disable it once you have finished configuring the banner.'));
            return $warningD;
        }
    }

    private function warningE()
    {
        if ((int)Configuration::get('PS_LGCOOKIES_NAVIGATION') > 0 and (int)Configuration::get('PS_LGCOOKIES_NAVIGATION_BTN') > 1) {
            $warningE = $this->displayError($this->l('The mode "Accept cookies through navigation" should be enabled only if the option "Banner without buttons" is selected.'));
            return $warningE;
        }
    }

    private function warningF()
    {
        if ((int)Configuration::get('PS_LGCOOKIES_NAVIGATION') == 0 and (int)Configuration::get('PS_LGCOOKIES_NAVIGATION_BTN') == 1) {
            $warningF = $this->displayError($this->l('The option "Banner without buttons" should be selected only if the mode "Accept cookies through navigation" is enabled.'));
            return $warningF;
        }
    }

    public function getContent()
    {
        $this->postProcess();
        $this->context->controller->addJqueryPlugin('ui.tooltip', null, true);
        $this->fields_form = array();
        $this->fields_form[0]['form']['tabs'] = array(
            'config' => $this->l('General settings'),
            'banner' => $this->l('Banner settings'),
            'buttons' => $this->l('Button settings'),
            'modules' => $this->l('Modules blocked'),
        );
        $field_type = 'switch';

        $this->fields_form[0]['form']['input'] = array(
            array(
                'label' => $this->l('IMPORTANT:'),
                'tab' => 'config',
                'desc' => $this->l('Don´t forget to disable the preview mode once you have finished configuring the banner.'),
                'type' => 'free',
                'name' => 'important',
            ),
            array(
                'type' => $field_type,
                'label' => $this->l('Preview mode:'),
                'name' => 'PS_LGCOOKIES_TESTMODE',
                'tab' => 'config',
                'required' => false,
                'desc' => $this->l('Enable this option to preview the cookie banner in your front-office without bothering your customers (when the preview mode is enabled, the banner doesn´t disappear, the module doesn´t block cookies and only the person using the IP below is able to see the cookie banner).'),
                'class' => 't',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'PS_LGCOOKIES_TESTMODE_on',
                        'value' => 1,
                        'label' => $this->l('Yes')
                    ),
                    array(
                        'id' => 'PS_LGCOOKIES_TESTMODE_off',
                        'value' => 0,
                        'label' => $this->l('No')
                    )
                ),
            ),
            array(
                'type' => 'ip',
                'label' => $this->l('IP  for the preview mode:'),
                'name' => 'PS_LGCOOKIES_IPTESTMODE',
                'tab' => 'config',
                'required' => false,
                'desc' => $this->l('Click on the button "Add IP" to be the only person able to see the banner (if the preview mode is enabled).'),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Cookie lifetime (seconds):'),
                'name' => 'PS_LGCOOKIES_TIMELIFE',
                'tab' => 'config',
                'required' => false,
                'desc' => $this->l('Set the duration during which the user consent will be saved (1 year = 31536000s).'),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Cookie name:'),
                'name' => 'PS_LGCOOKIES_NAME',
                'tab' => 'config',
                'required' => false,
                'desc' => $this->l('Choose the name of the cookie used by our module to remember user consent (don´t use any space).'),
            ),
            array(
                'type' => 'textarea',
                'label' => $this->l('SEO protection:'),
                'name' => 'PS_LGCOOKIES_BOTS',
                'tab' => 'config',
                'required' => false,
                'cols' => '10',
                'rows' => '5',
                'desc' => $this->l('The module will prevent the search engine bots above from seeing the cookie warning banner when they crawl your website.'),
            ),
            array(
                'type' => 'free',
                'tab' => 'config',
                'label' => ' ',
                'name' => 'help1',
            ),
            array(
                'type' => 'free',
                'tab' => 'config',
                'label' => ' ',
                'name' => 'help5',
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Banner position:'),
                'name' => 'PS_LGCOOKIES_POSITION',
                'tab' => 'banner',
                'required' => false,
                'desc' => $this->l('Choose the position of the warning banner (top or bottom of the page).'),
                'options' => array(
                    'query' => array(
                        array('id' => '1', 'name' => $this->l('Top')),
                        array('id' => '2', 'name' => $this->l('Bottom')),
                    ),
                    'id' => 'id',
                    'name' => 'name',

                ),
            ),
            array(
                'type' => 'color',
                'label' => $this->l('Background color:'),
                'name' => 'PS_LGCOOKIES_DIVCOLOR',
                'tab' => 'banner',
                'required' => false,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Background opacity:'),
                'name' => 'PS_LGCOOKIES_OPACITY',
                'tab' => 'banner',
                'required' => false,
                'desc' => $this->l('Choose the opacity of the background color (1 is opaque, 0 is transparent).'),
                'options' => array(
                    'query' => array(
                        array('id' => '1', 'name' => '1'),
                        array('id' => '0.9', 'name' => '0.9'),
                        array('id' => '0.8', 'name' => '0.8'),
                        array('id' => '0.7', 'name' => '0.7'),
                        array('id' => '0.6', 'name' => '0.6'),
                        array('id' => '0.5', 'name' => '0.5'),
                        array('id' => '0.4', 'name' => '0.4'),
                        array('id' => '0.3', 'name' => '0.3'),
                        array('id' => '0.2', 'name' => '0.2'),
                        array('id' => '0.1', 'name' => '0.1'),
                        array('id' => '0', 'name' => '0'),
                    ),
                    'id' => 'id',
                    'name' => 'name',

                ),
            ),
            array(
                'type' => 'color',
                'label' => $this->l('Shadow color:'),
                'name' => 'PS_LGCOOKIES_SHADOWCOLOR',
                'tab' => 'banner',
                'required' => false,
            ),
            array(
                'type' => 'color',
                'label' => $this->l('Font color:'),
                'name' => 'PS_LGCOOKIES_FONTCOLOR',
                'tab' => 'banner',
                'required' => false,
            ),
            array(
                'type' => 'textarea',
                'label' => $this->l('Banner message:'),
                'name' => 'content',
                'autoload_rte' => 'true',
                'lang' => 'true',
                'tab' => 'banner',
                'required' => false,
                'cols' => '10',
                'rows' => '5',
                'desc' => $this->l('Example: "Our webstore uses cookies to offer a better user experience and we recommend you to accept their use to fully enjoy your navigation."'),
            ),
            array(
                'type' => 'free',
                'tab' => 'banner',
                'label' => ' ',
                'name' => 'help2',
            ),
            array(
                'type' => 'free',
                'tab' => 'banner',
                'label' => ' ',
                'name' => 'help5',
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Button position:'),
                'name' => 'PS_LGCOOKIES_NAVIGATION_BTN',
                'tab' => 'buttons',
                'required' => false,
                'desc' => $this->l('Choose the position of the "I accept" and "More information" buttons inside the banner.'),
                'class' => 't',
                'options' => array(
                    'query' => array(
                        array('id' => '1', 'name' => $this->l('Banner without buttons')),
                        array('id' => '2', 'name' => $this->l('Buttons above the text')),
                        array('id' => '3', 'name' => $this->l('Buttons below the text')),
                        array('id' => '4', 'name' => $this->l('Buttons to the left of the text')),
                        array('id' => '5', 'name' => $this->l('Buttons to the right of the text')),

                    ),
                    'id' => 'id',
                    'name' => 'name',
                ),
            ),
            array(
                'type' => $field_type,
                'label' => $this->l('Accept cookies through navigation:'),
                'name' => 'PS_LGCOOKIES_NAVIGATION',
                'tab' => 'buttons',
                'required' => false,
                'desc' => $this->l('Disable this option is you want the banner to disappear only when users click on the "I accept" button (banner with buttons). And enable this option is you want the banner to disappear automatically when users keep browsing your website (banner without buttons).'),
                'class' => 't',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'PS_LGCOOKIES_NAVIGATION_on',
                        'value' => 1,
                        'label' => $this->l('Yes')
                    ),
                    array(
                        'id' => 'PS_LGCOOKIES_NAVIGATION_off',
                        'value' => 0,
                        'label' => $this->l('No')
                    )
                ),
            ),
            array(
                'type' => 'text',
                'lang' => 'true',
                'label' => $this->l('Title of the button 1 "I accept":'),
                'name' => 'button1',
                'tab' => 'buttons',
                'required' => false,
            ),
            array(
                'type' => 'color',
                'label' => $this->l('Button 1 background color:'),
                'name' => 'PS_LGCOOKIES_BTN1_BG_COLOR',
                'tab' => 'buttons',
                'required' => false,
            ),
            array(
                'type' => 'color',
                'label' => $this->l('Button 1 font color:'),
                'name' => 'PS_LGCOOKIES_BTN1_FONT_COLOR',
                'tab' => 'buttons',
                'required' => false,
            ),
            array(
                'type' => 'text',
                'lang' => 'true',
                'label' => $this->l('Title of the button 2 "More information":'),
                'name' => 'button2',
                'tab' => 'buttons',
                'required' => false,
            ),
            array(
                'type' => 'color',
                'label' => $this->l('Button 2 background color:'),
                'name' => 'PS_LGCOOKIES_BTN2_BG_COLOR',
                'tab' => 'buttons',
                'required' => false,
            ),
            array(
                'type' => 'color',
                'label' => $this->l('Button 2 font color:'),
                'name' => 'PS_LGCOOKIES_BTN2_FONT_COLOR',
                'tab' => 'buttons',
                'required' => false,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Link of the button 2 "More information":'),
                'name' => 'PS_LGCOOKIES_CMS',
                'tab' => 'buttons',
                'required' => false,
                'desc' => $this->l('When you click on the "More information" button, it will take you to CMS page you have selected.'),
                'options' => array(
                    'query' => CMSCore::getCMSPages($this->context->language->id),
                    'id' => 'id_cms',
                    'name' => 'meta_title',
                ),
            ),
            array(
                'type' => $field_type,
                'label' => $this->l('Open the link in a new window:'),
                'name' => 'PS_LGCOOKIES_CMS_TARGET',
                'tab' => 'buttons',
                'required' => false,
                'desc' => $this->l('When you click on the "More information" button, the CMS page will be opened in a new or the same window of your browser.'),
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'PS_LGCOOKIES_CMS_TARGET_on',
                        'value' => 1,
                        'label' => $this->l('Yes')
                    ),
                    array(
                        'id' => 'PS_LGCOOKIES_CMS_TARGET_off',
                        'value' => 0,
                        'label' => $this->l('No')
                    )
                ),
            ),
            array(
                'type' => $field_type,
                'label' => $this->l('Button to close the banner:'),
                'name' => 'PS_LGCOOKIES_SHOW_CLOSE',
                'tab' => 'buttons',
                'required' => false,
                'desc' => $this->l('Display a button to close the cookies banner.'),
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'PS_LGCOOKIES_SHOW_CLOSE_on',
                        'value' => 1,
                        'label' => $this->l('Yes')
                    ),
                    array(
                        'id' => 'PS_LGCOOKIES_SHOW_CLOSE_off',
                        'value' => 0,
                        'label' => $this->l('No')
                    )
                ),
            ),
            array(
                'type' => 'free',
                'tab' => 'buttons',
                'name' => 'help3',
                'label' => ' ',
            ),
            array(
                'type' => 'free',
                'tab' => 'buttons',
                'label' => ' ',
                'name' => 'help5',
            ),
            array(
                'type' => 'free',
                'label' => $this->l('Block cookies:'),
                'name' => 'PS_BANNER_LIST',
                'tab' => 'modules',
                'desc' => $this->l('Here is the list of all the modules installed on your store. Tick the modules that you want to disable until users give their consent.'),
            ),
            array(
                'type' => 'free',
                'tab' => 'modules',
                'label' => ' ',
                'name' => 'help4',
            ),
            array(
                'type' => 'free',
                'tab' => 'modules',
                'label' => ' ',
                'name' => 'help5',
            ),
        );
        $this->fields_form[0]['form']['submit'] = array(
            'title' => $this->l('Save'),
            'name' => 'submitForm',

        );
        $config_params = array();
        $config_params['tabs'] = $this->fields_form[0]['form']['tabs'];
        $form = new HelperForm($this);
        if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
            $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/bootstrap.js');
            $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/admin15.js');
            $this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/views/css/admin15.css');
            $ps15 = true;
        } else {
            $ps15 = false;
        }
        $form->tpl_vars = $config_params;
        $form->show_toolbar = true;
        $form->module = $this;
        $form->fields_value = $this->getConfigFormValues();
        $form->name_controller = 'lgsitemaps';
        $form->identifier = $this->identifier;
        $form->token = Tools::getAdminTokenLite('AdminModules');
        $form->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $form->default_form_language = $this->context->language->id;
        $form->allow_employee_form_lang = $this->context->language->id;
        $languages = Language::getLanguages();
        foreach ($languages as &$lang) {
            $lang['is_default'] = (int)($lang['id_lang'] == $this->context->language->id);
        }


        $form->languages = $languages;
        $form->toolbar_scroll = true;
        $form->title = $this->displayName;
        $form->submit_action = 'submitForm';
        $form->toolbar_btn = array(
            'back' =>
                array(
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                    'desc' => $this->l('Back to the list')
                )
        );
        $params = array();
        $params['link'] = $this->context->link;
        $params['current_id_lang'] = $this->context->language->id;
        $params['ps15'] = $ps15;
        $params['ssl'] = (int)Configuration::get('PS_SSL_ENABLED_EVERYWHERE');
        $this->context->smarty->assign($params);
        $content = $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'configure.tpl');
        return $this->getP().$this->warningA().$this->warningB().$this->warningC().$this->warningD().$this->warningE().$this->warningF().$content.$form->generateForm($this->fields_form);

    }

    public function postProcess()
    {
        if (Tools::getIsset('submitForm')) {
            $fields = array(
                'PS_LGCOOKIES_TESTMODE',
                'PS_LGCOOKIES_IPTESTMODE',
                'PS_LGCOOKIES_TIMELIFE',
                'PS_LGCOOKIES_NAME',
                'PS_LGCOOKIES_NAVIGATION',
                'PS_LGCOOKIES_BOTS',
                'PS_LGCOOKIES_CMS',
                'PS_LGCOOKIES_OPACITY',
                'PS_LGCOOKIES_DIVCOLOR',
                'PS_LGCOOKIES_SHADOWCOLOR',
                'PS_LGCOOKIES_FONTCOLOR',
                'PS_LGCOOKIES_NAVIGATION_BTN',
                'PS_LGCOOKIES_CMS_TARGET',
                'PS_LGCOOKIES_POSITION',
                'PS_LGCOOKIES_SHOW_CLOSE',
                'PS_LGCOOKIES_BTN1_FONT_COLOR',
                'PS_LGCOOKIES_BTN1_BG_COLOR',
                'PS_LGCOOKIES_BTN2_FONT_COLOR',
                'PS_LGCOOKIES_BTN2_BG_COLOR',
            );
            $res = true;
            foreach ($fields as $field) {
                $res &= Configuration::updateValue($field, Tools::getValue($field, ''));
            }
            $fields_lang = array('button1', 'button2', 'content');
            foreach (Language::getLanguages() as $lang) {
                Db::getInstance()->Execute('REPLACE INTO '._DB_PREFIX_.'lgcookieslaw_lang VALUES ('.(int)$lang['id_lang'].', \''.pSQL(Tools::getValue('button1_'.(int)$lang['id_lang'])).'\', \''.pSQL(Tools::getValue('button2_'.(int)$lang['id_lang'])).'\', \''.pSQL(Tools::getValue('content_'.(int)$lang['id_lang']), 'html').'\')');
            }

            // module list update
            Db::getInstance()->Execute('TRUNCATE TABLE '._DB_PREFIX_.'lgcookieslaw');
            foreach ($this->getModuleList() as $modulos) {
                if (Tools::getIsset('module'.$modulos['id_module'])) {
                    Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'lgcookieslaw VALUES ('.pSQL($modulos['id_module']).')');
                }
            }
        }


    }

    public function getConfigFormValues()
    {
        $fields = array(
            'PS_LGCOOKIES_TESTMODE',
            'PS_LGCOOKIES_IPTESTMODE',
            'PS_LGCOOKIES_TIMELIFE',
            'PS_LGCOOKIES_NAME',
            'PS_LGCOOKIES_NAVIGATION',
            'PS_LGCOOKIES_NAVIGATION2',
            'PS_LGCOOKIES_BOTS',
            'PS_LGCOOKIES_SHADOWCOLOR',
            'PS_LGCOOKIES_FONTCOLOR',
            'PS_LGCOOKIES_CMS',
            'PS_LGCOOKIES_OPACITY',
            'PS_LGCOOKIES_DIVCOLOR',
            'PS_LGCOOKIES_NAVIGATION_BTN',
            'PS_LGCOOKIES_CMS_TARGET',
            'PS_LGCOOKIES_POSITION',
            'PS_LGCOOKIES_SHOW_CLOSE',
            'PS_LGCOOKIES_BTN1_FONT_COLOR',
            'PS_LGCOOKIES_BTN1_BG_COLOR',
            'PS_LGCOOKIES_BTN2_FONT_COLOR',
            'PS_LGCOOKIES_BTN2_BG_COLOR',
        );
        $out = Configuration::getMultiple($fields);
        $fields_lang = array('button1', 'button2', 'content');
        foreach ($fields_lang as $field) {
            foreach (Language::getLanguages() as $lang) {
                $out[$field][$lang['id_lang']] = $this->getContentLang($lang['id_lang'], $field);
            }
        }

        $this->context->smarty->assign('module_list', $this->getModuleList());
        $out['PS_BANNER_LIST'] = $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'_configure'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'check_module_list.tpl');

        $out['help1'] = '<a href="../modules/'.$this->name.'/readme/readme_'.$this->l('en').'.pdf#page=4" target="_blank"><img src="../modules/'.$this->name.'/views/img/info.png"> '.$this->l('Read this page for more information').'</a>';
        $out['help2'] = '<a href="../modules/'.$this->name.'/readme/readme_'.$this->l('en').'.pdf#page=6" target="_blank"><img src="../modules/'.$this->name.'/views/img/info.png"> '.$this->l('Read this page for more information').'</a>';
        $out['help3'] = '<a href="../modules/'.$this->name.'/readme/readme_'.$this->l('en').'.pdf#page=9" target="_blank"><img src="../modules/'.$this->name.'/views/img/info.png"> '.$this->l('Read this page for more information').'</a>';
        $out['help4'] = '<a href="../modules/'.$this->name.'/readme/readme_'.$this->l('en').'.pdf#page=13" target="_blank"><img src="../modules/'.$this->name.'/views/img/info.png"> '.$this->l('Read this page for more information').'</a>';
        $out['important'] = '';
        $out['help5'] = '<a href="../modules/'.$this->name.'/readme/readme_'.$this->l('en').'.pdf#page=16" target="_blank"><img src="../modules/'.$this->name.'/views/img/info.png"> '.$this->l('FAQ: SEE THE COMMON ERRORS').'</a>';
        $out['important'] = '';
        return $out;
    }

    public function hookdisplayMobileTop($params)
    {
        return $this->hookTop($params);
    }

    public function hookTop($params)
    {
        $link = new Link();
        if (Configuration::get('PS_LGCOOKIES_POSITION') == 1) {
            $position = 'top:0;';
        } elseif (Configuration::get('PS_LGCOOKIES_POSITION') == 2) {
            $position = 'bottom:0;';
        }

        $this->context->smarty->assign(array(
            'cookie_message' => $this->getContentLang($this->context->cookie->id_lang, 'content'),
            'button1' => $this->getContentLang($this->context->cookie->id_lang, 'button1'),
            'button2' => $this->getContentLang($this->context->cookie->id_lang, 'button2'),
            'position' => $position,
            'cms_link' => $link->getCMSLink(Configuration::get('PS_LGCOOKIES_CMS')),
            'cms_target' => Configuration::get('PS_LGCOOKIES_CMS_TARGET'),
            'target' => Configuration::get('PS_LGCOOKIES_CMS'),
            'bgcolor' => Configuration::get('PS_LGCOOKIES_DIVCOLOR'),
            'fontcolor' => Configuration::get('PS_LGCOOKIES_FONTCOLOR'),
            'btn1_bgcolor' => Configuration::get('PS_LGCOOKIES_BTN1_BG_COLOR'),
            'btn1_fontcolor' => Configuration::get('PS_LGCOOKIES_BTN1_FONT_COLOR'),
            'btn2_bgcolor' => Configuration::get('PS_LGCOOKIES_BTN2_BG_COLOR'),
            'btn2_fontcolor' => Configuration::get('PS_LGCOOKIES_BTN2_FONT_COLOR'),
            'shadowcolor' => Configuration::get('PS_LGCOOKIES_SHADOWCOLOR'),
            'opacity' => 'opacity:' . Configuration::get('PS_LGCOOKIES_OPACITY'),
            'buttons_position' => Configuration::get('PS_LGCOOKIES_NAVIGATION_BTN'),
            'show_close' => Configuration::get('PS_LGCOOKIES_SHOW_CLOSE'),
            'path_module' => _MODULE_DIR_ . $this->name,
        ));

        if (Configuration::get('PS_LGCOOKIES_TESTMODE') == 1) {
            if (Configuration::get('PS_LGCOOKIES_IPTESTMODE') == $_SERVER['REMOTE_ADDR']) {
                return $this->display(__FILE__, '/views/templates/hook/cookieslaw.tpl');
            }
        } else {
            if (!$this->isBot($_SERVER['HTTP_USER_AGENT'])) {
                if (Tools::isSubmit('aceptocookies')) {
                    setcookie(
                        Configuration::get('PS_LGCOOKIES_NAME'),
                        '1',
                        time() + (int)Configuration::get('PS_LGCOOKIES_TIMELIFE'),
                        '/'
                    );
                    //Tools::redirect($_SERVER['REQUEST_URI']);
                    echo '<meta http-equiv="refresh" content="0; url=' . $_SERVER['REQUEST_URI'] . '" />';
                    die();
                }
                if (Configuration::get('PS_LGCOOKIES_NAVIGATION') == 1) {
                    if (!isset($_COOKIE[Configuration::get('PS_LGCOOKIES_NAME')])) {
                        $url_actual = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $url_actual = parse_url($url_actual);
                        if (!isset($_SERVER['HTTP_REFERER'])) {
                            $url_referer = $url_actual;
                        } else {
                            $url_referer = parse_url($_SERVER['HTTP_REFERER']);
                        }
                        if ($url_actual['host'] == $url_referer['host']) {
                            if (!isset($_COOKIE[Configuration::get('PS_LGCOOKIES_NAME')])) {
                                setcookie(
                                    Configuration::get('PS_LGCOOKIES_NAME'),
                                    '1',
                                    time() + (int)Configuration::get('PS_LGCOOKIES_TIMELIFE'),
                                    '/'
                                );
                            }
                        }
                    }

                    if (!isset($_COOKIE[Configuration::get('PS_LGCOOKIES_NAME')])) {
                        return $this->display(__FILE__, '/views/templates/hook/cookieslaw.tpl');
                    }
                } else {
                    if (!isset($_COOKIE[Configuration::get('PS_LGCOOKIES_NAME')])) {
                        return $this->display(__FILE__, '/views/templates/hook/cookieslaw.tpl');
                    }
                }
            }
        }
    }


    public function hookDisplayHeader($params)
    {
        $link = new Link();
        if (Configuration::get('PS_LGCOOKIES_POSITION') == 1) {
            $position = 'top:0;';
        } elseif (Configuration::get('PS_LGCOOKIES_POSITION') == 2) {
            $position = 'bottom:0;';
        }

        $this->context->smarty->assign(array(
            'cookie_message' => $this->getContentLang($this->context->cookie->id_lang, 'content'),
            'button1' => $this->getContentLang($this->context->cookie->id_lang, 'button1'),
            'button2' => $this->getContentLang($this->context->cookie->id_lang, 'button2'),
            'position' => $position,
            'cms_link' => $link->getCMSLink(Configuration::get('PS_LGCOOKIES_CMS')),
            'cms_target' => Configuration::get('PS_LGCOOKIES_CMS_TARGET'),
            'target' => Configuration::get('PS_LGCOOKIES_CMS'),
            'bgcolor' => Configuration::get('PS_LGCOOKIES_DIVCOLOR'),
            'fontcolor' => Configuration::get('PS_LGCOOKIES_FONTCOLOR'),
            'btn1_bgcolor' => Configuration::get('PS_LGCOOKIES_BTN1_BG_COLOR'),
            'btn1_fontcolor' => Configuration::get('PS_LGCOOKIES_BTN1_FONT_COLOR'),
            'btn2_bgcolor' => Configuration::get('PS_LGCOOKIES_BTN2_BG_COLOR'),
            'btn2_fontcolor' => Configuration::get('PS_LGCOOKIES_BTN2_FONT_COLOR'),
            'shadowcolor' => Configuration::get('PS_LGCOOKIES_SHADOWCOLOR'),
            'opacity' => 'opacity:' . Configuration::get('PS_LGCOOKIES_OPACITY'),
            'buttons_position' => Configuration::get('PS_LGCOOKIES_NAVIGATION_BTN'),
            'show_close' => Configuration::get('PS_LGCOOKIES_SHOW_CLOSE'),
            'path_module' => _MODULE_DIR_ . $this->name,
        ));

        return $this->display(__FILE__, '/views/templates/hook/cookieslaw_header.tpl');
    }
}
