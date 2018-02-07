<?php
/**
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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * @author PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
 * @license You only can use module, nothing more!
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(_PS_MODULE_DIR_.'prestahome/classes/PrestaHomeOptions.php')) {
    include_once _PS_MODULE_DIR_.'prestahome/classes/PrestaHomeOptions.php';
}

class PrestaHome extends Module
{
    private $api_prestahome = 'https://api.prestahome.com/';

    public function __construct()
    {
        $this->name = 'prestahome';
        $this->tab = 'front_office_features';
        $this->version = '1.0.8';
        $this->author = 'www.PrestaHome.com';

        parent::__construct();

        $this->displayName = $this->l('Prestahome Base Module');
        $this->description = $this->l('Framework module for your PrestaHome theme');

        if ($this->id) {
            if (!$this->isRegisteredInHook('displayBackOfficeHeader')) {
                $this->registerHook('displayBackOfficeHeader');
            }

            if (!$this->isRegisteredInHook('displayProductSecondImage')) {
                $this->registerHook('displayProductSecondImage');
            }
        }
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayTop')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('actionProductListModifier')
            || !$this->registerHook('displayProductSecondImage')
            || !$this->installQuickLink()
            || !$this->installPrestaHomeOptions()
            || !$this->setupConfiguration()
            ) {
            return false;
        } else {
            return true;
        }
    }

    public function installQuickLink()
    {
        $lang = Configuration::get('PS_LANG_DEFAULT');
        $q = new QuickAccess();
        $q->link = 'index.php?controller=AdminPrestaHomeOptions';
        $q->new_window = 0;
        $q->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $q->name[$lang['id_lang']] = $this->l('Theme Options');
        }

        $q->add();

        Configuration::updateValue('PRESTAHOME_QL', (int)$q->id);

        return true;
    }

    public function installPrestaHomeOptions()
    {
        if (!Tab::getIdFromClassName('AdminPrestaHomeOptions')) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->name = array();

            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = 'Theme Options';
            }

            $tab->class_name = 'AdminPrestaHomeOptions';
            $tab->id_parent = Tab::getIdFromClassName('AdminParentPreferences');
            $tab->module = $this->name;
            $tab->add();
        }
        $ThemeOptionsInstance = new PrestaHomeOptions();

        return $ThemeOptionsInstance->installOptions();
    }

    public function setupConfiguration()
    {
        if (file_exists(_PS_MODULE_DIR_.$this->name.'/init/init.php')) {
            require_once _PS_MODULE_DIR_.$this->name.'/init/init.php';
        }

        return true;
    }

    public function uninstall()
    {
        $idTabs = array();
        $idTabs[] = Tab::getIdFromClassName('AdminPrestaHomeOptions');
        foreach ($idTabs as $idTab) {
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }

        if (!parent::uninstall()
            || !$this->uninstallQuickLink(Configuration::get('PRESTAHOME_QL'))
            || !$this->uninstallPrestaHomeOptions()) {
                return false;
        } else {
            return true;
        }
    }

    public function uninstallPrestaHomeOptions()
    {
        return (Configuration::deleteByName('prestahome_options_default')
            && Configuration::deleteByName('prestahome_options_custom')
            && Configuration::deleteByName('PRESTAHOME_QL'));
    }

    public function uninstallQuickLink($id)
    {
        $q = new QuickAccess((int)$id);
        return $q->delete();
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (!isset($this->context->controller) || isset($this->context->controller) && $this->context->controller->controller_name != 'AdminDashboard') {
            return;
        }
        
        //check if currently updatingcheck if module is currently processing update
        if (!Module::isEnabled($this->name)) {
            return false;
        }
        
        if (method_exists($this->context->controller, 'addJquery')) {
            $this->context->controller->addJquery();

            $ThemeOptionsInstance = new PrestaHomeOptions();
            if (!$ThemeOptionsInstance->options['check_for_updates']) {
                return;
            }
            
            if (file_exists(_PS_MODULE_DIR_ . 'prestahome/theme.xml')) {
                $xml_theme = simplexml_load_file(_PS_MODULE_DIR_ . 'prestahome/theme.xml');
                $theme_name = $xml_theme->name;
                $theme_version = $xml_theme->version;
                
                $newer_version = Tools::jsonDecode(Tools::file_get_contents($this->api_prestahome.'check_for_update.php?checkNewerVersion=true&theme_name='.Tools::strtolower($theme_name).'&theme_version='.$theme_version), true);

                if (is_array($newer_version) && $newer_version['version'] != false) {
                    $newer_version = $newer_version['version'];
                    $update_available = true;
                } else {
                    return;
                }

                $this->context->smarty->assign(array(
                    'theme_name' => $theme_name,
                    'theme_version' => $newer_version,
                    'url' => $this->context->link->getAdminLink('AdminPrestaHomeOptions'),
                ));

                return $this->display(__FILE__, 'backoffice_header.tpl');
            }
        }
    }

    public function getImages($id_product, $id_lang, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        $sql = 'SELECT image_shop.`cover`, i.`id_image`, il.`legend`, i.`position`
                FROM `'._DB_PREFIX_.'image` i
                '.Shop::addSqlAssociation('image', 'i').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                WHERE i.`id_product` = '.(int)$id_product.'
                ORDER BY `position`';
        return Db::getInstance()->executeS($sql);
    }

    public function hookDisplayProductSecondImage($params)
    {
        $id_product = (int)$params['product']['id_product'];

        if (!$this->isCached('second_image.tpl', $this->getCacheId($id_product))) {
            $images = $this->getImages($id_product, (int)$this->context->language->id);
            $second_image = count($images) > 1 ? $images[1] : '';
            
            $this->smarty->assign(array(
                'second_image' => $second_image,
                'product' => $params['product'],
                'link' => $this->context->link
            ));
        }
        return $this->display(__FILE__, 'second_image.tpl', $this->getCacheId($id_product));
    }

    public function hookActionProductListModifier($params)
    {
        foreach ($params['cat_products'] as &$cat_products) {
            $cat_products['second_image'] = $this->getImages($cat_products['id_product'], Context::getContext()->language->id);
        }
    }

    public function hookDisplayTop($params)
    {
        // User CSS
        if (Shop::getContext() == Shop::CONTEXT_GROUP && Shop::isFeatureActive()) {
            $file_name = 'userCss-ShopGroup-'.(int)Context::getContext()->shop->getContextShopGroupID().'.css';
        } elseif (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $file_name = 'userCss-Shop-'.(int)Context::getContext()->shop->getContextShopID().'.css';
        } else {
            $file_name = 'userCss.css';
        }

        $file = _MODULE_DIR_.'prestahome/views/css/'.$file_name;
        $file_path = _PS_MODULE_DIR_.'prestahome/views/css/'.$file_name;

        if (file_exists($file_path)) {
            $this->context->controller->addCSS($file);
        }

        $file_suffix = '';
        if (Shop::getContext() == Shop::CONTEXT_GROUP) {
            $file_suffix = '_group_'.(int)$this->context->shop->getContextShopGroupID();
        } elseif (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $file_suffix = '_shop_'.(int)$this->context->shop->getContextShopID();
        }

        $this->context->controller->addCSS(_MODULE_DIR_.'prestahome/views/css/custom'.$file_suffix.'.css');
        $this->context->controller->addJS(_MODULE_DIR_.'prestahome/views/js/custom-header'.$file_suffix.'.js');
    }

    public function initThemeOptions()
    {
        $ThemeOptionsInstance = new PrestaHomeOptions();
        $theme_options = $ThemeOptionsInstance->getOptions(true);

        $this->context->smarty->assign(array(
            'theme_options' => $theme_options
        ));
    }

    public function registerHelpers()
    {
        // Helpers
        if (!isset($this->context->smarty->registered_plugins['function']['getImageByOption'])) {
            smartyRegisterFunction($this->context->smarty, 'function', 'getImageByOption', array(&$this, 'helperGetImageByOption'));
        }
    }
}
