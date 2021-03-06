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
{if count($categoryProducts) > 0 && $categoryProducts !== false}
<section class="page-product-box blockproductscategory carousel-style">
	<div class="heading_block">
        <h3 class="pull-left">
            <i class="icon icon-bars main-color"></i>
            <strong>{$categoryProducts|@count}</strong> {l s='other products in the same category:' mod='productscategory'}
        </h3>
        {include file="$tpl_dir./carousel-arrows.tpl" products=$categoryProducts}
    </div>
	<div id="productscategory_list" class="clearfix">
		{include file="$tpl_dir./product-list-light.tpl" products=$categoryProducts}
	</div>
</section>
{/if}