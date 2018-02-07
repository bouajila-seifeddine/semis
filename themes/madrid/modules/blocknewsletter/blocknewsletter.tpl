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
<!-- Block Newsletter module-->
{if isset($is_before_footer) && $is_before_footer}
<form id="newsletterRegistrationForm" class="col-md-7 col-sm-12 col-xs-12" action="{$link->getPageLink('index', null, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post">
	<h5>{l s='NEWSLETTER' mod='blocknewsletter'}</h5>
	<input id="newsletter-input" class="account_input" type="email" name="email" size="18" value="{if isset($msg) && $msg}{$msg}{elseif isset($value) && $value}{$value}{else}{l s='Enter your e-mail' mod='blocknewsletter'}{/if}" placeholder="Introduzca su dirección de correo electrónico" />
	<input class="button button-small btn-primary" type="submit" value="Enviar" name="submitNewsletter" />
	<input type="hidden" name="action" value="0" />
</form>
{else}
<div id="newsletter_block_left" class="newsletter_block block">
	<div class="heading_block">
		<h4>
			<i class="icon icon-envelope main-color"></i>
			{l s='Newsletter' mod='blocknewsletter'}</h4>
	</div>
	<div class="block_content">
		<form action="{$link->getPageLink('index', null, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post" class="std">
			<div class="{if isset($msg) && $msg } {if $nw_error}form-error{else}form-ok{/if}{/if}" >
				<input class="inputNew form-control grey newsletter-input" id="newsletter-input" type="text" name="email" size="18" value="{if isset($msg) && $msg}{$msg}{elseif isset($value) && $value}{$value}{else}{l s='Introduzca su dirección de correo electrónico' mod='blocknewsletter'}{/if}" />
                <button type="submit" name="submitNewsletter" class="button button-small">
                    <span>{l s='Ok' mod='blocknewsletter'}</span>
                </button>
				<input type="hidden" name="action" value="0" />
			</div>
		</form>
	</div>
</div>
{/if}
<!-- /Block Newsletter module-->
{strip}
{if isset($msg) && $msg}
{addJsDef msg_newsl=$msg|@addcslashes:'\''}
{/if}
{if isset($nw_error)}
{addJsDef nw_error=$nw_error}
{/if}
{addJsDefL name=placeholder_blocknewsletter}{l s='Enter your e-mail' mod='blocknewsletter' js=1}{/addJsDefL}
{if isset($msg) && $msg}
	{addJsDefL name=alert_blocknewsletter}{l s='Newsletter : %1$s' sprintf=$msg js=1 mod="blocknewsletter"}{/addJsDefL}
{/if}
{/strip}
