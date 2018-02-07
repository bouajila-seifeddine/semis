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
<script  data-keepinline="true">
{literal}

		(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-TJX7JLJ');

		{/literal}	
</script>


		<title>{$meta_title|escape:'html':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
{/if}
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		

		<link rel="stylesheet" href="https://www.semillaslowcost.com/fontawesome/css/font-awesome.min.css">
	<script  data-keepinline="true">
	{literal}

		function toggledisplayarrow() {
				
			    var up = document.getElementById("arrow-down");
			    var down = document.getElementById("arrow-up");
			    if (up.style.display === "none") {
			        up.style.display = "block";
			    } else {
			        up.style.display = "none";
			    }
			     if (down.style.display === "none") {
			        down.style.display = "block";
			    } else {
			        down.style.display = "none";
			    }
			}
    

{/literal}
	</script>

		<style>
		.asagiSabit {
    position: fixed;
    bottom: 10px;
    right: 10px;
    z-index: 999;
	}
	


	.whatsappBlock a {
    display: inline-block;
    height: 50px;
    background: #65BC54;
    padding: 5px 10px;
    color: #fff !important;
    font-weight: bold;
}
.column-h4{
	text-align: 	center;
}
			input.main-color {
    padding: 10PX;
    width: 30%;
    font-weight: bolder;
    color: white;
    background: #7bbd42;
}
.ac_results {
    width: auto !important;
}

.shopping_cart_mobile a.cart-contents>span {
    border: 0px !important;
    background: #7bbd42 !important;
    width: 77.5% !important;
    padding: 21.2px 13px 17px 23px !important;
    margin: 2px 0 0 !important;
    font-weight: 800;

}

.pagos-img {
   margin-top: 1%;

}


.bottom .container {
   background-color:#1d1f25;
}

 @media (max-width: 700px) {
	.bottom {
	   text-align: center;
	}
	img.pagos-img {
	    width: 80%;
	}
}

 @media (max-width: 1200px) {
.pagos1{
   margin-left: 0% !important;
}
 	
 

}
 @media (min-width: 1000px) {

 	.shopping_cart_mobile {
 		display: none;
 	}
 	
 

}

@media (max-width: 1000px) {
	.shopping_cart_desktop{
		display: none;
	}

	.bottom.pull-left{
		display: none;
	}
}

 @media (min-width: 768px) {

        .ph_megamenu_mobile_toggle {
                         display: none !important;
                     }

        #buscador-movil {
                         display: none;
                     }

}

 @media (max-width: 768px) {
                #search_block_top p span {
   			
   			     margin-top: .5% !important;
   			 width: 95% !important;
	}

	        #buscador-desktop {
                         display: none;
                     }

	                .search_query{
   			 width: 93.2% !important;
   			 margin-left: 3% !important;
	}
	#search_query_top {
    width: 100% !important;
  
}}
@media (max-width: 767px){

header.logo {
    margin-top: 3%;
    margin-bottom: 3%;
    display: block;

}

}

input#search_query_top{
	margin-left:0px;
}

form#searchbox {
    margin-left: 0px;
    margin-right: 0px;
}
@media (max-width: 768px){
.content_scene_cat_bg {
	background: transparent !important;
}

.ph_megamenu_mobile_toggle a.show_megamenu, a.hide_megamenu{
    padding-right: 10% !important;
    font-size: 22px !important;
    margin-bottom: 20px;
}}

			.ph_megamenu_mobile_toggle {
				top: 0px;
				left: -63px;
				width: 129%;
				position: fixed;
				z-index: 99999;
				text-align: right;
				padding-right: 0%;
				padding-left: 2%;
		}
		#search_block_top {
        background: #adafb3;
    padding: 10px;
    width: 99.5%;
}
		#search_block_top i {
    font-size: 24px
}

		#search_block_top i:hover {
    background: 24px
}

