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

{capture name=path}{l s='Search'}{/capture}
{if isset($instant_search) && $instant_search}
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
{/if}


{include file="$tpl_dir./errors.tpl"}
{if !$nbProducts}
	<p class="alert alert-warning" style="font-size: x-large;">
		{if isset($search_query) && $search_query}
			No se han encontrado resultados para su búsqueda&nbsp;"{if isset($search_query)}{$search_query|escape:'html':'UTF-8'}{/if}" Consulte las categorías o contacta con nuestros especialistas de atención al cliente y te recomendaremos el producto que más se ajuste a tus necesidades.
		{elseif isset($search_tag) && $search_tag}
			{l s='No results were found for your search'}&nbsp;"{$search_tag|escape:'html':'UTF-8'}"  Consulte las categorías o contacta con nuestros especialistas de atención al cliente y te recomendaremos el producto que más se ajuste a tus necesidades.
		{else}
			{l s='Please enter a search keyword'}
		{/if}
	</p>
{else}
	{if isset($instant_search) && $instant_search}
        <p class="alert alert-info">
            {if $nbProducts == 1}{l s='%d result has been found.' sprintf=$nbProducts|intval}{else}{l s='%d results have been found.' sprintf=$nbProducts|intval}{/if}
        </p>
    {/if}
    <div class="content_sortPagiBar">
        <div class="sortPagiBar clearfix {if isset($instant_search) && $instant_search} instant_search{/if}">
            {include file="$tpl_dir./product-sort.tpl"}
            {if !isset($instant_search) || (isset($instant_search) && !$instant_search)}
                {include file="./nbr-product-page.tpl"}
            {/if}
            {include file="./product-compare.tpl"}
        </div>
	</div>
	{include file="$tpl_dir./product-list.tpl" products=$search_products}
    <div class="bottom-pagination-content clearfix">
        {if !isset($instant_search) || (isset($instant_search) && !$instant_search)}
             {include file="$tpl_dir./pagination.tpl" no_follow=1 paginationId='bottom'}
        {/if}
    </div>
{/if}
