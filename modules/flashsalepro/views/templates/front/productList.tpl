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

{extends file=$layout}
{block name='content'}
{if $flash_sale_info.date_end neq '0000-00-00 00:00:00'}
	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
		<div class="view-all-outer-center">
			<div class="view-all-inner-center">
				<span class="">
					<div class="clock"></div>
				</span>
			</div>
		</div>
	<div class="clear">&nbsp;</div>
	</div>
{/if}
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">

{foreach from=$flash_sale_items key=k item=v}
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="well">
			<a href="{$v.product_link}">
				<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="view-all-outer-center">
						<div class="view-all-inner-center">
							<img class="img-responsive flash-sale-product-list-img" src="{$v.custom_img_link}">
						</div>
					</div>
				</div>
				<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="view-all-outer-center">
						<div class="view-all-inner-center">
							<h1><a class="product-name" href="{$v.product_link}" style="color:inherit;">{$v.product_name}</a></h1>
						</div>
					</div>
				</div>

				<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="view-all-outer-center">
						<div class="view-all-inner-center">
							<div class="" style="margin-top:0px !important;">
								<div class="product-discount" style="margin-top:0px !important;">
									<span class="regular-price" style="margin-top:0px !important;">
										<center>
										{if $flash_sale_info.currency_format eq 1}
											{$v.currency_sign}&nbsp;{$v.product_price}
										{else}
											{$v.product_price|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign}
										{/if}
										</center>
									</span>
								</div>
								<div class="product-price h5 has-discount" itemprop="offers" style="margin-top:0px !important;">
									<div class="current-price" style="margin-top:0px !important;">
										<span itemprop="price" style="margin-top:0px !important;">
											{if $flash_sale_info.currency_format eq 1}
												{$v.currency_sign}&nbsp;{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}
											{else}
												{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign}
											{/if}
										</span>
										<span class="discount discount-percentage" style="margin-top:0px !important;">
											-{if $v.discount_type eq "percentage"}
												{$v.discount|escape:'htmlall':'UTF-8'}&nbsp;%
											{else}
												{if $flash_sale_info.currency_format eq 1}
													{$v.currency_sign}{$v.discount|escape:'htmlall':'UTF-8'}
												{else}
													{$v.discount|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign}{/if}
											{/if}
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</a>
			<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="view-all-outer-center">
					<div class="view-all-inner-center">
						{if $ps17 eq true}
						<a class="button btn btn-default btn btn-primary" href="{$v.product_link}" rel="nofollow" title="{l s='View Product' mod='flashsalepro'}">
							<span>{l s='View product' mod='flashsalepro'}</span>
						</a>
						{else}
						<a class="button ajax_add_to_cart_button btn btn-default btn btn-primary add-to-cart" href="{$ps_url}panier?add=1&amp;id_product={$v.id_product|intval}&amp;id_shop={$flash_sale_info.id_shop|intval}" rel="nofollow" title="{l s='Add to cart' mod='flashsalepro'}" data-id-product="{$v.id_product|intval}" data-minimal_quantity="1" id="fs_add_to_cart_{$v.id_product|intval}">
							<span>{l s='Add to cart' mod='flashsalepro'}</span>
						</a>
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
{/foreach}
<div class="clear">&nbsp;</div>
</div>

{literal}
<script type="text/javascript">
			var date_end = "{/literal}{$flash_sale_info.end_date_timestamp}{literal}";
			var lang_code = "{/literal}{$flash_sale_info.lang_code}{literal}";
			var timer_bg_color = "{/literal}{$timer_bg_color}{literal}";
			var timer_text_color = "{/literal}{$timer_text_color}{literal}";
			var timer_dot_color = "{/literal}{$timer_dot_color}{literal}";
			$(document).ready(function(){
				$('.autoplay').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 1000,
					speed: 5000
				});
			});
</script>
{/literal}
{/block}