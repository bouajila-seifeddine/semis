{*
 * Cookies Plus
 *
 * NOTICE OF LICENSE
 *
 * This product is licensed for one customer to use on one installation (test stores and multishop included).
 * Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
 * whole or in part. Any other use of this module constitues a violation of the user agreement.
 *
 * DISCLAIMER
 *
 * NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
 * ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
 * WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
 * PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
 * IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
 *
 *  @author    idnovate.com <info@idnovate.com>
 *  @copyright 2018 idnovate.com
 *  @license   See above
*}

<style>
    .col-xs-6 {
        width: 50%;
        float: left;
        position: relative;
        min-height: 1px;
        padding-left: 5px;
        padding-right: 5px;
        box-sizing: border-box;
    }

    .col-xs-6:nth-child(2n+1){
        clear: left;
    }

    .module-list { overflow: hidden;}
    .module-list label { display: inline; }
    .module-list img { vertical-align: top; }
    .module-list div { margin-bottom: 10px; }
</style>

<div class="module-list">
    {foreach from=$allModules item=module}
    <div class="col-xs-6">
        <img src="../modules/{$module->name|escape:'htmlall':'UTF-8'}/logo.png" alt="{$module->displayName|escape:'htmlall':'UTF-8'}" width="32" height="32" />
        <input type="checkbox" name="{$fieldName|escape:'htmlall':'UTF-8'}[]" id="{$fieldName|escape:'htmlall':'UTF-8'}_module_{$module->id|escape:'htmlall':'UTF-8'}" value="{$module->id|escape:'htmlall':'UTF-8'}" {if isset($module->checked) && $module->checked}checked=checked{/if} />
        <label for="{$fieldName|escape:'htmlall':'UTF-8'}_module_{$module->id|escape:'htmlall':'UTF-8'}">{$module->displayName|escape:'htmlall':'UTF-8'}</label> ({$module->name|escape:'htmlall':'UTF-8'})
    </div>
    {/foreach}
</div>