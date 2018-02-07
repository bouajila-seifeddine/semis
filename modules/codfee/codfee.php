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

class CodFee extends PaymentModule
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'codfee';
		$this->tab = 'payments_gateways';
		$this->version = '1.92.0';
		$this->author = 'idnovate.com';
		$this->module_key = '3b802d29c8d730c7b17aa2970ab57c95';

		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		$this->is_eu_compatible = 1;

		parent::__construct();

		$this->displayName = $this->l('Cash on delivery with fee');
		$this->description = $this->l('Accept cash on delivery payments with extra fee.');

		/* Backward compatibility */
		if (version_compare(_PS_VERSION_, '1.5', '<'))
			require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
	}

	public function install()
	{
		if (_PS_VERSION_ < '1.5')
		{
			if (!parent::install()
				|| !Configuration::updateValue('COD_FEE_TAX', 0)
				|| !Configuration::updateValue('COD_FEE', 0)
				|| !Configuration::updateValue('COD_FREE_FEE', 0)
				|| !Configuration::updateValue('COD_FEE_TYPE', 0)
				|| !Configuration::updateValue('COD_FEE_MIN', 0)
				|| !Configuration::updateValue('COD_FEE_MAX', 0)
				|| !Configuration::updateValue('COD_FEE_MIN_AMOUNT', 0)
				|| !Configuration::updateValue('COD_FEE_MAX_AMOUNT', 0)
				|| !Configuration::updateValue('COD_FEE_CARRIERS', 0)
				|| !Configuration::updateValue('COD_FEE_STATUS', Configuration::get('PS_OS_PREPARATION'))
				|| !Configuration::updateValue('COD_SHOW_CONF', 1)
				|| !$this->registerHook('payment')
				|| !$this->registerHook('paymentReturn')
				|| !$this->registerHook('PDFInvoice')
				|| !$this->registerHook('header')
				|| (Hook::get('displayPaymentEU') && !$this->registerHook('displayPaymentEU')))
				return false;
			return true;
		}
		else
		{
			if (!parent::install()
				|| !Configuration::updateValue('COD_FEE_TAX', 0)
				|| !Configuration::updateValue('COD_FEE', 0)
				|| !Configuration::updateValue('COD_FREE_FEE', 0)
				|| !Configuration::updateValue('COD_FEE_TYPE', 0)
				|| !Configuration::updateValue('COD_FEE_MIN', 0)
				|| !Configuration::updateValue('COD_FEE_MAX', 0)
				|| !Configuration::updateValue('COD_FEE_MIN_AMOUNT', 0)
				|| !Configuration::updateValue('COD_FEE_MAX_AMOUNT', 0)
				|| !Configuration::updateValue('COD_FEE_CARRIERS', 0)
				|| !Configuration::updateValue('COD_FEE_STATUS', Configuration::get('PS_OS_PREPARATION'))
				|| !Configuration::updateValue('COD_SHOW_CONF', 1)
				|| !$this->registerHook('payment')
				|| !$this->registerHook('paymentReturn')
				|| !$this->registerHook('displayPaymentEU')
				|| !$this->registerHook('displayPDFInvoice')
				|| !$this->registerHook('header'))
				return false;

			return true;
		}
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('COD_FEE_TAX')
			|| !Configuration::deleteByName('COD_FEE')
			|| !Configuration::deleteByName('COD_FREE_FEE')
			|| !Configuration::deleteByName('COD_FEE_TYPE')
			|| !Configuration::deleteByName('COD_FEE_MIN')
			|| !Configuration::deleteByName('COD_FEE_MAX')
			|| !Configuration::deleteByName('COD_FEE_MIN_AMOUNT')
			|| !Configuration::deleteByName('COD_FEE_MAX_AMOUNT')
			|| !Configuration::deleteByName('COD_FEE_CARRIERS')
			|| !Configuration::deleteByName('COD_FEE_STATUS')
			|| !Configuration::deleteByName('COD_SHOW_CONF')
			|| !parent::uninstall())
			return false;

		return true;
	}

	public function getContent()
	{
		if (Tools::getValue('submitCOD'))
		{
			if (!count($this->_postErrors))
			{
				Configuration::updateValue('COD_FEE_TAX', (float)str_replace(',', '.', Tools::getValue('fee_tax') ? Tools::getValue('fee_tax') : 0 ));
				Configuration::updateValue('COD_FEE', (float)str_replace(',', '.', Tools::getValue('fee') ? Tools::getValue('fee') : 0 ));
				Configuration::updateValue('COD_FREE_FEE', (float)str_replace(',', '.', Tools::getValue('free_fee') ? Tools::getValue('free_fee') : 0 ));
				Configuration::updateValue('COD_FEE_TYPE', (float)str_replace(',', '.', Tools::getValue('feetype') ? Tools::getValue('feetype') : 0 ));
				Configuration::updateValue('COD_FEE_MIN', (float)str_replace(',', '.', Tools::getValue('feemin') ? Tools::getValue('feemin') : 0 ));
				Configuration::updateValue('COD_FEE_MAX', (float)str_replace(',', '.', Tools::getValue('feemax') ? Tools::getValue('feemax') : 0 ));
				Configuration::updateValue('COD_FEE_MIN_AMOUNT', (float)str_replace(',', '.', Tools::getValue('minimum_amount') ? Tools::getValue('minimum_amount') : '0' ));
				Configuration::updateValue('COD_FEE_MAX_AMOUNT', (float)str_replace(',', '.', Tools::getValue('maximum_amount') ? Tools::getValue('maximum_amount') : 0 ));
				Configuration::updateValue('COD_FEE_CARRIERS', trim(Tools::getValue('id_carriers'), ';'));
				Configuration::updateValue('COD_FEE_STATUS', Tools::getValue('fee_status'));
				Configuration::updateValue('COD_SHOW_CONF', Tools::getValue('show_conf'));

				$this->displayConf();
			}
			else
				$this->displayErrors();
		}

		$this->displayFormSettings();

		$this->context->smarty->assign(array(
			'displayName'	=> $this->displayName,
			'cf_path'		=> $this->_path,
			'html'			=> $this->_html,
		));

		if (version_compare(_PS_VERSION_, '1.5', '<'))
			return $this->display(__FILE__, 'views/templates/hook/admin.tpl');
		else
			return $this->display(__FILE__, 'admin.tpl');
	}

	public function displayFormSettings()
	{
		$conf = Configuration::getMultiple(array('COD_FEE_TAX','COD_FEE','COD_FREE_FEE','COD_FEE_TYPE','COD_FEE_MIN','COD_FEE_MAX','COD_FEE_MIN_AMOUNT','COD_FEE_MAX_AMOUNT','COD_FEE_CARRIERS','COD_FEE_STATUS', 'COD_SHOW_CONF'));
		$fee_tax = Tools::getValue('fee_tax') ? Tools::getValue('fee_tax') : $conf['COD_FEE_TAX'] != '' ? $conf['COD_FEE_TAX'] : '0';
		$fee = Tools::getValue('fee') ? Tools::getValue('fee') :  $conf['COD_FEE'] != '' ? $conf['COD_FEE'] : '0';
		$free_fee = Tools::getValue('free_fee') ? Tools::getValue('free_fee') : $conf['COD_FREE_FEE'] != '' ? $conf['COD_FREE_FEE'] : '0';
		$feetype = Tools::getValue('feetype') ? Tools::getValue('feetype') : $conf['COD_FEE_TYPE'] != '' ? $conf['COD_FEE_TYPE'] : '0';
		$feemin = Tools::getValue('feemin') ? Tools::getValue('feemin') : $conf['COD_FEE_MIN'] != '' ? $conf['COD_FEE_MIN'] : '0';
		$feemax = Tools::getValue('feemax') ? Tools::getValue('feemax') : $conf['COD_FEE_MAX'] != '' ? $conf['COD_FEE_MAX'] : '0';
		$minimum_amount = Tools::getValue('minimum_amount') ? Tools::getValue('minimum_amount') : $conf['COD_FEE_MIN_AMOUNT'] != '' ? $conf['COD_FEE_MIN_AMOUNT'] : '0';
		$maximum_amount = Tools::getValue('maximum_amount') ? Tools::getValue('maximum_amount') : $conf['COD_FEE_MAX_AMOUNT'] != '' ? $conf['COD_FEE_MAX_AMOUNT'] : '0';
		$id_carriers = Tools::getValue('id_carriers') ? Tools::getValue('id_carriers') : $conf['COD_FEE_CARRIERS'] != '' ? $conf['COD_FEE_CARRIERS'] : '';
		$show_conf = Tools::getValue('show_conf') ? Tools::getValue('show_conf') : $conf['COD_SHOW_CONF'] != '' ? $conf['COD_SHOW_CONF'] : '';

		$carriers = null;
		$html_carriers = '';

		if (_PS_VERSION_ < '1.5')
		{
			$carriers = Carrier::getCarriers($this->context->cookie->id_lang, true, false, false, null, PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);
			$statuses = OrderState::getOrderStates((int)$this->context->cookie->id_lang);
		}
		else
		{
			$carriers = Carrier::getCarriers($this->context->language->id, true, false, false, null, Carrier::ALL_CARRIERS);
			$statuses = OrderState::getOrderStates((int)$this->context->language->id);
		}

		$id_carriers_selected_array = explode(';', $id_carriers);
		foreach ($carriers as $carrier)
		{
			$selected = 0;
			if (version_compare(_PS_VERSION_, '1.5', '<'))
				$carrier_id_key = 'id_carrier';
			else
				$carrier_id_key = 'id_reference';

			foreach ($id_carriers_selected_array as $key => $id)
			{
				if ($id_carriers_selected_array[$key] == $carrier[$carrier_id_key])
				{
					$selected = 1;
					continue;
				}
			}
			$html_carriers .= '<div class="row" style="clear:both"><label for="id_carrier'.$carrier[$carrier_id_key].'" >'.$carrier['name'].'</label>
								<div class="margin-form">
									<input type="checkbox" onChange="setIdCarriers();" id="id_carrier'.$carrier[$carrier_id_key].'" name="id_carrier" value="'.$carrier[$carrier_id_key].'" '.($selected == 1 ? 'checked="checked" ' : '').'/>
								</div></div>';
		}

		$html_carriers .= '<input type="hidden" id="id_carriers" name="id_carriers" value="'.trim($id_carriers, ';').'"/>';

		$status_html = '';
		$default_status = Configuration::get('PS_OS_PREPARATION');
		foreach ($statuses as $status)
		{
			if (Configuration::get('COD_FEE_STATUS') == $status['id_order_state'])
				$default_status = $status['name'];
			if (Configuration::get('COD_FEE_STATUS') != $status['id_order_state'])
				$status_html .= '<option value='.$status['id_order_state'].'>'.$status['name'].'</option>';
		}

		$default_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));

		$this->_html .= '
		<script type="text/javascript">
			function viewOptions(select){
				if (select.value == "0")
				{
					document.getElementById(\'amount1\').style.display=\'block\';
					document.getElementById(\'amount2\').style.display=\'block\';
					document.getElementById(\'percentage1\').style.display=\'none\';
					document.getElementById(\'percentage2\').style.display=\'none\';
					document.getElementById(\'minimumfee1\').style.display=\'none\';
					document.getElementById(\'minimumfee2\').style.display=\'none\';
					document.getElementById(\'maximumfee1\').style.display=\'none\';
					document.getElementById(\'maximumfee2\').style.display=\'none\';
				}
				if (select.value == "1")
				{
					document.getElementById(\'amount1\').style.display=\'none\';
					document.getElementById(\'amount2\').style.display=\'none\';
					document.getElementById(\'percentage1\').style.display=\'block\';
					document.getElementById(\'percentage2\').style.display=\'block\';
					document.getElementById(\'minimumfee1\').style.display=\'block\';
					document.getElementById(\'minimumfee2\').style.display=\'block\';
					document.getElementById(\'maximumfee1\').style.display=\'block\';
					document.getElementById(\'maximumfee2\').style.display=\'block\';
				}
				if (select.value == "2")
				{
					document.getElementById(\'amount1\').style.display=\'block\';
					document.getElementById(\'amount2\').style.display=\'block\';
					document.getElementById(\'percentage1\').style.display=\'block\';
					document.getElementById(\'percentage2\').style.display=\'block\';
					document.getElementById(\'minimumfee1\').style.display=\'block\';
					document.getElementById(\'minimumfee2\').style.display=\'block\';
					document.getElementById(\'maximumfee1\').style.display=\'block\';
					document.getElementById(\'maximumfee2\').style.display=\'block\';
				}
			}
			function setIdCarriers(){
				var ids = "";
				$("input[name=\'id_carrier\']").each(function(){
					if ($(this).attr("checked"))
					{
						ids = $(this).attr("value")+";"+ids;
					}
				});
				document.getElementById(\'id_carriers\').value=ids;
			}
		</script>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="form" id="form">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Fee settings').'</legend>
			<label>'.$this->l('Type:').'</label>
			<div class="margin-form">
				<select name="feetype" onChange="viewOptions(this)">
				<option value="0" '.(!$feetype || $feetype == '0' ? 'selected' : '').' >'.$this->l('Fixed').'</option>
				<option value="1" '.($feetype == '1' ? 'selected' : '').' >'.$this->l('Percentage').'</option>
				<option value="2" '.($feetype == '2' ? 'selected' : '').' >'.$this->l('Fixed').'+'.$this->l('Percentage').'</option>
				</select>
			</div>
			<label id="amount1" '.(!$feetype || $feetype == '0' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Amount:').'</label>
			<div class="margin-form">
				<div id="amount2" '.(!$feetype || $feetype == '0' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="fee" value="'.htmlentities($fee, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Fixed amount to add to the cost of the order.').'</span></div>
			</div>
			<label id="percentage1" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Percentage:').'</label>
			<div class="margin-form">
				<div id="percentage2" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="fee_tax" value="'.htmlentities($fee_tax, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">%</span><br /><span>'.$this->l('Percentage to add to the cost of the order.').'</span></div>
			</div>
			<label id="minimumfee1" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Minimum Fee:').'</label>
			<div class="margin-form">
				<div id="minimumfee2" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="feemin" value="'.htmlentities($feemin, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Minimum fee to add to the cost of the order.').'</span></div>
			</div>
			<label id="maximumfee1" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Maximum Fee:').'</label>
			<div class="margin-form">
				<div id="maximumfee2" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="feemax" value="'.htmlentities($feemax, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Maximum fee to add to the cost of the order.').'</span></div>
			</div>
			<center><input type="submit" name="submitCOD" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		<br />
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Order amount settings').'</legend>
			<label id="minimumamount">'.$this->l('Minimum amount:').'</label>
			<div class="margin-form">
				<div id="minimumamount" ><input type="text" size="10" name="minimum_amount" value="'.htmlentities($minimum_amount, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Minimum amount to be available this payment method (0 to disable).').'</span></div>
			</div>
			<label id="maximumamount">'.$this->l('Maximum amount:').'</label>
			<div class="margin-form">
				<div id="maximumamount" ><input type="text" size="10" name="maximum_amount" value="'.htmlentities($maximum_amount, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Maximum amount to be available this payment method (0 to disable).').'</span></div>
			</div>
			<label id="freefee">'.$this->l('Minimum amount for free fee:').'</label>
			<div class="margin-form">
				<div id="freefee" ><input type="text" size="10" name="free_fee" value="'.htmlentities($free_fee, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Amount from which the fee is free (0 to disable).').'</span></div>
			</div>
			<br /><center><input type="submit" name="submitCOD" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		<br />
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Delivery options settings').'</legend>
			<p class="clear">'.$this->l('Select delivery options with enabled cash on delivery payment option:').'</p><br />
			'.$html_carriers.'
					<br />
			<label>'.$this->l('Show confirmation page').'</label>
			<div class="margin-form">
				<input type="checkbox" id="show_conf" name="show_conf" value="1" '.($show_conf == 1 ? 'checked="checked" ' : '').'/>
			</div>
			<br />
			<label>'.$this->l('Initial order status').'</label>
			<div class="margin-form">
				<select name="fee_status">
					<option value="'.(Configuration::get('COD_FEE_STATUS') ? Configuration::get('COD_FEE_STATUS') : '').'">'.$default_status.'</option>
					'.$status_html.'
				</select>
				<p class="clear">'.$this->l('Initial status of validated order.').'</p>
			</div>
			<center><input type="submit" name="submitCOD" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		</form><br />';
	}

	public function displayConf()
	{
		$this->_html .= '
		<div class="bootstrap"><div class="conf confirm alert alert-success">
			'.$this->l('Settings updated').'
		</div></div>';
	}

	public function displayErrors()
	{
		$nbErrors = count($this->_postErrors);
		$this->_html .= '
		<div class="alert error">
			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
			<ol>';
		foreach ($this->_postErrors as $error)
			$this->_html .= '<li>'.$error.'</li>';
		$this->_html .= '
			</ol>
		</div>';
	}

	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return;

		$state = $params['objOrder']->getCurrentState();

		if ($state == Configuration::get('COD_FEE_STATUS') || $state == Configuration::get('PS_OS_OUTOFSTOCK'))
		{
			$this->context->smarty->assign(array(
				'total'			=> Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
				'success'		=> true,
				'id_order'		=> $params['objOrder']->id
			));
		}
		else
			$this->context->smarty->assign('status', false);

		if (version_compare(_PS_VERSION_, '1.5', '<'))
			return $this->display(__FILE__, 'views/templates/hook/payment_return.tpl');
		else
			return $this->display(__FILE__, 'payment_return.tpl');
	}

	public function hookHeader($params)
	{
		if (_PS_VERSION_ < '1.5')
		{
			Tools::addCSS($this->_path.'css/codfee_1.4.css', 'all');
			Tools::addJS(($this->_path).'js/codfee.js');
		}
		elseif (_PS_VERSION_ >= '1.6')
		{
			$this->context->controller->addCSS($this->_path.'css/codfee_1.6.css', 'all');
			$this->context->controller->addJS(($this->_path).'js/codfee.js');
		}
		else
		{
			$this->context->controller->addCSS($this->_path.'css/codfee_1.5.css', 'all');
			$this->context->controller->addJS(($this->_path).'js/codfee.js');
		}
	}

	public function hookPayment($params)
	{
		$minimum_amount = Configuration::get('COD_FEE_MIN_AMOUNT');
		$maximum_amount = Configuration::get('COD_FEE_MAX_AMOUNT');
		$fee = (float)Tools::ps_round((float)$this->getFeeCost($params['cart']), 2);
		$cartcost = $params['cart']->getOrderTotal(true, 3);
		$cartwithoutshipping = $params['cart']->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
		$shippingcost = $cartcost - $cartwithoutshipping;
		$total = $fee + $cartcost;
		$id_carriers_selected_array = explode(';', Configuration::get('COD_FEE_CARRIERS'));
		$carrier_selected = new Carrier($params['cart']->id_carrier);

		$this->context->smarty->assign(array(
			'this_path' 		=> $this->_path,
			'cartcost' 			=> number_format((float)$cartcost, 2, '.', ''),
			'fee' 				=> number_format((float)$fee, 2, '.', ''),
			'minimum_amount' 	=> number_format((float)$minimum_amount, 2, '.', ''),
			'maximum_amount' 	=> number_format((float)$maximum_amount, 2, '.', ''),
			'shippingcost' 		=> number_format((float)$shippingcost, 2, '.', ''),
			'total' 			=> number_format((float)$total, 2, '.', ''),
			'show_conf'			=> Configuration::get('COD_SHOW_CONF'),
			'carriers_array' 	=> $id_carriers_selected_array,
			'carrier_selected' 	=> version_compare(_PS_VERSION_, '1.5', '<') ? $carrier_selected->id : $carrier_selected->id_reference,
			'this_path_ssl'		=> (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
		));

		if (version_compare(_PS_VERSION_, '1.5', '<'))
			return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
		else
			return $this->display(__FILE__, 'payment.tpl');
	}

	public function hookDisplayPaymentEU($params)
	{
		if (!$this->active)
			return;

		foreach ($params['cart']->getProducts() as $product)
		{
			$pd = ProductDownload::getIdFromIdProduct((int)($product['id_product']));
			if ($pd && Validate::isUnsignedInt($pd))
				return false;
		}

		$minimum_amount = Configuration::get('COD_FEE_MIN_AMOUNT');
		$maximum_amount = Configuration::get('COD_FEE_MAX_AMOUNT');
		$fee = (float)Tools::ps_round((float)$this->getFeeCost($params['cart']), 2);
		$cartcost = $params['cart']->getOrderTotal(true, 3);
		$cartwithoutshipping = $params['cart']->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
		$shippingcost = $cartcost - $cartwithoutshipping;
		$total = $fee + $cartcost;
		$id_carriers_selected_array = explode(';', Configuration::get('COD_FEE_CARRIERS'));
		$carrier_selected = new Carrier($params['cart']->id_carrier);
		$currency = (int)$params['cart']->id_currency;

		return array(
			'this_path' => $this->_path,
			'cartcost' => number_format((float)$cartcost, 2, '.', ''),
			'fee' => number_format((float)$fee, 2, '.', ''),
			'minimum_amount' => number_format((float)$minimum_amount, 2, '.', ''),
			'maximum_amount' => number_format((float)$maximum_amount, 2, '.', ''),
			'shippingcost' => number_format((float)$shippingcost, 2, '.', ''),
			'total' => number_format((float)$total, 2, '.', ''),
			'carriers_array' => $id_carriers_selected_array,
			'carrier_selected' => version_compare(_PS_VERSION_, '1.5', '<') ? $carrier_selected->id : $carrier_selected->id_reference,
			'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/',
			'cta_text' => $this->l('Pay with cash on delivery:').' '.$this->convertSign(Tools::displayPrice($cartcost, $currency, false)).' + '.$this->convertSign(Tools::displayPrice($fee, $currency, false)).' '.$this->l('(COD fee)').' = '.$this->convertSign(Tools::displayPrice($total, $currency, false)),
			'logo' => Media::getMediaPath(dirname(__FILE__).'/img/codfee.gif'),
			'action' => $this->context->link->getModuleLink($this->name, 'validation', array('confirm' => true))
		);
	}

	/**
	* Displays the COD fee on the invoice (PS 1.4 versions)
	*
	* @param $params contains an instance of OrderInvoice
	* @return string
	*
	*/
	public function hookPDFInvoice($params)
	{
		$order = new Order($params['id_order']);
		$currency = new Currency($order->id_currency);
		$cart = new Cart($order->id_cart);
		$codfee = number_format((float)$this->getFeeCost($cart, 2), 2, '.', '');

		if ($order->module == 'codfee' && $codfee > 0)
		{
			$pdf = $params['pdf'];
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(0, 40, utf8_decode($this->l('Cash on delivery fee applied: ')).$this->convertSign(Tools::displayPrice($codfee, $currency, false)), 0, 0, 'C');
			$pdf->Ln(5);
		}
	}

	/**
	* Displays the COD fee on the invoice (PS 1.5 versions)
	*
	* @param $params contains an instance of OrderInvoice
	* @return string
	*
	*/
	public function hookDisplayPDFInvoice($params)
	{
		$order_invoice = $params['object'];
		if (!($order_invoice instanceof OrderInvoice))
			return;

		$order = new Order((int)$order_invoice->id_order);
		$currency = new Currency($order->id_currency);
		$cart = new Cart($order->id_cart);
		$codfee = number_format((float)$this->getFeeCost($cart, 2), 2, '.', '');

		$return = '';
		if ($order->module == 'codfee' && $codfee > 0)
			$return = sprintf($this->l('Cash on delivery fee applied: ').Tools::displayPrice($codfee, $currency, false));

		return $return;
	}

	public function execPayment($cart)
	{
		if (!$this->_checkCurrency($cart))
			return;
		else
		{
			$cashOnDelivery = new CodFee();
			$fee = (float)Tools::ps_round((float)$cashOnDelivery->getFeeCost($cart), 2);
			$cartcost = $cart->getOrderTotal(true, 3);
			$cartwithoutshipping = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
			$shippingcost = $cartcost - $cartwithoutshipping;
			$total = $fee + $cartcost;
			$cart->additional_shipping_cost = $fee;

			if (Tools::isSubmit('paymentSubmit'))
			{
				$authorized = false;
				if (version_compare(_PS_VERSION_, '1.4.4', '>='))
					$modules = Module::getPaymentModules();
				else
					$modules = $this->getPaymentModules();

				foreach ($modules as $module)
				{
					if ($module['name'] == 'codfee')
					{
						$authorized = true;
						break;
					}
				}

				if (!$authorized)
					die($this->module->l('This payment method is not available.'));

				$id_currency = (int)Tools::getValue('id_currency');
				if (_PS_VERSION_ < '1.5')
				{
					$this->validateOrder14($cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $this->displayName, null, null, $id_currency, false, $cart->secure_key);
					//Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$this->id.'&id_order='.$this->CodFee->currentOrder.'&key='.$cart->secure_key);
				}
				else if (_PS_VERSION_ >= '1.6')
				{
					$this->validateOrder16((int)$this->context->cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $this->displayName, null, null, null, false, $cart->secure_key);
					Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$this->id.'&id_order='.$this->CodFee->currentOrder.'&key='.$cart->secure_key);
				}
				else if (_PS_VERSION_ >= '1.5.3')
				{
					$this->validateOrder153((int)$this->context->cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $this->displayName, null, null, null, false, $cart->secure_key);
					Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$this->id.'&id_order='.$this->CodFee->currentOrder.'&key='.$cart->secure_key);
				}
				else
				{
					$this->validateOrder15((int)$this->context->cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $this->displayName, null, null, null, false, $cart->secure_key);
					Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$this->id.'&id_order='.$this->CodFee->currentOrder.'&key='.$cart->secure_key);
				}

				$this->context->smarty->assign(array(
					'total'			=> $total,
					'success'		=> true,
					'currency'		=> new Currency((int)$cart->id_currency),
				));

				if (version_compare(_PS_VERSION_, '1.5', '<'))
					return $this->display(__FILE__, 'views/templates/hook/payment_return.tpl');
				else
					return $this->display(__FILE__, 'payment_return.tpl');
			}

			if (_PS_VERSION_ < '1.5')
			{
				$currency = new Currency($cart->id_currency);
				$conv_rate = (float)$currency->conversion_rate;
			}
			else
				$conv_rate = (float)$this->context->currency->conversion_rate;

			$carriers = explode(';', Configuration::get('COD_FEE_CARRIERS'));

			$this->context->smarty->assign(array(
				'this_path' => $this->_path,
				'nbProducts' => $cart->nbProducts(),
				'cartcost' => number_format((float)$cartcost, 2, '.', ''),
				'cartwithoutshipping' => number_format((float)$cartwithoutshipping, 2, '.', ''),
				'shippingcost' => number_format((float)$shippingcost, 2, '.', ''),
				'fee' => number_format((float)$fee, 2, '.', ''),
				'free_fee' => (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2),
				'currency' => new Currency((int)$cart->id_currency),
				'total' => number_format((float)$total, 2, '.', ''),
				'carrier' => $cart->id_carrier,
				'carriers' => $carriers,
				'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/codfee/'
			));

			$this->context->smarty->assign('this_path', __PS_BASE_URI__.'modules/codfee/');

			if (version_compare(_PS_VERSION_, '1.5', '<'))
				return $this->display(__FILE__, 'views/templates/front/codfee_val.tpl');
			else
				return $this->display(__FILE__, 'codfee_val.tpl');

		}
	}

	public function getFeeCost($cart)
	{
		if (_PS_VERSION_ < '1.5')
		{
			$currency = new Currency($cart->id_currency);
			$conv_rate = (float)$currency->conversion_rate;
		}
		else
			$conv_rate = (float)$this->context->currency->conversion_rate;

		if (Configuration::get('COD_FEE_TYPE') == 0)
		{
			$free_fee = (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2);
			$cartvalue = (float)$cart->getOrderTotal(true, 3);

			if (($free_fee < $cartvalue) && ($free_fee != 0))
				return (float)0;
			else
				return (float)Tools::ps_round((float)Configuration::get('COD_FEE') * (float)$conv_rate, 2);
		}
		else if (Configuration::get('COD_FEE_TYPE') == 1)
		{
			$minimalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MIN') * (float)$conv_rate, 2);
			$maximalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MAX') * (float)$conv_rate, 2);
			$free_fee = (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2);
			$cartvalue = (float)$cart->getOrderTotal(true, 3);
			$percent = (float)Configuration::get('COD_FEE_TAX');
			$percent = $percent / 100;
			$fee = $cartvalue * $percent;

			if (($fee < $minimalfee) && ($minimalfee != 0))
				$fee = $minimalfee;
			elseif (($fee > $maximalfee) && ($maximalfee != 0))
				$fee = $maximalfee;

			if (($free_fee < $cartvalue) && ($free_fee != 0))
				$fee = 0;

			return (float)$fee;
		}
		else if (Configuration::get('COD_FEE_TYPE') == 2)
		{
			$minimalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MIN') * (float)$conv_rate, 2);
			$maximalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MAX') * (float)$conv_rate, 2);
			$free_fee = (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2);
			$cartvalue = (float)$cart->getOrderTotal(true, 3);
			$percent = (float)Configuration::get('COD_FEE_TAX');
			$percent = $percent / 100;
			$fee_tax = (float)Tools::ps_round((float)Configuration::get('COD_FEE') * (float)$conv_rate, 2);
			$fee = ($cartvalue * $percent) + $fee_tax;

			if (($fee < $minimalfee) && ($minimalfee != 0))
				$fee = $minimalfee;
			else if (($fee > $maximalfee) && ($maximalfee != 0))
				$fee = $maximalfee;

			if (($free_fee < $cartvalue) && ($free_fee != 0))
				$fee = 0;

			return (float)$fee;
		}
	}

	public function _checkCurrency($cart)
	{
		$currency_order = new Currency((int)$cart->id_currency);
		$currencies_module = $this->getCurrency();

		if (is_array($currencies_module))
			foreach ($currencies_module as $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;

		return false;
	}

	/**
	* Validate an order in database
	* Function called from a payment module
	*
	* @param integer $id_cart Value
	* @param integer $id_order_state Value
	* @param float $amount_paid Amount really paid by customer (in the default currency)
	* @param string $payment_method Payment method (eg. 'Credit card')
	* @param string $message Message to attach to order
	*/
	public function validateOrder16($id_cart, $id_order_state, $amount_paid, $codfee, $payment_method = 'Unknown',
		$message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false,
		$secure_key = false, Shop $shop = null)
	{
		$this->context->cart = new Cart($id_cart);
		$this->context->customer = new Customer($this->context->cart->id_customer);
		$this->context->language = new Language($this->context->cart->id_lang);
		$this->context->shop = ($shop ? $shop : new Shop($this->context->cart->id_shop));
		ShopUrl::resetMainDomainCache();
		$id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
		$this->context->currency = new Currency($id_currency, null, $this->context->shop->id);
		if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
			$context_country = $this->context->country;
		$order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
		if (!Validate::isLoadedObject($order_status))
			throw new PrestaShopException('Can\'t load Order state status');
		if (!$this->active)
			die(Tools::displayError());
		// Does order already exists ?
		if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false)
		{
			if ($secure_key !== false && $secure_key != $this->context->cart->secure_key)
				die(Tools::displayError());
			// For each package, generate an order
			$delivery_option_list = $this->context->cart->getDeliveryOptionList();
			$package_list = $this->context->cart->getPackageList();
			$cart_delivery_option = $this->context->cart->getDeliveryOption();
			// If some delivery options are not defined, or not valid, use the first valid option
			foreach ($delivery_option_list as $id_address => $package)
				if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package))
					foreach ($package as $key => $val)
					{
						$cart_delivery_option[$id_address] = $key;
						break;
					}
			$order_list = array();
			$order_detail_list = array();
			$reference = Order::generateReference();
			$this->currentOrderReference = $reference;
			$order_creation_failed = false;
			$cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);
			foreach ($cart_delivery_option as $id_address => $key_carriers)
				foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data)
					foreach ($data['package_list'] as $id_package)
					{
						// Rewrite the id_warehouse
						$package_list[$id_address][$id_package]['id_warehouse'] = (int)$this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int)$id_carrier);
						$package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
					}
			// Make sure CarRule caches are empty
			CartRule::cleanCache();

			foreach ($package_list as $id_address => $packageByAddress)
				foreach ($packageByAddress as $id_package => $package)
				{
					$order = new Order();
					$order->product_list = $package['product_list'];

					if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
					{
						$address = new Address($id_address);
						$this->context->country = new Country($address->id_country, $this->context->cart->id_lang);
					}

					$carrier = null;
					if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier']))
					{
						$carrier = new Carrier($package['id_carrier'], $this->context->cart->id_lang);
						$order->id_carrier = (int)$carrier->id;
						$id_carrier = (int)$carrier->id;
					}
					else
					{
						$order->id_carrier = 0;
						$id_carrier = 0;
					}

					$order->id_customer = (int)$this->context->cart->id_customer;
					$order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
					$order->id_address_delivery = (int)$id_address;
					$order->id_currency = $this->context->currency->id;
					$order->id_lang = (int)$this->context->cart->id_lang;
					$order->id_cart = (int)$this->context->cart->id;
					$order->reference = $reference;
					$order->id_shop = (int)$this->context->shop->id;
					$order->id_shop_group = (int)$this->context->shop->id_shop_group;
					$order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
					$order->payment = $payment_method;
					if (isset($this->name))
						$order->module = $this->name;
					$order->recyclable = $this->context->cart->recyclable;
					$order->gift = (int)$this->context->cart->gift;
					$order->gift_message = $this->context->cart->gift_message;
					$order->mobile_theme = $this->context->cart->mobile_theme;
					$order->conversion_rate = $this->context->currency->conversion_rate;
					$amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
					$order->total_paid_real = 0;

					$order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
					$order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
					$order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
					$order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
					$order->total_discounts = $order->total_discounts_tax_incl;

					$codfee_wt = 0;
					if (!is_null($carrier) && Validate::isLoadedObject($carrier))
					{
						$order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
						$codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
					}
					$order->total_shipping_tax_excl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 4);
					$order->total_shipping_tax_incl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 4);
					$order->total_shipping = $order->total_shipping_tax_incl;

					$order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
					$order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
					$order->total_wrapping = $order->total_wrapping_tax_incl;

					$order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, 2);
					$order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, 2);
					$order->total_paid = $order->total_paid_tax_incl;
					$order->invoice_date = '0000-00-00 00:00:00';
					$order->delivery_date = '0000-00-00 00:00:00';
					$order->codfee = $codfee;
					// Creating order
					$result = $order->add();
					if (!$result)
						throw new PrestaShopException('Can\'t save Order');
					// Amount paid by customer is not the right one -> Status = payment error
					// We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
					// if ($order->total_paid != $order->total_paid_real)
					// We use number_format in order to compare two string
					if ($order_status->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2))
						$id_order_state = Configuration::get('PS_OS_ERROR');
					$order_list[] = $order;
					// Insert new Order detail list using cart for the current order
					$order_detail = new OrderDetail(null, null, $this->context);
					$order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
					$order_detail_list[] = $order_detail;
					// Adding an entry in order_carrier table
					if (!is_null($carrier))
					{
						$order_carrier = new OrderCarrier();
						$order_carrier->id_order = (int)$order->id;
						$order_carrier->id_carrier = (int)$id_carrier;
						$order_carrier->weight = (float)$order->getTotalWeight();
						$order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
						$order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
						$order_carrier->add();
					}
				}

			// The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
			if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
				$this->context->country = $context_country;
			// Register Payment only if the order status validate the order
			if ($order_status->logable)
			{
				// $order is the last order loop in the foreach
				// The method addOrderPayment of the class Order make a create a paymentOrder
				//	linked to the order reference and not to the order id
				if (isset($extra_vars['transaction_id']))
					$transaction_id = $extra_vars['transaction_id'];
				else
					$transaction_id = null;

				if (!$order->addOrderPayment($amount_paid, null, $transaction_id))
					throw new PrestaShopException('Can\'t save Order Payment');
			}
			// Next !
			$only_one_gift = false;
			$cart_rule_used = array();
			$products = $this->context->cart->getProducts();
			$cart_rules = $this->context->cart->getCartRules();

			// Make sure CarRule caches are empty
			CartRule::cleanCache();

			foreach ($order_detail_list as $key => $order_detail)
			{
				$order = $order_list[$key];
				if (!$order_creation_failed && isset($order->id))
				{
					if (!$secure_key)
						$message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
					// Optional message to attach to this order
					if (isset($message) & !empty($message))
					{
						$msg = new Message();
						$message = strip_tags($message, '<br>');
						if (Validate::isCleanHtml($message))
						{
							$msg->message = $message;
							$msg->id_order = (int)$order->id;
							$msg->private = 1;
							$msg->add();
						}
					}
					// Insert new Order detail list using cart for the current order
					//$orderDetail = new OrderDetail(null, null, $this->context);
					//$orderDetail->createList($order, $this->context->cart, $id_order_state);
					// Construct order detail table for the email
					$products_list = '';
					$virtual_product = true;
					foreach ($order->product_list as $key => $product)
					{
						$price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
						$price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
						$customization_quantity = 0;
						$customized_datas = Product::getAllCustomizedDatas((int)$order->id_cart);
						if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']]))
						{
							$customization_text = '';
							foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization)
							{
								if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD]))
									foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text)
										$customization_text .= $text['name'].': '.$text['value'].'<br />';
								if (isset($customization['datas'][Product::CUSTOMIZE_FILE]))
									$customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])).'<br />';
								$customization_text .= '---<br />';
							}
							$customization_text = rtrim($customization_text, '---<br />');
							$customization_quantity = (int)$product['customization_quantity'];
							$products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
								<td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
								<td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.Tools::displayError('Customized').(!empty($customization_text) ? ' - '.$customization_text : '').'</strong></td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
								<td style="padding: 0.6em 0.4em; width: 15%;">'.$customization_quantity.'</td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
							</tr>';
						}
						if (!$customization_quantity || (int)$product['cart_quantity'] > $customization_quantity)
							$products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
								<td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
								<td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod((int)$this->context->customer->id) == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
								<td style="padding: 0.6em 0.4em; width: 15%;">'.((int)$product['cart_quantity'] - $customization_quantity).'</td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(((int)$product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
							</tr>';
						// Check if is not a virutal product for the displaying of shipping
						if (!$product['is_virtual'])
							$virtual_product &= false;
					} // end foreach ($products)
					$cart_rules_list = '';
					$total_reduction_value_ti = 0;
					$total_reduction_value_tex = 0;
					foreach ($cart_rules as $cart_rule)
					{
						$package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
						$values = array(
							'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package),
							'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package)
						);
						// If the reduction is not applicable to this order, then continue with the next one
						if (!$values['tax_excl'])
							continue;
						/* IF
						** - This is not multi-shipping
						** - The value of the voucher is greater than the total of the order
						** - Partial use is allowed
						** - This is an "amount" reduction, not a reduction in % or a gift
						** THEN
						** The voucher is cloned with a new value corresponding to the remainder
						*/
						if (count($order_list) == 1 && $values['tax_incl'] > ($order->total_products_wt - $total_reduction_value_ti) && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0)
						{
							// Create a new voucher from the original
							$voucher = new CartRule($cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
							unset($voucher->id);
							// Set a new voucher code
							$voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
							if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2])
								$voucher->code = preg_replace('/'.$matches[0].'$/', '-'.((int)$matches[1] + 1), $voucher->code);
							// Set the new voucher value
							if ($voucher->reduction_tax)
								$voucher->reduction_amount = $values['tax_incl'] - ($order->total_products_wt - $total_reduction_value_ti) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_incl : 0);
							else
								$voucher->reduction_amount = $values['tax_excl'] - ($order->total_products - $total_reduction_value_tex) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_excl : 0);
							$voucher->id_customer = $order->id_customer;
							$voucher->quantity = 1;
							$voucher->quantity_per_user = 1;
							$voucher->free_shipping = 0;
							if ($voucher->add())
							{
								// If the voucher has conditions, they are now copied to the new voucher
								CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);
								$params = array(
									'{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
									'{voucher_num}' => $voucher->code,
									'{firstname}' => $this->context->customer->firstname,
									'{lastname}' => $this->context->customer->lastname,
									'{id_order}' => $order->reference,
									'{order_name}' => $order->getUniqReference()
								);
								Mail::Send(
									(int)$order->id_lang,
									'voucher',
									sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
									$params,
									$this->context->customer->email,
									$this->context->customer->firstname.' '.$this->context->customer->lastname,
									null, null, null, null, _PS_MAIL_DIR_, false, (int)$order->id_shop
								);
							}
							$values['tax_incl'] -= $values['tax_incl'] - $order->total_products_wt;
							$values['tax_excl'] -= $values['tax_excl'] - $order->total_products;
						}
						$total_reduction_value_ti += $values['tax_incl'];
						$total_reduction_value_tex += $values['tax_excl'];
						$order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values, 0, $cart_rule['obj']->free_shipping);
						if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used))
						{
							$cart_rule_used[] = $cart_rule['obj']->id;
							// Create a new instance of Cart Rule without id_lang, in order to update its quantity
							$cart_rule_to_update = new CartRule($cart_rule['obj']->id);
							$cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
							$cart_rule_to_update->update();
						}
						$cart_rules_list .= '
						<tr>
							<td colspan="4" style="padding:0.6em 0.4em;text-align:right">'.Tools::displayError('Voucher name:').' '.$cart_rule['obj']->name.'</td>
							<td style="padding:0.6em 0.4em;text-align:right">'.($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false).'</td>
						</tr>';
					}
					// Specify order id for message
					$old_message = Message::getMessageByCartId((int)$this->context->cart->id);
					if ($old_message)
					{
						$update_message = new Message((int)$old_message['id_message']);
						$update_message->id_order = (int)$order->id;
						$update_message->update();
						// Add this message in the customer thread
						$customer_thread = new CustomerThread();
						$customer_thread->id_contact = 0;
						$customer_thread->id_customer = (int)$order->id_customer;
						$customer_thread->id_shop = (int)$this->context->shop->id;
						$customer_thread->id_order = (int)$order->id;
						$customer_thread->id_lang = (int)$this->context->language->id;
						$customer_thread->email = $this->context->customer->email;
						$customer_thread->status = 'open';
						$customer_thread->token = Tools::passwdGen(12);
						$customer_thread->add();
						$customer_message = new CustomerMessage();
						$customer_message->id_customer_thread = $customer_thread->id;
						$customer_message->id_employee = 0;
						$customer_message->message = $update_message->message;
						$customer_message->private = 0;
						if (!$customer_message->add())
							$this->errors[] = Tools::displayError('An error occurred while saving message');
					}
					// Hook validate order
					Hook::exec('actionValidateOrder', array(
						'cart' => $this->context->cart,
						'order' => $order,
						'customer' => $this->context->customer,
						'currency' => $this->context->currency,
						'orderStatus' => $order_status
					));
					foreach ($this->context->cart->getProducts() as $product)
						if ($order_status->logable)
							ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);

					// Switch to back order if needed
					if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState())
					{
						$history = new OrderHistory();
						$history->id_order = (int)$order->id;
						$history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
						$history->addWithemail();
					}
					else
					{
						$new_history = new OrderHistory();
						$new_history->id_order = (int)$order->id;
						$new_history->changeIdOrderState((int)$id_order_state, $order, true);
						$new_history->addWithemail(true, $extra_vars);
					}
					unset($order_detail);
					// Order is reloaded because the status just changed
					$order = new Order($order->id);
					// Send an e-mail to customer (one order = one email)
					if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id)
					{
						$invoice = new Address($order->id_address_invoice);
						$delivery = new Address($order->id_address_delivery);
						$delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
						$invoice_state = $invoice->id_state ? new State($invoice->id_state) : false;
						$data = array(
						'{firstname}' => $this->context->customer->firstname,
						'{lastname}' => $this->context->customer->lastname,
						'{email}' => $this->context->customer->email,
						'{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
						'{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
						'{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
							'firstname'	=> '<span style="font-weight:bold;">%s</span>',
							'lastname'	=> '<span style="font-weight:bold;">%s</span>'
						)),
						'{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
								'firstname'	=> '<span style="font-weight:bold;">%s</span>',
								'lastname'	=> '<span style="font-weight:bold;">%s</span>'
						)),
						'{delivery_company}' => $delivery->company,
						'{delivery_firstname}' => $delivery->firstname,
						'{delivery_lastname}' => $delivery->lastname,
						'{delivery_address1}' => $delivery->address1,
						'{delivery_address2}' => $delivery->address2,
						'{delivery_city}' => $delivery->city,
						'{delivery_postal_code}' => $delivery->postcode,
						'{delivery_country}' => $delivery->country,
						'{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
						'{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
						'{delivery_other}' => $delivery->other,
						'{invoice_company}' => $invoice->company,
						'{invoice_vat_number}' => $invoice->vat_number,
						'{invoice_firstname}' => $invoice->firstname,
						'{invoice_lastname}' => $invoice->lastname,
						'{invoice_address2}' => $invoice->address2,
						'{invoice_address1}' => $invoice->address1,
						'{invoice_city}' => $invoice->city,
						'{invoice_postal_code}' => $invoice->postcode,
						'{invoice_country}' => $invoice->country,
						'{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
						'{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
						'{invoice_other}' => $invoice->other,
						'{order_name}' => $order->getUniqReference(),
						'{date}' => Tools::displayDate(date('Y-m-d H:i:s'), null, 1),
						'{carrier}' => $virtual_product ? Tools::displayError('No carrier') : $carrier->name,
						'{payment}' => Tools::substr($order->payment, 0, 32),
						'{products}' => $this->formatProductAndVoucherForEmail($products_list),
						'{discounts}' => $this->formatProductAndVoucherForEmail($cart_rules_list),
						'{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false),
						'{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
						'{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
						'{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
						'{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.$this->l('COD fee included'),
						'{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false));
						if (is_array($extra_vars))
							$data = array_merge($data, $extra_vars);
						// Join PDF invoice
						if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number)
						{
							$pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
							$file_attachement['content'] = $pdf->render(false);
							$file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang, null, $order->id_shop).sprintf('%06d', $order->invoice_number).'.pdf';
							$file_attachement['mime'] = 'application/pdf';
						}
						else
							$file_attachement = null;
						if (Validate::isEmail($this->context->customer->email))
							Mail::Send(
								(int)$order->id_lang,
								'order_conf',
								Mail::l('Order confirmation', (int)$order->id_lang),
								$data,
								$this->context->customer->email,
								$this->context->customer->firstname.' '.$this->context->customer->lastname,
								null,
								null,
								$file_attachement,
								null, _PS_MAIL_DIR_, false, (int)$order->id_shop
							);
					}
					// updates stock in shops
					if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
					{
						$product_list = $order->getProducts();
						foreach ($product_list as $product)
						{
							// if the available quantities depends on the physical stock
							if (StockAvailable::dependsOnStock($product['product_id']))
							{
								// synchronizes
								StockAvailable::synchronize($product['product_id'], $order->id_shop);
							}
						}
					}
				}
				else
				{
					$error = Tools::displayError('Order creation failed');
					Logger::addLog($error, 4, '0000002', 'Cart', (int)$order->id_cart);
					die($error);
				}
			} // End foreach $order_detail_list
			// Use the last order as currentOrder
			$this->currentOrder = (int)$order->id;
			return true;
		}
		else
		{
			$error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
			Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
			die($error);
		}
	}

	public function validateOrder153($id_cart, $id_order_state, $amount_paid, $codfee, $payment_method = 'Unknown',
		$message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false,
		$secure_key = false, Shop $shop = null)
	{
		$this->context->cart = new Cart($id_cart);
		$this->context->customer = new Customer($this->context->cart->id_customer);
		$this->context->language = new Language($this->context->cart->id_lang);
		$this->context->shop = ($shop ? $shop : new Shop($this->context->cart->id_shop));
		$id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
		$this->context->currency = new Currency($id_currency, null, $this->context->shop->id);
		if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
			$context_country = $this->context->country;

		$order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
		if (!Validate::isLoadedObject($order_status))
			throw new PrestaShopException('Can\'t load Order state status');

		if (!$this->active)
			die(Tools::displayError());
		// Does order already exists ?
		if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false)
		{
			if ($secure_key !== false && $secure_key != $this->context->cart->secure_key)
				die(Tools::displayError());

			// For each package, generate an order
			$delivery_option_list = $this->context->cart->getDeliveryOptionList();
			$package_list = $this->context->cart->getPackageList();
			$cart_delivery_option = $this->context->cart->getDeliveryOption();

			// If some delivery options are not defined, or not valid, use the first valid option
			foreach ($delivery_option_list as $id_address => $package)
				if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package))
					foreach ($package as $key => $val)
					{
						$cart_delivery_option[$id_address] = $key;
						break;
					}

			$order_list = array();
			$order_detail_list = array();
			$reference = Order::generateReference();
			$this->currentOrderReference = $reference;

			$order_creation_failed = false;
			$cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);

			if ($this->context->cart->orderExists())
			{
				$error = Tools::displayError('An order has already been placed using this cart.');
				Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
				die($error);
			}

			foreach ($cart_delivery_option as $id_address => $key_carriers)
				foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data)
					foreach ($data['package_list'] as $id_package)
					{
						// Rewrite the id_warehouse
						$package_list[$id_address][$id_package]['id_warehouse'] = (int)$this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int)$id_carrier);
						$package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
					}
			// Make sure CarRule caches are empty
			CartRule::cleanCache();

			foreach ($package_list as $id_address => $packageByAddress)
				foreach ($packageByAddress as $id_package => $package)
				{
					$order = new Order();
					$order->product_list = $package['product_list'];

					if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
					{
						$address = new Address($id_address);
						$this->context->country = new Country($address->id_country, $this->context->cart->id_lang);
					}

					$carrier = null;
					if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier']))
					{
						$carrier = new Carrier($package['id_carrier'], $this->context->cart->id_lang);
						$order->id_carrier = (int)$carrier->id;
						$id_carrier = (int)$carrier->id;
					}
					else
					{
						$order->id_carrier = 0;
						$id_carrier = 0;
					}

					$order->id_customer = (int)$this->context->cart->id_customer;
					$order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
					$order->id_address_delivery = (int)$id_address;
					$order->id_currency = $this->context->currency->id;
					$order->id_lang = (int)$this->context->cart->id_lang;
					$order->id_cart = (int)$this->context->cart->id;
					$order->reference = $reference;
					$order->id_shop = (int)$this->context->shop->id;
					$order->id_shop_group = (int)$this->context->shop->id_shop_group;

					$order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
					$order->payment = $payment_method;
					if (isset($this->name))
						$order->module = $this->name;
					$order->recyclable = $this->context->cart->recyclable;
					$order->gift = (int)$this->context->cart->gift;
					$order->gift_message = $this->context->cart->gift_message;
					$order->conversion_rate = $this->context->currency->conversion_rate;
					$amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
					$order->total_paid_real = 0;

					$order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
					$order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);

					$order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
					$order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
					$order->total_discounts = $order->total_discounts_tax_incl;

					$codfee_wt = 0;

					if (!is_null($carrier) && Validate::isLoadedObject($carrier))
					{
						$order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
						$codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
					}

					$order->total_shipping_tax_excl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 4);
					$order->total_shipping_tax_incl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 4);
					$order->total_shipping = $order->total_shipping_tax_incl;

					$order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
					$order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
					$order->total_wrapping = $order->total_wrapping_tax_incl;

					$order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, 2);
					$order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, 2);
					$order->total_paid = $order->total_paid_tax_incl;

					$order->invoice_date = '0000-00-00 00:00:00';
					$order->delivery_date = '0000-00-00 00:00:00';

					$order->codfee = $codfee;

					// Creating order
					$result = $order->add();

					if (!$result)
						throw new PrestaShopException('Can\'t save Order');

					// Amount paid by customer is not the right one -> Status = payment error
					// We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
					// if ($order->total_paid != $order->total_paid_real)
					// We use number_format in order to compare two string
					if ($order_status->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2))
						$id_order_state = Configuration::get('PS_OS_ERROR');

					$order_list[] = $order;

					// Insert new Order detail list using cart for the current order
					$order_detail = new OrderDetail(null, null, $this->context);
					$order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
					$order_detail_list[] = $order_detail;

					// Adding an entry in order_carrier table
					if (!is_null($carrier))
					{
						$order_carrier = new OrderCarrier();
						$order_carrier->id_order = (int)$order->id;
						$order_carrier->id_carrier = (int)$id_carrier;
						$order_carrier->weight = (float)$order->getTotalWeight();
						$order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
						$order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
						$order_carrier->add();
					}
				}

			// The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
			if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
				$this->context->country = $context_country;

			// Register Payment only if the order status validate the order
			if ($order_status->logable)
			{
				// $order is the last order loop in the foreach
				// The method addOrderPayment of the class Order make a create a paymentOrder
				//	linked to the order reference and not to the order id
				if (isset($extra_vars['transaction_id']))
					$transaction_id = $extra_vars['transaction_id'];
				else
					$transaction_id = null;

				if (!$order->addOrderPayment($amount_paid, null, $transaction_id))
					throw new PrestaShopException('Can\'t save Order Payment');
			}

			// Next !
			$only_one_gift = false;
			$cart_rule_used = array();
			$products = $this->context->cart->getProducts();
			$cart_rules = $this->context->cart->getCartRules();

			// Make sure CarRule caches are empty
			CartRule::cleanCache();

			foreach ($order_detail_list as $key => $order_detail)
			{
				$order = $order_list[$key];
				if (!$order_creation_failed & isset($order->id))
				{
					if (!$secure_key)
						$message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
					// Optional message to attach to this order
					if (isset($message) & !empty($message))
					{
						$msg = new Message();
						$message = strip_tags($message, '<br>');
						if (Validate::isCleanHtml($message))
						{
							$msg->message = $message;
							$msg->id_order = (int)$order->id;
							$msg->private = 1;
							$msg->add();
						}
					}

					// Insert new Order detail list using cart for the current order
					//$orderDetail = new OrderDetail(null, null, $this->context);
					//$orderDetail->createList($order, $this->context->cart, $id_order_state);

					// Construct order detail table for the email
					$products_list = '';
					$virtual_product = true;
					foreach ($products as $key => $product)
					{
						$price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
						$price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

						$customization_quantity = 0;
						if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']]))
						{
							$customization_text = '';
							foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']] as $customization)
							{
								if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD]))
									foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text)
										$customization_text .= $text['name'].': '.$text['value'].'<br />';

								if (isset($customization['datas'][Product::CUSTOMIZE_FILE]))
									$customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])).'<br />';

								$customization_text .= '---<br />';
							}

							$customization_text = rtrim($customization_text, '---<br />');

							$customization_quantity = (int)$product['customizationQuantityTotal'];
							$products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
								<td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
								<td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.Tools::displayError('Customized').(!empty($customization_text) ? ' - '.$customization_text : '').'</strong></td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
								<td style="padding: 0.6em 0.4em; width: 15%;">'.$customization_quantity.'</td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
							</tr>';
						}

						if (!$customization_quantity || (int)$product['cart_quantity'] > $customization_quantity)
							$products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
								<td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
								<td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
								<td style="padding: 0.6em 0.4em; width: 15%;">'.((int)$product['cart_quantity'] - $customization_quantity).'</td>
								<td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(((int)$product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
							</tr>';

						// Check if is not a virutal product for the displaying of shipping
						if (!$product['is_virtual'])
							$virtual_product &= false;

					} // end foreach ($products)

					$cart_rules_list = '';
					foreach ($cart_rules as $cart_rule)
					{
						$package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
						$values = array(
							'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL, $package),
							'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL, $package)
						);

						// If the reduction is not applicable to this order, then continue with the next one
						if (!$values['tax_excl'])
							continue;

						$order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values);

						/* IF
						** - This is not multi-shipping
						** - The value of the voucher is greater than the total of the order
						** - Partial use is allowed
						** - This is an "amount" reduction, not a reduction in % or a gift
						** THEN
						** The voucher is cloned with a new value corresponding to the remainder
						*/
						if (count($order_list) == 1 && $values['tax_incl'] > $order->total_products_wt && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0)
						{
							// Create a new voucher from the original
							$voucher = new CartRule($cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
							unset($voucher->id);

							// Set a new voucher code
							$voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
							if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2])
								$voucher->code = preg_replace('/'.$matches[0].'$/', '-'.((int)$matches[1] + 1), $voucher->code);

							// Set the new voucher value
							if ($voucher->reduction_tax)
								$voucher->reduction_amount = $values['tax_incl'] - $order->total_products_wt;
							else
								$voucher->reduction_amount = $values['tax_excl'] - $order->total_products;

							$voucher->id_customer = $order->id_customer;
							$voucher->quantity = 1;
							if ($voucher->add())
							{
								// If the voucher has conditions, they are now copied to the new voucher
								CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);

								$params = array(
									'{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
									'{voucher_num}' => $voucher->code,
									'{firstname}' => $this->context->customer->firstname,
									'{lastname}' => $this->context->customer->lastname,
									'{id_order}' => $order->reference,
									'{order_name}' => $order->getUniqReference()
								);
								Mail::Send(
									(int)$order->id_lang,
									'voucher',
									sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
									$params,
									$this->context->customer->email,
									$this->context->customer->firstname.' '.$this->context->customer->lastname,
									null, null, null, null, _PS_MAIL_DIR_, false, (int)$order->id_shop
								);
							}
						}

						if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used))
						{
							$cart_rule_used[] = $cart_rule['obj']->id;

							// Create a new instance of Cart Rule without id_lang, in order to update its quantity
							$cart_rule_to_update = new CartRule($cart_rule['obj']->id);
							$cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
							$cart_rule_to_update->update();
						}

						$cart_rules_list .= '
						<tr>
							<td colspan="4" style="padding:0.6em 0.4em;text-align:right">'.Tools::displayError('Voucher name:').' '.$cart_rule['obj']->name.'</td>
							<td style="padding:0.6em 0.4em;text-align:right">'.($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false).'</td>
						</tr>';
					}

					// Specify order id for message
					$old_message = Message::getMessageByCartId((int)$this->context->cart->id);
					if ($old_message)
					{
						$update_message = new Message((int)$old_message['id_message']);
						$update_message->id_order = (int)$order->id;
						$update_message->update();

						// Add this message in the customer thread
						$customer_thread = new CustomerThread();
						$customer_thread->id_contact = 0;
						$customer_thread->id_customer = (int)$order->id_customer;
						$customer_thread->id_shop = (int)$this->context->shop->id;
						$customer_thread->id_order = (int)$order->id;
						$customer_thread->id_lang = (int)$this->context->language->id;
						$customer_thread->email = $this->context->customer->email;
						$customer_thread->status = 'open';
						$customer_thread->token = Tools::passwdGen(12);
						$customer_thread->add();

						$customer_message = new CustomerMessage();
						$customer_message->id_customer_thread = $customer_thread->id;
						$customer_message->id_employee = 0;
						$customer_message->message = htmlentities($update_message->message, ENT_COMPAT, 'UTF-8');
						$customer_message->private = 0;

						if (!$customer_message->add())
							$this->errors[] = Tools::displayError('An error occurred while saving message');
					}

					// Hook validate order
					Hook::exec('actionValidateOrder', array(
						'cart' => $this->context->cart,
						'order' => $order,
						'customer' => $this->context->customer,
						'currency' => $this->context->currency,
						'orderStatus' => $order_status
					));

					foreach ($this->context->cart->getProducts() as $product)
						if ($order_status->logable)
							ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);

					if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState())
					{
						$history = new OrderHistory();
						$history->id_order = (int)$order->id;
						$history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
						$history->addWithemail();
					}

					// Set order state in order history ONLY even if the "out of stock" status has not been yet reached
					// So you migth have two order states
					$new_history = new OrderHistory();
					$new_history->id_order = (int)$order->id;
					$new_history->changeIdOrderState((int)$id_order_state, $order, true);
					$new_history->addWithemail(true, $extra_vars);

					unset($order_detail);

					// Order is reloaded because the status just changed
					$order = new Order($order->id);

					// Send an e-mail to customer (one order = one email)
					if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id)
					{
						$invoice = new Address($order->id_address_invoice);
						$delivery = new Address($order->id_address_delivery);
						$delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
						$invoice_state = $invoice->id_state ? new State($invoice->id_state) : false;

						$data = array(
						'{firstname}' => $this->context->customer->firstname,
						'{lastname}' => $this->context->customer->lastname,
						'{email}' => $this->context->customer->email,
						'{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
						'{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
						'{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
							'firstname'	=> '<span style="font-weight:bold;">%s</span>',
							'lastname'	=> '<span style="font-weight:bold;">%s</span>'
						)),
						'{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
								'firstname'	=> '<span style="font-weight:bold;">%s</span>',
								'lastname'	=> '<span style="font-weight:bold;">%s</span>'
						)),
						'{delivery_company}' => $delivery->company,
						'{delivery_firstname}' => $delivery->firstname,
						'{delivery_lastname}' => $delivery->lastname,
						'{delivery_address1}' => $delivery->address1,
						'{delivery_address2}' => $delivery->address2,
						'{delivery_city}' => $delivery->city,
						'{delivery_postal_code}' => $delivery->postcode,
						'{delivery_country}' => $delivery->country,
						'{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
						'{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
						'{delivery_other}' => $delivery->other,
						'{invoice_company}' => $invoice->company,
						'{invoice_vat_number}' => $invoice->vat_number,
						'{invoice_firstname}' => $invoice->firstname,
						'{invoice_lastname}' => $invoice->lastname,
						'{invoice_address2}' => $invoice->address2,
						'{invoice_address1}' => $invoice->address1,
						'{invoice_city}' => $invoice->city,
						'{invoice_postal_code}' => $invoice->postcode,
						'{invoice_country}' => $invoice->country,
						'{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
						'{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
						'{invoice_other}' => $invoice->other,
						'{order_name}' => $order->getUniqReference(),
						'{date}' => Tools::displayDate(date('Y-m-d H:i:s'), (int)$order->id_lang, 1),
						'{carrier}' => $virtual_product ? Tools::displayError('No carrier') : $carrier->name,
						'{payment}' => Tools::substr($order->payment, 0, 32),
						'{products}' => $this->formatProductAndVoucherForEmail($products_list),
						'{discounts}' => $this->formatProductAndVoucherForEmail($cart_rules_list),
						'{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false),
						'{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
						'{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
						'{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
						'{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.$this->l('COD fee included'),
						'{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false));

						if (is_array($extra_vars))
							$data = array_merge($data, $extra_vars);

						// Join PDF invoice
						if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number)
						{
							$pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
							$file_attachement['content'] = $pdf->render(false);
							$file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang).sprintf('%06d', $order->invoice_number).'.pdf';
							$file_attachement['mime'] = 'application/pdf';
						}
						else
							$file_attachement = null;

						if (Validate::isEmail($this->context->customer->email))
							Mail::Send(
								(int)$order->id_lang,
								'order_conf',
								Mail::l('Order confirmation', (int)$order->id_lang),
								$data,
								$this->context->customer->email,
								$this->context->customer->firstname.' '.$this->context->customer->lastname,
								null,
								null,
								$file_attachement,
								null, _PS_MAIL_DIR_, false, (int)$order->id_shop
							);
					}

					// updates stock in shops
					if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
					{
						$product_list = $order->getProducts();
						foreach ($product_list as $product)
						{
							// if the available quantities depends on the physical stock
							if (StockAvailable::dependsOnStock($product['product_id']))
							{
								// synchronizes
								StockAvailable::synchronize($product['product_id'], $order->id_shop);
							}
						}
					}
				}
				else
				{
					$error = Tools::displayError('Order creation failed');
					Logger::addLog($error, 4, '0000002', 'Cart', (int)$order->id_cart);
					die($error);
				}
			} // End foreach $order_detail_list
			// Use the last order as currentOrder
			$this->currentOrder = (int)$order->id;
			return true;
		}
		else
		{
			$error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
			Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
			die($error);
		}
	}

	/**
	* Validate an order in database
	* Function called from a payment module
	* VERSION 1.5
	*
	* @param integer $id_cart Value
	* @param integer $id_order_state Value
	* @param float $amount_paid Amount really paid by customer (in the default currency)
	* @param string $payment_method Payment method (eg. 'Credit card')
	* @param string $message Message to attach to order
	*/
	public function validateOrder15($id_cart, $id_order_state, $amount_paid, $codfee, $payment_method = 'Unknown',
		$message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false,
		$secure_key = false, Shop $shop = null)
	{
		$this->context->cart = new Cart($id_cart);
		$this->context->customer = new Customer($this->context->cart->id_customer);
		$this->context->language = new Language($this->context->cart->id_lang);
		$this->context->shop = ($shop ? $shop : new Shop($this->context->cart->id_shop));
		$id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
		$this->context->currency = new Currency($id_currency, null, $this->context->shop->id);
		if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
			$context_country = $this->context->country;

		$order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
		if (!Validate::isLoadedObject($order_status))
			throw new PrestaShopException('Can\'t load Order state status');

		if (!$this->active)
			die(Tools::displayError());
		// Does order already exists ?
		if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false)
		{
			if ($secure_key !== false && $secure_key != $this->context->cart->secure_key)
				die(Tools::displayError());

			// For each package, generate an order
			$delivery_option_list = $this->context->cart->getDeliveryOptionList();
			$package_list = $this->context->cart->getPackageList();
			$cart_delivery_option = $this->context->cart->getDeliveryOption();

			// If some delivery options are not defined, or not valid, use the first valid option
			foreach ($delivery_option_list as $id_address => $package)
				if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package))
					foreach ($package as $key => $val)
					{
						$cart_delivery_option[$id_address] = $key;
						break;
					}

			$order_list = array();
			$order_detail_list = array();
			$reference = Order::generateReference();
			$this->currentOrderReference = $reference;

			$order_creation_failed = false;
			$cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);

			if ($this->context->cart->orderExists())
			{
				$error = Tools::displayError('An order has already been placed using this cart.');
				Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
				die($error);
			}

			foreach ($cart_delivery_option as $id_address => $key_carriers)
				foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data)
					foreach ($data['package_list'] as $id_package)
						$package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;

			// Make sure CarRule caches are empty
			CartRule::cleanCache();

			foreach ($package_list as $id_address => $packageByAddress)
				foreach ($packageByAddress as $id_package => $package)
				{
					$order = new Order();
					$order->product_list = $package['product_list'];

					if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
					{
						$address = new Address($id_address);
						$this->context->country = new Country($address->id_country, $this->context->cart->id_lang);
					}

					$carrier = null;
					if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier']))
					{
						$carrier = new Carrier($package['id_carrier'], $this->context->cart->id_lang);
						$order->id_carrier = (int)$carrier->id;
						$id_carrier = (int)$carrier->id;
					}
					else
					{
						$order->id_carrier = 0;
						$id_carrier = 0;
					}

					$order->id_customer = (int)$this->context->cart->id_customer;
					$order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
					$order->id_address_delivery = (int)$id_address;
					$order->id_currency = $this->context->currency->id;
					$order->id_lang = (int)$this->context->cart->id_lang;
					$order->id_cart = (int)$this->context->cart->id;
					$order->reference = $reference;
					$order->id_shop = (int)$this->context->shop->id;
					$order->id_shop_group = (int)$this->context->shop->id_shop_group;

					$order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
					$order->payment = $payment_method;
					if (isset($this->name))
						$order->module = $this->name;
					$order->recyclable = $this->context->cart->recyclable;
					$order->gift = (int)$this->context->cart->gift;
					$order->gift_message = $this->context->cart->gift_message;
					$order->conversion_rate = $this->context->currency->conversion_rate;
					$amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
					$order->total_paid_real = 0;

					$order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
					$order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);

					$order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
					$order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
					$order->total_discounts = $order->total_discounts_tax_incl;

					$codfee_wt = 0;

					if (!is_null($carrier) && Validate::isLoadedObject($carrier))
					{
						$order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
						$codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
					}

					$order->total_shipping_tax_excl = (float)Tools::ps_round(($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 2);
					$order->total_shipping_tax_incl = (float)Tools::ps_round(($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 2);
					$order->total_shipping = $order->total_shipping_tax_incl;

					$order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
					$order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
					$order->total_wrapping = $order->total_wrapping_tax_incl;

					$order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, 2);
					$order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, 2);
					$order->total_paid = $order->total_paid_tax_incl;

					$order->invoice_date = '0000-00-00 00:00:00';
					$order->delivery_date = '0000-00-00 00:00:00';

					$order->codfee = $codfee;

					// Creating order
					$result = $order->add();

					if (!$result)
						throw new PrestaShopException('Can\'t save Order');

					// Amount paid by customer is not the right one -> Status = payment error
					// We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
					// if ($order->total_paid != $order->total_paid_real)
					// We use number_format in order to compare two string
					if ($order_status->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2))
						$id_order_state = Configuration::get('PS_OS_ERROR');

					$order_list[] = $order;

					// Insert new Order detail list using cart for the current order
					$order_detail = new OrderDetail(null, null, $this->context);
					$order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
					$order_detail_list[] = $order_detail;

					// Adding an entry in order_carrier table
					if (!is_null($carrier))
					{
						$order_carrier = new OrderCarrier();
						$order_carrier->id_order = (int)$order->id;
						$order_carrier->id_carrier = (int)$id_carrier;
						$order_carrier->weight = (float)$order->getTotalWeight();
						$order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
						$order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
						$order_carrier->add();
					}
				}

			// The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
			if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
				$this->context->country = $context_country;

			// Register Payment only if the order status validate the order
			if ($order_status->logable)
			{
				// $order is the last order loop in the foreach
				// The method addOrderPayment of the class Order make a create a paymentOrder
				//	linked to the order reference and not to the order id
				if (!$order->addOrderPayment($amount_paid))
					throw new PrestaShopException('Can\'t save Order Payment');
			}

			// Next !
			$only_one_gift = false;
			$cart_rule_used = array();
			$products = $this->context->cart->getProducts();
			$cart_rules = $this->context->cart->getCartRules();

			// Make sure CarRule caches are empty
			CartRule::cleanCache();

			foreach ($order_detail_list as $key => $order_detail)
			{
				$order = $order_list[$key];
				if (!$order_creation_failed & isset($order->id))
				{
					if (!$secure_key)
						$message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
					// Optional message to attach to this order
					if (isset($message) & !empty($message))
					{
						$msg = new Message();
						$message = strip_tags($message, '<br>');
						if (Validate::isCleanHtml($message))
						{
							$msg->message = $message;
							$msg->id_order = (int)$order->id;
							$msg->private = 1;
							$msg->add();
						}
					}

					// Insert new Order detail list using cart for the current order
					//$orderDetail = new OrderDetail(null, null, $this->context);
					//$orderDetail->createList($order, $this->context->cart, $id_order_state);

					// Construct order detail table for the email
					$products_list = '';
					$virtual_product = true;
					foreach ($products as $key => $product)
					{
						$price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
						$price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

						$customization_quantity = 0;
						if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']]))
						{
							$customization_text = '';
							foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']] as $customization)
							{
								if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD]))
									foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text)
										$customization_text .= $text['name'].': '.$text['value'].'<br />';

								if (isset($customization['datas'][Product::CUSTOMIZE_FILE]))
									$customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])).'<br />';

								$customization_text .= '---<br />';
							}

							$customization_text = rtrim($customization_text, '---<br />');

							$customization_quantity = (int)$product['customizationQuantityTotal'];
							$products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
								<td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
								<td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.Tools::displayError('Customized').(!empty($customization_text) ? ' - '.$customization_text : '').'</strong></td>
								<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
								<td style="padding: 0.6em 0.4em; text-align: center;">'.$customization_quantity.'</td>
								<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
							</tr>';
						}

						if (!$customization_quantity || (int)$product['cart_quantity'] > $customization_quantity)
							$products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
								<td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
								<td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
								<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
								<td style="padding: 0.6em 0.4em; text-align: center;">'.((int)$product['cart_quantity'] - $customization_quantity).'</td>
								<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(((int)$product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
							</tr>';

						// Check if is not a virutal product for the displaying of shipping
						if (!$product['is_virtual'])
							$virtual_product &= false;

					} // end foreach ($products)

					$cart_rules_list = '';
					foreach ($cart_rules as $cart_rule)
					{
						$package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
						$values = array(
							'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL, $package),
							'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL, $package)
						);

						// If the reduction is not applicable to this order, then continue with the next one
						if (!$values['tax_excl'])
							continue;

						$order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values);

						/* IF
						** - This is not multi-shipping
						** - The value of the voucher is greater than the total of the order
						** - Partial use is allowed
						** - This is an "amount" reduction, not a reduction in % or a gift
						** THEN
						** The voucher is cloned with a new value corresponding to the remainder
						*/
						if (count($order_list) == 1 && $values['tax_incl'] > $order->total_products_wt && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0)
						{
							// Create a new voucher from the original
							$voucher = new CartRule($cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
							unset($voucher->id);

							// Set a new voucher code
							$voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
							if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2])
								$voucher->code = preg_replace('/'.$matches[0].'$/', '-'.((int)$matches[1] + 1), $voucher->code);

							// Set the new voucher value
							if ($voucher->reduction_tax)
								$voucher->reduction_amount = $values['tax_incl'] - $order->total_products_wt;
							else
								$voucher->reduction_amount = $values['tax_excl'] - $order->total_products;

							$voucher->id_customer = $order->id_customer;
							$voucher->quantity = 1;
							if ($voucher->add())
							{
								// If the voucher has conditions, they are now copied to the new voucher
								CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);

								$params = array(
									'{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
									'{voucher_num}' => $voucher->code,
									'{firstname}' => $this->context->customer->firstname,
									'{lastname}' => $this->context->customer->lastname,
									'{id_order}' => $order->reference,
									'{order_name}' => $order->getUniqReference()
								);
								Mail::Send(
									(int)$order->id_lang,
									'voucher',
									sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
									$params,
									$this->context->customer->email,
									$this->context->customer->firstname.' '.$this->context->customer->lastname,
									null, null, null, null, _PS_MAIL_DIR_, false, (int)$order->id_shop
								);
							}
						}

						if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used))
						{
							$cart_rule_used[] = $cart_rule['obj']->id;

							// Create a new instance of Cart Rule without id_lang, in order to update its quantity
							$cart_rule_to_update = new CartRule($cart_rule['obj']->id);
							$cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
							$cart_rule_to_update->update();
						}

						$cart_rules_list .= '
						<tr style="background-color:#EBECEE;">
							<td colspan="4" style="padding:0.6em 0.4em;text-align:right">'.Tools::displayError('Voucher name:').' '.$cart_rule['obj']->name.'</td>
							<td style="padding:0.6em 0.4em;text-align:right">'.($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false).'</td>
						</tr>';
					}

					// Specify order id for message
					$old_message = Message::getMessageByCartId((int)$this->context->cart->id);
					if ($old_message)
					{
						$message = new Message((int)$old_message['id_message']);
						$message->id_order = (int)$order->id;
						$message->update();

						// Add this message in the customer thread
						$customer_thread = new CustomerThread();
						$customer_thread->id_contact = 0;
						$customer_thread->id_customer = (int)$order->id_customer;
						$customer_thread->id_shop = (int)$this->context->shop->id;
						$customer_thread->id_order = (int)$order->id;
						$customer_thread->id_lang = (int)$this->context->language->id;
						$customer_thread->email = $this->context->customer->email;
						$customer_thread->status = 'open';
						$customer_thread->token = Tools::passwdGen(12);
						$customer_thread->add();

						$customer_message = new CustomerMessage();
						$customer_message->id_customer_thread = $customer_thread->id;
						$customer_message->id_employee = 0;
						$customer_message->message = htmlentities($message->message, ENT_COMPAT, 'UTF-8');
						$customer_message->private = 0;

						if (!$customer_message->add())
							$this->errors[] = Tools::displayError('An error occurred while saving message');
					}

					// Hook validate order
					Hook::exec('actionValidateOrder', array(
						'cart' => $this->context->cart,
						'order' => $order,
						'customer' => $this->context->customer,
						'currency' => $this->context->currency,
						'orderStatus' => $order_status
					));

					foreach ($this->context->cart->getProducts() as $product)
						if ($order_status->logable)
							ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);

					if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState())
					{
						$history = new OrderHistory();
						$history->id_order = (int)$order->id;
						$history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
						$history->addWithemail();
					}

					// Set order state in order history ONLY even if the "out of stock" status has not been yet reached
					// So you migth have two order states
					$new_history = new OrderHistory();
					$new_history->id_order = (int)$order->id;
					$new_history->changeIdOrderState((int)$id_order_state, $order, true);
					$new_history->addWithemail(true, $extra_vars);

					unset($order_detail);

					// Order is reloaded because the status just changed
					$order = new Order($order->id);

					// Send an e-mail to customer (one order = one email)
					if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id)
					{
						$invoice = new Address($order->id_address_invoice);
						$delivery = new Address($order->id_address_delivery);
						$delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
						$invoice_state = $invoice->id_state ? new State($invoice->id_state) : false;

						$data = array(
						'{firstname}' => $this->context->customer->firstname,
						'{lastname}' => $this->context->customer->lastname,
						'{email}' => $this->context->customer->email,
						'{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
						'{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
						'{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
							'firstname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>',
							'lastname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>'
						)),
						'{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
								'firstname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>',
								'lastname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>'
						)),
						'{delivery_company}' => $delivery->company,
						'{delivery_firstname}' => $delivery->firstname,
						'{delivery_lastname}' => $delivery->lastname,
						'{delivery_address1}' => $delivery->address1,
						'{delivery_address2}' => $delivery->address2,
						'{delivery_city}' => $delivery->city,
						'{delivery_postal_code}' => $delivery->postcode,
						'{delivery_country}' => $delivery->country,
						'{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
						'{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
						'{delivery_other}' => $delivery->other,
						'{invoice_company}' => $invoice->company,
						'{invoice_vat_number}' => $invoice->vat_number,
						'{invoice_firstname}' => $invoice->firstname,
						'{invoice_lastname}' => $invoice->lastname,
						'{invoice_address2}' => $invoice->address2,
						'{invoice_address1}' => $invoice->address1,
						'{invoice_city}' => $invoice->city,
						'{invoice_postal_code}' => $invoice->postcode,
						'{invoice_country}' => $invoice->country,
						'{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
						'{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
						'{invoice_other}' => $invoice->other,
						'{order_name}' => $order->getUniqReference(),
						'{date}' => Tools::displayDate(date('Y-m-d H:i:s'), (int)$order->id_lang, 1),
						'{carrier}' => $virtual_product ? Tools::displayError('No carrier') : $carrier->name,
						'{payment}' => Tools::substr($order->payment, 0, 45),
						'{products}' => $this->formatProductAndVoucherForEmail($products_list),
						'{discounts}' => $this->formatProductAndVoucherForEmail($cart_rules_list),
						'{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false),
						'{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
						'{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
						'{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
						'{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.$this->l('COD fee included'),
						'{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false));

						if (is_array($extra_vars))
							$data = array_merge($data, $extra_vars);

						// Join PDF invoice
						if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number)
						{
							$pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
							$file_attachement['content'] = $pdf->render(false);
							$file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang).sprintf('%06d', $order->invoice_number).'.pdf';
							$file_attachement['mime'] = 'application/pdf';
						}
						else
							$file_attachement = null;

						if (Validate::isEmail($this->context->customer->email))
							Mail::Send(
								(int)$order->id_lang,
								'order_conf',
								Mail::l('Order confirmation', (int)$order->id_lang),
								$data,
								$this->context->customer->email,
								$this->context->customer->firstname.' '.$this->context->customer->lastname,
								null,
								null,
								$file_attachement,
								null, _PS_MAIL_DIR_, false, (int)$order->id_shop
							);
					}

					// updates stock in shops
					if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
					{
						$product_list = $order->getProducts();
						foreach ($product_list as $product)
						{
							// if the available quantities depends on the physical stock
							if (StockAvailable::dependsOnStock($product['product_id']))
							{
								// synchronizes
								StockAvailable::synchronize($product['product_id'], $order->id_shop);
							}
						}
					}
				}
				else
				{
					$error = Tools::displayError('Order creation failed');
					Logger::addLog($error, 4, '0000002', 'Cart', (int)$order->id_cart);
					die($error);
				}
			} // End foreach $order_detail_list
			// Use the last order as currentOrder
			$this->currentOrder = (int)$order->id;
			return true;
		}
		else
		{
			$error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
			Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
			die($error);
		}
	}

	/**
	* Validate an order in database
	* Function called from a payment module
	* VERSION 1.4
	*
	* @param integer $id_cart Value
	* @param integer $id_order_state Value
	* @param float $amountPaid Amount really paid by customer (in the default currency)
	* @param string $paymentMethod Payment method (eg. 'Credit card')
	* @param string $message Message to attach to order
	*/
	public function validateOrder14($id_cart, $id_order_state, $amountPaid, $codfee, $paymentMethod = 'Unknown', $message = null, $extraVars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false)
	{
		global $cart;

		$cart = new Cart((int)($id_cart));
		// Does order already exists ?
		if (Validate::isLoadedObject($cart) && $cart->OrderExists() == false)
		{
			if ($secure_key !== false && $secure_key != $cart->secure_key)
				die(Tools::displayError());

			// Copying data from cart
			$order = new Order();
			$order->id_carrier = (int)($cart->id_carrier);
			$order->id_customer = (int)($cart->id_customer);
			$order->id_address_invoice = (int)($cart->id_address_invoice);
			$order->id_address_delivery = (int)($cart->id_address_delivery);
			$vat_address = new Address((int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
			$order->id_currency = ($currency_special ? (int)($currency_special) : (int)($cart->id_currency));
			$order->id_lang = (int)($cart->id_lang);
			$order->id_cart = (int)($cart->id);
			$customer = new Customer((int)($order->id_customer));
			$order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($customer->secure_key));
			$order->payment = $paymentMethod;
			if (isset($this->name))
				$order->module = $this->name;
			$order->recyclable = $cart->recyclable;
			$order->gift = (int)($cart->gift);
			$order->gift_message = $cart->gift_message;
			$currency = new Currency($order->id_currency);
			$order->conversion_rate = $currency->conversion_rate;
			$amountPaid = !$dont_touch_amount ? Tools::ps_round((float)($amountPaid), 2) : $amountPaid;
			$order->total_paid_real = $amountPaid;
			$order->total_products = (float)($cart->getOrderTotal(false, Cart::ONLY_PRODUCTS));
			$order->total_products_wt = (float)($cart->getOrderTotal(true, Cart::ONLY_PRODUCTS));
			$order->total_discounts = (float)(abs($cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS)));
			$order->total_shipping = (float)($cart->getOrderShippingCost() + $codfee);
			$order->carrier_tax_rate = (float)Tax::getCarrierTaxRate($cart->id_carrier, (int)$cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
			$codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
			$order->total_wrapping = (float)(abs($cart->getOrderTotal(true, Cart::ONLY_WRAPPING)));
			$order->total_paid = (float)(Tools::ps_round((float)($cart->getOrderTotal(true, Cart::BOTH) + $codfee), 2));
			$order->invoice_date = '0000-00-00 00:00:00';
			$order->delivery_date = '0000-00-00 00:00:00';
			$order->codfee = $codfee;
			// Amount paid by customer is not the right one -> Status = payment error
			// We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
			// if ($order->total_paid != $order->total_paid_real)
			// We use number_format in order to compare two string
			if (number_format($order->total_paid, 2) != number_format($order->total_paid_real, 2))
				$id_order_state = Configuration::get('PS_OS_ERROR');
			// Creating order
			if ($cart->OrderExists() == false)
				$result = $order->add();
			else
			{
				$errorMessage = Tools::displayError('An order has already been placed using this cart.');
				Logger::addLog($errorMessage, 4, '0000001', 'Cart', (int)$order->id_cart);
				die($errorMessage);
			}

			// Next !
			if ($result && isset($order->id))
			{
				if (!$secure_key)
					$message .= $this->l('Warning : the secure key is empty, check your payment account before validation');
				// Optional message to attach to this order
				if (isset($message) && !empty($message))
				{
					$msg = new Message();
					$message = strip_tags($message, '<br>');
					if (Validate::isCleanHtml($message))
					{
						$msg->message = $message;
						$msg->id_order = (int)$order->id;
						$msg->private = 1;
						$msg->add();
					}
				}

				// Insert products from cart into order_detail table
				$products = $cart->getProducts();
				$productsList = '';
				$db = Db::getInstance();
				$query = 'INSERT INTO `'._DB_PREFIX_.'order_detail`
					(`id_order`, `product_id`, `product_attribute_id`, `product_name`, `product_quantity`, `product_quantity_in_stock`, `product_price`, `reduction_percent`, `reduction_amount`, `group_reduction`, `product_quantity_discount`, `product_ean13`, `product_upc`, `product_reference`, `product_supplier_reference`, `product_weight`, `tax_name`, `tax_rate`, `ecotax`, `ecotax_tax_rate`, `discount_quantity_applied`, `download_deadline`, `download_hash`)
				VALUES ';

				$customizedDatas = Product::getAllCustomizedDatas((int)($order->id_cart));
				Product::addCustomizationPrice($products, $customizedDatas);
				$outOfStock = false;

				$storeAllTaxes = array();

				foreach ($products as $key => $product)
				{
					$productQuantity = (int)(Product::getQuantity((int)($product['id_product']), ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null)));
					$quantityInStock = ($productQuantity - (int)($product['cart_quantity']) < 0) ? $productQuantity : (int)($product['cart_quantity']);
					if ($id_order_state != Configuration::get('PS_OS_CANCELED') && $id_order_state != Configuration::get('PS_OS_ERROR'))
					{
						if (Product::updateQuantity($product, (int)$order->id))
							$product['stock_quantity'] -= $product['cart_quantity'];
						if ($product['stock_quantity'] < 0 && Configuration::get('PS_STOCK_MANAGEMENT'))
							$outOfStock = true;

						Product::updateDefaultAttribute($product['id_product']);
					}
					$price = Product::getPriceStatic((int)($product['id_product']), false, ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null), 6, null, false, true, $product['cart_quantity'], false, (int)($order->id_customer), (int)($order->id_cart), (int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
					$price_wt = Product::getPriceStatic((int)($product['id_product']), true, ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null), 2, null, false, true, $product['cart_quantity'], false, (int)($order->id_customer), (int)($order->id_cart), (int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));

					/* Store tax info */
					$id_country = (int)Country::getDefaultCountryId();
					$id_state = 0;
					$id_county = 0;
					$rate = 0;
					$id_address = $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
					$address_infos = Address::getCountryAndState($id_address);
					if ($address_infos['id_country'])
					{
						$id_country = (int)($address_infos['id_country']);
						$id_state = (int)$address_infos['id_state'];
						$id_county = (int)County::getIdCountyByZipCode($address_infos['id_state'], $address_infos['postcode']);
					}
					$allTaxes = TaxRulesGroup::getTaxes((int)Product::getIdTaxRulesGroupByIdProduct((int)$product['id_product']), $id_country, $id_state, $id_county);
					$nTax = 0;
					foreach ($allTaxes as $res)
					{
						if (!isset($storeAllTaxes[$res->id]))
						{
							$storeAllTaxes[$res->id] = array();
							$storeAllTaxes[$res->id]['amount'] = 0;
						}
						$storeAllTaxes[$res->id]['name'] = $res->name[(int)$order->id_lang];
						$storeAllTaxes[$res->id]['rate'] = $res->rate;

						if (!$nTax++)
							$storeAllTaxes[$res->id]['amount'] += ($price * ($res->rate * 0.01)) * $product['cart_quantity'];
						else
						{
							$priceTmp = $price_wt / (1 + ($res->rate * 0.01));
							$storeAllTaxes[$res->id]['amount'] += ($price_wt - $priceTmp) * $product['cart_quantity'];
						}
					}
					/* End */

					// Add some informations for virtual products
					$deadline = '0000-00-00 00:00:00';
					$download_hash = null;
					if ($id_product_download = ProductDownload::getIdFromIdProduct((int)($product['id_product'])))
					{
						$productDownload = new ProductDownload((int)($id_product_download));
						$deadline = $productDownload->getDeadLine();
						$download_hash = $productDownload->getHash();
					}

					// Exclude VAT
					if (Tax::excludeTaxeOption())
					{
						$product['tax'] = 0;
						$product['rate'] = 0;
						$tax_rate = 0;
					}
					else
						$tax_rate = Tax::getProductTaxRate((int)($product['id_product']), $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

					$ecotaxTaxRate = 0;
					if (!empty($product['ecotax']))
						$ecotaxTaxRate = Tax::getProductEcotaxRate($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

					$product_price = (float)Product::getPriceStatic((int)($product['id_product']), false, ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null), (Product::getTaxCalculationMethod((int)($order->id_customer)) == PS_TAX_EXC ? 2 : 6), null, false, false, $product['cart_quantity'], false, (int)($order->id_customer), (int)($order->id_cart), (int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}), $specificPrice, false, false);

					$group_reduction = (float)GroupReduction::getValueForProduct((int)$product['id_product'], $customer->id_default_group) * 100;
					if (!$group_reduction)
						$group_reduction = Group::getReduction((int)$order->id_customer);

					$quantityDiscount = SpecificPrice::getQuantityDiscount((int)$product['id_product'], Shop::getCurrentShop(), (int)$cart->id_currency, (int)$vat_address->id_country, (int)$customer->id_default_group, (int)$product['cart_quantity']);
					$unitPrice = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, 1, false, (int)$order->id_customer, null, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
					$quantityDiscountValue = $quantityDiscount ? ((Product::getTaxCalculationMethod((int)$order->id_customer) == PS_TAX_EXC ? Tools::ps_round($unitPrice, 2) : $unitPrice) - $quantityDiscount['price'] * (1 + $tax_rate / 100)) : 0.00;
					$query .= '('.(int)($order->id).',
						'.(int)($product['id_product']).',
						'.(isset($product['id_product_attribute']) ? (int)($product['id_product_attribute']) : 'null').',
						\''.pSQL($product['name'].((isset($product['attributes']) && $product['attributes'] != null) ? ' - '.$product['attributes'] : '')).'\',
						'.(int)($product['cart_quantity']).',
						'.$quantityInStock.',
						'.$product_price.',
						'.(float)(($specificPrice && $specificPrice['reduction_type'] == 'percentage') ? $specificPrice['reduction'] * 100 : 0.00).',
						'.(float)(($specificPrice && $specificPrice['reduction_type'] == 'amount') ? (!$specificPrice['id_currency'] ? Tools::convertPrice($specificPrice['reduction'], $order->id_currency) : $specificPrice['reduction']) : 0.00).',
						'.$group_reduction.',
						'.$quantityDiscountValue.',
						'.(empty($product['ean13']) ? 'null' : '\''.pSQL($product['ean13']).'\'').',
						'.(empty($product['upc']) ? 'null' : '\''.pSQL($product['upc']).'\'').',
						'.(empty($product['reference']) ? 'null' : '\''.pSQL($product['reference']).'\'').',
						'.(empty($product['supplier_reference']) ? 'null' : '\''.pSQL($product['supplier_reference']).'\'').',
						'.(float)($product['id_product_attribute'] ? $product['weight_attribute'] : $product['weight']).',
						\''.(empty($tax_rate) ? '' : pSQL($product['tax'])).'\',
						'.(float)($tax_rate).',
						'.(float)Tools::convertPrice((float)$product['ecotax'], (int)$order->id_currency).',
						'.(float)$ecotaxTaxRate.',
						'.(($specificPrice && $specificPrice['from_quantity'] > 1) ? 1 : 0).',
						\''.pSQL($deadline).'\',
						\''.pSQL($download_hash).'\'),';

					$customizationQuantity = 0;
					if (isset($customizedDatas[$product['id_product']][$product['id_product_attribute']]))
					{
						$customizationText = '';
						foreach ($customizedDatas[$product['id_product']][$product['id_product_attribute']] as $customization)
						{
							if (isset($customization['datas'][_CUSTOMIZE_TEXTFIELD_]))
								foreach ($customization['datas'][_CUSTOMIZE_TEXTFIELD_] as $text)
									$customizationText .= $text['name'].':'.' '.$text['value'].'<br />';

							if (isset($customization['datas'][_CUSTOMIZE_FILE_]))
								$customizationText .= count($customization['datas'][_CUSTOMIZE_FILE_]).' '.Tools::displayError('image(s)').'<br />';

							$customizationText .= '---<br />';
						}

						$customizationText = rtrim($customizationText, '---<br />');

						$customizationQuantity = (int)($product['customizationQuantityTotal']);
						$productsList .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
							<td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
							<td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.$this->l('Customized').(!empty($customizationText) ? ' - '.$customizationText : '').'</strong></td>
							<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt, $currency, false).'</td>
							<td style="padding: 0.6em 0.4em; text-align: center;">'.$customizationQuantity.'</td>
							<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice($customizationQuantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt), $currency, false).'</td>
						</tr>';
					}

					if (!$customizationQuantity || (int)$product['cart_quantity'] > $customizationQuantity)
						$productsList .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
							<td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
							<td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
							<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt, $currency, false).'</td>
							<td style="padding: 0.6em 0.4em; text-align: center;">'.((int)($product['cart_quantity']) - $customizationQuantity).'</td>
							<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(((int)($product['cart_quantity']) - $customizationQuantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt), $currency, false).'</td>
						</tr>';
				} // end foreach ($products)
				$query = rtrim($query, ',');
				$result = $db->Execute($query);

				/* Add carrier tax */
				$shippingCostTaxExcl = $cart->getOrderShippingCost((int)$order->id_carrier, false) + $codfee_wt;
				$allTaxes = TaxRulesGroup::getTaxes((int)Carrier::getIdTaxRulesGroupByIdCarrier((int)$order->id_carrier), $id_country, $id_state, $id_county);
				$nTax = 0;

				foreach ($allTaxes as $res)
				{
					if (!isset($res->id))
						continue;

					if (!isset($storeAllTaxes[$res->id]))
						$storeAllTaxes[$res->id] = array();
					if (!isset($storeAllTaxes[$res->id]['amount']))
						$storeAllTaxes[$res->id]['amount'] = 0;
					$storeAllTaxes[$res->id]['name'] = $res->name[(int)$order->id_lang];
					$storeAllTaxes[$res->id]['rate'] = $res->rate;

					if (!$nTax++)
						$storeAllTaxes[$res->id]['amount'] += ($shippingCostTaxExcl * (1 + ($res->rate * 0.01))) - $shippingCostTaxExcl;
					else
					{
						$priceTmp = $order->total_shipping / (1 + ($res->rate * 0.01));
						$storeAllTaxes[$res->id]['amount'] += $order->total_shipping - $priceTmp;
					}
				}

				/* Store taxes */
				foreach ($storeAllTaxes as $t)
					Db::getInstance()->Execute('
					INSERT INTO '._DB_PREFIX_.'order_tax (id_order, tax_name, tax_rate, amount)
					VALUES ('.(int)$order->id.', \''.pSQL($t['name']).'\', '.(float)($t['rate']).', '.(float)$t['amount'].')');

				// Insert discounts from cart into order_discount table
				$discounts = $cart->getDiscounts();
				$discountsList = '';
				$total_discount_value = 0;
				$shrunk = false;
				foreach ($discounts as $discount)
				{
					$objDiscount = new Discount((int)$discount['id_discount']);
					$value = $objDiscount->getValue(count($discounts), $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS), $order->total_shipping, $cart->id);
					if ($objDiscount->id_discount_type == 2 && in_array($objDiscount->behavior_not_exhausted, array(1,2)))
						$shrunk = true;

					if ($shrunk && ($total_discount_value + $value) > ($order->total_products_wt + $order->total_shipping + $order->total_wrapping))
					{
						$amount_to_add = ($order->total_products_wt + $order->total_shipping + $order->total_wrapping) - $total_discount_value;
						if ($objDiscount->id_discount_type == 2 && $objDiscount->behavior_not_exhausted == 2)
						{
							$voucher = new Discount();
							foreach ($objDiscount as $key => $discountValue)
								$voucher->$key = $discountValue;
							$voucher->name = 'VSRK'.(int)$order->id_customer.'O'.(int)$order->id;
							$voucher->value = (float)$value - $amount_to_add;
							$voucher->add();
							$params['{voucher_amount}'] = Tools::displayPrice($voucher->value, $currency, false);
							$params['{voucher_num}'] = $voucher->name;
							$params['{firstname}'] = $customer->firstname;
							$params['{lastname}'] = $customer->lastname;
							$params['{id_order}'] = $order->id;
							@Mail::Send((int)$order->id_lang, 'voucher', Mail::l('New voucher regarding your order #', (int)$order->id_lang).$order->id, $params, $customer->email, $customer->firstname.' '.$customer->lastname);
						}
					}
					else
						$amount_to_add = $value;
					$order->addDiscount($objDiscount->id, $objDiscount->name, $amount_to_add);
					$total_discount_value += $amount_to_add;
					if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED'))
						$objDiscount->quantity = $objDiscount->quantity - 1;
					$objDiscount->update();

					$discountsList .= '<tr style="background-color:#EBECEE;">
							<td colspan="4" style="padding: 0.6em 0.4em; text-align: right;">'.$this->l('Voucher code:').' '.$objDiscount->name.'</td>
							<td style="padding: 0.6em 0.4em; text-align: right;">'.($value != 0.00 ? '-' : '').Tools::displayPrice($value, $currency, false).'</td>
					</tr>';
				}

				// Specify order id for message
				$oldMessage = Message::getMessageByCartId((int)($cart->id));
				if ($oldMessage)
				{
					$message = new Message((int)$oldMessage['id_message']);
					$message->id_order = (int)$order->id;
					$message->update();
				}

				// Hook new order
				$orderStatus = new OrderState((int)$id_order_state, (int)$order->id_lang);
				if (Validate::isLoadedObject($orderStatus))
				{
					Hook::newOrder($cart, $order, $customer, $currency, $orderStatus);
					foreach ($cart->getProducts() as $product)
						if ($orderStatus->logable)
							ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
				}

				if (isset($outOfStock) && $outOfStock && Configuration::get('PS_STOCK_MANAGEMENT'))
				{
					$history = new OrderHistory();
					$history->id_order = (int)$order->id;
					$history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), (int)$order->id);
					$history->addWithemail();
				}

				// Set order state in order history ONLY even if the "out of stock" status has not been yet reached
				// So you migth have two order states
				$new_history = new OrderHistory();
				$new_history->id_order = (int)$order->id;
				$new_history->changeIdOrderState((int)$id_order_state, (int)$order->id);
				$new_history->addWithemail(true, $extraVars);

				// Order is reloaded because the status just changed
				$order = new Order($order->id);

				// Send an e-mail to customer
				if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $customer->id)
				{
					$invoice = new Address((int)($order->id_address_invoice));
					$delivery = new Address((int)($order->id_address_delivery));
					$carrier = new Carrier((int)($order->id_carrier), $order->id_lang);
					$delivery_state = $delivery->id_state ? new State((int)($delivery->id_state)) : false;
					$invoice_state = $invoice->id_state ? new State((int)($invoice->id_state)) : false;

					$data = array(
					'{firstname}' => $customer->firstname,
					'{lastname}' => $customer->lastname,
					'{email}' => $customer->email,
					'{delivery_block_txt}' => $this->_getFormatedAddress14($delivery, "\n"),
					'{invoice_block_txt}' => $this->_getFormatedAddress14($invoice, "\n"),
					'{delivery_block_html}' => $this->_getFormatedAddress14($delivery, '<br />',
						array(
							'firstname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>',
							'lastname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>')),
					'{invoice_block_html}' => $this->_getFormatedAddress14($invoice, '<br />',
						array(
							'firstname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>',
							'lastname'	=> '<span style="color:#DB3484; font-weight:bold;">%s</span>')),
					'{delivery_company}' => $delivery->company,
					'{delivery_firstname}' => $delivery->firstname,
					'{delivery_lastname}' => $delivery->lastname,
					'{delivery_address1}' => $delivery->address1,
					'{delivery_address2}' => $delivery->address2,
					'{delivery_city}' => $delivery->city,
					'{delivery_postal_code}' => $delivery->postcode,
					'{delivery_country}' => $delivery->country,
					'{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
					'{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
					'{delivery_other}' => $delivery->other,
					'{invoice_company}' => $invoice->company,
					'{invoice_vat_number}' => $invoice->vat_number,
					'{invoice_firstname}' => $invoice->firstname,
					'{invoice_lastname}' => $invoice->lastname,
					'{invoice_address2}' => $invoice->address2,
					'{invoice_address1}' => $invoice->address1,
					'{invoice_city}' => $invoice->city,
					'{invoice_postal_code}' => $invoice->postcode,
					'{invoice_country}' => $invoice->country,
					'{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
					'{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
					'{invoice_other}' => $invoice->other,
					'{order_name}' => sprintf('#%06d0', (int)($order->id)),
					'{date}' => Tools::displayDate(date('Y-m-d H:i:s'), (int)($order->id_lang), 1),
					'{carrier}' => $carrier->name,
					'{payment}' => Tools::substr($order->payment, 0, 45),
					'{products}' => $productsList,
					'{discounts}' => $discountsList,
					'{total_paid}' => Tools::displayPrice($order->total_paid, $currency, false),
					'{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $currency, false),
					'{total_discounts}' => Tools::displayPrice($order->total_discounts, $currency, false),
					'{total_shipping}' => Tools::displayPrice($order->total_shipping, $currency, false).'<br />'.$this->l('COD fee included'),
					'{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $currency, false));

					if (is_array($extraVars))
						$data = array_merge($data, $extraVars);

					// Join PDF invoice
					if ((int)(Configuration::get('PS_INVOICE')) && Validate::isLoadedObject($orderStatus) && $orderStatus->invoice && $order->invoice_number)
					{
						$fileAttachment['content'] = PDF::invoice($order, 'S');
						$fileAttachment['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)($order->id_lang)).sprintf('%06d', $order->invoice_number).'.pdf';
						$fileAttachment['mime'] = 'application/pdf';
					}
					else
						$fileAttachment = null;

					if (Validate::isEmail($customer->email))
						Mail::Send((int)$order->id_lang, 'order_conf', Mail::l('Order confirmation', (int)$order->id_lang), $data, $customer->email, $customer->firstname.' '.$customer->lastname, null, null, $fileAttachment);
				}
				$this->currentOrder = (int)$order->id;
				return true;
			}
			else
			{
				$errorMessage = Tools::displayError('Order creation failed');
				Logger::addLog($errorMessage, 4, '0000002', 'Cart', (int)$order->id_cart);
				die($errorMessage);
			}
		}
		else
		{
			$errorMessage = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
			Logger::addLog($errorMessage, 4, '0000001', 'Cart', (int)$cart->id);
			die($errorMessage);
		}
	}

	/**
	* @param Object Address $the_address that needs to be txt formated
	* @return String the txt formated address block
	*/

	protected function _getFormatedAddress14(Address $the_address, $line_sep, $fields_style = array())
	{
		return AddressFormat::generateAddress($the_address, array('avoid' => array()), $line_sep, ' ', $fields_style);
	}

	private function convertSign($s)
	{
		return str_replace(array('€', '£', '¥'), array(chr(128), chr(163), chr(165)), $s);
	}

	public static function getPaymentModules()
	{
		global $cart, $cookie;
		$id_customer = (int)($cookie->id_customer);
		$billing = new Address((int)($cart->id_address_invoice));

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT DISTINCT h.`id_hook`, m.`name`, hm.`position`
		FROM `'._DB_PREFIX_.'module_country` mc
		LEFT JOIN `'._DB_PREFIX_.'module` m ON m.`id_module` = mc.`id_module`
		INNER JOIN `'._DB_PREFIX_.'module_group` mg ON (m.`id_module` = mg.`id_module`)
		INNER JOIN `'._DB_PREFIX_.'customer_group` cg on (cg.`id_group` = mg.`id_group` AND cg.`id_customer` = '.(int)($id_customer).')
		LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`
		LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
		WHERE h.`name` = \'payment\'
		AND mc.id_country = '.(int)($billing->id_country).'
		AND m.`active` = 1
		ORDER BY hm.`position`, m.`name` DESC');

		return $result;
	}
}
?>