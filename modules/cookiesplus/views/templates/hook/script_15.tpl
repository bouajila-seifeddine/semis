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

<script>
    // <![CDATA[
    var C_P_COOKIE_VALUE = "{if isset($C_P_COOKIE_VALUE)}{$C_P_COOKIE_VALUE|escape:'htmlall':'UTF-8'}{/if}";
    var C_P_DEFAULT_VALUE = {$C_P_DEFAULT_VALUE|intval};
    var C_P_VERSION = "{$C_P_VERSION|escape:'htmlall':'UTF-8'}";
    var C_P_THEME_NAME = "{$C_P_THEME_NAME|escape:'htmlall':'UTF-8'}";
    var C_P_CMS = "{$C_P_CMS|escape:'htmlall':'UTF-8'}";

    {if isset($C_P_SCRIPT) && $C_P_SCRIPT}
        {* Do not escape this var, it include JS code *}
        {$C_P_SCRIPT nofilter}
    {/if}
    // ]]>
</script>
