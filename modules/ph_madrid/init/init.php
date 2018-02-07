<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once('../../../config/config.inc.php');
include_once('../../../init.php');

if (!defined('_PS_VERSION_'))
    exit;

Configuration::updateValue('PS_ALLOW_MOBILE_DEVICE', 0);
Configuration::updateValue('PS_NAVIGATION_PIPE', '>');
Configuration::updateValue('FOOTER_POWEREDBY', 0);
Configuration::updateValue('FOOTER_CMS', null);
Configuration::updateValue('PS_JS_DEFER', 1);
Configuration::updateValue('PS_CSS_THEME_CACHE', 1);
Configuration::updateValue('PS_ALLOW_HTML_IFRAME', 1);
Configuration::updateValue('PS_IMAGE_QUALITY', 'png');