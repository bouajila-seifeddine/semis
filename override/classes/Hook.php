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
class Hook extends HookCore
{
    /*
    * module: cookiesplus
    * date: 2018-06-22 12:21:33
    * version: 1.1.4
    */
    public static function getHookModuleExecList($hook_name = null)
    {
        $modules = parent::getHookModuleExecList($hook_name);
        if (Module::isEnabled('cookiesplus')) {
            include_once(_PS_MODULE_DIR_.'cookiesplus/cookiesplus.php');
            return CookiesPlus::updateCookie($modules);
        }
        return $modules;
    }
}
