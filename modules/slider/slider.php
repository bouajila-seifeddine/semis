<?php
/**
 * NOTICE OF LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * ...........................................................................
 *
 * @package   Slider
 * @author    Paul MORA
 * @copyright Copyright (c) 2012-2014 SAS BlobMarket - www.blobmarket.com - Paul MORA
 * @license   MIT license
 * Support by mail  :  contact@blobmarket.com
 */

if (!defined('_PS_VERSION_'))
  exit;

// Loading Models
require_once(_PS_MODULE_DIR_ . 'slider/models/Slideshow.php');

class Slider extends Module
{
	// DB file
	const INSTALL_SQL_FILE = 'install.sql';
	const UNINSTALL_SQL_FILE = 'uninstall.sql';

	public function __construct()
	{
		$this->name = 'slider';
		$this->tab = 'front_office_features';
		$this->version = '2.0';
        $this->author = 'BlobMarket - blobmarket.com';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.5');

		parent::__construct();

        $this->displayName = $this->l('Slider');
        $this->description = $this->l('Add a slideshow to your homepage and manage it easily');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

	/**
 	 * install
	 */
	public function install()
	{
		if (!parent::install() ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('displayTopColumn') ||
            !$this->registerHook('displayFooter') ||
			!mkdir(_PS_IMG_DIR_.'slider') ||
            !$this->installModuleTab('AdminSlider', array((int)(Configuration::get('PS_LANG_DEFAULT'))=>'Slider'), 'AdminParentModules') ||
            !$this->_sql(self::INSTALL_SQL_FILE)
            )
			return false;

        $shop_list = Shop::getContextListShopID();
        $excepts = array('address', 'addresses', 'attachment', 'auth', 'bestsales', 'cart', 'category', 'changecurrency',
            'cms', 'compare', 'contact', 'discount', 'getfile', 'guesttracking', 'history', 'identity', 'manufacturer',
            'myaccount', 'newproducts', 'order', 'orderconfirmation', 'orderdetail', 'orderfollow', 'orderopc', 'orderreturn',
            'orderslip', 'pagenotfound', 'parentorder', 'password', 'pdfinvoice', 'pdforderreturn', 'pdforderslip',
            'pricesdrop', 'product', 'search', 'sitemap', 'statistics', 'stores', 'supplier');
        $excepts_footer = array('address', 'addresses', 'attachment', 'auth', 'bestsales', 'cart', 'category', 'changecurrency',
            'cms', 'compare', 'contact', 'discount', 'getfile', 'guesttracking', 'history', 'identity', 'manufacturer',
            'myaccount', 'newproducts', 'order', 'orderconfirmation', 'orderdetail', 'orderfollow', 'orderopc', 'orderreturn',
            'orderslip', 'pagenotfound', 'parentorder', 'password', 'pdfinvoice', 'pdforderreturn', 'pdforderslip',
            'pricesdrop', 'product', 'search', 'index', 'statistics', 'stores', 'supplier');

        // Save modules exception for each shop
        foreach ($shop_list as $shop_id)
        {
            foreach ($excepts as $except)
            {
                if (!$except)
                    continue;
                $insertException = array(
                    'id_module' => (int)$this->id,
                    'id_hook' => (int)Hook::getIdByName('displayHeader'),
                    'id_shop' => (int)$shop_id,
                    'file_name' => pSQL($except),
                );
                $result = Db::getInstance()->insert('hook_module_exceptions', $insertException);
                if (!$result)
                    return false;

                $insertException = array(
                    'id_module' => (int)$this->id,
                    'id_hook' => (int)Hook::getIdByName('displayTopColumn'),
                    'id_shop' => (int)$shop_id,
                    'file_name' => pSQL($except),
                );
                $result = Db::getInstance()->insert('hook_module_exceptions', $insertException);
                if (!$result)
                    return false;
            }

            foreach ($excepts_footer as $except)
            {
                if (!$except)
                    continue;
                $insertException = array(
                    'id_module' => (int)$this->id,
                    'id_hook' => (int)Hook::getIdByName('displayFooter'),
                    'id_shop' => (int)$shop_id,
                    'file_name' => pSQL($except),
                );
                $result = Db::getInstance()->insert('hook_module_exceptions', $insertException);
                if (!$result)
                    return false;
            }
        }

		return true;
	}

    /**
     * uninstall
     */
    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->uninstallModuleTab('AdminSlider') ||
            !$this->deleteDir(_PS_IMG_DIR_.'slider') ||
            !$this->_sql(self::UNINSTALL_SQL_FILE)
        )
            return false;
        return true;
    }

    private static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);

        return true;
    }

    /**
     * install Tab
     */
    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        $idTab = Tab::getIdFromClassName($idTabParent);
        $pass = true ;
        @copy(dirname(__FILE__).'/img/logo.gif', _PS_IMG_DIR_.'t/'.$tabClass.'.gif');
        $tab = new Tab();
        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTab;
        $pass = $tab->save();

        return($pass);
    }

    /**
     * uninstall Tab
     */
    private function uninstallModuleTab($tabClass)
    {
        $pass = true ;
        @unlink(_PS_IMG_DIR_.'t/'.$tabClass.'.gif');
        $idTab = Tab::getIdFromClassName($tabClass);
        if($idTab != 0)
        {
            $tab = new Tab($idTab);
            $pass = $tab->delete();
        }
        return($pass);
    }

    /**
     * Create / Remove Table
     */
    private function _sql($file)
    {
        if (!file_exists(dirname(__FILE__).'/sql/'.$file))
            return false;
        else if (!$sql = file_get_contents(dirname(__FILE__).'/sql/'.$file))
            return false;
        $sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
        // Insert default template data
        $sql = str_replace('THE_FIRST_DEFAULT', serialize(array('width' => 1, 'height' => 1)), $sql);
        $sql = str_replace('FLY_IN_DEFAULT', serialize(array('width' => 1, 'height' => 1)), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

        foreach ($sql as $query)
            if (!Db::getInstance()->execute(trim($query)))
                return false;

        return true;
    }

	/**
 	 * admin page
	 */
	public function getContent()
	{
        return '<h2>'.$this->displayName.'</h2>
            <fieldset>
                <legend><img src="'._MODULE_DIR_.$this->name.'/logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
                <p>'.$this->l('In order to configure your slides, follow the link "Slider" created in your "Modules" tab').'</p>
            </fieldset>
            <br />
            <fieldset>
                <p>'.$this->l('This module is completely free'). '</p>
                <p>'.$this->l('In exchange, a link to BlobMarket has been placed in the footer of the Sitemap page of your shop'). '</p>
                <p>'.$this->l('If you want to remove this link, untransplant the module from the displayFooter position'). '</p>
            </fieldset>

        ';
	}

	// FRONT OFFICE HOOKS

	/**
 	 * <head> Hook
	 */
	public function hookDisplayHeader()
	{
		// CSS
        $this->context->controller->addCSS($this->_path.'views/css/slippry.css');
        $this->context->controller->addCSS($this->_path.'views/css/slider.css');
		// JS
        $this->context->controller->addJS($this->_path.'views/js/slippry.min.js');
        $this->context->controller->addJS($this->_path.'views/js/slider.js');
	}

    /**
     * Top column hook
     */
    public function hookDisplayTopColumn($params)
    {
        $this->smarty->assign(array(
            'slides' => Slideshow::getSlides(Context::getContext()->language->id),
            'image_url' => _PS_IMG_.'slider/',
        ));

        return $this->display(__FILE__, 'views/templates/front/slider.tpl');
    }

	/**
 	 * Top of pages hook
	 */
	public function hookDisplayTop($params)
	{
		return $this->hookDisplayTopColumn($params);
	}

    /**
 	 * Home page hook
	 */
	public function hookDisplayHome($params)
	{
		return $this->hookDisplayTopColumn($params);
	}

    /**
 	 * Footer hook
	 */
	public function hookDisplayFooter($params)
	{
		return "This PrestaShop store uses BlobMarket's module Slider 2 : <a href='http://code.blobmarket.com/en/item/slider-2/40/'>http://code.blobmarket.com/en/item/slider-2/40/</a>";
	}
}

?>