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
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$language_code|escape:'html':'UTF-8'}"><![endif]-->
<html lang="{$language_code|escape:'html':'UTF-8'}">
	<head>
		<meta charset="utf-8" />

{if $page_name == "index"}
		<title>Venta de Semillas de Marihuana - Comprar en Semillas Low Cost</title>

{else}
		<title>{$meta_title|escape:'html':'UTF-8'}</title>
{/if}

{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
{/if}
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}{/if}follow" />
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=5.0, initial-scale=1.0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="theme-color" content="#7BBD42"/>
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />

		
<meta name="ahrefs-site-verification" content="24ee5c6c2ac37d315bdf8cb6a3404f792950bc69d672644bba85ac204d254d73">
		<link rel="stylesheet" href="https://www.semillaslowcost.com/fontawesome/css/font-awesome.min.css">

{$dir=$smarty.server.REQUEST_URI}

{if isset($css_files)  && strpos($dir,'blog')==false}
	{foreach from=$css_files key=css_uri item=media}
		<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
	{/foreach}
{/if}



		{if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def) && strpos($dir,'blog')==false}
			{$js_def}
			{foreach from=$js_files item=js_uri}
			<script src="{$js_uri|escape:'html':'UTF-8'}"></script>
			{/foreach}
		{/if}
		{if $page_name != 'manufacturer' && $page_name != 'supplier' && strpos($dir,'blog')==false}
    		{$HOOK_HEADER}
		{/if}
		




		
		{*
		<link rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext" type="text/css" media="all" />
		*}


		<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		{if $theme_options['use_custom_fonts'] == '1'}
		<link href="{$base_dir_ssl}modules/prestahome/views/css/customFonts.css" rel="stylesheet" media="all" />
		{/if}	
		

{if $page_name =='index'}


<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "Organization",
		"name": "Semillas Low Cost",
		"url": "https://www.semillaslowcost.com/",
		"logo": "https://www.semillaslowcost.com/img/semillaslowcost-logo-1515411406.jpg",
		"telephone": [
				"960992794",
				"+34653323445 "
		],
	
		"contactPoint": [
			{
				"@type": "ContactPoint",
				"telephone": "+34653323445",
				"contactType": "customer service"
				
			}
		],
		"sameAs": [
			"https://www.facebook.com/SemillasLowCost/",
			"https://www.instagram.com/semillaslowcost/",
			"https://twitter.com/SemillasLowCost"
		]
	}
</script>
{/if}
	</head>
	


	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso} cssAnimate{if $theme_options['ph_layout'] == 'boxed'} boxed{/if}">


{block name='header_banner'}
	<div class="header-banner">
		{hook h='displayBanner'}
	</div>
{/block}

	{if !isset($content_only) || !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
			<div id="restricted-country">
				<p>{l s='You cannot place a new order from your country.'}{if isset($geolocation_country) && $geolocation_country} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span>{/if}</p>
			</div>
		{/if}
		{if $page_name =='pagenotfound'}<span class="arrow-notfound hidden-xs"><i class="icon icon-arrow-right"></i></span>{/if}

		{if $page_name =='index'}
			{hook h="displayPrestaHomeSlider"}
		{/if}
		
		<div class="boxed-wrapper">
			{if $smarty.get.controller == "index"}

			{/if}

		{if $theme_options['ph_show_topbar'] == '1'}	
		<div class="topbar">
			<div class="container">
				{hook h="displayBeforeHeader"}
				<div class="telefonodiv">
					<i class="fa fa-whatsapp" aria-hidden="true"></i>  <span class="telefono">+34 653 323 445</span>
				</div>
				<div class="avisoheader" >
					<p class="aviso"></p>
				</div>
				<div class="col-lg-8 col-md-7 hidden-xs shortlinks pull-right">
				
					<ul class="nolist row pull-right">
					
						<li><a href="{$base_dir_ssl}">{l s='Inicio'}</a></li>
						<li><a href="/pedido-rapido">{l s='Pedido rápido'}</a></li>
						{if $is_logged}
						<li><a href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log me out' mod='blockuserinfo'}">{l s='Salir de mi cuenta'}</a></li>
						{else}
						<li><a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">{l s='Mi cuenta'}</a></li>
						{/if}
					</ul>
				</div><!-- .shortlinks -->
			</div><!-- .container -->
		</div><!-- .topbar -->
		{/if}

		<header class="top{if $theme_options['menutop_sticky'] == 'header'} top-sticky{/if}">
			<div class="pattern"></div>
			<div style="display: none;"></div>
			<div class="container">
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="row">
						<a href="{if $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" class="logo" title="{l s='back to the homepage'}"><img src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}" class="img-responsive logotipo-header" /></a>
					</div>
				</div>
					<div class="mobile-clear clearfix"></div>

				{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
			</div><!-- .container -->
		</header>

		<div class="container content">
		{if $page_name =='index'}
			{hook h="displayTopColumn"}
		{/if}
			<div class="columns row">	
				{if $page_name !='index' && $page_name !='pagenotfound'}
				<div class="col-xs-12">
					{include file="$tpl_dir./breadcrumb.tpl"}
				</div>
				{/if}

				{if isset($left_column_size) && isset($right_column_size)}{assign var='cols' value=(12 - $left_column_size - $right_column_size)}{else}{assign var='cols' value=12}{/if}
				<div id="center_column" class="center_column col-xs-12 {if $hide_left_column && $hide_right_column}col-sm-{$cols|intval}{else}col-sm-12{/if} col-md-{$cols|intval}{if !empty($left_column_size)} col-md-push-3{/if}">
					<div class="background">
	<!-- Block search module TOP -->
	{if $smarty.get.controller != "orderopc" && $smarty.get.controller != "orderconfirmation" && strpos($dir,'blog')==false}
	
	

							{include file="$tpl_dir./blocksearch-instantsearch.tpl"}
	<div id="buscador-desktop">
	<h3 class="buscador-title"><label for="search_query_top">ENCUENTRA TU PRODUCTO UTILIZANDO <span style="color: white; font-weight: bolder;">EL BUSCADOR</span></label></h3>
	<div id="search_block_top" class="">
	<form method="get" action="{$link->getPageLink('search', null, null, null, false, null, true)|escape:'html':'UTF-8'}" id="searchbox" class="row">
		<div style="margin: 0px;">
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query" type="text" id="search_query_top" name="search_query" placeholder="¿Qué producto estás buscando?" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
			<span>
				
                <input class="main-color" style="text-indent:0px; text-indent:0px; width:30%; float:right; line-height: 0px; height: 37px;" type="submit" value="BUSCAR" />
				
			</span>
		</div>
	</form>
</div>
</div><!-- .buscador-desktop -->


{/if}
	{/if}
