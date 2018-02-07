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
{if $new_products OR $specials OR $bestsellers}
<div id="ph_tabs_home">
	<h3 class="page-heading grey"><span>{l s='Our offer' mod='ph_tabs_home'}</span></h3>
	<ul class="nav nav-tabs">
		{if $new_products && count($new_products) > 0}
		<li>
			<a data-toggle="tab" href="#tab1">{l s='New products' mod='ph_tabs_home'}</a>
		</li>
		{/if}

		{if $specials && count($specials) > 0}
		<li>
			<a data-toggle="tab" href="#tab2">{l s='Sale' mod='ph_tabs_home'}</a>
		</li>
		{/if}

		{if $bestsellers && count($bestsellers) > 0}
		<li>
			<a data-toggle="tab" href="#tab3">{l s='Bestsellers' mod='ph_tabs_home'}</a>
		</li>
		{/if}
	</ul>
	<div class="tab-content">
		{if $new_products && count($new_products) > 0}
		<div class="tab-pane fade" id="tab1">
			<div class="owl-carousel-ph">
			{include file="$tpl_dir./product-list.tpl" products=$new_products custom_columns=Configuration::get('PH_TABS_HOME_PRODUCTS_IN_ROW')|intval}
			</div>
		</div><!-- .tab-pane -->
		{/if}

		{if $specials && count($specials) > 0}
		<div class="tab-pane fade" id="tab2">
			{include file="$tpl_dir./product-list.tpl" products=$specials custom_columns=Configuration::get('PH_TABS_HOME_PRODUCTS_IN_ROW')|intval}
		</div><!-- .tab-pane -->
		{/if}

		{if $bestsellers && count($bestsellers) > 0}
		<div class="tab-pane fade" id="tab3">
			{include file="$tpl_dir./product-list.tpl" products=$bestsellers custom_columns=Configuration::get('PH_TABS_HOME_PRODUCTS_IN_ROW')|intval}
		</div><!-- .tab-pane -->
		{/if}

	</div><!-- .tab-content -->
</div><!-- #ph_tabs_home -->
<script>
$(function() {
	$('#ph_tabs_home .nav li', this).first().addClass('active');
	$('#ph_tabs_home .tab-content .tab-pane', this).first().addClass('active in');
});
</script>
{/if}
