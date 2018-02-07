{*
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css" media="all">
<link href="https://fonts.googleapis.com/css?family=Hind" rel="stylesheet" type="text/css" media="all">
<link href="https://fonts.googleapis.com/css?family=Maven+Pro" rel="stylesheet" type="text/css" media="all">
<link href="https://fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet" type="text/css" media="all">
<link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet" type="text/css" media="all">
<link href="https://fonts.googleapis.com/css?family=Forum" rel="stylesheet" type="text/css" media="all">


<div id="ps_banner_ajax">

	{include file="./banner-html.tpl"}

    <script>
        window.topBanner = {
            cta_link: '{$banner['cta_link']|escape:'quotes':'UTF-8'}{* url *}',
            token: '{$token|escape:'htmlall':'UTF-8'}',
            front_controller: '{$front_controller nofilter}'
        };
    </script>


</div>
