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
    #cookie-link {
        float: right;
        border-left: 1px solid #515151;
    }
    #cookie-link a {
        display: block;
        color: #fff;
        font-weight: bold;
        padding: 8px 10px 11px 10px;
        text-shadow: 1px 1px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        line-height: 18px;
        text-decoration: none;
    }
</style>

<div id="cookie-link">
    <a style="cursor:pointer" onclick="cookieGdpr.displayModalAdvanced(false);" title="{l s='Your cookie settings' mod='cookiesplus'}" rel="nofollow""><i class="icon-certificate"></i> {l s='Your cookie settings' mod='cookiesplus'}</a>
</div>