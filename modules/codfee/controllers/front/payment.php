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
class CodFeePaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$this->display_column_left = false;
		parent::initContent();

		$cart = $this->context->cart;

		$cashOnDelivery = new CodFee();
		$fee = (float)Tools::ps_round((float)$cashOnDelivery->getFeeCost($cart), 2);
		$cartcost = $cart->getOrderTotal(true, 3);
		$cartwithoutshipping = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
		$shippingcost = $cartcost - $cartwithoutshipping;
		$total = $fee + $cartcost;
		$cart->additional_shipping_cost = $fee;

		$authorized = false;

		foreach (Module::getPaymentModules() as $module)
		{
			if ($module['name'] == 'codfee')
			{
				$authorized = true;
				break;
			}
		}

		if (!$authorized)
			die($this->module->l('This payment method is not available.', 'payment'));

		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');

		if (!$cashOnDelivery->_checkCurrency($cart))
			Tools::redirect('index.php?controller=order');

		$conv_rate = (float)$this->context->currency->conversion_rate;

		$carriers = explode(';', Configuration::get('COD_FEE_CARRIERS'));

		$this->context->smarty->assign(array(
			'nbProducts' => $cart->nbProducts(),
			'cartcost' => number_format((float)$cartcost, 2, '.', ''),
			'cartwithoutshipping' => number_format((float)$cartwithoutshipping, 2, '.', ''),
			'shippingcost' => number_format((float)$shippingcost, 2, '.', ''),
			'fee' => number_format((float)$fee, 2, '.', ''),
			'free_fee' => (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2),
			'currency' => new Currency((int)$cart->id_currency),
			'total' => Tools::displayPrice($total, $this->context->currency),
			'carrier' => $cart->id_carrier,
			'carriers' => $carriers,
			'ps_version' => _PS_VERSION_,
			'this_path' => $this->module->getPathUri(),
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
		));

		$this->setTemplate('codfee_val.tpl');
		if (version_compare(_PS_VERSION_, '1.5', '<'))
			$this->setTemplate(__FILE__, 'views/templates/front/codfee_val.tpl');
		else
			$this->setTemplate('codfee_val.tpl');
	}
}
