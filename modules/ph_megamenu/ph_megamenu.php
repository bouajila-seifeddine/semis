<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
/**
* @author    PrestaHome Team <support@prestahome.com>
* @copyright  Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/

if (file_exists(_PS_MODULE_DIR_ . 'ph_megamenu/models/PrestaHomeMegaMenu.php')) {
    require_once _PS_MODULE_DIR_ . 'ph_megamenu/models/PrestaHomeMegaMenu.php';
}

if (!defined('_PS_VERSION_')) {
    exit;
}

class PH_MegaMenu extends Module
{
    private $page_name;
    private $user_groups;
    
    public function __construct()
    {
        $this->name = 'ph_megamenu';
        $this->tab = 'front_office_features';
        $this->version = '1.0.19';
        $this->author = 'www.PrestaHome.com';
        $this->module_key = '08153672d664dfe43c31285984c554ca';
        $this->need_instance = 0;
        $this->is_configurable = 1;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->secure_key = Tools::encrypt($this->name);

        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation('prestahome_megamenu', array('type' => 'shop'));
        }
        
        parent::__construct();

        $this->displayName = $this->l('Mega Menu');
        $this->description = $this->l('Create beautiful Mega Menu with categories, products and custom blocks');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module?');
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
            !$this->prepareModuleSettings() ||
            !$this->registerHook('displayTop') ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('displayPrestaHomeMegaMenu') ||

            !$this->registerHook('actionObjectCategoryUpdateAfter') ||
            !$this->registerHook('actionObjectCategoryDeleteAfter') ||
            !$this->registerHook('actionObjectCategoryAddAfter') ||
            !$this->registerHook('actionObjectCmsUpdateAfter') ||
            !$this->registerHook('actionObjectCmsDeleteAfter') ||
            !$this->registerHook('actionObjectCmsAddAfter') ||
            !$this->registerHook('actionObjectSupplierUpdateAfter') ||
            !$this->registerHook('actionObjectSupplierDeleteAfter') ||
            !$this->registerHook('actionObjectSupplierAddAfter') ||
            !$this->registerHook('actionObjectManufacturerUpdateAfter') ||
            !$this->registerHook('actionObjectManufacturerDeleteAfter') ||
            !$this->registerHook('actionObjectManufacturerAddAfter') ||
            !$this->registerHook('actionObjectProductUpdateAfter') ||
            !$this->registerHook('actionObjectProductDeleteAfter') ||
            !$this->registerHook('actionObjectProductAddAfter') ||
            !$this->registerHook('categoryUpdate') ||
            !$this->registerHook('actionShopDataDuplication')) {
            return false;
        }
        return true;
    }

    public function prepareModuleSettings()
    {
        // Database
        $sql = array();
        include(dirname(__file__).'/init/install_sql.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
                return false;
            }
        }

        // Tabs
        $parent_tab = new Tab();

        foreach (Language::getLanguages(false) as $lang) {
            $parent_tab->name[$lang['id_lang']] = $this->l('Mega Menu');
        }

        $parent_tab->class_name = 'AdminPrestaHomeMegaMenu';
        $parent_tab->id_parent = Tab::getCurrentParentId();
        $parent_tab->module = $this->name;
        $parent_tab->add();

        $settings_tab = new Tab();

        foreach (Language::getLanguages(false) as $lang) {
            $settings_tab->name[$lang['id_lang']] = $this->l('Mega Menu - Settings');
        }

        $settings_tab->class_name = 'AdminPrestaHomeMegaMenuSettings';
        $settings_tab->id_parent = -1;
        $settings_tab->module = $this->name;
        $settings_tab->add();

        // Configurations
        Configuration::updateGlobalValue('PH_MM_CATEGORIES_SORTBY', 'position');
        Configuration::updateGlobalValue('PH_MM_PRODUCT_WIDTH', '3');
        Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_TITLE', true);
        Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_SECOND_IMAGE', true);
        Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_PRICE', true);
        Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_DESC', true);
        Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_ADD2CART', true);
        Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_VIEW', true);
        Configuration::updateGlobalValue('PH_MM_PRODUCT_SHOW_QUICK_VIEW', true);
        Configuration::updateGlobalValue('PH_MM_DEFAULT_LABEL_BG', '#009ad0');
        Configuration::updateGlobalValue('PH_MM_DEFAULT_LABEL_COLOR', '#ffffff');
        Configuration::updateGlobalValue('PH_MM_USE_SLIDE_EFFECT', false);

        // Demo content
        Shop::addTableAssociation('prestahome_megamenu', array('type' => 'shop'));
        Shop::setContext(Shop::CONTEXT_ALL);

        /**
            For theme developers - you're welcome!
        **/
        if (file_exists(_PS_MODULE_DIR_.'ph_megamenu/init/my-install.php')) {
            include_once _PS_MODULE_DIR_.'ph_megamenu/init/my-install.php';
        }

        return true;
    }

    public function uninstall()
    {
        $this->clearMenuCache();

        if (!parent::uninstall()) {
            return false;
        }

        // Database
        $sql = array();
        include_once(dirname(__file__).'/init/uninstall_sql.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
                return false;
            }
        }

        // Tabs
        $idTabs = array();
        $idTabs[] = Tab::getIdFromClassName('AdminPrestaHomeMegaMenu');
        $idTabs[] = Tab::getIdFromClassName('AdminPrestaHomeMegaMenuSettings');

        foreach ($idTabs as $idTab) {
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }

        // Configurations
        Configuration::deleteByName('PH_MM_CATEGORIES_SORTBY');
        Configuration::deleteByName('PH_MM_PRODUCT_WIDTH');
        Configuration::deleteByName('PH_MM_PRODUCT_SHOW_TITLE');
        Configuration::deleteByName('PH_MM_PRODUCT_SHOW_SECOND_IMAGE');
        Configuration::deleteByName('PH_MM_PRODUCT_SHOW_PRICE');
        Configuration::deleteByName('PH_MM_PRODUCT_SHOW_ADD2CART');
        Configuration::deleteByName('PH_MM_PRODUCT_SHOW_VIEW');
        Configuration::deleteByName('PH_MM_PRODUCT_SHOW_QUICK_VIEW');
        Configuration::deleteByName('PH_MM_DEFAULT_LABEL_BG');
        Configuration::deleteByName('PH_MM_DEFAULT_LABEL_COLOR');
        Configuration::deleteByName('PH_MM_USE_SLIDE_EFFECT');
        Configuration::deleteByName('PH_MM_PRODUCT_SHOW_DESC');


        // For theme developers - you're welcome!
        if (file_exists(_PS_MODULE_DIR_.'ph_megamenu/init/my-uninstall.php')) {
            include_once _PS_MODULE_DIR_.'ph_megamenu/init/my-uninstall.php';
        }

        return true;
    }

    public function hookActionShopDataDuplication($params)
    {
        $links = Db::getInstance()->executeS('
            SELECT *
            FROM '._DB_PREFIX_.'prestahome_megamenu_shop
            WHERE id_shop = '.(int)$params['old_id_shop']);

        foreach ($links as $id => $link) {
            Db::getInstance()->execute('
                INSERT IGNORE INTO '._DB_PREFIX_.'prestahome_megamenu_shop (id_prestahome_megamenu, id_shop)
                VALUES ('.(int)$link['id_prestahome_megamenu'].', '.(int)$params['new_id_shop'].')');
        }

        foreach ($links as $id => $link) {
            $lang = Db::getInstance()->executeS('
                    SELECT id_lang, id_prestahome_megamenu, title, url, label_text, content
                    FROM '._DB_PREFIX_.'prestahome_megamenu_lang
                    WHERE id_prestahome_megamenu = '.(int)$link['id_prestahome_megamenu']);

            foreach ($lang as $l) {
                Db::getInstance()->execute('
                    INSERT IGNORE INTO '._DB_PREFIX_.'prestahome_megamenu_lang (id_prestahome_megamenu, id_lang, title, url, label_text, content)
                    VALUES ('.(int)$l['id_prestahome_megamenu'].', '.(int)$l['id_lang'].', '.$l['title'].', '.$l['url'].', '.$l['label_text'].', '.$l['content'].')');
            }
        }
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'css/font-awesome.css');
        $this->context->controller->addCSS($this->_path.'css/ph_megamenu.css');
        $this->context->controller->addCSS($this->_path.'css/custom.css');
        $this->context->controller->addJS($this->_path.'js/ph_megamenu.js');
        $this->context->controller->addJS($this->_path.'js/custom.js');
        $this->context->controller->addJS($this->_path.'js/jquery.fitvids.js');
        $this->context->controller->addJqueryPlugin(array('hoverIntent'));
    }

    public function hookDisplayPrestaHomeMegaMenu($params)
    {
        return $this->hookDisplayTop($params);
    }

    public function hookDisplayTop($params)
    {
        $this->page_name = Dispatcher::getInstance()->getController();
        $this->user_groups =  ($this->context->customer->isLogged() ? $this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));

        if (!$this->isCached('ph_megamenu.tpl', $this->getCacheId())) {
            $menu = PrestaHomeMegaMenu::getMegaMenu();
            $id_lang = (int)$this->context->language->id;

            foreach ($menu as &$tab) {
                if (isset($tab['childrens'])) {
                    foreach ($tab['childrens'] as $key => $children_tab) {
                        // Mega Categories
                        if ($children_tab['type'] == 4) {
                            $tab['childrens'][$key]['categories'] = $this->generateCategoriesMenu(self::getNestedCategories($children_tab['id_category_parent'], $id_lang, true, $this->user_groups));
                        }

                        // Product(s)
                        if ($children_tab['type'] == 6) {
                            $tab['childrens'][$key]['products'] = PrestaHomeMegaMenu::getTabProducts(explode(',', $children_tab['id_product']), $id_lang);
                        }
                    }
                }

                if ($tab['type'] == 2) {
                    $tab['dropdown'] = $this->generateCategoriesMenu(self::getNestedCategories($tab['id_category_parent'], $id_lang, true, $this->user_groups), true);
                }
            }

            $this->context->smarty->assign('menu', $menu);
        }

        return $this->display(__FILE__, 'ph_megamenu.tpl', $this->getCacheId());
    }

    private function generateCategoriesMenu($categories, $dropdown = false)
    {
        //$page_name = Dispatcher::getInstance()->getController();

        foreach ($categories as $key => $category) {
            if ($dropdown == true) {
                $categories = $category['children'];
            }

            // if (isset($category['children']) && !empty($category['children']) && $category['level_depth'] == 2)
            // {
            //     $categories = $category['children'];
            // }
        }

        return $categories;
    }

    public static function getNestedCategories(
        $root_category = null,
        $id_lang = false,
        $active = true,
        $groups = null,
        $use_shop_restriction = true,
        $sql_filter = '',
        $sql_sort = '',
        $sql_limit = ''
    ) {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }

        $cache_id = 'Category::getNestedCategories_'.md5((int)$root_category.(int)$id_lang.(int)$active.(int)$active
            .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        $sort_by_option = Configuration::get('PH_MM_CATEGORIES_SORTBY');

        if ($sort_by_option == 'name') {
            $custom_sort_by = 'cl.`name` ASC';
        } elseif ($sort_by_option == 'id') {
            $custom_sort_by = 'c.`id_category` ASC';
        } else {
            $custom_sort_by = 'category_shop.`position` ASC';
        }

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS('
                SELECT c.*, cl.*
                FROM `'._DB_PREFIX_.'category` c
                '.($use_shop_restriction ? Shop::addSqlAssociation('category', 'c') : '').'
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').'
                '.(isset($groups) && Group::isFeatureActive() ? 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON c.`id_category` = cg.`id_category`' : '').'
                '.(isset($root_category) ? 'RIGHT JOIN `'._DB_PREFIX_.'category` c2 ON c2.`id_category` = '.(int)$root_category.' AND c.`nleft` >= c2.`nleft` AND c.`nright` <= c2.`nright`' : '').'
                WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
                '.($active ? ' AND c.`active` = 1' : '').'
                '.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
                '.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
                '.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
                '.($sql_sort == '' && $use_shop_restriction ? ', '.$custom_sort_by.'' : '').'
                '.($sql_limit != '' ? $sql_limit : ''));

            $categories = array();
            $buff = array();

            if (!isset($root_category)) {
                $root_category = 1;
            }
            
            foreach ($result as $row) {
                $current = &$buff[$row['id_category']];
                $current = $row;

                if ($row['id_category'] == $root_category) {
                    $categories[$row['id_category']] = &$current;
                } else {
                    $buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
                }
            }

            Cache::store($cache_id, $categories);
        }

        return Cache::retrieve($cache_id);
    }

    protected function getCacheId($name = null)
    {
        parent::getCacheId($name);
        $page_name = in_array($this->page_name, array('category', 'supplier', 'manufacturer', 'cms', 'product')) ? $this->page_name : 'index';
        return 'ph_megamenu|'.(int)Tools::usingSecureMode().'|'.$page_name.'|'.(int)$this->context->shop->id.'|'.implode(', ', $this->user_groups).'|'.(int)$this->context->language->id.'|'.(int)Tools::getValue('id_category').'|'.(int)Tools::getValue('id_manufacturer').'|'.(int)Tools::getValue('id_supplier').'|'.(int)Tools::getValue('id_cms').'|'.(int)Tools::getValue('id_product');
    }

    public function hookActionObjectCategoryAddAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectCategoryUpdateAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectCmsUpdateAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectCmsDeleteAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectCmsAddAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectSupplierUpdateAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectSupplierDeleteAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectSupplierAddAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectManufacturerUpdateAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectManufacturerDeleteAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectManufacturerAddAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectProductUpdateAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectProductDeleteAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookActionObjectProductAddAfter($params)
    {
        $this->clearMenuCache();
    }
    
    public function hookCategoryUpdate($params)
    {
        $this->clearMenuCache();
    }
    
    public function clearMenuCache()
    {
        $this->_clearCache('ph_megamenu.tpl');
    }
}
