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


{if $logged}
<!-- Block myaccount module -->
<div class="block_footer col-md-3 col-sm-6 col-xs-12">
	<h4><a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='Manage my customer account' mod='blockmyaccountfooter'}">{l s='My account' mod='blockmyaccountfooter'}</a></h4>
	<div class="block_content toggle-footer">
		<ul>
			<li><a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='My orders' mod='blockmyaccountfooter'}">{l s='My orders' mod='blockmyaccountfooter'}</a></li>
			{if $returnAllowed}<li><a href="{$link->getPageLink('order-follow', true)|escape:'html':'UTF-8'}" title="{l s='My merchandise returns' mod='blockmyaccountfooter'}">{l s='My merchandise returns' mod='blockmyaccountfooter'}</a></li>{/if}
			<li><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='My addresses' mod='blockmyaccountfooter'}">{l s='My addresses' mod='blockmyaccountfooter'}</a></li>
			<li><a href="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" title="{l s='Manage my personal information' mod='blockmyaccountfooter'}">{l s='My personal info' mod='blockmyaccountfooter'}</a></li>
			{$HOOK_BLOCK_MY_ACCOUNT}
            {if $is_logged}<li><a href="{$link->getPageLink('index')}?mylogout" title="{l s='Sign out' mod='blockmyaccountfooter'}">{l s='Sign out' mod='blockmyaccountfooter'}</a></li>{/if}
		</ul>
	</div>
</div>
<!-- /Block myaccount module -->
{else}
<!-- Block myaccount module -->
<div class="block_footer col-md-3 col-sm-6 col-xs-12">
	<h4>{l s='My account' mod='blockmyaccountfooter'}</h4>
	<div class="block_content toggle-footer">
		<ul>
			<li><a href="https://www.semillaslowcost.com/inicio-sesion" title="Inicia sesión">INICIAR SESIÓN</a></li>
			{$HOOK_BLOCK_MY_ACCOUNT}
		</ul>
	</div>
</div>
<!-- /Block myaccount module -->
{/if}