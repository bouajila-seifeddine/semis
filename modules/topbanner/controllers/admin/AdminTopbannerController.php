<?php
/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 *  @copyright 2007-2017 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!class_exists('TopBannerClass')) {
    include_once(_PS_MODULE_DIR_ . 'topbanner/classes/TopBannerClass.php');
}

if (!class_exists('TopBannerLangClass')) {
    include_once(_PS_MODULE_DIR_ . 'topbanner/classes/TopBannerLangClass.php');
}

class AdminTopbannerController extends ModuleAdminController
{

    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    public function ajaxProcessChangeState()
    {
        $id_banner = (int) Tools::getValue('id_banner', 0);
        $state = (int) Tools::getValue('state', 0);
        if ($state == 1) {
            $this->module->disableAll();
        }

        $query = 'UPDATE ' . _DB_PREFIX_ . 'topbanner SET status = ' . (int) $state . ' WHERE id_banner = ' . (int) $id_banner;
        die(Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query));
    }

    public function ajaxProcessPreview()
    {
        $topbanner = new Topbanner();

        $params = array();
        parse_str(Tools::getValue('data'), $params);

        $paramsFormatted = array();
        foreach ($params as $key => $value) {
            $newKey = str_replace('topbanner_banner_', '', $key);

            if ($newKey == 'id') {
                $newKey = 'id_banner';
            }

            $paramsFormatted[$newKey] = $value;
        }

        die($topbanner->previewBanner($paramsFormatted, Tools::getValue('iso_lang')));
    }
}
