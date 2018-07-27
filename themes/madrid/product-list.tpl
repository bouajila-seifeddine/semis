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
{if isset($products) && $products && $smarty.server.REQUEST_URI|strstr:"/blog/"}
	   {*define number of products per line in other page for desktop*}
    {if $page_name !='index' && $page_name !='product'}
        {assign var='nbItemsPerLine' value=3}
        {assign var='nbItemsPerLineTablet' value=2}
        {assign var='nbItemsPerLineMobile' value=3}
    {else}
        {assign var='nbItemsPerLine' value=4}
        {assign var='nbItemsPerLineTablet' value=3}
        {assign var='nbItemsPerLineMobile' value=2}
    {/if}
    {*define numbers of product per line in other page for tablet*}
    {assign var='nbLi' value=$products|@count}
    {math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
    {math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$nbItemsPerLineTablet assign=nbLinesTablet}
    <!-- Products list -->
    <div {if isset($id) && $id} id="{$id}"{/if} class="product_list grid row{if isset($class) && $class} {$class}{/if}">
    {foreach from=$products item=product name=products}
        {math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$nbItemsPerLine assign=totModulo}
        {math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineTablet assign=totModuloTablet}
        {math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineMobile assign=totModuloMobile}
        {if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
        {if $totModuloTablet == 0}{assign var='totModuloTablet' value=$nbItemsPerLineTablet}{/if}
        {if $totModuloMobile == 0}{assign var='totModuloMobile' value=$nbItemsPerLineMobile}{/if}
        <div class="ajax_block_product {if $smarty.foreach.products.iteration%$nbItemsPerLine == 0} last-in-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLine == 1} first-in-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModulo)} last-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 0} last-item-of-tablet-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 0} last-item-of-mobile-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModuloMobile)} last-mobile-line{/if}">
            <div class="product-container product-container-blog" itemscope itemtype="http://schema.org/Product">
                <div class="left-block left-blockblog">
                    <div class="product-image-container">
                        <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" target="_blank">
                            <img class="replace-2x img-responsive" id="{$product.link_rewrite}-img" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
                        </a>
                     
                  
                       
                        {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                            <a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}"  target="_blank">
                                <span class="sale-label">{l s='Sale!'}</span>
                            </a>
                        {/if}
                    </div>
                    {hook h="displayProductDeliveryTime" product=$product}
                    {hook h="displayProductPriceBlock" product=$product type="weight"}
                </div>
                <div class="right-block right-blockblog">
                    <h5 itemprop="name">
                        {if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
                        <a class="product-name-blog" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url"  target="_blank">
                            {$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
                        </a>
                    </h5>
                    {hook h='displayProductListReviews' product=$product}
                   
                    {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                    <div class="content_price">
                        {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                            <span class="price product-price-blog" id="{$product.link_rewrite}-price">
                                {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                            </span>
                            {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                                {hook h="displayProductPriceBlock" product=$product type="old_price"}
                                <span class="old-price product-price">
                                    {displayWtPrice p=$product.price_without_reduction}
                                </span>
                                {hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
                                {if $product.specific_prices.reduction_type == 'percentage'}
                                    <span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
                                {/if}
                            {/if}
                            {hook h="displayProductPriceBlock" product=$product type="price"}
                            {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                        {/if}
                    </div>
                    {/if}
{* Added for attributes *}
{assign var="cantidad" value=$product.quantity}
{if $product.combis.values}
<div class="att_list" style="display:block;">

    <fieldset>
    	
        <div class="attribute_list">
            <table class="semillas_atributos_blog"><tbody>

         
    
        {foreach from=$product.combis.values key=id_product_attribute item=combination}
        {if $combination.quantity > 0}
        {assign var="cantidad" value=$cantidad + $combination.quantity}
        <tr>
            <td class="attributes_name_blog">{$combination.attributes_names|escape:'html':'UTF-8'}</td>
            <td class="semillas_atributos_radio_blog"> 
                <input type="radio" id="{$combination.reference|escape:'html':'UTF-8'}" class="attribute_radio" name="{$product.link_rewrite}" value="{$id_product_attribute|intval},{$combination.price+($combination.price*0.21)}" {if $id_product_attribute == $product.cache_default_attribute } checked="checked"{/if}><label for="{$combination.attributes_names|escape:'html':'UTF-8'}"><span><span></span></span></label>
            </td>
        </tr>
        {/if}
        {/foreach}
           <script>
            $(document).on("change","input[name='{$product.link_rewrite}']",function(){
            var radios = document.getElementsByName('{$product.link_rewrite}');
            var value;
            for (var i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    // get value, set checked flag or do whatever you need to
                    value = radios[i].value; 
                    var res = value.split(",");
                    var link = document.getElementById('{$product.link_rewrite}_link').href.toString();    
                    var reExp = /id_product_attribute=[0-9]*/;
                    var newText = link.replace(reExp,'id_product_attribute=' + res[0]);
                    document.getElementById('{$product.link_rewrite}_link').href = newText;
                    document.getElementById('{$product.link_rewrite}-price').innerHTML = parseFloat(res[1]).toFixed(2) + ' €';
                    

                   
                    
                }
            }
});

      function addCarrito(url, imgurl) {
       
$.ajax({
type: 'post',
url: url,        
         
success:function(response)
{
    // Get the modal
        var modal = document.getElementById('myModal');
       
    // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close2")[0];
        modal.style.display = "block";
        document.getElementById('modal-img').src = imgurl;
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
         modal.style.display = "none";
         // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}
document.getElementsByClassName("whatsappBlock2")[0].style.display = "block";
},

error : function(xhr, status, error)
{
    console.log("Status of error message" + status + "Error is" + error);
}   

});
      
                }
        </script>
    </tbody>
    </table>
    </div>
    </fieldset>
</div>
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close2">&times;</span>
    <p>Producto añadido correctamente al carrito de compra.</p>
    <img id="modal-img" src=""><br />
    <button class="btn btn-dark" onclick="document.getElementById('myModal').style.display = 'none'">Seguir Leyendo</button>
    <button class="btn info" onclick="window.open('https://www.semillaslowcost.com/pedido-rapido','_blank')">Ir al carrito</button>

  </div>

</div>
{/if}
{* Added for attributes *}


                    <div class="button-container add_cart_{$product.id_product}" ref="{$product.id_product}">

                        {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE && $cantidad > 0}
                            {if (!isset($product.customization_required) || !$product.customization_required)}
                                {capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
                                <a id="{$product.link_rewrite}_link" class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}{if $product.combis.values}&id_product_attribute={$product.cache_default_attribute}{/if}" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}"  target="_blank" onClick="event.preventDefault();addCarrito(document.getElementById('{$product.link_rewrite}_link').href, document.getElementById('{$product.link_rewrite}-img').src)">
                                    <span>COMPRAR</span>
                                </a>
                            {else}
                                <span class="button ajax_add_to_cart_button btn btn-default disabled">
                                    <span>{l s='Add to cart'}</span>
                                </span>
                            {/if}
                        {/if}
                        <a class="button lnk_view btn btn-default" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View'}"  target="_blank">
                            <span>{if (isset($product.customization_required) && $product.customization_required)}{l s='Customize'}{else}FICHA PRODUCTO{/if}</span>
                        </a>
                    </div>
                    {if isset($product.color_list)}
                        <div class="color-list-container">{$product.color_list}</div>
                    {/if}

                    <div class="product-flags">
                        {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                            {if isset($product.online_only) && $product.online_only}
                                <span class="online_only">{l s='Online only'}</span>
                            {/if}
                        {/if}
                        
                                <span class="discount-blog">Aprovecha ahora, producto de oportunidad!</span>
                            
                    </div>
               
                </div>
                {if $page_name != 'index'}
                     <div class="functional-buttons clearfix">
                        {hook h='displayProductListFunctionalButtons' product=$product}
                        {if isset($comparator_max_item) && $comparator_max_item}
                            <div class="compare">
                                <a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}">{l s='Add to Compare'}</a>
                            </div>
                        {/if}
                    </div>
                {/if}
            </div><!-- .product-container> -->
        </div>
    {/foreach}
    </div>
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{elseif isset($products) && $products}
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
							<h3 itemprop="name"><a itemprop="url" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'|truncate:25:'...'} </a></h3>

							{if $product.id_category_default == 21}
							<a href="/21-sweet-seeds-feminizadas"><span class="bank-name">Sweet Seeds</span></a>
							{/if}
							{if $product.id_category_default == 33}
							<a href="/33-sweet-seeds-autoflorecientes"><span class="bank-name">Sweet Seeds Autoflorecientes</span></a>
							{/if}


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