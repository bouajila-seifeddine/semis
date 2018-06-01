<?php
/**
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
*/

class Module extends ModuleCore
{
    public static function hookExec($hook_name, $hookArgs = array(), $id_module = null)
    {
        if (version_compare(_PS_VERSION_, '1.5', '>=') || !Module::isInstalled('cookiesplus')) {
            return parent::hookExec($hook_name, $hookArgs, $id_module);
        } else {
            include_once(_PS_MODULE_DIR_.'cookiesplus/cookiesplus.php');
            return CookiesPlus::updateCookie14($hook_name, $hookArgs, $id_module);
        }
    }

    public static function hookExecPayment()
    {
        if (version_compare(_PS_VERSION_, '1.5', '>=') || !Module::isInstalled('cookiesplus')) {
            return parent::hookExecPayment();
        } else {
            include_once(_PS_MODULE_DIR_.'cookiesplus/cookiesplus.php');
            return CookiesPlus::hookExecPayment14();
        }
    }
}
