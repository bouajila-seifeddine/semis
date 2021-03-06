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

{capture name=path}{l s='Your shopping cart'}{/capture}

{assign var='current_step' value='summary'}
{include file="$tpl_dir./order-steps.tpl"}
{include file="$tpl_dir./errors.tpl"}
<div class="col-md-7 tabla-compra row">
<div id="order-detail-content" class="clearBoth">
	<h1 id="cart_title">{l s='Shopping-cart summary'}</h1>
	{if !isset($empty) && !$PS_CATALOG_MODE}
	<span class="heading-counter">{l s='Your shopping cart contains:'}
		<span id="summary_products_quantity">{$productNumber} {if $productNumber == 1}{l s='product'}{else}{l s='products'}{/if}</span>
	</span>
	{/if}

	{if isset($account_created)}
		<p class="alert alert-success clearBoth">
			{l s='Your account has been created.'}
		</p>
	{/if}

{if isset($empty)}
	<p class="alert alert-warning clearBoth">{l s='Your shopping cart is empty.'}</p>
</div><!-- #order-detail-content -->
{elseif $PS_CATALOG_MODE}
	<p class="alert alert-warning clearBoth">{l s='This store has not accepted your new order.'}</p>
</div><!-- #order-detail-content -->
{else}
	<p id="emptyCartWarning" class="alert alert-warning unvisible clearBoth">{l s='Your shopping cart is empty.'}</p>
	{assign var='total_discounts_num' value="{if $total_discounts != 0}1{else}0{/if}"}
	{assign var='use_show_taxes' value="{if $use_taxes && $show_taxes}2{else}0{/if}"}
	{assign var='total_wrapping_taxes_num' value="{if $total_wrapping != 0}1{else}0{/if}"}
	{* eu-legal *}
	{hook h="displayBeforeShoppingCartBlock"}
		<table id="cart_summary" class="table {if $PS_STOCK_MANAGEMENT}stock-management-on{else}stock-management-off{/if}">
			<thead>
				<tr>
					<th class="cart_product first_item">{l s='Product'}</th>
					<th class="cart_description item">{l s='Description'}</th>
					{if $PS_STOCK_MANAGEMENT}
						{assign var='col_span_subtotal' value='3'}
						
					{else}
						{assign var='col_span_subtotal' value='2'}
					{/if}
					<th class="cart_unit item text-right">{l s='Unit price'}</th>
					<th class="cart_quantity item text-center">Cantidad</th>
					<th class="cart_total item text-right">{l s='Total'}</th>
					<th class="cart_delete last_item">&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr class="cart_total_price">
					<td rowspan="{$rowspan_total}" colspan="7" class="cart_voucher">
						<a class="have_voucher" href="javascript:;" onclick="$('.div-boucher').toggle(); return false; ">¿Tienes un código de descuento?</a>
				
					</td>
				</tr>
			</tfoot>
			<tbody>

				{assign var='odd' value=0}
				{assign var='hay_vapeo' value=0}
				{assign var='have_non_virtual_products' value=false}
				{foreach $products as $product}
				
				
					{if $product.is_virtual == 0}
						{assign var='have_non_virtual_products' value=true}
					{/if}
					{assign var='productId' value=$product.id_product}
					{assign var='productAttributeId' value=$product.id_product_attribute}
					{assign var='quantityDisplayed' value=0}
					{assign var='odd' value=($odd+1)%2}
					{assign var='ignoreProductLast' value=isset($customizedDatas.$productId.$productAttributeId) || count($gift_products)}
					{if in_array(218,Product::getProductCategories($productId))}
						{assign var='hay_vapeo' value=1}
					{/if}
					{* Display the product line *}
					{include file="$tpl_dir./shopping-cart-product-line.tpl" productLast=$product@last productFirst=$product@first}
					{* Then the customized datas ones*}
					{if isset($customizedDatas.$productId.$productAttributeId)}
						{foreach $customizedDatas.$productId.$productAttributeId[$product.id_address_delivery] as $id_customization=>$customization}
							<tr
								id="product_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}"
								class="product_customization_for_{$product.id_product}_{$product.id_product_attribute}_{$product.id_address_delivery|intval}{if $odd} odd{else} even{/if} customization alternate_item {if $product@last && $customization@last && !count($gift_products)}last_item{/if}">
								<td></td>
								<td colspan="3">
									{foreach $customization.datas as $type => $custom_data}
										{if $type == $CUSTOMIZE_FILE}
											<div class="customizationUploaded">
												<ul class="nolist customizationUploaded">
													{foreach $custom_data as $picture}
														<li><img src="{$pic_dir}{$picture.value}_small" alt="" class="customizationUploaded" /></li>
													{/foreach}
												</ul>
											</div>
										{elseif $type == $CUSTOMIZE_TEXTFIELD}
											<ul class="nolist typedText">
												{foreach $custom_data as $textField}
													<li>
														{if $textField.name}
															{$textField.name}
														{else}
															{l s='Text #'}{$textField@index+1}
														{/if}
														: {$textField.value}
													</li>
												{/foreach}
											</ul>
										{/if}
									{/foreach}
								</td>
								<td class="cart_quantity" colspan="1">
									{if isset($cannotModify) AND $cannotModify == 1}
										<span>{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}{$customizedDatas.$productId.$productAttributeId|@count}{else}{$product.cart_quantity-$quantityDisplayed}{/if}</span>
									{else}
										<input type="hidden" value="{$customization.quantity}" name="quantity_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}_hidden"/>
										<input type="text" value="{$customization.quantity}" class="cart_quantity_input grey" name="quantity_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}"/>
										<div class="cart_quantity_button clearfix">
											{if $product.minimal_quantity < ($customization.quantity -$quantityDisplayed) OR $product.minimal_quantity <= 1}
												<a
													id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}"
													class="cart_quantity_down button-minus"
													href="{$link->getPageLink('cart', true, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_address_delivery={$product.id_address_delivery}&amp;id_customization={$id_customization}&amp;op=down&amp;token={$token_cart}")|escape:'html':'UTF-8'}"
													rel="nofollow"
													title="{l s='Subtract'}">
													<span><i class="icon icon-minus"></i></span>
												</a>
											{else}
												<a
													id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}"
													class="cart_quantity_down button-minus disabled"
													href="#"
													title="{l s='Subtract'}">
													<span><i class="icon icon-minus"></i></span>
												</a>
											{/if}
											<a
												id="cart_quantity_up_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}"
												class="cart_quantity_up button-plus"
												href="{$link->getPageLink('cart', true, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_address_delivery={$product.id_address_delivery}&amp;id_customization={$id_customization}&amp;token={$token_cart}")|escape:'html':'UTF-8'}"
												rel="nofollow"
												title="{l s='Add'}">
												<span><i class="icon icon-plus"></i></span>
											</a>
										</div>
									{/if}
								</td>
								<td>
								</td>
								<td class="cart_delete text-center">
									{if isset($cannotModify) AND $cannotModify == 1}
									{else}
										<a
											id="{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}"
											class="cart_quantity_delete"
											href="{$link->getPageLink('cart', true, NULL, "delete=1&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;id_address_delivery={$product.id_address_delivery}&amp;token={$token_cart}")|escape:'html':'UTF-8'}"
											rel="nofollow"
											title="{l s='Delete'}">
											<i class="icon icon-remove"></i>
										</a>
									{/if}
								</td>
								
							</tr>
							{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
						{/foreach}

						{* If it exists also some uncustomized products *}
						{if $product.quantity-$quantityDisplayed > 0}{include file="$tpl_dir./shopping-cart-product-line.tpl" productLast=$product@last productFirst=$product@first}{/if}
					{/if}
				{/foreach}
				{assign var='last_was_odd' value=$product@iteration%2}
				{foreach $gift_products as $product}
					{assign var='productId' value=$product.id_product}
					{assign var='productAttributeId' value=$product.id_product_attribute}
					{assign var='quantityDisplayed' value=0}
					{assign var='odd' value=($product@iteration+$last_was_odd)%2}
					{assign var='ignoreProductLast' value=isset($customizedDatas.$productId.$productAttributeId)}
					{assign var='cannotModify' value=1}
					{* Display the gift product line *}
					{include file="$tpl_dir./shopping-cart-product-line.tpl" productLast=$product@last productFirst=$product@first}
				{/foreach}
			</tbody>
			{if sizeof($discounts)}
				<tbody>
					{foreach $discounts as $discount}
						{if (float)$discount.value_real == 0}
							{continue}
						{/if}
						<tr class="cart_discount {if $discount@last}last_item{elseif $discount@first}first_item{else}item{/if}" id="cart_discount_{$discount.id_discount}">
							<td class="cart_discount_name" colspan="{if $PS_STOCK_MANAGEMENT}3{else}2{/if}">{$discount.name}</td>
							<td class="cart_discount_price">
								<span class="price-discount price">
								{if !$priceDisplay}{displayPrice price=$discount.value_real*-1}{else}{displayPrice price=$discount.value_tax_exc*-1}{/if}
								</span>
							</td>
							<td class="cart_discount_delete"><span class="cart_ph_input">1</span></td>
							<td class="cart_discount_price">
								<span class="price-discount price">{if !$priceDisplay}{displayPrice price=$discount.value_real*-1}{else}{displayPrice price=$discount.value_tax_exc*-1}{/if}</span>
							</td>
							<td class="price_discount_del text-center">
								{if strlen($discount.code)}
									<a
										href="{if $opc}{$link->getPageLink('order-opc', true)}{else}{$link->getPageLink('order', true)}{/if}?deleteDiscount={$discount.id_discount}"
										class="price_discount_delete"
										title="{l s='Delete'}">
										<i class="icon icon-remove"></i>
									</a>
								{/if}
							</td>
						</tr>
					{/foreach}
				</tbody>
			{/if}
		</table>
		{if $hay_vapeo == 0 && $conf_horario == 1 && $horario == "abierto"}
				<div class="horario-aviso">
					<p class="horario-aviso-p1">¡Compra ahora y recibeló mañana! <i class="fa fa-angle-down" onclick="$('#letra-peque').toggle();"></i></p>
					<p  class="horario-aviso-p2" style="display:none;" id="letra-peque">Válido únicamente en envíos realizados dentro de la península.</p>	
				</div>
		{/if}
		
	</div> <!-- end order-detail-content -->
					{if $voucherAllowed}
					<div class="div-boucher" style="display:none;">
						{if isset($errors_discount) && $errors_discount}

						<ul class="alert alert-danger">
						{foreach $errors_discount as $k=>$error}
							<li>{$error|escape:'html':'UTF-8'}</li>
						{/foreach}
						</ul>
						{/if}
						<form action="{if $opc}{$link->getPageLink('order-opc', true)}{else}{$link->getPageLink('order', true)}{/if}" method="post" id="voucher">
							<fieldset>
								<h4>{l s='Vouchers'}</h4>
								<input type="text" class="discount_name col-md-4" id="discount_name" name="discount_name" value="{if isset($discount_name) && $discount_name}{$discount_name}{/if}" placeholder="Introduce aquí el cupón descuento" />
								<input type="hidden" name="submitDiscount" />
								<button type="submit" name="submitAddDiscount" class="button button-large"><span>ENVIAR</span></button>
							</fieldset>
						</form>
						{if $displayVouchers}
						<p id="title" class="title-offers">{l s='Take advantage of our exclusive offers:'}</p>
						<div id="display_cart_vouchers">
							{foreach $displayVouchers as $voucher}
								{if $voucher.code != ''}<span class="voucher_name" data-code="{$voucher.code|escape:'html':'UTF-8'}">{$voucher.code|escape:'html':'UTF-8'}</span> - {/if}{$voucher.name}<br />
							{/foreach}
						</div>
						{/if}
						</div>
					{/if}
	

	<div class="summary-shopping col-md-8 pull-right">
		<div class="row">
			<div class="summary-bg">
				
				<table>
					{assign var='rowspan_total' value=2+$total_discounts_num+$total_wrapping_taxes_num}

					{if $use_taxes && $show_taxes && $total_tax != 0}
						{assign var='rowspan_total' value=$rowspan_total+1}
					{/if}

					{if $priceDisplay != 0}
						{assign var='rowspan_total' value=$rowspan_total+1}
					{/if}

					{if $total_shipping_tax_exc <= 0 && !isset($virtualCart)}
						{assign var='rowspan_total' value=$rowspan_total+1}
					{else}
						{if $use_taxes && $total_shipping_tax_exc != $total_shipping}
							{if $priceDisplay && $total_shipping_tax_exc > 0}
								{assign var='rowspan_total' value=$rowspan_total+1}
							{elseif $total_shipping > 0}
								{assign var='rowspan_total' value=$rowspan_total+1}
							{/if}
						{elseif $total_shipping_tax_exc > 0}
							{assign var='rowspan_total' value=$rowspan_total+1}
						{/if}
					{/if}

				{if $use_taxes}
					{if $priceDisplay}
						<tr>
							{*<td rowspan="{$rowspan_total}" colspan="3" id="cart_voucher" class="cart_voucher">
								{if $voucherAllowed}
									{if isset($errors_discount) && $errors_discount}
										<ul class="alert alert-danger">
											{foreach $errors_discount as $k=>$error}
												<li>{$error|escape:'html':'UTF-8'}</li>
											{/foreach}
										</ul>
									{/if}
									<form action="{if $opc}{$link->getPageLink('order-opc', true)}{else}{$link->getPageLink('order', true)}{/if}" method="post" id="voucher">
										<fieldset>
											<h4>{l s='Vouchers'}</h4>
											<input type="text" class="discount_name form-control" id="discount_name" name="discount_name" value="{if isset($discount_name) && $discount_name}{$discount_name}{/if}" />
											<input type="hidden" name="submitDiscount" />
											<button type="submit" name="submitAddDiscount" class="button btn btn-default button-small"><span>{l s='OK'}</span></button>
										</fieldset>
									</form>
									{if $displayVouchers}
										<p id="title" class="title-offers">{l s='Take advantage of our exclusive offers:'}</p>
										<div id="display_cart_vouchers">
											{foreach $displayVouchers as $voucher}
												{if $voucher.code != ''}<span class="voucher_name" data-code="{$voucher.code|escape:'html':'UTF-8'}">{$voucher.code|escape:'html':'UTF-8'}</span> - {/if}{$voucher.name}<br />
											{/foreach}
										</div>
									{/if}
								{/if}
							</td>*}
							<td>{if $display_tax_label}{l s='Total products (tax excl.)'}{else}{l s='Total products'}{/if}</td>
							<td class="price" id="total_product">{displayPrice price=$total_products}</td>
						</tr>
					{else}
						<tr>
							{*<td rowspan="{$rowspan_total}" colspan="2" id="cart_voucher" class="cart_voucher">
								{if $voucherAllowed}
									{if isset($errors_discount) && $errors_discount}
										<ul class="alert alert-danger">
											{foreach $errors_discount as $k=>$error}
												<li>{$error|escape:'html':'UTF-8'}</li>
											{/foreach}
										</ul>
									{/if}
									<form action="{if $opc}{$link->getPageLink('order-opc', true)}{else}{$link->getPageLink('order', true)}{/if}" method="post" id="voucher">
										<fieldset>
											<h4>{l s='Vouchers'}</h4>
											<input type="text" class="discount_name form-control" id="discount_name" name="discount_name" value="{if isset($discount_name) && $discount_name}{$discount_name}{/if}" />
											<input type="hidden" name="submitDiscount" />
											<button type="submit" name="submitAddDiscount" class="button btn btn-default button-small"><span>{l s='OK'}</span></button>
										</fieldset>
									</form>
									{if $displayVouchers}
										<p id="title" class="title-offers">{l s='Take advantage of our exclusive offers:'}</p>
										<div id="display_cart_vouchers">
											{foreach $displayVouchers as $voucher}
												{if $voucher.code != ''}<span class="voucher_name" data-code="{$voucher.code|escape:'html':'UTF-8'}">{$voucher.code|escape:'html':'UTF-8'}</span> - {/if}{$voucher.name}<br />
											{/foreach}
										</div>
									{/if}
								{/if}
							</td>*}
							<td>{if $display_tax_label}{l s='Total products (tax incl.)'}{else}{l s='Total products'}{/if}</td>
							<td class="price" id="total_product">{displayPrice price=$total_products_wt}</td>
						</tr>
					{/if}
				{else}
					<tr>
						{*<td rowspan="{$rowspan_total}" colspan="2" id="cart_voucher" class="cart_voucher">
							{if $voucherAllowed}
								{if isset($errors_discount) && $errors_discount}
									<ul class="alert alert-danger">
										{foreach $errors_discount as $k=>$error}
											<li>{$error|escape:'html':'UTF-8'}</li>
										{/foreach}
									</ul>
								{/if}
								<form action="{if $opc}{$link->getPageLink('order-opc', true)}{else}{$link->getPageLink('order', true)}{/if}" method="post" id="voucher">
									<fieldset>
										<h4>{l s='Vouchers'}</h4>
										<input type="text" class="discount_name form-control" id="discount_name" name="discount_name" value="{if isset($discount_name) && $discount_name}{$discount_name}{/if}" />
										<input type="hidden" name="submitDiscount" />
										<button type="submit" name="submitAddDiscount" class="button btn btn-default button-small">
											<span>{l s='OK'}</span>
										</button>
									</fieldset>
								</form>
								{if $displayVouchers}
									<p id="title" class="title-offers">{l s='Take advantage of our exclusive offers:'}</p>
									<div id="display_cart_vouchers">
										{foreach $displayVouchers as $voucher}
											{if $voucher.code != ''}<span class="voucher_name" data-code="{$voucher.code|escape:'html':'UTF-8'}">{$voucher.code|escape:'html':'UTF-8'}</span> - {/if}{$voucher.name}<br />
										{/foreach}
									</div>
								{/if}
							{/if}
						</td>*}
						<td>{l s='Total products'}</td>
						<td class="price" id="total_product">{displayPrice price=$total_products}</td>
					</tr>
				{/if}
				<tr{if $total_wrapping == 0} style="display: none;"{/if}>
					<td>
						{if $use_taxes}
							{if $display_tax_label}{l s='Total gift wrapping (tax incl.)'}{else}{l s='Total gift-wrapping cost'}{/if}
						{else}
							{l s='Total gift-wrapping cost'}
						{/if}
					</td>
					<td class="price-discount price" id="total_wrapping">
						{if $use_taxes}
							{if $priceDisplay}
								{displayPrice price=$total_wrapping_tax_exc}
							{else}
								{displayPrice price=$total_wrapping}
							{/if}
						{else}
							{displayPrice price=$total_wrapping_tax_exc}
						{/if}
					</td>
				</tr>
				{if $total_shipping_tax_exc <= 0 && !isset($virtualCart)}
					<tr class="cart_total_delivery{if !$opc && (!isset($cart->id_address_delivery) || !$cart->id_address_delivery)} unvisible{/if}">
						<td>{l s='Total shipping'}</td>
						<td class="price" id="total_shipping">{l s='Free Shipping!'}</td>
					</tr>
				{else}
					{if $use_taxes && $total_shipping_tax_exc != $total_shipping}
						{if $priceDisplay}
							<tr class="cart_total_delivery{if $total_shipping_tax_exc <= 0} unvisible{/if}">
								<td>{if $display_tax_label}{l s='Total shipping (tax excl.)'}{else}{l s='Total shipping'}{/if}</td>
								<td class="price" id="total_shipping">{displayPrice price=$total_shipping_tax_exc}</td>
							</tr>
						{else}
							<tr class="cart_total_delivery{if $total_shipping <= 0} unvisible{/if}">
								<td>{if $display_tax_label}{l s='Total shipping (tax incl.)'}{else}{l s='Total shipping'}{/if}</td>
								<td class="price" id="total_shipping" >{displayPrice price=$total_shipping}</td>
							</tr>
						{/if}
					{else}
						<tr class="cart_total_delivery{if $total_shipping_tax_exc <= 0} unvisible{/if}">
							<td>{l s='Total shipping'}</td>
							<td class="price" id="total_shipping" >{displayPrice price=$total_shipping_tax_exc}</td>
						</tr>
					{/if}
				{/if}
				<tr class="cart_total_voucher{if $total_discounts == 0} unvisible{/if}">
					<td>
						{if $display_tax_label}
							{if $use_taxes && $priceDisplay == 0}
								{l s='Total vouchers (tax incl.)'}
							{else}
								{l s='Total vouchers (tax excl.)'}
							{/if}
						{else}
							{l s='Total vouchers'}
						{/if}
					</td>
					<td class="price-discount price" id="total_discount">
						{if $use_taxes && $priceDisplay == 0}
							{assign var='total_discounts_negative' value=$total_discounts * -1}
						{else}
							{assign var='total_discounts_negative' value=$total_discounts_tax_exc * -1}
						{/if}
						{displayPrice price=$total_discounts_negative}
					</td>
				</tr>
				{if $use_taxes && $show_taxes && $total_tax != 0 }
					{if $priceDisplay != 0}
					<tr class="cart_total_price">
						<td>{if $display_tax_label}{l s='Total (tax excl.)'}{else}{l s='Total'}{/if}</td>
						<td class="price" id="total_price_without_tax">{displayPrice price=$total_price_without_tax}</td>
					</tr>
					{/if}
					
				{/if}
				<tr class="cart_total_price">
					<td class="total_price_container">
						<span>{l s='Total'}</span>
					</td>
					{if $use_taxes}
						<td class="price" id="total_price_container">
							<span id="total_price">{displayPrice price=$total_price}</span>
						</td>
					{else}
						<td class="price" id="total_price_container">
							<span id="total_price">{displayPrice price=$total_price_without_tax}</span>
						</td>
					{/if}
				</tr>
			</table>
		</div>
	</div><!-- .row -->
</div><!-- .summary-shopping --><div class="clearfix"></div>
</div>

	

	{if $show_option_allow_separate_package}
	<p>
		<label for="allow_seperated_package" class="checkbox inline">
			<input type="checkbox" name="allow_seperated_package" id="allow_seperated_package" {if $cart->allow_seperated_package}checked="checked"{/if} autocomplete="off"/>
			{l s='Send available products first'}
		</label>
	</p>
	{/if}

	{* Define the style if it doesn't exist in the PrestaShop version*}
	{* Will be deleted for 1.5 version and more *}
	{if !isset($addresses_style)}
		{$addresses_style.company = 'address_company'}
		{$addresses_style.vat_number = 'address_company'}
		{$addresses_style.firstname = 'address_name'}
		{$addresses_style.lastname = 'address_name'}
		{$addresses_style.address1 = 'address_address1'}
		{$addresses_style.address2 = 'address_address2'}
		{$addresses_style.city = 'address_city'}
		{$addresses_style.country = 'address_country'}
		{$addresses_style.phone = 'address_phone'}
		{$addresses_style.phone_mobile = 'address_phone_mobile'}
		{$addresses_style.alias = 'address_title'}
	{/if}

	{if ((!empty($delivery_option) AND !isset($virtualCart)) OR $delivery->id OR $invoice->id) AND !$opc}
		<div class="order_delivery clearBoth row">
			{if !isset($formattedAddresses) || (count($formattedAddresses.invoice) == 0 && count($formattedAddresses.delivery) == 0) || (count($formattedAddresses.invoice.formated) == 0 && count($formattedAddresses.delivery.formated) == 0)}
				{if $delivery->id}
					<div class="col-xs-12 col-sm-6"{if !$have_non_virtual_products} style="display: none;"{/if}>
						<ul id="delivery_address" class="address item box nolist">
							<li><h3 class="page-heading"><span>{l s='Delivery address'}&nbsp;<span class="address_alias">({$delivery->alias})</span></span></h3></li>
							{if $delivery->company}<li class="address_company">{$delivery->company|escape:'html':'UTF-8'}</li>{/if}
							<li class="address_name">{$delivery->firstname|escape:'html':'UTF-8'} {$delivery->lastname|escape:'html':'UTF-8'}</li>
							<li class="address_address1">{$delivery->address1|escape:'html':'UTF-8'}</li>
							{if $delivery->address2}<li class="address_address2">{$delivery->address2|escape:'html':'UTF-8'}</li>{/if}
							<li class="address_city">{$delivery->postcode|escape:'html':'UTF-8'} {$delivery->city|escape:'html':'UTF-8'}</li>
							<li class="address_country">{$delivery->country|escape:'html':'UTF-8'} {if $delivery_state}({$delivery_state|escape:'html':'UTF-8'}){/if}</li>
						</ul>
					</div>
				{/if}
				{if $invoice->id}
					<div class="col-xs-12 col-sm-6">
						<ul id="invoice_address" class="address alternate_item box nolist">
							<li><h3 class="page-eading"><span>{l s='Invoice address'}&nbsp;<span class="address_alias">({$invoice->alias})</span></span></h3></li>
							{if $invoice->company}<li class="address_company">{$invoice->company|escape:'html':'UTF-8'}</li>{/if}
							<li class="address_name">{$invoice->firstname|escape:'html':'UTF-8'} {$invoice->lastname|escape:'html':'UTF-8'}</li>
							<li class="address_address1">{$invoice->address1|escape:'html':'UTF-8'}</li>
							{if $invoice->address2}<li class="address_address2">{$invoice->address2|escape:'html':'UTF-8'}</li>{/if}
							<li class="address_city">{$invoice->postcode|escape:'html':'UTF-8'} {$invoice->city|escape:'html':'UTF-8'}</li>
							<li class="address_country">{$invoice->country|escape:'html':'UTF-8'} {if $invoice_state}({$invoice_state|escape:'html':'UTF-8'}){/if}</li>
						</ul>
					</div>
				{/if}
			{else}
				{foreach from=$formattedAddresses key=k item=address}
					<div class="col-xs-12 col-sm-6"{if $k == 'delivery' && !$have_non_virtual_products} style="display: none;"{/if}>
						<ul class="nolist address {if $address@last}last_item{elseif $address@first}first_item{/if} {if $address@index % 2}alternate_item{else}item{/if} box">
							<li>
								<h3 class="page-heading">
									<span>
									{if $k eq 'invoice'}
										{l s='Invoice address'}
									{elseif $k eq 'delivery' && $delivery->id}
										{l s='Delivery address'}
									{/if}
									{if isset($address.object.alias)}
										<span class="address_alias">({$address.object.alias})</span>
									{/if}
									</span>
								</h3>
							</li>
							{foreach $address.ordered as $pattern}
								{assign var=addressKey value=" "|explode:$pattern}
								{assign var=addedli value=false}
								{foreach from=$addressKey item=key name=foo}
								{$key_str = $key|regex_replace:AddressFormat::_CLEANING_REGEX_:""}
									{if isset($address.formated[$key_str]) && !empty($address.formated[$key_str])}
										{if (!$addedli)}
											{$addedli = true}
											<li><span class="{if isset($addresses_style[$key_str])}{$addresses_style[$key_str]}{/if}">
										{/if}
										{$address.formated[$key_str]|escape:'html':'UTF-8'}
									{/if}
									{if ($smarty.foreach.foo.last && $addedli)}
										</span></li>
									{/if}
								{/foreach}
							{/foreach}
						</ul>
					</div>
				{/foreach}
			{/if}
		</div>
	{/if}

	{if !empty($HOOK_SHOPPING_CART_EXTRA)}
		<div class="clear"></div>
		<div class="cart_navigation_extra">
			<div id="HOOK_SHOPPING_CART_EXTRA">{$HOOK_SHOPPING_CART_EXTRA}</div>
		</div>
	{/if}
{strip}
{addJsDef currencySign=$currencySign|html_entity_decode:2:"UTF-8"}
{addJsDef currencyRate=$currencyRate|floatval}
{addJsDef currencyFormat=$currencyFormat|intval}
{addJsDef currencyBlank=$currencyBlank|intval}
{addJsDef deliveryAddress=$cart->id_address_delivery|intval}
{addJsDefL name=txtProduct}{l s='product' js=1}{/addJsDefL}
{addJsDefL name=txtProducts}{l s='products' js=1}{/addJsDefL}
{/strip}
{/if}
