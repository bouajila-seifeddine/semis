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

{if $block == 1}
	<!-- Block CMS module -->
	{foreach from=$cms_titles key=cms_key item=cms_title}
		<div class="block">
			<div class="heading_block dark">
				<h4>
					<i class="icon icon-quote-left"></i>
					<a href="{$cms_title.category_link|escape:'html':'UTF-8'}">
						{if !empty($cms_title.name)}{$cms_title.name}{else}{$cms_title.category_name}{/if}
					</a>
				</h4>
			</div>
			<div class="block_content">
				<ul>
					{foreach from=$cms_title.categories item=cms_page}
						{if isset($cms_page.link)}
							<li class="bullet">
								<a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.name|escape:'html':'UTF-8'}">
									{$cms_page.name|escape:'html':'UTF-8'}
								</a>
							</li>
						{/if}
					{/foreach}
					{foreach from=$cms_title.cms item=cms_page}
						{if isset($cms_page.link)}
							<li>
								<a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.meta_title|escape:'html':'UTF-8'}">
									{$cms_page.meta_title|escape:'html':'UTF-8'}
								</a>
							</li>
						{/if}
					{/foreach}
					{if $cms_title.display_store}
						<li>
							<a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockcms'}">
								{l s='Our stores' mod='blockcms'}
							</a>
						</li>
					{/if}
				</ul>
			</div>
		</div>
	{/foreach}
	<!-- /Block CMS module -->
{else}
	<!-- MODULE Block footer -->
	<div class="block_footer col-md-3 col-sm-6 col-xs-12">
		<h4>{l s='Information' mod='blockcms'}</h4>
		<ul class="toggle-footer">
			{if $show_price_drop && !$PS_CATALOG_MODE}
				<li class="item">
					<a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='Specials' mod='blockcms'}">
						{l s='Specials' mod='blockcms'}
					</a>
				</li>
			{/if}
			{if $show_new_products}
			<li class="item">
				<a href="{$link->getPageLink('new-products')|escape:'html':'UTF-8'}" title="{l s='New products' mod='blockcms'}">
					{l s='New products' mod='blockcms'}
				</a>
			</li>
			{/if}
			{if $show_best_sales && !$PS_CATALOG_MODE}
				<li class="item">
					<a href="{$link->getPageLink('best-sales')|escape:'html':'UTF-8'}" title="{l s='Top sellers' mod='blockcms'}">
						{l s='Top sellers' mod='blockcms'}
					</a>
				</li>
			{/if}
			
			{if $show_contact}
			<li class="item">
				<a href="{$link->getPageLink($contact_url, true)|escape:'html':'UTF-8'}" title="{l s='Contact us' mod='blockcms'}">
					{l s='Contact us' mod='blockcms'}
				</a>
			</li>
			{/if}
			{foreach from=$cmslinks item=cmslink}
				{if $cmslink.meta_title != ''}
					<li class="item">
						<a href="{$cmslink.link|escape:'html':'UTF-8'}" title="{$cmslink.meta_title|escape:'html':'UTF-8'}">
							{$cmslink.meta_title|escape:'html':'UTF-8'}
						</a>
					</li>
				{/if}
			{/foreach}
			{if $show_sitemap}
			<li>
				<a href="{$link->getPageLink('sitemap')|escape:'html':'UTF-8'}" title="{l s='Sitemap' mod='blockcms'}">
					{l s='Sitemap' mod='blockcms'}
				</a>
			</li>
			{/if}
		</ul>
		{$footer_text}
	</div>
	{if $display_poweredby}
	<section class="bottom-footer col-xs-12">
		<div>
			&copy; 2014 {l s='[1]Ecommerce software by %s[/1]' mod='blockcms' sprintf=['PrestaShop™'] tags=['<a class="_blank" href="http://www.prestashop.com">']}
		</div>
	</section>
	{/if}
	<!-- /MODULE Block footer -->
{/if}
