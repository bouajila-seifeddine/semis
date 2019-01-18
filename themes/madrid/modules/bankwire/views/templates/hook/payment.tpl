{*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="col-md-4 col-sm-6 col-xs-12">
	<p class="payment_module">
		<a class="bankwire" href="{$link->getModuleLink('bankwire', 'payment')|escape:'html':'UTF-8'}" title="{l s='Pay by bank wire' mod='bankwire'}">
			<img src="/img/pagos/transferencia.jpg"  onmouseover="this.src='/img/pagos/transferencia1.jpg';" onmouseout="this.src='/img/pagos/transferencia.jpg';"  alt="Pago con Transferencia" /> <br />
			{l s='Pay by bank wire' mod='bankwire'} <span>{l s='(order processing will be longer)' mod='bankwire'}</span>
		</a>
	</p>
</div>
