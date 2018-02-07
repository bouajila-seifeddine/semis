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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<tr id="row_category_{$id_category|escape:'htmlall':'UTF-8'}">
	<td>{$id_category|escape:'htmlall':'UTF-8'}</td>
	<td>{l s='Category' mod='flashsalepro'}</td>
	<td>{$category_name|escape:'htmlall':'UTF-8'}</td>
	<td>
	<div class="row category">
		<div id="flash_type_div" style="float:left;width:53px;">
		    <div class="btn-group" data-toggle="buttons">
		        <label for="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_amount" class="btn btn-info" id="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_amount_label" onclick="update_payment_type_div_class('category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_amount_label', {$id_category|escape:'intval'}, 'category');">
		                <input type="radio" name="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type" id="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_amount" value="amount" checked='checked'>{$default_currency_sign|escape:'htmlall':'UTF-8'}
		        </label>
		        <label for="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_percent" class="btn btn-default" id="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_percent_label" onclick="update_payment_type_div_class('category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_percent_label', {$id_category|escape:'intval'}, 'category');">
		                <input type="radio" name="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type" id="category_{$id_category|escape:'htmlall':'UTF-8'}_discount_type_percent" value="percentage">%
		        </label>
		    </div>
		</div>
		<div class="input-group flash-sale-amount-div">
			<div class="input-group-addon" id="{$id_category|escape:'htmlall':'UTF-8'}category_discount_type_symbol">{$default_currency_sign|escape:'htmlall':'UTF-8'}</div>
				<input class="form-control flash-sale-amount-input" placeholder="{l s='Amount' mod='flashsalepro'}" type="text" id="amount_category{$id_category|escape:'htmlall':'UTF-8'}" name="amount_category{$id_category|escape:'htmlall':'UTF-8'}" onblur="insertItemDiscountToDB({$id_category|escape:'intval'}, 'category')">
				<input type="hidden" id="id_category{$id_category|escape:'htmlall':'UTF-8'}" name="id_category{$id_category|escape:'htmlall':'UTF-8'}" value="{$id_category|escape:'htmlall':'UTF-8'}">
			</div>
		</div>
	</div>
	</td>
	<td><i class="icon-times" onClick="removeItemFromTable({$id_category|escape:'intval'}, 'category')" style="color:red;cursor:pointer;"></i></td>
</tr>