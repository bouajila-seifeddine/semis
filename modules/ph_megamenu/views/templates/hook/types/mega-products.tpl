{*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*}
{if $megamenu.display_title}
	<h3>
		{if $megamenu.url != ''}
		<a href="{$megamenu.url|escape:'htmlall':'UTF-8'}" title="{$megamenu.title|escape:'htmlall':'UTF-8'}">
		{/if}
			{if $megamenu.icon != ''}
				<i class="fa {$megamenu.icon|escape:'htmlall':'UTF-8'}"></i>
			{/if}
			
			{$megamenu.title|escape:'htmlall':'UTF-8'}
		{if $megamenu.url != ''}
		</a>
		{/if}
	</h3>
{/if}

{if sizeof($megamenu.products)}
<div class="product_list_ph">
	{foreach $megamenu.products as $product}
	<div class="ph_product_item ajax_block_product menu_product_{$product.id_product|intval} ph-col ph-col-{Configuration::get('PH_MM_PRODUCT_WIDTH')|intval} col-sm-6 col-xs-12" itemscope itemtype="http://schema.org/Product">
		<div class="ph_product_image">
			<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}" itemprop="url">
				<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'htmlall':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width|intval}" height="{$homeSize.height|intval}"{/if} itemprop="image" />
			</a>
		</div><!-- .ph_product_image -->

		{if Configuration::get('PH_MM_PRODUCT_SHOW_TITLE')}
		<p class="ph_product_name" itemprop="name">
			{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
			<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}" itemprop="url" >
				{$product.name|truncate:45:'...'|escape:'htmlall':'UTF-8'}
			</a>
		</p><!-- .ph_product_name -->
		{/if}
		
		{if Configuration::get('PH_MM_PRODUCT_SHOW_DESC')}
		<p class="ph_product_desc" itemprop="description">
			{$product.description_short|strip_tags|escape:'htmlall':'UTF-8'|truncate:120:'...'}
		</p>
		{/if}

		{if Configuration::get('PH_MM_PRODUCT_SHOW_PRICE')}
			{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
			<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="ph_product_price">
				{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
					<span itemprop="price" class="price product-price">
						{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
					</span>
					<meta itemprop="priceCurrency" content="{$currency->iso_code|escape:'htmlall':'UTF-8'}" />
					{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
						{hook h="displayProductPriceBlock" product=$product type="old_price"}
						<span class="old-price product-price">
							{displayWtPrice p=$product.price_without_reduction}
						</span>
						{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
						{if $product.specific_prices.reduction_type == 'percentage'}
							<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100|escape:'htmlall':'UTF-8'}%</span>
						{/if}
					{/if}
					{hook h="displayProductPriceBlock" product=$product type="price"}
					{hook h="displayProductPriceBlock" product=$product type="unit_price"}
				{/if}
			</div>
			{/if}
		{/if}

		<div class="ph_product_btn">
			{if Configuration::get('PH_MM_PRODUCT_SHOW_ADD2CART')}
				{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
					{if ($product.allow_oosp || $product.quantity > 0)}
						{if isset($static_token)}
							<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'htmlall':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='ph_megamenu'}" data-id-product="{$product.id_product|intval}">
								<span>{l s='Add to cart' mod='ph_megamenu'}</span>
							</a>
						{else}
							<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'htmlall':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='ph_megamenu'}" data-id-product="{$product.id_product|intval}">
								<span>{l s='Add to cart' mod='ph_megamenu'}</span>
							</a>
						{/if}
					{else}
						<span class="button ajax_add_to_cart_button btn btn-default disabled">
							<span>{l s='Add to cart' mod='ph_megamenu'}</span>
						</span>
					{/if}
				{/if}
			{/if}
			{if Configuration::get('PH_MM_PRODUCT_SHOW_VIEW')}
			<a itemprop="url" class="button lnk_view btn btn-default" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='View' mod='ph_megamenu'}">
				<span>{l s='More' mod='ph_megamenu'}</span>
			</a>
			{/if}
		</div>
	</div><!-- .ph_product_item -->
	{/foreach}
</div>
{/if}
