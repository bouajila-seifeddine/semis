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

{capture name=path}{l s='Order confirmation'}{/capture}



{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{include file="$tpl_dir./errors.tpl"}

{if $proximo_gratis == "si"}
<div class="box col-md-6 col-xs-12 col-sm-12">
  <h1 class="under-header"><span class="peque-verde">{l s='¡Muchas gracias por confiar en nosotros!'}</span><br />
      {l s='Pedido confirmado'}
  </h1> 
  <img class="gracias-compra"  src="img/gracias-compra.png" alt="Gracias por comprar" />
</div>  
{elseif $module_name == "redsys" && $proximo_gratis == "no"}
  <div class="box col-md-12 col-xs-12 col-sm-12">
    <h1 class="under-header"><span class="peque-verde">{l s='¡Muchas gracias por confiar en nosotros!'}</span><br />
      {l s='Pedido confirmado'}
   </h1> 
  </div> 
{else}
<div class="box col-md-6 col-xs-12 col-sm-12">
  <h1 class="under-header"><span class="peque-verde">{l s='¡Muchas gracias por confiar en nosotros!'}</span><br />
      {l s='Pedido confirmado'}
  </h1> 
  <img class="gracias-compra" src="img/gracias-compra.png" alt="Gracias por comprar" />
</div>  
 <div class="col-md-6 col-xs-12 col-sm-12" id="div-form-confirmacion">
  <h3>¿Qué te ha parecido el proceso de compra en Semillas Low Cost?</h3>
  <form action="#"  method="post">
  <section  class="section-select-conf">

  <div class="opciones-container">
  <div class="div-opcion col-md-3 col-md-offset-2 col-sm-4 col-xs-4">
     <input type="radio" id="control_01" name="carita" value="1" checked>
     <label for="control_01" class="conf-label">
        <h5>Genial</h5>
       <i class="fa fa-smile-o"></i>
    </label>
  </div>
<div class="div-opcion col-md-3 col-sm-4 col-xs-4">
  <input type="radio" id="control_02" name="carita" value="2">
  <label for="control_02" class="conf-label">
    <h5>Normal</h5>
    <i class="fa fa-meh-o"></i>
  </label>
</div>
<div class="div-opcion col-md-3 col-sm-4 col-xs-4">
  <input type="radio" id="control_03" name="carita" value="3">
  <label for="control_03" class="conf-label">
    <h5>A mejorar</h5>
    <i class="fa fa-frown-o"></i>
  </label>
</div>
</div>
<div class="clearfix"></div>

<div class="col-md-9 col-md-offset-2 col-sm-12 col-xs-12 cont-textarea">
<textarea class="form-control" id="sugerencia" name="sugerencia" placeholder="¿En qué podemos mejorar?"></textarea>
</div>
<div class="clearfix"></div>
<div class="col-md-4 col-md-offset-2 cont-submit" style="padding-top: 15px;"> <button type="submit" name="submitMessage" id="submitMessage" class="button btn-primary"><span>Enviar</span></button></div>

</section>
</form>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
{/if}

{if $proximo_gratis == "si"}
<div class="box col-md-6 col-xs-12 col-sm-12">
<div class="reloj-container">
  <h2 class="under-header">
    <span class="peque-cuadro-verde">Si se te olvidó comprar algún producto te mostramos una lista de sugerencias</span><br />
    Cómpralo <span class="verde">antes de 30</span> minutos y  <span class="magenta">te pagamos el envío</span>
  </h2>

  <div class="clearfix"></div>
  <span id="countdown"></span>
      <div class="clock">
          <div class="timer-countdown days" style="display: inline-block;"></div>
          <div class="column column-borde">
            <div class="timer-countdown" id="horas-timer">12</div>
            <div class="text-countdown">HORAS</div>
          </div>
          <div class="timer-countdown"></div>
          <div class="column column-borde">
            <div class="timer-countdown" id="minutos-timer">12</div>
            <div class="text-countdown">MINUTOS</div>
          </div>
          <div class="timer-countdown"></div>
          <div class="column">
            <div class="timer-countdown" id="segundos-timer">12</div>
            <div class="text-countdown">SEGUNDOS</div>
          </div>
          <div class="clear"> </div>
        </div>
        <div class="clearfix"></div>
</div>
</div>
{/if}
{if $productos_vistos_data}
<div class="clearfix"></div>
  <div>
    <h4 class="header-wished-poducts">
     
     
        <strong>Productos que pueden interesarte</strong>
     

   </h4>
    {foreach from=$productos_vistos_data item=product}
      <div class=" col-md-2ot col-sm-3 col-xs-6 product product-wished" itemtype="http://schema.org/Product" itemscope="">
          <div class="bg">
             <div class="inner second-image">
              <a class="quick-view quick-comprar" href="https://www.semillaslowcost.com/{$product.category_link}/{$product.id_product}-{$product.link_rewrite}.html" rel="https://www.semillaslowcost.com/{$product.category_link}/{$product.id_product}-{$product.link_rewrite}.html">
              <span>Comprar</span>
            </a>
                <div class="img_hover"></div>
                 <a itemprop="url" href="https://www.semillaslowcost.com/{$product.category_link}/{$product.id_product}-{$product.link_rewrite}.html" title="{$product.name}"> <img itemprop="image" data-src="https://www.semillaslowcost.com/{$product.id_image}-home_default/{$product.link_rewrite}.jpg" alt="{$product.name}" class="lazy img-responsive first-image" src="https://www.semillaslowcost.com/{$product.id_image}-home_default/{$product.link_rewrite}.jpg" alt="{$product.name}"> </a>
                   <div class="icons"></div>
                 <div class="info">
                    <h3 itemprop="name"><a itemprop="url" href="https://www.semillaslowcost.com/{$product.category_link}/{$product.id_product}-{$product.link_rewrite}.html" title="{$product.name}">{$product.name|truncate:18:'...'|escape:'html':'UTF-8'} </a></h3>
                   <div class="price" itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
                   <!--  <span itemprop="price" class="price "> {$product.price_attribute|string_format:"%.2f"}€ </span> -->
                      <meta itemprop="priceCurrency" content="EUR">

                  </div>
                </div>
              </div>
          </div>
      </div>
    {/foreach}
  </div>
  {/if}

<div class="box col-md-5 col-xs-12 col-sm-12">
<h2>{l s='Order confirmation'}</h2>	
{$HOOK_ORDER_CONFIRMATION}
{$HOOK_PAYMENT_RETURN}
{if $is_guest}
	<p>{l s='Your order ID is:'} <span class="bold">{$id_order_formatted}</span> . {l s='Your order ID has been sent via email.'}</p>
</div>   
    <p class="cart_navigation exclusive">
	<a class="button" href="{$link->getPageLink('guest-tracking', true, NULL, "id_order={$reference_order|urlencode}&email={$email|urlencode}")|escape:'html':'UTF-8'}" title="{l s='Follow my order'}"><i class="icon icon-chevron-left"></i> {l s='Follow my order'}</a>
    </p>
{else}
<p class="cart_navigation exclusive">
	<a class="button" href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Go to your order history page'}"><i class="icon icon-chevron-left"></i> {l s='View your order history'}</a>
</p>
</div>
{if  $mensaje_enviado}
<h3>¡Gracias por confiar en Semillas Low Cost, te esperamos pronto!</h3>
<div class="clearfix"></div>
{else}
  {if $module_name == "redsys"}
<div class="col-md-7 col-xs-12 col-sm-12" id="div-form-confirmacion">
	<h3>¿Qué te ha parecido el proceso de compra en Semillas Low Cost?</h3>
	<form action="#"  method="post">
	<section  class="section-select-conf">

  <div class="opciones-container">
  <div class="div-opcion col-md-3 col-md-offset-2 col-sm-4 col-xs-4">
     <input type="radio" id="control_01" name="carita" value="1" checked>
     <label for="control_01" class="conf-label">
        <h5>Genial</h5>
       <i class="fa fa-smile-o"></i>
    </label>
  </div>
<div class="div-opcion col-md-3 col-sm-4 col-xs-4">
  <input type="radio" id="control_02" name="carita" value="2">
  <label for="control_02" class="conf-label">
    <h5>Normal</h5>
    <i class="fa fa-meh-o"></i>
  </label>
</div>
<div class="div-opcion col-md-3 col-sm-4 col-xs-4">
  <input type="radio" id="control_03" name="carita" value="3">
  <label for="control_03" class="conf-label">
    <h5>A mejorar</h5>
    <i class="fa fa-frown-o"></i>
  </label>
</div>
</div>
<div class="clearfix"></div>

<div class="col-md-9 col-md-offset-2 col-sm-12 col-xs-12 cont-textarea">
<textarea class="form-control" id="sugerencia" name="sugerencia" placeholder="¿En qué podemos mejorar?"></textarea>
</div>
<div class="clearfix"></div>
<div class="col-md-4 col-md-offset-2 cont-submit" style="padding-top: 15px;"> <button type="submit" name="submitMessage" id="submitMessage" class="button btn-primary"><span>Enviar</span></button></div>

</section>
</form>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
{/if}
{/if}

	
{/if}
