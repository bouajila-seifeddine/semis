<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

/**
 * This is a parent class for the module and it provides general functions
 */
class ElegantalTinyPngImageCompressModule extends Module
{

    /**
     * List of hooks to register during installation
     * @var array
     */
    protected $hooksToRegister = array();

    /**
     * List of module settings to be saved as Configuration record
     * @var array
     */
    protected $settings = array();

    /**
     * ID of this module as product on addons
     * @var int
     */
    protected $productIdOnAddons = '';

    /**
     * URL where users rate products on addons
     * @var string URL
     */
    protected $rateModuleUrl = 'http://addons.prestashop.com/en/ratings.php';

    /**
     * URL of developer modules page on addons
     * @var string URL
     */
    protected $developerModulesUrl = 'http://addons.prestashop.com/en/2_community-developer?contributor=581692';

    /**
     * Name of the directory where documentation of the module is kept
     * @var string
     */
    protected $docsFolder = 'docs';

    /**
     * Installs module
     * @return boolean
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()) {
            return false;
        }

        foreach ($this->hooksToRegister as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }

        if (!empty($this->settings) && is_array($this->settings)) {
            // Generate CRON secure key if setting exists
            if (isset($this->settings['cron_secure_key'])) {
                $this->settings['cron_secure_key'] = Tools::passwdGen(12);
            }

            $settings = ElegantalTinyPngImageCompressTools::serialize($this->settings);

            // Save for each shop
            $shop_groups = Shop::getTree();
            foreach ($shop_groups as $shop_group) {
                foreach ($shop_group['shops'] as $shop) {
                    if (!Configuration::updateValue($this->name, $settings, false, $shop['id_shop_group'], $shop['id_shop'])) {
                        return false;
                    }
                }
            }

            // Save for all shops
            if (!Configuration::updateValue($this->name, $settings, false, null, null)) {
                return false;
            }
        }

        $file = dirname(__FILE__) . "/../installer/install.php";
        if (is_file($file)) {
            if (!self::executeSqlFromFile($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Un-install module
     * @return boolean
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        if (!empty($this->settings) && is_array($this->settings)) {
            if (!Configuration::deleteByName($this->name)) {
                return false;
            }
        }

        $file = dirname(__FILE__) . "/../installer/uninstall.php";
        if (is_file($file)) {
            if (!self::executeSqlFromFile($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Runs SQL query from given file
     * @param string $file
     * @return boolean
     */
    private static function executeSqlFromFile($file)
    {
        // Check if file exists
        if (!is_file($file) || !is_readable($file)) {
            return false;
        }

        // Check if array of queries loaded
        $queries = require_once($file);
        if (empty($queries)) {
            return false;
        }

        // Execute queries
        foreach ($queries as $query) {
            if (Db::getInstance()->execute($query) == false) {
                throw new Exception(Db::getInstance()->getMsgError());
            }
        }

        return true;
    }

