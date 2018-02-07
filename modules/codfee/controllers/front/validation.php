<?php
/**
* Cash On Delivery With Fee
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2014 idnovate.com
*  @license   See above
*/

/**
 * @since 1.5.0
 */
class CodFeeValidationModuleFrontController extends ModuleFrontController
{
	/**
	 * @see FrontController::postProcess()
	 */
	public function postProcess()
	{
		$cart = $this->context->cart;
		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'codfee')
			{
				$authorized = true;
				break;
			}
		if (!$authorized)
			die($this->module->l('This payment method is not available.', 'validation'));

		$cashOnDelivery = new CodFee();
		$fee = (float)Tools::ps_round((float)$cashOnDelivery->getFeeCost($cart), 2);
		$cartcost = $cart->getOrderTotal(true, 3);
		$cartwithoutshipping = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
		$total = $fee + $cartcost;
		$cart->additional_shipping_cost = $fee;

		if (_PS_VERSION_ >= '1.6')
		{
			$cashOnDelivery->validateOrder16((int)$this->context->cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $cashOnDelivery->displayName, null, null, null, false, $cart->secure_key);
			Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key);
		}
		elseif (_PS_VERSION_ >= '1.5.3')
		{
			$cashOnDelivery->validateOrder153((int)$this->context->cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $cashOnDelivery->displayName, null, null, null, false, $cart->secure_key);
			Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key);
		}
		else
		{
			$cashOnDelivery->validateOrder15((int)$this->context->cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $cashOnDelivery->displayName, null, null, null, false, $cart->secure_key);
			Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key);
		}
	}
}
