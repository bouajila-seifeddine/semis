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

<script type="text/javascript">

	$(document).ready(function(){
		var flag=0;
		var comprobador = true;
		$.ajax({

			type: "GET",
			url: "cagar_html.php",
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			data: {
				'offset':0,
				'limit':10
			},
			success: function(data){
				$('#resultados').append(data);
				$('.loading').remove();

				
				flag = flag + 1;
			}
		});

		$(document).scroll(function(){
				
			//ACORDARSE EL FLAG<NUMERO SON EL NUMERO TOTAL DE CATEGORIA PARA QUE CUANDO ACABE QUITE EL GIF
			if((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 800 && comprobador == true && flag<12)
			{
				comprobador = false;
				$('#resultados').append("<div class='loading'  style='margin-bottom: 500px; text-align: center;'>  <img src='https://pruebas.semillaslowcost.com/img/cargando.gif'> </div>");
				var llamada = $.ajax({

			type: "GET",
			url: "cagar_html.php",
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
			data: {
				'offset': flag,
				'limit':10
			},
			success: function(data){
				$('#resultados').append(data);
				$('.loading').remove();
				flag = flag + 1;
				comprobador = true;
			}
			});


			}

});



	});


</script>

{if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
    {if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
        <ul id="home-page-tabs" class="nav nav-tabs clearfix">
			{$HOOK_HOME_TAB}
		</ul>
	{/if}
	<div class="tab-content">{$HOOK_HOME_TAB_CONTENT}</div>
{/if}
{if isset($HOOK_HOME) && $HOOK_HOME|trim}
	{$HOOK_HOME}
{/if}

<div id="resultados"></div>