#ph_megamenu_wrapper #ph_megamenu {
    background: #7bbd42
}
#search_block_top i {
    font-size: 24px;
    background: #7bbd42
}
		div#buscador {
         padding-top: 8px;
                               
                               margin-bottom: 2%;
                               opacity:0.9 ;
                            }

                 #search_query_top {
                            margin-left: 21px;
                            border: 2px solid #2c313b;
                            border-right: 2px solid #2c313b;
                            width: 69.5% !important;
                            background: white;
                           
                     }
                     input.main-color {
						    font-size: 19px;
						}

                     .buscador-title{
                     	color: #7bbd42;
                     	text-align: center;
                     	font-weight: normal;
						margin-top:0px; 
                     }
                     div#buscador-desktop {
                     	background: #2c313b;
   						 margin-bottom: 2%;
   						 padding: 2%;
}

                   div#buscador-movil {
                     	background: #2c313b;
   						 margin-bottom: 2%;
   						 padding: 2%;
}
			.mobile_menu{ 

				max-height: 100%;
				 position: fixed;
				 overflow-y: auto;
				 margin-top: -27px; 
				  left: 29%; 
				  top: 44px;}

				  a.main-color {
    padding: 10PX;
    width: 30%;
    font-weight: bolder;
    color: white;
    background: #7bbd42;
}
      .shopping_cart_mobile span.ajax_cart_no_product{
      	color:white !important; } 
      
     .shopping_cart_mobile .icon-shopping-cart:before {
            color: white;
            }    
		</style>







{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
		<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
	{/foreach}
{/if}

{$dir=$smarty.server.REQUEST_URI}


		{if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def) && strpos($dir,'blog')==false}
			{$js_def}
			{foreach from=$js_files item=js_uri}
			<script src="{$js_uri|escape:'html':'UTF-8'}"></script>
			{/foreach}
		{/if}
		{$HOOK_HEADER}




		
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
		<script>
			$(document).ready(function(){
				//setTimeout(function() alert("etra"), 20000);
				window.setTimeout(cerrarCookies, 20000);
			});
			function cerrarCookies() {
			 	var x = document.getElementsByClassName("lgcookieslaw_banner");
			 	x[0].style.display = 'none';    
			}
		</script>


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
			<!-- Google Tag Manager (noscript) -->
				<noscript>
				<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TJX7JLJ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
				<!-- End Google Tag Manager (noscript) -->
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
						<li><a href="{$link->getPageLink('cart', true)|escape:'html':'UTF-8'}">{l s='Pedido rápido'}</a></li>
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
			<div class="container">
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="row">
						<a href="{if $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" class="logo" title="{l s='back to the homepage'}"><img src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}" class="img-responsive logotipo-header" /></a>
					</div>
				</div>
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
				<div id="center_column" class="center_column col-xs-12 {if $hide_left_column && $hide_right_column}col-sm-{$cols|intval}{else}col-sm-{$cols-1|intval}{/if} col-md-{$cols|intval}{if !empty($left_column_size)} col-md-push-3 col-sm-push-4{/if}">
					<div class="background">
	<!-- Block search module TOP -->
	{if $smarty.get.controller != "orderopc" && strpos($dir,'blog')==false}
	
	

							{include file="$tpl_dir./blocksearch-instantsearch.tpl"}
	<div id="buscador-desktop">
	<h3 class="buscador-title">ENCUENTRA TU PRODUCTO UTILIZANDO <span style="color: white; font-weight: bolder;">EL BUSCADOR</span></h3>
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

	<div id="buscador-movil">
	<h3 class="buscador-title">ENCUENTRA TU PRODUCTO UTILIZANDO <span style="color: white; font-weight: bolder;">EL BUSCADOR</span></h3>
	<div id="search_block_top" class="">
	<form method="get" action="{$link->getPageLink('search', null, null, null, false, null, true)|escape:'html':'UTF-8'}" id="searchbox" class="row">
		<div style="margin: 0px;">
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
						<input class="search_query" type="text" id="search_query_top" name="search_query" style="margin-left:0px !important; width: 100% !important;" placeholder="¿Qué producto estás buscando?" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
			<span>
				
                <input class="main-color" style="text-indent:0px; text-indent:0px; width:100%;  line-height: 0px; height: 37px; margin-top: 1%;" type="submit" value="BUSCAR" />
				
			</span>

		</div>
	</form>
</div>
</div><!-- .buscador-desktop -->
{/if}
	{/if}
