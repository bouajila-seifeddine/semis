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

abstract class PaymentModule extends PaymentModuleCore {

    /**
     * Validate an order in database
     * Function called from a payment module
     *
     * @param int $id_cart
     * @param int $id_order_state
     * @param float   $amount_paid    Amount really paid by customer (in the default currency)
     * @param string  $payment_method Payment method (eg. 'Credit card')
     * @param null    $message        Message to attach to order
     * @param array   $extra_vars
     * @param null    $currency_special
     * @param bool    $dont_touch_amount
     * @param bool    $secure_key
     * @param Shop    $shop
     *
     * @return bool
     * @throws PrestaShopException
     */
    public function validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null) {
        if (parent::validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method, $message, $extra_vars, $currency_special, $dont_touch_amount, $secure_key, $shop)) {
            if ((int) $this->context->cookie->id_cart > 0) {
                $this->context->cookie->__unset('id_cart');
            }
        }
    }

}

