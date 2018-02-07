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

<!-- MODULE Block contact infos -->
<div class="block_footer block_contact col-md-3 col-sm-6 col-xs-12" itemtype="http://schema.org/Organization" itemscope="">
	<div>
        <h4 class="dark">{l s='contact with us' mod='blockcontactinfos'}</h4>
        <div class="toggle-footer">
            <div class="item">
                <i class="pull-left icon icon-map-marker"></i>
                <p>
                   {if $blockcontactinfos_company != ''}<span itemprop="name">{$blockcontactinfos_company|escape:'html':'UTF-8'}</span>{/if}
                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        {if $blockcontactinfos_address_1 != ''}<span itemprop="streetAddress">{$blockcontactinfos_address_1|escape:'html':'UTF-8'}</span>{/if}
                        {if $blockcontactinfos_address_2 != ''}<span itemprop="addressLocality">{$blockcontactinfos_address_2|escape:'html':'UTF-8'}</span>{/if}
                        {if $blockcontactinfos_address_3 != ''}<span itemprop="postalCode">{$blockcontactinfos_address_3|escape:'html':'UTF-8'}</span>{/if}
                    </span>
                </p>
            </div><!-- .item -->

            {if $blockcontactinfos_phone_1 != ''}
            <div class="item even">
                <i class="pull-left icon icon-phone"></i>
                <p>
                    <span itemprop="telephone">{$blockcontactinfos_phone_1|escape:'html':'UTF-8'}</span>
                    <span>{$blockcontactinfos_phone_2|escape:'html':'UTF-8'}</span>
                </p>
            </div><!-- .item -->
            {/if}

            {if $blockcontactinfos_email != ''}
            <div class="item last-item mail">
                <i class="pull-left icon icon-envelope"></i>
                <p itemprop="email">
                    {$blockcontactinfos_email|escape:'html':'UTF-8'}
                </p>
            </div><!-- .item -->
            {/if}
        </div>
    </div>
</div>
<!-- /MODULE Block contact infos -->
