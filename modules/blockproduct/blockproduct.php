<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once 'classes/BlockProductDetail.php';
class BlockProduct extends Module
{
    private $_html = '';
    const INSTALL_SQL_FILE = 'install.sql';
    public function __construct()
    {
        $this->name = 'blockproduct';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Webkul';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Block Product');
        $this->description = $this->l('Block product based on IP address or country wise');
    }

    private function _postValidation()
    {
        if (Tools::isSubmit('btnSubmit')) {
            if (Tools::getValue('SHOW_PRODUCT_DETAIL_PAGE')) {
                if (Tools::getValue('WK_PRODUCT_DEFAULT_MESSAGE') == '') {
                    $this->_postErrors[] = $this->l('Default message field is required');
                }
            }
        }
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue('SHOW_PRODUCT_DETAIL_PAGE', Tools::getValue('SHOW_PRODUCT_DETAIL_PAGE'));
            Configuration::updateValue('WK_PRODUCT_DEFAULT_MESSAGE', Tools::getValue('WK_PRODUCT_DEFAULT_MESSAGE'));
            Configuration::updateValue('WK_COUNTRY_BASED_ON', Tools::getValue('WK_COUNTRY_BASED_ON'));

            $module_config = $this->context->link->getAdminLink('AdminModules');
            Tools::redirectAdmin($module_config.'&configure='.$this->name.'&module_name='.$this->name.'&conf=4');
        }
    }

    // For configuration page.
    public function getContent()
    {
        $this->context->controller->addJs($this->_path.'views/js/configuration.js');

        if (Tools::isSubmit('btnSubmit')) {
            $this->_postValidation();
            if (!count($this->_postErrors)) {
                $this->_postProcess();
            } else {
                foreach ($this->_postErrors as $err) {
                    $this->_html .= $this->displayError($err);
                }
            }
        } else {
            $this->_html .= '<br />';
        }

        $this->_html .= $this->renderForm();

        return $this->_html;
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Block Product Configuration'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Show product detail page'),
                        'class' => 't',
                        'name' => 'SHOW_PRODUCT_DETAIL_PAGE',
                        'values' => array(
                                        array(
                                            'id' => 'active_on',
                                            'value' => 1,
                                            'label' => $this->l('Enabled'),
                                        ),
                                        array(
                                            'id' => 'active_off',
                                            'value' => 0,
                                            'label' => $this->l('Disabled'),
                                        ),
                                    ),
                        'hint' => $this->l('If yes, customer can see product detail.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Default message'),
                        'col' => 3,
                        'class' => 'default_message',
                        'name' => 'WK_PRODUCT_DEFAULT_MESSAGE',
                        'hint' => $this->l('If product detail page is enable, than what message show in detail page for customer.'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Get country'),
                        'name' => 'WK_COUNTRY_BASED_ON',
                        'options' => array(
                            'query' => array(
                                array('key' => '1', 'name' => 'Customer Address'),
                                array('key' => '2', 'name' => 'IP Address'),
                            ),
                            'id' => 'key',
                            'name' => 'name',
                        ),
                        'hint' => $this->l('Get country detail based on customer address or IP address wise.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'SHOW_PRODUCT_DETAIL_PAGE' => Tools::getValue('SHOW_PRODUCT_DETAIL_PAGE', Configuration::get('SHOW_PRODUCT_DETAIL_PAGE')),
            'WK_PRODUCT_DEFAULT_MESSAGE' => Tools::getValue('WK_PRODUCT_DEFAULT_MESSAGE', Configuration::get('WK_PRODUCT_DEFAULT_MESSAGE')),
            'WK_COUNTRY_BASED_ON' => Tools::getValue('WK_COUNTRY_BASED_ON', Configuration::get('WK_COUNTRY_BASED_ON')),
        );
    }

    //Backend:add tab in product catelog.
    public function hookDisplayAdminProductsExtra($params)
    {
        $id_product = Tools::getValue('id_product');
        $country = Country::getCountries($this->context->language->id);
        if ($id_product) {
            $obj_blockproductdetail = new BlockProductDetail();
            $blockproduct = $obj_blockproductdetail->checkBlockProduct($id_product);
            if ($blockproduct['active']) {
                $block_country = explode(';', $blockproduct['block_country']);
                $block_ip = str_replace(';', "\n", $blockproduct['block_ip']);
                $this->context->smarty->assign(
                    array(
                        'active' => $blockproduct['active'],
                        'block_country' => $block_country,
                        'block_ip' => $block_ip,
                    )
                );
            } else {
                $this->context->smarty->assign('active', 0);
            }
            $this->context->smarty->assign(
                array(
                    'id_product' => $id_product,
                    'country' => $country,
                )
            );
        }

        return $this->display(__FILE__, 'addtab.tpl');
    }
    //Delete Product From Cart.
    protected function deleteCartProduct()
    {
        $cart_id = $this->context->cart->id;
        $obj_blockproductdetail = new BlockProductDetail();
        $obj_cart = new Cart($cart_id);
        if ($obj_cart && isset($obj_cart)) {
            $cartproduct = $obj_cart->getProducts();
            if ($cartproduct) {
                foreach ($cartproduct as $product) {
                    $checkproduct = $obj_blockproductdetail->checkBlockProduct($product['id_product']);
                    if ($checkproduct && $checkproduct['active']) {
                        // checking country wise
                        $allowed = $this->isBlackListCountryForProduct($checkproduct);
                        // IP based
                        if (!$allowed) {
                            $allowed = $this->isBlackListIpForProduct($checkproduct);
                        }

                        if ($allowed) {
                            $obj_cart->deleteProduct($checkproduct['id_product']);
                        }
                    }
                }
            }
        }
    }

    //For order page.
    public function hookDisplayHeader()
    {
        $controller = Tools::getValue('controller');
        if ($controller == 'order' || $controller == 'orderopc') {
            $this->deleteCartProduct();
        }
    }
    //For Address change.
    public function hookDisplayCarrierList($params)
    {
        $this->deleteCartProduct();
    }

    //Get country iso code.
    protected function getIsoCode()
    {
        $user_ip = Tools::getRemoteAddr();
        if (Configuration::get('WK_COUNTRY_BASED_ON') == 1) {
            if ($this->context->cookie->logged) {
                $iso_code = $this->context->country->iso_code;
            } else {
                $iso_code = $this->getLocationInfoByIp($user_ip);
            }
        } else {
            $iso_code = $this->getLocationInfoByIp($user_ip);
        }

        return $iso_code;
    }

    //Check product for country wise.
    protected function isBlackListCountryForProduct($blockproduct)
    {
        $allowed = false;
        $iso_code = $this->getIsoCode();
        $block_country = explode(';', $blockproduct['block_country']);
        if (is_array($block_country) && count($block_country) && $iso_code) {
            if (in_array(Tools::strtoupper($iso_code), $block_country)) {
                $allowed = true;
            }
        }

        return $allowed;
    }

    //check product for ip address wise.
    protected function isBlackListIpForProduct($blockproduct)
    {
        $allowed = false;
        $user_ip = Tools::getRemoteAddr();
        $block_ip = explode(';', $blockproduct['block_ip']);
        $ips = array();
        
        if (is_array($block_ip) && count($block_ip)) {
            foreach ($block_ip as $ip) {
                $ips = array_merge($ips, explode("\n", $ip));
            }
        }

        $ips = array_map('trim', $ips);
        
        if (is_array($ips) && count($ips)) {
            foreach ($ips as $ip) {
                if (!empty($ip) && preg_match('/^'.$ip.'.*/', $user_ip)) {
                    $allowed = true;
                }
            }
        }

        return $allowed;
    }

    //get country iso code through ip address.
    protected function getLocationInfoByIp($ip)
    {
        $result = false;
        $ip_data = Tools::jsonDecode(Tools::file_get_contents('http://www.geoplugin.net/json.gp?ip='.$ip));
        
        if ($ip_data && $ip_data->geoplugin_countryName != null) {
            $result = $ip_data->geoplugin_countryCode;
        }
        
        return $result;
    }

    //For product detail page.
    public function hookDisplayRightColumnProduct()
    {
        $id_product = (int) Tools::getValue('id_product');
        $allowed = false;
        
        $obj_blockproductdetail = new BlockProductDetail();
        $blockproduct = $obj_blockproductdetail->checkBlockProduct($id_product);
        
        if ($blockproduct && $blockproduct['active']) {
            // checking country wise
            $allowed = $this->isBlackListCountryForProduct($blockproduct);
            // IP based
            if (!$allowed) {
                $allowed = $this->isBlackListIpForProduct($blockproduct);
            }

            if ($allowed) {
                if (!Configuration::get('SHOW_PRODUCT_DETAIL_PAGE')) {
                    Tools::redirect($this->context->link->getPageLink('index', true));
                }
            }
        }

        if ($allowed) {
            $this->context->smarty->assign('message', Configuration::get('WK_PRODUCT_DEFAULT_MESSAGE'));

            return $this->display(__FILE__, 'blockmessage.tpl');
        }
    }

    // In Front list of product.
    public function hookDisplayProductListReviews($params)
    {
        $id_product = $params['product']['id_product'];
        $allowed = false;
        $check = 1;
        $obj_blockproductdetail = new BlockProductDetail();
        $allblockproduct = $obj_blockproductdetail->getAllBlockProductId();

        if ($allblockproduct) {
            foreach ($allblockproduct as $blockproduct) {
                if ($id_product == $blockproduct['id_product'] && $blockproduct['active']) {
                    // checking country wise
                    $allowed = $this->isBlackListCountryForProduct($blockproduct);
                    // IP based
                    if (!$allowed) {
                        $allowed = $this->isBlackListIpForProduct($blockproduct);
                        $check = 2;
                    }
                }

                if ($allowed) {
                    $this->context->smarty->assign('id_product', $id_product);
                    $this->context->smarty->assign('check', $check);
                    break;
                } else {
                    $this->context->smarty->assign('check', 0);
                }
            }
        }
        return $this->display(__FILE__, 'disabled.tpl');
    }

    //For admin add product.
    public function hookActionProductAdd($params)
    {
        $id_product = $params['id_product'];
        
        if (isset($id_product) && $id_product) {
            $obj_blockproductdetail = new BlockProductDetail();
            $obj_blockproductdetail->id_product = $id_product;
            $obj_blockproductdetail->save();
        }
    }

    //For admin update product.
    public function hookActionProductUpdate($params)
    {
        $id_product = (int) Tools::getValue('id_product');
        $update_quantity = Tools::getValue('ajaxProductQuantity'); // When Quantity update through ajax.
        if (isset($id_product) && $id_product && !$update_quantity) {
            $active = Tools::getValue('active_block');
            $country = implode(';', Tools::getValue('countries'));
            $blacklist_ip = str_replace("\n", ';', str_replace("\r", '', Tools::getValue('blacklist_ip')));
            //var_dump($active);
            $obj_blockproductdetail = new BlockProductDetail();
            $blockproduct = $obj_blockproductdetail->checkBlockProduct($id_product);
            
            if ($blockproduct) {
                $obj_blockproductdetail = new BlockProductDetail($id_product);
                $obj_blockproductdetail->active = $active;
                $obj_blockproductdetail->block_country = $country;
                $obj_blockproductdetail->block_ip = $blacklist_ip;
                $obj_blockproductdetail->save();
            } else {
                $obj_blockproductdetail->id_product = $id_product;
                $obj_blockproductdetail->active = $active;
                $obj_blockproductdetail->block_country = $country;
                $obj_blockproductdetail->block_ip = $blacklist_ip;
                $obj_blockproductdetail->save();
            }
        }
    }

    //Install mudule function.
    public function install()
    {
        if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE)) {
            return (false);
        } elseif (!$sql = Tools::file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE)) {
            return (false);
        }

        $sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);

        foreach ($sql as $query) {
            if ($query) {
                if (!Db::getInstance()->execute(trim($query))) {
                    return false;
                }
            }
        }
        if (!parent::install()
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('displayProductTab')
            || !$this->registerHook('displayRightColumnProduct')
            || !$this->registerHook('displayProductListReviews')
            || !$this->registerHook('displayCarrierList')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('actionProductAdd')) {
            return false;
        }

        Configuration::updateValue('SHOW_PRODUCT_DETAIL_PAGE', 0);
        Configuration::updateValue('WK_PRODUCT_DEFAULT_MESSAGE', 'This product not available in this country');
        Configuration::updateValue('WK_COUNTRY_BASED_ON', 1);

        return true;
    }

    //For delete database table.
    public function dropTable()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'block_product_detail`');
    }

    //For uninstall module.
    public function uninstall()
    {
        if (!parent::uninstall()
            || !$this->dropTable()) {
            return false;
        }

        return true;
    }
}
