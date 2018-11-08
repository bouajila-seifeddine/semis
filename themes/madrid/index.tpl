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

{if $deviceType == 'computer'}
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
			if((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 3700 && comprobador == true && flag<12)
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
{/if}
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

<div class="index-desc">
	<h2>Especialistas en semillas de marihuana</h2>
	<p>S.L.C es posiblemente el <a href="https://www.semillaslowcost.com/grow-shop/">Grow Shop online más barato</a> que podréis encontrar en la red. Esta empresa valenciana lleva muchos años dedicándose a la <b>comercialización de semillas de marihuana de máxima calidad.</b>  De hecho, la empresa surgió con la intención de hacer llegar las mejores semillas de marihuana a todos los rincones del planeta y a cualquier cultivador gracias a su venta online, sea cual sea su nivel adquisitivo. De momento, S.L.C reparte semillas y cualquier tipo de producto relacionado con el cultivo agrícola a toda Europa y Latinoamérica.</p>
	<p>La empresa surgió ya que considerábamos que el mercado cannábico necesitaba distribuidoras que ajustarán al máximo los precios e hicieran llegar a cualquier cultivador todo lo que necesitará, sin importar su nivel económico. Obviamente vivimos de esto y tenemos que obtener beneficios, pero nuestra intención es ofrecer siempre el precio más competitivo para que todos podáis haceros con esas semillas de marihuana que deseáis o ese <a href="https://www.semillaslowcost.com/93-armarios-de-cultivo">armario de cultivo</a> con el que tanto os gustaría trabajar. </p>

	<img src="https://www.semillaslowcost.com/grow-shop/promociones-grow-shop.png" class="img-semillas-index" alt="Semillas Cannabis">

	<h2>Bancos que tenemos en SLC</h2>
	<ul class="lista-bancos-index">
		<li><a href="https://www.semillaslowcost.com/81-ace-seeds" title="Comprar semillas de Ace Seeds">Ace Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/26-advanced-seeds-feminizadas" title="Comprar semillas de Advanced Seeds">Advanced Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/254-bcn-seeds-autoflorecientes" title="Comprar semillas de BCN Seeds">BCN Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/38-barney-s-farm-autoflorecientes" title="Comprar semillas de Barney’s Farm">Barney’s Farm</a></li>
		<li><a href="https://www.semillaslowcost.com/80-big-buddha-seeds" title="Comprar semillas de Big Buddha Seeds">Big Buddha Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/37-buddha-seeds-autoflorecientes" title="Comprar semillas de Buddha Seeds">Buddha Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/88-coleccion-clasica-cbd-seeds" title="Comprar semillas de CBD Seeds">CBD Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/116-cannabiogen" title="Comprar semillas de Cannabiogen">Cannabiogen</a></li>
		<li><a href="https://www.semillaslowcost.com/73-dna-genetics" title="Comprar semillas de DNA Genetics">DNA Genetics</a></li>
		<li><a href="https://www.semillaslowcost.com/77-delicious-seeds-feminizadas" title="Comprar semillas de Delicious Seeds">Delicious Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/30-dinafem-autoflorecientes" title="Comprar semillas de Dinafem">Dinafem</a></li>
		<li><a href="https://www.semillaslowcost.com/256-dr-underground-feminizadas" title="Comprar semillas de Dr. Underground">Dr. Underground</a></li>
		<li><a href="https://www.semillaslowcost.com/34-dutch-passion-autoflorecientes" title="Comprar semillas de Dutch Passion">Dutch Passion</a></li>
		<li><a href="https://www.semillaslowcost.com/67-eva-seeds" title="Comprar semillas de Eva Seeds">Eva Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/272-flying-dutchmen-feminizadas" title="Comprar semillas de Flying Dutchmen">Flying Dutchmen</a></li>
		<li><a href="https://www.semillaslowcost.com/31-geaseeds-autoflorecientes" title="Comprar semillas de Gea Seeds">Gea Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/258-genehtik-feminizadas" title="Comprar semillas de Genehtik">Genehtik</a></li>
		<li><a href="https://www.semillaslowcost.com/64-green-house-seeds-feminizadas" title="Comprar semillas de Green House Seeds">Green House Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/262-humboldt-seeds-autoflorecientes" title="Comprar semillas de Humboldt Seeds">Humboldt Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/66-kannabia" title="Comprar semillas de Kannabia">Kannabia</a></li>
		<li><a href="https://www.semillaslowcost.com/76-mandala-seeds" title="Comprar semillas de Mandala Seeds">Mandala Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/68-medical-seeds-feminizadas" title="Comprar semillas de Medical Seeds">Medical Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/70-mr-nice" title="Comprar semillas de Mr. Nice">Mr. Nice</a></li>
		<li><a href="https://www.semillaslowcost.com/266-paradise-seeds-autoflorecientes" title="Comprar semillas de Paradise Seeds">Paradise Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/29-positronics-feminizadas" title="Comprar semillas de Positronics">Positronics</a></li>
		<li><a href="https://www.semillaslowcost.com/69-pyramid-seeds-feminizadas" title="Comprar semillas de Pyramid Seeds">Pyramid Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/269-reggae-seeds-feminizadas" title="Comprar semillas de Reggae Seeds">Reggae Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/72-ripper-seeds" title="Comprar semillas de Ripper Seeds">Ripper Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/268-royal-queen-seeds-autoflorecientes" title="Comprar semillas de Royal Queen Seeds">Royal Queen Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/82-sagarmatha" title="Comprar semillas de Sagarmatha">Sagarmatha</a></li>
		<li><a href="https://www.semillaslowcost.com/78-samsara-seeds" title="Comprar semillas de SamSara Seeds">SamSara Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/84-seedmakers-feminizadas" title="Comprar semillas de Seedmakers">Seedmakers</a></li>
		<li><a href="https://www.semillaslowcost.com/20-sensi-seeds-feminizadas" title="Comprar semillas de Sensi Seeds">Sensi Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/71-serious-seeds-feminizadas" title="Comprar semillas de Serious Seeds">Serious Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/33-sweet-seeds-autoflorecientes" title="Comprar semillas de Sweet Seeds">Sweet Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/87-the-moon-seeds" title="Comprar semillas de The Moon Seeds">The Moon Seeds</a></li>
		<li><a href="https://www.semillaslowcost.com/61-white-label-regulares" title="Comprar semillas de White Label">White Label</a></li>
		<li><a href="https://www.semillaslowcost.com/79-world-of-seeds" title="Comprar semillas de World of Seeds">World of Seeds</a></li>
	</ul>
	<h2>¿Qué tipos de semillas ofertamos?</h2>
	<p>Ofertamos todo tipo de semillas, desde regulares, hasta feminizadas, autoflorecientes, ricas en CBD o cualquier otro tipo de semillas que se oferten. </p>
	<h3>Regulares</h3>
	<img src="img/index/semillas-marihuana-regulares.jpg" class="img-semillas-index" alt="Semillas Regulares">
	<p>Las  <a href="https://www.semillaslowcost.com/16-semillas-regulares" title="Comprar Semillas Regulares">semillas regulares</a> son las que se han cultivado desde el principio, semillas que pueden ser machos o hembras por igual. Estas genéticas suelen ser las más resistentes y potentes, pero nos obligan a sexar para retirar los machos y, por tanto, perdemos aproximadamente un 50% de las cepas que hemos hecho crecer. </p>
	<h3>Feminizadas</h3>
	<img src="img/index/semillas-marihuana-feminizadas.jpg" class="img-semillas-index" alt="Semillas Feminizadas">
	<p>Sin duda las más codiciadas, las <a href="https://www.semillaslowcost.com/13-semillas-feminizadas" title="Comprar Semillas Feminizadas">semillas feminizadas</a> son aquellas que han sido producidas asegurando un 99% de posibilidades de que produzcan una cepa hembra. De ese modo nos aseguramos que todas las plantas que cultivamos serán hembras y no tendremos que matar a los machos ni estar atentos para no sufrir polinizaciones.</p>
	<h3>Autoflorecientes</h3>
	<img src="img/index/semillas-marihuana-autofloreciente.jpg" class="img-semillas-index" alt="Semillas Automáticas">
	<p>Otro tipo son las <a href="https://www.semillaslowcost.com/15-semillas-autoflorecientes" title="Comprar Semillas Autoflorecientes">semillas autoflorecientes o automáticas</a>, estas son feminizadas que han sido cruzadas con una variedad (Rudelaris) que las hace crecer mucho más rápido, cogollar mucho más rápido, alcanzar tamaños reducidos y no depender del fotoperiodo para cogollar.</p>
	<h3>Otras Opciones</h3>
	<p>Otras opciones son las semillas feminizadas rápidas o Fast Version y las ricas en CBD. Estas variedades con Cbd son las denominadas semillas de marihuana medicinales ya que la marihuana que produzca tendrá cualidades medicinales. Incluso disponemos en catálogo de variedades famosas en EEUU o fenotipos raros y únicos capaces de generar flores rojas o moradas</p>
	<h2>Precio inigualable</h2>
	<p>En resumen, S.L.C trabaja con la intención de hacer llegar la máxima variedad de semillas de marihuana al mejor precio posible y al máximo número posible de países. Un trabajo que consideramos necesario en la industria cannábica actual ya que los bancos de confianza tienen precios poco asequibles debido al elevado número de intermediarios. Y los bancos a granel tienen precios interesantes, pero no nos ofrecen unas semillas de cannabis con garantías de calidad fiable. Por tanto, era 100% necesaria la aparición de una empresa que trate de reducir intermediarios y oferte precios más ajustados y competitivos. Hemos bajado tanto los precios que podrás comprar las mejores semillas de marihuana a 1 Euro la unidad. Básicamente eso es S.L.C, una página fiable, con productos de garantías y los precios más ajustados de internet. </p>

</div>
<div id="posts">
	<h2 class="pull-left"> <img class="icon icon-money main-color" width="38" height="38" style="padding:3%;" alt="icono hoja marihuana" src="https://www.semillaslowcost.com/img/hojaico.png"> <strong>Últimos Artículos del Blog</strong></h2>
<div class="clearfix"></div>
	 {if isset($posts) && $posts|@count}
                 {foreach from=$posts item=post}
                  <div class='col-sm-6 col-md-4 index-post' style="background-image: url('{$post->image}');">
                  	<a href="blog/{$post->url}/" title="Artículo sobre {$post->title}"  target="_blank">
                   		<h3>{$post->title}</h3>
                	</a>
                  </div>

                 {/foreach}
     {/if}


</div>
<div class="clearfix clear-separa"></div>
