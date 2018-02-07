{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{literal}
	<style>
		.lgcookieslaw_banner {
			display: table;
			width:100%;
			position:fixed;
			left:0;
			background: {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
			border-color: {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
			border-left: 1px solid {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
			border-radius: 3px 3px 3px 3px;
			border-right: 1px solid {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
			color: {/literal}{$fontcolor|escape:'html':'UTF-8'}{literal} !important;
			z-index: 99999;
			border-style: solid;
			border-width: 1px;
			margin: 0;
			outline: medium none;
			text-align: center;
            vertical-align: middle;
			-webkit-box-shadow: 0px 1px 5px 0px {/literal}{$shadowcolor|escape:'html':'UTF-8'}{literal};
			-moz-box-shadow:    0px 1px 5px 0px {/literal}{$shadowcolor|escape:'html':'UTF-8'}{literal};
			box-shadow:         0px 1px 5px 0px {/literal}{$shadowcolor|escape:'html':'UTF-8'}{literal};
			{/literal}
				{$position|escape:'html':'UTF-8'};
				{$opacity|escape:'html':'UTF-8'};
			{literal}
		}

		.lgcookieslaw_banner > form
		{
			position:relative;
		}

		.lgcookieslaw_banner > form input.lgcookieslaw_btn
		{
			border-color: {/literal}{$btn1_bgcolor|escape:'html':'UTF-8'}{literal} !important;
			background: {/literal}{$btn1_bgcolor|escape:'html':'UTF-8'}{literal} !important;
			color: {/literal}{$btn1_fontcolor|escape:'html':'UTF-8'}{literal};
			text-align: center;
			margin: 0px 0px 8px 0px;
			padding: 5px 7px;
			display: inline-block;
			border: 0;
			font-weight: bold;
            height: 26px;
            width: auto;
		}

		.lgcookieslaw_banner > form input:hover.lgcookieslaw_btn
		{
			
			opacity: 0.85;
			filter: alpha(opacity=85);
		}

		.lgcookieslaw_banner > form a.lgcookieslaw_btn
		{
			border-color: {/literal}{$btn2_bgcolor|escape:'html':'UTF-8'}{literal};
			background: {/literal}{$btn2_bgcolor|escape:'html':'UTF-8'}{literal};
			color: {/literal}{$btn2_fontcolor|escape:'html':'UTF-8'}{literal};
			margin: 0px 0px 8px 0px;
			text-align: center;
			padding: 5px 7px;
			display: inline-block;
			border: 0;
			font-weight: bold;
            height: 26px;
            width: auto;
		}

        @media (max-width: 768px) {
            .lgcookieslaw_banner > form input.lgcookieslaw_btn,
            .lgcookieslaw_banner > form a.lgcookieslaw_btn { 
                height: auto;
            }
        }

		.lgcookieslaw_banner > form a:hover.lgcookieslaw_btn
		{
			opacity: 0.85;
			filter: alpha(opacity=85);
		}

		.lgcookieslaw_close_banner_btn
		{
			cursor:pointer;
			width:21px;
			height:21px;
			max-width:21px;
		}

	</style>
	<script type="text/javascript">
		function closeinfo()
		{
			$('.lgcookieslaw_banner').hide();
		}
	</script>
{/literal}