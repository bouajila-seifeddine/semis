<?php
/*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class OrderConfirmationControllerCore extends FrontController
{
    public $ssl = true;
    public $php_self = 'order-confirmation';
    public $id_cart;
    public $id_module;
    public $id_order;
    public $reference;
    public $secure_key;

    /**
     * Initialize order confirmation controller
     * @see FrontController::init()
     */
    public function init()
    {
        parent::init();

        $this->id_cart = (int)(Tools::getValue('id_cart', 0));
        $is_guest = false;

        /* check if the cart has been made by a Guest customer, for redirect link */
        if (Cart::isGuestCartByCartId($this->id_cart)) {
            $is_guest = true;
            $redirectLink = 'index.php?controller=guest-tracking';
        } else {
            $redirectLink = 'index.php?controller=history';
        }

        $this->id_module = (int)(Tools::getValue('id_module', 0));
        $this->id_order = Order::getOrderByCartId((int)($this->id_cart));
        $this->secure_key = Tools::getValue('key', false);
        $order = new Order((int)($this->id_order));
        if ($is_guest) {
            $customer = new Customer((int)$order->id_customer);
            $redirectLink .= '&id_order='.$order->reference.'&email='.urlencode($customer->email);
        }
        if (!$this->id_order || !$this->id_module || !$this->secure_key || empty($this->secure_key)) {
            Tools::redirect($redirectLink.(Tools::isSubmit('slowvalidation') ? '&slowvalidation' : ''));
        }
        $this->reference = $order->reference;
        if (!Validate::isLoadedObject($order) || $order->id_customer != $this->context->customer->id || $this->secure_key != $order->secure_key) {
            Tools::redirect($redirectLink);
        }
        $module = Module::getInstanceById((int)($this->id_module));
        if ($order->module != $module->name) {
            Tools::redirect($redirectLink);
        }

        $proximo_gratis="no";
        $address = new Address($order->id_address_delivery);
        if ($order->getOrdersTotalPaid() > 49 && $module->name ==  "redsys" && $address->country == "Spain" && $address->id_state != 321 && $address->id_state != 351 && $address->id_state != 363 && $address->id_state != 364 && $address->id_state != 339){
            $coupon = new CartRule();

            //put here the coupon name + translations
             //ex: if your website is in french (id language = 1) and english (id language = 2)
            $coupon->name = array(4=>"Envi-gratis-".Tools::passwdGen(5));

             //by default cart rule is valid for one client that can use it one time

              //validity
            $coupon->date_from =  date('Y-m-d H:i:s', time());

            //Le asignamos caducidad 1800s = media hora
            $coupon->date_to = date('Y-m-d H:i:s', time() + 1800);
            
             //Partial Use
            $coupon->partial_use = 0;
            $coupon->reduction_tax = 1;
            $coupon->free_shipping = 1;

            //Solo valido para el usuario que ha realizado el pedido
            $coupon->id_customer = $this->context->customer->id;
        
            $proximo_gratis="si";
            
            //this creates the coupon
            $coupon->add();
        }
        $this->context->smarty->assign('module_name', $module->name);
        $this->context->smarty->assign('proximo_gratis', $proximo_gratis);
    }

    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign(array(
            'is_guest' => $this->context->customer->is_guest,
            'HOOK_ORDER_CONFIRMATION' => $this->displayOrderConfirmation(),
            'HOOK_PAYMENT_RETURN' => $this->displayPaymentReturn()
        ));

        if ($this->context->customer->is_guest) {
            $this->context->smarty->assign(array(
                'id_order' => $this->id_order,
                'reference_order' => $this->reference,
                'id_order_formatted' => sprintf('#%06d', $this->id_order),
                'email' => $this->context->customer->email
            ));
            /* If guest we clear the cookie for security reason */
            $this->context->customer->mylogout();
        }


        $productos_vistos_datos = array();

        

        //Si no hay productos en el carro y existe la cookie de productos vistos
        if (isset($this->context->cookie->visited_products)){
                
                $productos_vistos_datos  = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT p.`id_product`, p.`active`, pl.`link_rewrite`, p.`id_category_default`, pl.`name`, cl.`link_rewrite` AS `category_link`,c.`id_category`, c.`position`, i.`id_image`, cl.`name` AS `category_name`, pa.`id_product_attribute`, pa.`price` AS `price_attribute`, p.`price` FROM `ps_product` p LEFT JOIN `ps_product_lang` pl ON (p.`id_product` = pl.`id_product`) LEFT JOIN `ps_category_product` c ON (c.`id_product` = p.`id_product`) LEFT JOIN `ps_category_lang` cl ON (c.`id_category` = cl.`id_category`) LEFT JOIN `ps_image_shop` i ON (i.`id_product` = p.`id_product`) LEFT JOIN `ps_product_attribute` pa ON (pa.`id_product` = p.`id_product`) WHERE p.`id_product` IN ('.$this->context->cookie->visited_products.') AND p.`active` = 1 AND cl.`id_lang` = 4 AND pl.`id_lang` = 4 GROUP BY p.`id_product` ORDER BY pl.`name`');
           
            //Lo transformamos a array para operar
            $array_poductos = explode(',', $this->context->cookie->visited_products);
            $poductos_restantes = 5 - count($array_poductos);

            if ($poductos_restantes > 0){
                 $productos_vistos_datos_extra  = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT p.`id_product`, p.`id_category_default`, p.`active`, pl.`link_rewrite`, pl.`name`, cl.`link_rewrite` AS `category_link`,c.`id_category`, c.`position`, i.`id_image`, cl.`name` AS `category_name`, pa.`id_product_attribute`, pa.`price` AS `price_attribute`, p.`price` FROM `ps_product` p LEFT JOIN `ps_product_lang` pl ON (p.`id_product` = pl.`id_product`) LEFT JOIN `ps_category_product` c ON (c.`id_product` = p.`id_product`) LEFT JOIN `ps_category_lang` cl ON (c.`id_category` = cl.`id_category`) LEFT JOIN `ps_image_shop` i ON (i.`id_product` = p.`id_product`) LEFT JOIN `ps_product_attribute` pa ON (pa.`id_product` = p.`id_product`) WHERE cl.`id_category` = '.$productos_vistos_datos[0]['id_category_default'].' AND p.`active` = 1 AND cl.`id_lang` = 4 AND pl.`id_lang` = 4 GROUP BY p.`id_product` ORDER BY RAND() LIMIT '.$poductos_restantes);

                                 $productos_vistos_datos = array_merge($productos_vistos_datos, $productos_vistos_datos_extra);

            }

           

        }

         $mensaje_enviado = false;
        if (Tools::getValue('carita')){
               $carita_string = "";
               $sugerencia = "NO HA DEJADO MENSAJE";
               if (Tools::getValue('sugerencia')){ $sugerencia = Tools::getValue('sugerencia');}

               if(Tools::getValue('carita') == 1){$carita_string = "buena"; }
               if(Tools::getValue('carita') == 2){$carita_string = "regular"; }
               if(Tools::getValue('carita') == 3){$carita_string = "mala"; }
                
               Mail::Send(
                    (int)(Configuration::get('PS_LANG_DEFAULT')), // defaut language id
                         'contact', // email template file to be use
                         'Cuestionario satisfacción de cliente', // email subject
                          array(
                              '{email}' => Configuration::get('PS_SHOP_EMAIL'), // sender email addresss
                               '{message}' => 'El cliente '.$this->context->customer->email.' Opina que la experiencia ha sido '.$carita_string.' y de sugerencia: '.$sugerencia// email content
                          ),
                 'feedback@semillaslowcost.com', // receiver email address 
                    NULL, //receiver name
                 NULL, //from email address
                 NULL  //from name
              );

             $mensaje_enviado = true;

         }

         $this->context->smarty->assign('mensaje_enviado', $mensaje_enviado);
        $this->context->smarty->assign('productos_vistos_data', $productos_vistos_datos);

        $this->setTemplate(_PS_THEME_DIR_.'order-confirmation.tpl');
    }

    /**
     * Execute the hook displayPaymentReturn
     */
    public function displayPaymentReturn()
    {
        if (Validate::isUnsignedId($this->id_order) && Validate::isUnsignedId($this->id_module)) {
            $params = array();
            $order = new Order($this->id_order);
            $currency = new Currency($order->id_currency);

            if (Validate::isLoadedObject($order)) {
                $params['total_to_pay'] = $order->getOrdersTotalPaid();
                $params['currency'] = $currency->sign;
                $params['objOrder'] = $order;
                $params['currencyObj'] = $currency;

                return Hook::exec('displayPaymentReturn', $params, $this->id_module);
            }
        }
        return false;
    }

    /**
     * Execute the hook displayOrderConfirmation
     */
    public function displayOrderConfirmation()
    {
        if (Validate::isUnsignedId($this->id_order)) {
            $params = array();
            $order = new Order($this->id_order);
            $currency = new Currency($order->id_currency);

            if (Validate::isLoadedObject($order)) {
                $params['total_to_pay'] = $order->getOrdersTotalPaid();
                $params['currency'] = $currency->sign;
                $params['objOrder'] = $order;
                $params['currencyObj'] = $currency;

                return Hook::exec('displayOrderConfirmation', $params);
            }
        }
        return false;
    }
}
