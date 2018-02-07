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
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2015 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * @author PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
 * @license You only can use module, nothing more!
 */
if (!defined('_PS_VERSION_'))
    exit;

class ph_madrid extends Module
{
    public static $custom_hooks = array(
        array('displayBeforeHeader', 'displayBeforeHeader', '', 1),
        array('displayBeforeContent', 'displayBeforeContent', '', 1),
        array('displayAfterContent', 'displayAfterContent', '', 1),
        array('displayBeforeFooter', 'displayBeforeFooter', '', 1),    
        array('displayAfterFooter', 'displayAfterFooter', '', 1),    
    );

    public function __construct()
    {
        $this->name = 'ph_madrid';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'www.PrestaHome.com';
        $this->dependencies = 'prestahome';

        parent::__construct();

        $this->displayName = $this->l('PrestaHome Madrid Theme - Base Module');   
        $this->description = $this->l('Base module of Madrid Theme theme - installation is required');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayTop')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayFooter')
            || !$this->setupConfiguration()
            || !$this->addCustomHooks()
            )
            return false;
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !$this->removeCustomHooks())
            return false;
        return true;
    }

    public function setupConfiguration()
    {
        if(file_exists(_PS_MODULE_DIR_.$this->name.'/init/init.php'))
            require_once _PS_MODULE_DIR_.$this->name.'/init/init.php';

        return true;
    }
	
    public function addCustomHooks()
    {
        $res = true;
        foreach(self::$custom_hooks as $v)
        {
            if(!$res)
                break;
            if (!Validate::isHookName($v[0]))
                continue;
                
            $id_hook = Hook::getIdByName($v[0]);
            if (!$id_hook)
            {
                $new_hook = new Hook();
                $new_hook->name = pSQL($v[0]);
                $new_hook->title = pSQL($v[1]);
                $new_hook->description = pSQL($v[2]);
                $new_hook->position = pSQL($v[3]);
                $new_hook->live_edit = 0;
                $new_hook->add();
                $id_hook = $new_hook->id;
                if (!$id_hook)
                    $res = false;
            }
            else
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'hook` set `title`="'.$v[1].'", `description`="'.$v[2].'", `position`="'.$v[3].'" where `id_hook`='.$id_hook);
            }
        }
        return $res;
    }

    public function removeCustomHooks()
    {
        $sql = 'DELETE FROM `'._DB_PREFIX_.'hook` WHERE ';
        foreach(self::$custom_hooks as $v)
            $sql .= ' `name` = "'.$v[0].'" OR';
        return Db::getInstance()->execute(rtrim($sql,'OR').';');
    }

	
    public function hookDisplayTop()
    {
        return;
    }
}