    /**
     * Clears smarty cache for front-end templates after POST request on admin
     */
    public function clearFrontTplCaches()
    {
        if ($this->isPostRequest()) {
            $cacheIds = $this->getCacheIdsForSmarty();
            if (!empty($cacheIds)) {
                $tpls = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'hook' . DIRECTORY_SEPARATOR . '*.tpl');
                foreach ($tpls as $tpl) {
                    if (is_file($tpl)) {
                        foreach ($cacheIds as $cacheId) {
                            $this->_clearCache(basename($tpl), $cacheId);
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns IDs for clearing Smarty caches. It should be overridden by child class.
     * @return array
     */
    protected function getCacheIdsForSmarty()
    {
        return array();
    }

    /**
     * Checks if the request is POST request
     * @return bool
     */
    public function isPostRequest()
    {
        return isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'], 'POST');
    }

    /**
     * Returns error/success messages set before redirect
     * @return string HTML
     */
    public function getRedirectAlerts($isHtml = true)
    {
        $html = '';

        if (isset($this->context->cookie->redirect_errors)) {
            $html .= $isHtml ? $this->displayError($this->context->cookie->redirect_errors) : $this->context->cookie->redirect_errors;
            unset($this->context->cookie->redirect_errors);
        }
        if (isset($this->context->cookie->redirect_messages)) {
            $html .= $isHtml ? $this->displayConfirmation($this->context->cookie->redirect_messages) : $this->context->cookie->redirect_messages;
            unset($this->context->cookie->redirect_messages);
        }

        return $html;
    }

    /**
     * Sets redirect alert message to cookie so that it can be displayed after page redirect
     * @param mixed:array|string $message single message or array of messages
     * @param string $type
     */
    public function setRedirectAlert($message, $type, $isHtml = true)
    {
        if (is_array($message)) {
            $line = $isHtml ? '<br>' : PHP_EOL;
            $message = implode($line, $message);
        }
        $type = ($type == 'error') ? 'redirect_errors' : 'redirect_messages';
        $this->context->cookie->__set($type, $message);
    }

    /**
     * Redirect function to be used on back-office
     * @param array $params
     */
    public function redirectAdmin($params = array())
    {
        $this->clearFrontTplCaches();
        $url = $this->getAdminUrl($params);
        Tools::redirectAdmin($url);
    }

    /**
     * Returns module URL for back-office
     * @param array $params
     * @return string
     */
    public function getAdminUrl($params = array())
    {
        $url = $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        foreach ($params as $key => $value) {
            $url .= '&' . $key . '=' . $value;
        }
        return $url;
    }

    /**
     * Returns absolute module URL for back-office
     * @param array $params
     * @return string
     */
    public function getAdminAbsoluteUrl($params = array())
    {
        return _PS_BASE_URL_ . __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/' . $this->getAdminUrl($params);
    }

    /**
     * Returns base URL for admin panel
     * @return string URL
     */
    public function getAdminBaseUrl()
    {
        return _PS_BASE_URL_ . __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/';
    }

    /**
     * Returns URL to module directory
     * @return string
     */
    public function getModuleUrl()
    {
        return $this->_path;
    }

    /**
     * Returns URL where users contact developer on addons
     * @return string URL
     */
    public function getContactDeveloperUrl()
    {
        $url = $this->developerModulesUrl;
        if ($this->productIdOnAddons) {
            $url = 'https://addons.prestashop.com/en/write-to-developper?id_product=' . $this->productIdOnAddons;
        }
        return $url;
    }

    /**
     * Returns URL where modules of developer displayed
     * @return string URL
     */
    public function getDeveloperModulesUrl()
    {
        return $this->developerModulesUrl;
    }

    /**
     * Returns URL where users rate modules on addons
     * @return string URL
     */
    public function getRateModuleUrl()
    {
        return $this->rateModuleUrl;
    }

    /**
     * Returns list of documentation URL per language
     * @return array
     */
    public function getDocumentationUrls()
    {
        $urls = array();
        $files = $this->getDocumentationFiles();
        foreach ($files as $lang => $file) {
            $urls[$lang] = $this->getModuleUrl() . $this->docsFolder . '/' . $file;
        }

        return $urls;
    }

    /**
     * Returns list of documentation files
     * @return array
     */
    protected function getDocumentationFiles()
    {
        $files = array();
        $dir = $this->getPathToDocumentationDir();
        $docs = glob($dir . DIRECTORY_SEPARATOR . "*.pdf");
        foreach ($docs as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                $lang = $filename;
                if (preg_match("/readme_([a-zA-Z]+).pdf/", $filename, $match) && isset($match[1])) {
                    $lang = $match[1];
                }
                $files[$lang] = $filename;
            }
        }

        return $files;
    }

    /**
     * Returns documentation directory
     * @return string
     * @throws Exception
     */
    protected function getPathToDocumentationDir()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $this->docsFolder;
        if (!is_dir($dir)) {
            throw new PrestaShopException("Documentation folder is invalid");
        }

        return $dir;
    }

    /**
     * Detects device and returns true if the device is a mobile, false otherwise
     * @return boolean
     */
    public function isMobile()
    {
        $file1 = _PS_TOOL_DIR_ . 'mobile_Detect/Mobile_Detect.php';
        $file2 = _PS_TOOL_DIR_ . '../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
        if (file_exists($file1)) {
            require_once($file1);
        } elseif (file_exists($file2)) {
            require_once($file2);
        } else {
            return false;
        }

        $detect = new Mobile_Detect();
        if ($detect->isMobile() == true && $detect->isTablet() == false) {
            return true;
        }

        return false;
    }

    /**
     * Displays warning message(s). It is created here because early prestashop 1.6 version does not have it.
     * @param string|array $warning
     * @return string
     */
    public function displayWarning($warning)
    {
        if (method_exists(get_parent_class(), 'displayWarning')) {
            return parent::displayWarning($warning);
        }

        return is_array($warning) ? implode('<br>', $warning) : $warning;
    }

    /**
     * Make PHP max execution time limit higher in order for the module not to reach timeout
     */
    protected function setTimeLimit()
    {
        @set_time_limit(0);
        @ini_set('max_execution_time', 600);
    }

    /**
     * Action function to manage settings. Renders and processes settings form
     * @return html
     */
    protected function settings()
    {
        $html = '';

        // Get settings from DB
        $settings = ElegantalTinyPngImageCompressTools::unserialize(Configuration::get($this->name));
        if (empty($settings)) {
            $settings = $this->settings;
        }
        foreach ($settings as $key => $value) {
            if (array_key_exists($key, $this->settings)) {
                $this->settings[$key] = $value;
            }
        }

        // Process Form
        if ($this->isPostRequest()) {
            $errors = array();

            foreach ($this->settings as $key => $value) {
                if (Tools::isSubmit($key)) {
                    $submitValue = ElegantalTinyPngImageCompressTools::unserialize(Tools::getValue($key));
                    if (ElegantalTinyPngImageCompressTools::serialize($submitValue) !== Tools::getValue($key)) {
                        // submitted value is not serialized
                        $this->settings[$key] = Tools::getValue($key);
                    } else {
                        $this->settings[$key] = $submitValue;
                    }
                } else {
                    $errors[] = $this->l('Invalid Settings.');
                }
            }

            if (empty($errors)) {
                if (!empty($this->settings) && Configuration::updateValue($this->name, ElegantalTinyPngImageCompressTools::serialize($this->settings))) {
                    $this->setRedirectAlert($this->l('Settings saved successfully.'), 'success');
                    if (_PS_VERSION_ < '1.6') {
                        $this->redirectAdmin();
                    } else {
                        $this->redirectAdmin(array('event' => 'settings'));
                    }
                } else {
                    $html .= $this->displayError($this->l('Settings could not be saved.'));
                }
            } else {
                $html .= $this->displayError(implode('<br>', $errors));
            }
        }

        //Render Form
        $inputs = array();
        foreach ($this->settings as $key => &$value) {
            if (is_array($value)) {
                $value = ElegantalTinyPngImageCompressTools::serialize($value);
            }
            $inputs[] = array(
                'type' => 'text',
                'label' => Tools::ucfirst(str_replace('_', ' ', $key)),
                'name' => $key
            );
        }
        $fields_value = $this->settings;
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Edit Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => $inputs,
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Back'),
                        'icon' => 'process-icon-back'
                    ),
                )
            )
        );

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submit_settings';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(array('event' => 'settings'));
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code
            ),
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $html .= $helper->generateForm(array($fields_form));

        return $html;
    }

    /**
     * Returns all settings
     * @return array
     */
    public function getSettings()
    {
        $settings = ElegantalTinyPngImageCompressTools::unserialize(Configuration::get($this->name));
        if (empty($settings)) {
            $settings = $this->settings;
        }

        return array_merge($this->settings, $settings);
    }

    /**
     * Returns value of requested setting
     * @param string $name
     * @return string|boolean
     */
    public function getSetting($name, $default = false)
    {
        $settings = ElegantalTinyPngImageCompressTools::unserialize(Configuration::get($this->name));
        if (empty($settings)) {
            $settings = $this->settings;
        }
        if (array_key_exists($name, $settings)) {
            return $settings[$name];
        } elseif (array_key_exists($name, $this->settings)) {
            return $this->settings[$name];
        }

        return $default;
    }

    /**
     * Sets value of setting
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function setSetting($name, $value)
    {
        $settings = ElegantalTinyPngImageCompressTools::unserialize(Configuration::get($this->name));
        if (empty($settings)) {
            $settings = $this->settings;
        }
        if (array_key_exists($name, $settings) || array_key_exists($name, $this->settings)) {
            $settings[$name] = $value;
            return Configuration::updateValue($this->name, ElegantalTinyPngImageCompressTools::serialize($settings));
        }

        return false;
    }

    /**
     * Clear settings
     * @return boolean
     */
    public function clearSettings()
    {
        if (!Configuration::updateValue($this->name, ElegantalTinyPngImageCompressTools::serialize($this->settings))) {
            return false;
        }
        return true;
    }
}
