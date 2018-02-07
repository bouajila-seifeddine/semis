{**
* 2007-2017 PrestaShop
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
* @copyright 2007-2017 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<h3><i class="icon-book"></i> {l s='Documentation' mod='topbanner'} </h3>
<div class="form-group">
    <p>{l s='The Top Banner module developed by PrestaShop allows you to active a banner (with the possibility of adding a timer) on top of every pages of your website.' mod='topbanner'}</p>
    <p>{l s='You will be able to' mod='topbanner'}</p>
    <ul>
        <li>{l s='Promote your coupon codes and run successfully your sales promotions' mod='topbanner'}</li>
        <li>{l s='Get in contact with your visitors more easily and efficiently' mod='topbanner'}</li>
        <li>{l s='Increase the conversion rate on your website and your sales' mod='topbanner'}</li>
    </ul>
    <br>
    <p>
        {if $iso_code != 'fr'}
        <a class="btn btn-default" href="{$module_dir}readme_en.pdf" target="_blank"><i class="icon-book" aria-hidden="true"></i>&nbsp;{l s='Documentation' mod='topbanner'}</a>
        {else}
        <a class="btn btn-default" href="{$module_dir}readme_fr.pdf" target="_blank"><i class="icon-book" aria-hidden="true"></i>&nbsp;{l s='Documentation' mod='topbanner'}</a>
        {/if}
    </p>
</div>