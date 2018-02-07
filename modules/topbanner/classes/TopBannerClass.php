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

class TopBannerClass extends ObjectModel
{

    public $id_banner;
    public $name;
    public $height;
    public $background;
    public $type;
    public $subtype;
    public $cartrule;
    public $timer;
    public $timer_background;
    public $timer_text_color;
    public $text_size;
    public $text_font;
    public $text_color;
    public $cta;
    public $cta_text_color;
    public $cta_background;
    public $status;
    protected $table = 'topbanner';
    protected $identifier = 'id_banner';

    public function getFields()
    {
        parent::validateFields();

        $fields = array();

        $fields['id_banner'] = (int) $this->id_banner;
        $fields['name'] = pSQL($this->name);
        $fields['height'] = (int) $this->height;
        $fields['background'] = pSQL($this->background);
        $fields['type'] = (int) $this->type;
        $fields['subtype'] = (int) $this->subtype;
        $fields['cartrule'] = (int) $this->cartrule;

        $fields['timer'] = (int) $this->timer;
        $fields['timer_background'] = pSQL($this->timer_background);
        $fields['timer_text_color'] = pSQL($this->timer_text_color);

        $fields['text_size'] = (int) $this->text_size;
        $fields['text_font'] = (int) $this->text_font;
        $fields['text_color'] = pSQL($this->text_color);

        $fields['cta'] = (int) $this->cta;
        $fields['cta_text_color'] = pSQL($this->cta_text_color);
        $fields['cta_background'] = pSQL($this->cta_background);

        $fields['status'] = (int) $this->status;

        return $fields;
    }

    public static function getAllBanners($id_lang)
    {
        $query = 'SELECT t.*, cr.code, crl.name as cr_name '
                . 'FROM ' . _DB_PREFIX_ . 'topbanner t '
                . 'JOIN ' . _DB_PREFIX_ . 'cart_rule cr ON t.cartrule = cr.id_cart_rule '
                . 'JOIN ' . _DB_PREFIX_ . 'cart_rule_lang crl ON cr.id_cart_rule = crl.id_cart_rule '
                . 'WHERE crl.id_lang = ' . (int) $id_lang;
        $cartRuleBanners = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        $query = 'SELECT t.* '
                . 'FROM ' . _DB_PREFIX_ . 'topbanner t '
                . 'WHERE cartrule = 0';
        $noCartRuleBanners = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return array_merge($cartRuleBanners, $noCartRuleBanners);
    }

    public static function getBanner()
    {
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'topbanner WHERE status = 1';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
    }

    public static function getBannerById($id_banner)
    {
        $query = 'SELECT * '
                . 'FROM ' . _DB_PREFIX_ . 'topbanner t '
                . 'WHERE t.id_banner = ' . (int) $id_banner;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
    }

    public static function disableAll()
    {
        $query = 'UPDATE ' . _DB_PREFIX_ . 'topbanner SET status = 0';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
    }

    public static function disableBanner($id_banner)
    {
        $query = 'UPDATE ' . _DB_PREFIX_ . 'topbanner SET status = 0 WHERE id_banner = ' . (int) $id_banner;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
    }

    public static function deleteBanner($id_banner)
    {
        $query = 'DELETE FROM ' . _DB_PREFIX_ . 'topbanner WHERE id_banner = ' . (int) $id_banner;
        if (Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query)) {
            $query = 'DELETE FROM ' . _DB_PREFIX_ . 'topbanner_lang WHERE id_banner = ' . (int) $id_banner;
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
        }
    }
}
