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
{if isset($products) && $products}
	<!-- Products list -->
	<div class="product_list product_list_ph clearBoth items-{$theme_options['ph_list_items']}">
		<div class="row">
			{foreach from=$products item=product name=products}
			<div class="
				{if $theme_options['ph_list_items'] == 2}col-sm-6
				{else if $theme_options['ph_list_items'] == 3}col-md-4 col-sm-4
				{else if $theme_options['ph_list_items'] == 4}col-md-3 col-sm-6
				{else if $theme_options['ph_list_items'] == 6}col-md-2 col-sm-6
				{else}col-md-4 col-sm-6{/if} 
				col-xs-12 product" itemtype="http://schema.org/Product" itemscope="">
				<div class="bg">
					<div class="inner second-image">
						<div class="img_hover"></div>

						<a itemprop="url" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
							<img itemprop="image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" class="img-responsive first-image" />
							{hook h='displayProductSecondImage' product=$product}
						</a>

						<!-- labels, flags -->
						<span class="labels">
							{if isset($product.new) && $product.new == 1}
							<span class="new">{l s='Nuevo'}</span>
							{/if}

							{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
							<span class="sale">{l s='Sale!'}</span>
							{/if}

							{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
								{if isset($product.online_only) && $product.online_only}
								<span>{l s='Online only'}</span>
								{/if}
							{/if}
							{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
								{elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
								<span class="sale">{l s='Producto con descuento!'}</span>
							{/if}

							{if !$product.available_for_order || isset($restricted_country_mode) || $product.quantity < 1 && !$product.allow_oosp}
								{if $theme_options['ph_display_noavailable_text']}
									<span>{$theme_options['ph_noavailable_text']}</span>
								{/if}
							{/if}
						</span>

						<!-- useful icons -->
						<div class="icons">
							{if $theme_options['ph_display_add2cart']}
							{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
								{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
									{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
									<a class="ajax_add_to_cart_button add_to_cart main-color" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
										<i class="icon icon-shopping-cart"></i>
									</a>
								{else}
									<span class="ajax_add_to_cart_button add_to_cart disabled">
										<i class="icon icon-shopping-cart"></i>
									</span>
								{/if}
							{/if}
							{/if}
							{hook h='displayProductListFunctionalButtons' product=$product}
							{if $page_name != 'index'}
								{if isset($comparator_max_item) && $comparator_max_item}
								<a class="add_to_compare main-color" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='Add to Compare'}" data-id-product="{$product.id_product}">
									<i class="icon icon-bar-chart"></i>
								</a>
								{/if}
							{/if}

							{if isset($quick_view) && $quick_view && $theme_options['ph_quickview'] == '1'}
							<a class="quick-view main-color" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}">
								<i class="icon icon-eye"></i>
							</a>
							{/if}
						</div>
						<div class="info">
							<h3 itemprop="name"><a itemprop="url" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'|truncate:25:'...'}</a></h3>
							{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
							<div class="price" itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
								{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode) && $product.price != 0}

									<span itemprop="price" class="price {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0 && $theme_options['ph_display_price_wo_reduction']}new-price{/if}">
										{if !$priceDisplay}{convertPrice price=$product.price}
										{else}

										{convertPrice price=$product.price_tax_exc}
										{/if}
									</span>
									<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
									{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0 && $theme_options['ph_display_price_wo_reduction']}
										{hook h="displayProductPriceBlock" product=$product type="old_price"}
										<span class="old-price">
											{displayWtPrice p=$product.price_without_reduction}
										</span>
										{*{if $product.specific_prices.reduction_type == 'percentage'}
											<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
										{/if}*}
									{/if}
									{hook h="displayProductPriceBlock" product=$product type="price"}
									{hook h="displayProductPriceBlock" product=$product type="unit_price"}
								{/if}
							</div>
							{/if}
						</div>
					</div><!-- .inner -->
					<div class="list_info">
						{hook h='displayProductListReviews' product=$product}
						<div class="btn-container">
							{hook h='displayProductListFunctionalButtons' product=$product}
							{if $page_name != 'index'}
								{if isset($comparator_max_item) && $comparator_max_item}
								<a class="add_to_compare button-m" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='Add to Compare'}" data-id-product="{$product.id_product}">
									<i class="icon icon-bar-chart"></i>
								</a>
								{/if}
							{/if}
						</div>
						<h3><a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'|truncate:45:'...'}</a></h3>
						<div class="price">
							{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
								<span class="price {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}new-price{/if}">
									{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
								</span>
								{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									{hook h="displayProductPriceBlock" product=$product type="old_price"}
									<span class="old-price">
										{displayWtPrice p=$product.price_without_reduction}
									</span>
									{if $product.specific_prices.reduction_type == 'percentage'}
										<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
									{/if}
								{/if}
								{hook h="displayProductPriceBlock" product=$product type="price"}
								{hook h="displayProductPriceBlock" product=$product type="unit_price"}
							{/if}
						</div>

						<p itemprop="description">
							{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
						</p>

						{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
							{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
								{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
								<a class="ajax_add_to_cart_button add_to_cart button btn-primary" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
									{l s='Add to cart'}
								</a>
							{else}
								<span class="ajax_add_to_cart_button add_to_cart button btn-primary">
									{l s='Out of stock'}
								</span>
							{/if}
						{/if}
					</div><!-- .list_info -->
				</div><!-- .bg -->
			</div>
			{/foreach}
		</div><!-- .row -->
	</div><!-- .product_list_ph -->	
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/if}
