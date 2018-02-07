{**
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
*}

{assign var='carrier_ok' value=false}
{foreach $carriers_array as $carrier_array}
	{if $carrier_selected == $carrier_array}
		{$carrier_ok = true}
		{break}
	{/if}
{/foreach}

{if version_compare($smarty.const._PS_VERSION_, '1.6', '>=')}
	{if $carrier_ok == true || !isset($carriers_array)}
		{if ($maximum_amount > 0 && $cartcost < $maximum_amount) || ($maximum_amount == 0)}
			{if $cartcost > $minimum_amount}
				<div class="row">
					<div class="col-xs-12">
						<p class="payment_module">
							<a href="{if $show_conf}{$link->getModuleLink('codfee', 'payment')|escape:'htmlall':'UTF-8'}{else}{$link->getModuleLink('codfee', 'validation', [], true)|escape:'htmlall':'UTF-8'}{/if}" title="{l s='Cash on delivery with fee' mod='codfee'}" class="cash">
								{l s='Cash on delivery:' mod='codfee'} <span>{convertPriceWithCurrency price=$cartcost currency=$currency}
											+ {convertPriceWithCurrency price=$fee currency=$currency} {l s='(COD fee)' mod='codfee'}
											= {convertPriceWithCurrency price=$total currency=$currency}</span>
							</a>
						</p>
				    </div>
				</div>
			{/if}
		{/if}
	{/if}
{else}
	{if $carrier_ok == true || !isset($carriers_array)}
		{if ($maximum_amount > 0 && $cartcost < $maximum_amount) || ($maximum_amount == 0)}
			{if $cartcost > $minimum_amount}
				<p class="payment_module">
					<a href="{if version_compare($smarty.const._PS_VERSION_, '1.5', '<')}{$this_path_ssl|escape:'htmlall':'UTF-8'}payment.php{if $show_conf}?paymentSubmit{/if}{else}{if $show_conf}{$link->getModuleLink('codfee', 'payment', [], true)|escape:'htmlall':'UTF-8'}{else}{$link->getModuleLink('codfee', 'validation', [], true)|escape:'htmlall':'UTF-8'}{/if}{/if}" title="{l s='Cash on delivery with fee' mod='codfee'}">
						<img src="{$this_path|escape:'htmlall':'UTF-8'}img/codfee.gif" alt="{l s='Cash on delivery with fee' mod='codfee'}" />
						{l s='Cash on delivery:' mod='codfee'} {convertPriceWithCurrency price=$cartcost currency=$currency}
						+ {convertPriceWithCurrency price=$fee currency=$currency} {l s='(COD fee)' mod='codfee'}
						= {convertPriceWithCurrency price=$total currency=$currency}
					</a>
				</p>
			{/if}
		{/if}
	{/if}
{/if}
