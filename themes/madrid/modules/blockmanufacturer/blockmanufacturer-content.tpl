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

<!-- Block manufacturers module -->
{if $manufacturers}
<div class="manufacturers carousel-style animated" data-fx="fadeIn">
	<div class="heading_block margin-bottom">
		<h4 class="pull-left">
			<i class="icon icon-plus-circle main-color"></i>
			<strong>{l s='our' mod='blockmanufacturer'}</strong> {l s='manufacturers' mod='blockmanufacturer'}
		</h4>
		{if $manufacturers|@count > 6}
		<div class="arrow_container pull-right">
			<a href="#" class="arrow-ph arrow-prev" title="{l s='Previous' mod='blockmanufacturer'}"><i class="icon icon-angle-left"></i></a>
			<a href="#" class="arrow-ph arrow-next" title="{l s='Next' mod='blockmanufacturer'}"><i class="icon icon-angle-right"></i></a>
		</div>
		{/if}
	</div>
	<div class="row">
		<div class="product_list_ph owl-carousel-ph clearBoth" data-max-items="6">
			{foreach from=$manufacturers item=manufacturer name=manufacturer_list}
			<div class="col-md-2 col-sm-2 col-xs-12 item">
				<a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html':'UTF-8'}" title="{l s='More about %s' mod='blockmanufacturer' sprintf=[$manufacturer.name]}">
					<img src="{$img_manu_dir}{$manufacturer.id_manufacturer}-ph_manu.jpg" alt="{$manufacturer.name|escape:'html':'UTF-8'}" class="img-repsonsive" />
				</a>
			</div>
			{/foreach}
		</div>
	</div>
</div><!-- .manufacturers -->
{/if}
<!-- /Block manufacturers module -->
