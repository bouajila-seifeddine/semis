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

class TopBannerLangClass extends ObjectModel
{

    public $id_banner;
    public $id_lang;
    public $id_shop;
    public $name;
    public $value;
    protected $table = 'topbanner_lang';

    public static function saveValue($id_banner, $id_lang, $name, $value)
    {
        $operation = '';
        if (is_null($id_banner)) {
            $operation = 'insert';
        } else {
            $exist = 'SELECT id_banner '
                    . 'FROM ' . _DB_PREFIX_ . 'topbanner_lang '
                    . 'WHERE id_banner = ' . (int) $id_banner . ' '
                    . 'AND id_lang = ' . (int) $id_lang . ' '
                    . 'AND name = "' . pSQL($name) . '"';
            $count = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($exist);
            $operation = (count($count) > 0) ? 'update' : 'insert';
        }

        switch ($operation) {
            case 'insert':
                $query = 'INSERT INTO ' . _DB_PREFIX_ . 'topbanner_lang(id_banner, id_lang, name, value) '
                        . 'VALUES(' . (int) $id_banner . ', ' . (int) $id_lang . ', "' . pSQL($name) . '", "' . pSQL($value) . '")';
                break;
            case 'update':
                $query = 'UPDATE ' . _DB_PREFIX_ . 'topbanner_lang '
                        . 'SET value = "' . pSQL($value) . '" '
                        . 'WHERE id_banner = ' . (int) $id_banner . ' '
                        . 'AND id_lang = ' . (int) $id_lang . ' '
                        . 'AND name = "' . pSQL($name) . '"';
                break;
            default:
                return 'An error occured - saveValue';
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
    }

    public static function getLangs($id_banner)
    {
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'topbanner_lang WHERE id_banner = ' . (int) $id_banner;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    public static function getLangsPreview($banner)
    {
        $langs = array();

        foreach ($banner as $key => $field) {
            if (strpos($key, '-')) {
                $explode = explode('-', $key);
                $langs[] = array(
                    'id_lang' => $explode[1],
                    'name' => $explode[0],
                    'value' => $field
                );
            }
        }

        return $langs;
    }

    public static function getValue($id_lang, $name, $langs)
    {
        foreach ($langs as $lang) {
            if ($lang['id_lang'] == $id_lang && $name == $lang['name']) {
                return $lang['value'];
            }
        }
    }
}
