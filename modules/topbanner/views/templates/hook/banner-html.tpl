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

<div id="ps_topbanner_wrapper">
	{$banner['text']|escape:'htmlall':'UTF-8'}

	{if $banner['timer'] == 1}
		{$banner['timer_left_text']|escape:'htmlall':'UTF-8'}
		{include file="./timer.tpl"}
		{$banner['timer_right_text']|escape:'htmlall':'UTF-8'}
	{/if}

	{if $banner['cta'] == 1}
		<a class="ps_topbanner_cta">{$banner['cta_text']|escape:'htmlall':'UTF-8'}</a>
	{/if}

</div>

<style>
    
    {if $banner['timer'] == 1}
    @media all and (max-width: 480px) {
        #ps_topbanner_wrapper {
            display: none;
        }
    }
    {/if}

	header .banner {
		background-color: {$banner['background']|escape:'htmlall':'UTF-8'};
	}

	#ps_topbanner_wrapper {
		width: 100%;
		left: 0;
		z-index: 999;
		top: 0;

		height: {$banner['height']|escape:'htmlall':'UTF-8'}px;
		line-height: {$banner['height']|escape:'htmlall':'UTF-8'}px;
		background-color: {$banner['background']|escape:'htmlall':'UTF-8'};
		font-size: {$banner['text_size']|escape:'htmlall':'UTF-8'}px;
		color: {$banner['text_color']|escape:'htmlall':'UTF-8'};

		text-align: center;

		{if $fontFamily == 'Roboto' || $fontFamily == 'Hind' || $fontFamily == 'Maven Pro'}
			font-family: '{$fontFamily|escape:'htmlall':'UTF-8'}', sans-serif;
		{else}
			font-family: '{$fontFamily|escape:'htmlall':'UTF-8'}', serif;
		{/if}
	}
    
    @media only screen and (max-width:320px) {
        #ps_topbanner_wrapper { font-size: .9em }
    }

	{if $banner['cta'] == 1}
		#ps_topbanner_wrapper {
			cursor: pointer;
		}

		.ps_topbanner_cta {
			color: {$banner['cta_text_color']|escape:'htmlall':'UTF-8'}!important;
			padding: 5px;
			background-color: {$banner['cta_background']|escape:'htmlall':'UTF-8'};
			border-radius: 4px;
		}
	{/if}
    
</style>