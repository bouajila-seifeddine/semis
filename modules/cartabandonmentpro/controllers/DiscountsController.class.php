<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class DiscountsController
{
    public function __construct()
    {
    }

    public static function getDiscounts($wich_remind = 1)
    {
        $query = "SELECT *
                    FROM `"._DB_PREFIX_."cartabandonmentpro_cartrule` cc
                    WHERE id_template = ".(int)$wich_remind."
                    ORDER BY tranche";
        $results = Db::getInstance()->ExecuteS($query);
        return $results;
    }

    public static function saveDiscountsTxt($id_shop = false)
    {
        if (!$id_shop) {
            $languages = Language::getLanguages();
        } else {
            $languages = Language::getLanguages($id_shop);
        }

        foreach ($languages as $language) {
            Configuration::updateValue('CARTABAND_DISC_VAL', array(
                $language['id_lang'] => Tools::getValue('discount_val_text_' . $language['id_lang'])
            ));
            Configuration::updateValue('CARTABAND_SHIPP_VAL', array(
                $language['id_lang']  => Tools::getValue('discount_shipping_text_' . $language['id_lang'])
            ));
        }
    }

    public static function createDiscount($id_customer, $value, $validity, $type, $min, $with_taxes)
    {
        $code = 'CAV'.Tools::substr(sha1(microtime()), 6, 5);

        $voucher = new CartRule();
        $voucher->id_customer = (int)$id_customer;
        $voucher->code = $code;
        $voucher->name[Configuration::get('PS_LANG_DEFAULT')] = $code;
        $voucher->quantity = 1;
        $voucher->quantity_per_user = 1;
        $voucher->active = true;
        $voucher->shop_restriction = true;
        $voucher->cart_rule_restriction = 1;
        $voucher->highlight = false;
        $voucher->cart_rule_restriction = true;
        $now = time();
        $voucher->date_from = date('Y-m-d H:i:s', $now);
        $voucher->date_to = date('Y-m-d H:i:s', $now + (3600 * 24 * $validity));

        $voucher->description = 'Cart Abandonment Pro '.$code;

        if ($type == 'shipping') {
            $type = Discount::FREE_SHIPPING;
            $voucher->free_shipping = true;
        } elseif ($type == 'percent') {
            $voucher->free_shipping = false;
            $type = Discount::PERCENT;
        } else {
            $voucher->free_shipping = false;
            $voucher->reduction_tax = $with_taxes;
            $type = Discount::AMOUNT;
        }

        $voucher->id_discount_type = (int)$type;
        $voucher->minimum_amount = $min;
        $voucher->minimum_amount_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');

        if ($type == Discount::AMOUNT) {
            $voucher->reduction_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
            $voucher->reduction_amount = $value;
        } elseif ($type == Discount::PERCENT) {
            $voucher->reduction_percent = $value;
        }

        $voucher->save();
        Db::getInstance()->execute('
            INSERT INTO `'._DB_PREFIX_.'cart_rule_shop` (`id_cart_rule`, `id_shop`)
            VALUES ('.(int)$voucher->id.', '.(int)Shop::getContextShopId().')');
        return $voucher;
    }
}
