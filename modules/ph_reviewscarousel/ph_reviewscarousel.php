<?php
/*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class PH_ReviewsCarousel extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();

    private $configs_array = array(
        'ITEMS' => 3,
        'ITEMS_DESKTOP' => 3,
        'ITEMS_TABLET' => 2,
        'ITEMS_MOBILE' => 1,
        'SPEED' => 200,
        'PAGI_SPEED' => 800,
        'REWIND_SPEED' => 1000,
        'AUTOPLAY' => 0,
        'STOP_HOVER' => false,
        'NAVIGATION' => false,
        'PAGINATION' => true,
        'RESPONSIVE' => false,
        'PRICE' => true,
        'REVIEWS_NB_DISPLAY' => true,
        'LOAD_OWL_FROM_THEME' => false,
        'ITEMS_COLUMN' => 2,
    );

    public static $cfg_prefix = 'PH_REVIEWSCAROUSEL_';
    
    public function __construct()
    {
        $this->name = 'ph_reviewscarousel';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'www.PrestaHome.com';
        $this->need_instance = 0;
        $this->is_configurable = 1;
        $this->ps_versions_compliancy['min'] = '1.6.0.3';
        $this->ps_versions_compliancy['max'] = '1.6.1.0';
        $this->dependencies = array('productcomments');
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Reviews Carousel / Slider');
        $this->description = $this->l('Reviews Carousel by PrestaHome');

        if(!Module::isInstalled('productcomments') || !Module::isEnabled('productcomments'))
            $this->warning = $this->l('Product Comments module must be installed, enabled and configured to use this module.');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');
    }

    public function install()
    {

        $this->_clearCache('*');

        // Hooks & Install
        return (parent::install() 
                && $this->prepareModuleSettings() 
                && $this->registerHook('displayPrestaHomeReviewsCarousel') 
                && $this->registerHook('displayLeftColumn') 
                && $this->registerHook('displayRightColumn') 
                && $this->registerHook('displayHome')
                && $this->registerHook('displayHeader')
                && $this->registerHook('addProduct')
                && $this->registerHook('updateProduct')
                && $this->registerHook('deleteProduct')
                && $this->registerHook('moduleRoutes') 
            );
    }

    public function prepareModuleSettings()
    {
        foreach($this->configs_array as $key => $value)
        {
            Configuration::updateGlobalValue(self::$cfg_prefix.$key, $value);
        }

        // For theme developers - you're welcome!
        if(file_exists(_PS_MODULE_DIR_.'ph_reviewscarousel/init/my-install.php'))
            include_once _PS_MODULE_DIR_.'ph_reviewscarousel/init/my-install.php';
       
        return true;
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        if (!parent::uninstall()) {
            return false;
        }

        foreach($this->configs_array as $key => $value)
        {
            Configuration::deleteByName(self::$cfg_prefix.$key);
        }

        // For theme developers - you're welcome!
        if(file_exists(_PS_MODULE_DIR_.'ph_reviewscarousel/init/my-uninstall.php'))
            include_once _PS_MODULE_DIR_.'ph_reviewscarousel/init/my-uninstall.php';

        return true;
    }

    public function hookAddProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookUpdateProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookDeleteProduct($params)
    {
        $this->_clearCache('*');
    }

    public function _clearCache($template, $cache_id = NULL, $compile_id = NULL)
    {
        parent::_clearCache('home.tpl');
    }

    public function getContent()
    {
        $this->initFieldsForm();
        if (Tools::getIsset('save'.$this->name))
        {
            foreach($this->fields_form as $form)
                foreach($form['form']['input'] as $field)
                    if(isset($field['validation']))
                    {
                        $errors = array();       
                        $value = Tools::getValue($field['name']);
                        if (isset($field['required']) && $field['required'] && $value==false && (string)$value != '0')
                                $errors[] = sprintf(Tools::displayError('Field "%s" is required.'), $field['label']);
                        elseif($value)
                        {
                            if (!Validate::$field['validation']($value))
                                $errors[] = sprintf(Tools::displayError('Field "%s" is invalid.'), $field['label']);
                        }
                        // Set default value
                        if ($value === false && isset($field['default_value']))
                            $value = $field['default_value'];
                            
                        if(count($errors))
                        {
                            $this->validation_errors = array_merge($this->validation_errors, $errors);
                        }
                        elseif($value==false)
                        {
                            switch($field['validation'])
                            {
                                case 'isUnsignedId':
                                case 'isUnsignedInt':
                                case 'isInt':
                                case 'isBool':
                                    $value = 0;
                                break;
                                default:
                                    $value = '';
                                break;
                            }
                            Configuration::updateValue($field['name'], $value);
                        }
                        else
                            Configuration::updateValue($field['name'], $value);
                    }
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
                $this->_html .= Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=6&token='.Tools::getAdminTokenLite('AdminModules'));
        }

        $helper = $this->initForm();
        foreach($this->configs_array as $key => $value)
        {
            $helper->fields_value[self::$cfg_prefix.$key] = Configuration::get(self::$cfg_prefix.$key);
        }
     
        return $this->_html.$helper->generateForm($this->fields_form);
    }

    protected function initFieldsForm()
    {
        $i = 0;
        $this->fields_form[$i]['form'] = array(
            'legend' => array(
                'title' => $this->l('Reviews on homepage'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Visible items (desktop):'),
                    'name' => self::$cfg_prefix.'ITEMS_DESKTOP',
                    'size' => 2,
                    'desc' => $this->l('The number of products visible on desktops with width screen resolution up to 1199px and more'),
                    'required' => true,
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Visible items (tablet):'),
                    'name' => self::$cfg_prefix.'ITEMS_TABLET',
                    'size' => 2,
                    'desc' => $this->l('The number of products visible on tablets with width screen resolution under 768px'),
                    'required' => true,
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Visible items (mobile):'),
                    'name' => self::$cfg_prefix.'ITEMS_MOBILE',
                    'size' => 2,
                    'desc' => $this->l('The number of products visible on mobile devices with width screen resolution under 479px'),
                    'required' => true,
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Autoplay:'),
                    'name' => self::$cfg_prefix.'AUTOPLAY',
                    'size' => 2,
                    'desc' => $this->l('In ms, for example 5000 to play every 5 seconds. Set to 0 to disable Autoplay.'),
                    'required' => true,
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Navigation:'),
                    'name' => self::$cfg_prefix.'NAVIGATION',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => self::$cfg_prefix.'NAVIGATION_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => self::$cfg_prefix.'NAVIGATION_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('Prev & Next links'),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show product price?'),
                    'name' => self::$cfg_prefix.'PRICE',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => self::$cfg_prefix.'PRICE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => self::$cfg_prefix.'PRICE_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show number of the reviews?'),
                    'name' => self::$cfg_prefix.'REVIEWS_NB_DISPLAY',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => self::$cfg_prefix.'REVIEWS_NB_DISPLAY_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => self::$cfg_prefix.'REVIEWS_NB_DISPLAY_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );
        $i++;

        $this->fields_form[$i]['form'] = array(
            'legend' => array(
                'title' => $this->l('Reviews in sidebar'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Visible items:'),
                    'name' => self::$cfg_prefix.'ITEMS_COLUMN',
                    'size' => 2,
                    'desc' => $this->l('The number of products visible in shop column'),
                    'required' => true,
                    'validation' => 'isUnsignedInt',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );
        $i++;

        $this->fields_form[$i]['form'] = array(
            'legend' => array(
                'title' => $this->l('Miscellaneous'),
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Load Owl Carousel from Theme?'),
                    'name' => self::$cfg_prefix.'LOAD_OWL_FROM_THEME',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => self::$cfg_prefix.'LOAD_OWL_FROM_THEME_on',
                            'value' => 1,
                            'label' => $this->l('Theme')
                        ),
                        array(
                            'id' => self::$cfg_prefix.'LOAD_OWL_FROM_THEME_off',
                            'value' => 0,
                            'label' => $this->l('Module')
                        ),
                    ),
                    'validation' => 'isBool',
                ), 
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );
        $i++;
        
    }

    protected function initForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

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
        return $helper;
    }

    public function assignModuleVariables($params)
    {
        $this->smarty->assign(array(
            self::$cfg_prefix.'ITEMS_DESKTOP'         => Configuration::get(self::$cfg_prefix.'ITEMS_DESKTOP'),
        ));
    }

    public function hookHeader($params)
    {
        if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index')
        {
            $this->context->controller->addCSS($this->_path.'/css/ph_reviewscarousel.css', 'all');

            if(!Configuration::get(self::$cfg_prefix.'LOAD_OWL_FROM_THEME'))
                $this->context->controller->addJS($this->_path.'/js/owl.carousel.min.js', 'all');

            $this->context->controller->addJS($this->_path.'/js/ph_reviewscarousel.js', 'all');
        }
    }

    public function hookDisplayHome($params)
    {
        if(!Module::isInstalled('productcomments') || !Module::isEnabled('productcomments'))
            return;

        $this->assignModuleVariables($params);


        $this->smarty->assign(array(
            'reviews' => $this->getAllReviews($params),
        ));

        return $this->display(__FILE__, 'home.tpl');
    }

    public function hookDisplayLeftColumn($params)
    {
        if(!Module::isInstalled('productcomments') || !Module::isEnabled('productcomments'))
            return;

        $this->assignModuleVariables($params);
        $params['limit'] = Configuration::get(self::$cfg_prefix.'ITEMS_COLUMN');
        $this->smarty->assign(array(
            'reviews' => $this->getAllReviews($params)
        ));

        return $this->display(__FILE__, 'column.tpl');
    }

    public function getAllReviews($params)
    {
        if(file_exists(_PS_MODULE_DIR_.'productcomments/ProductComment.php'))
            require_once _PS_MODULE_DIR_.'productcomments/ProductComment.php';
        else
            return array();

        $validate = Configuration::get('PRODUCT_COMMENTS_MODERATE');

        $reviews = (Db::getInstance()->executeS('
        SELECT pc.`id_product_comment`, pc.`id_product`, IF(c.id_customer, CONCAT(c.`firstname`, \' \',  c.`lastname`), pc.customer_name) customer_name, pc.`content`, pc.`title`, pc.`grade`, pc.`date_add`, pl.`name`
        FROM `'._DB_PREFIX_.'product_comment` pc
        LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = pc.`id_customer`)
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = pc.`id_product` AND pl.`id_lang` = '.(int)Context::getContext()->language->id.Shop::addSqlRestrictionOnLang('pl').')
        '.($validate == '1' ? 'WHERE pc.`validate` = 1' : '').'
        ORDER BY pc.`date_add` DESC
        '.(isset($params['limit']) ? 'LIMIT '.(int)$params['limit'] : '')
        ));

        foreach($reviews as &$review)
        {
            $product = new Product((int)$review['id_product'], false, $this->context->language->id);

            $cover = Product::getCover((int)$review['id_product']); 
            $review['product_image'] = $this->context->link->getImageLink($product->link_rewrite, $cover['id_image'], ImageType::getFormatedName('small'));
            $review['product_link'] = $product->getLink();
            $review['product_price'] = $product->getPrice();
        }

        return $reviews;
    }
}
