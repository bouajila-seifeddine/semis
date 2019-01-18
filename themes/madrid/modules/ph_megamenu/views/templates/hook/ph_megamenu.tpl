{*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*}
{if $menu}
<div class="ph_megamenu_mobile_toggle">
	<div class="mobile-menu-triggers">
			<a href="#" class="show_megamenu" aria-label="Mostrar Menu"><i class="fa fa-bars"></i><span>MENÚ</span></a>
			<a href="#" class="hide_megamenu" aria-label="Esconder Menu"><i class="fa fa-times"></i><span>MENÚ</span></a>
	</div>
	<div class="mobile-menu-logo">
				<a href="https://www.semillaslowcost.com/" aria-label="Inicio"><img src="https://www.semillaslowcost.com/img/logo-mobile.png" alt="Logo Semillas Low Cost"></a>
	</div>
	<div class="contenedor-iconos-menu">
		<div class="mobile-menu-user">
			<a href="https://www.semillaslowcost.com/inicio-sesion?back=my-account"><i class="fa fa-user" aria-label="Iniciar Sesión"></i></a>
		</div>
		<div class="mobile-menu-buscador">
			<i class="fa fa-search" onclick="document.getElementById('search_block_top_mobile').classList.toggle('hidden'); window.scrollTo(0,0);" aria-label="Buscador"></i>
		</div>
		<div class="mobile-menu-carrito">	
		<a  href="" onclick="togglecarromobile()"><i class="pull-right icon icon-shopping-cart"><span class="cart-qties-mobile" id="cart-qties-mobile-id" {nocache}{if $cart_qties == 0} style="display:none;" {/if}>{$cart_qties}{/nocache}</span> </i></a>

		</div>
	</div>
</div>
<div id="ph_megamenu_wrapper" class="clearBoth">
	<nav role="navigation">
		<ul id="ph_megamenu" class="ph_megamenu">
			{foreach from=$menu item=tab key=key}
				<li class="menu_link_{$tab.id_prestahome_megamenu|intval}{if $tab.class != ''} {$tab.class|escape:'htmlall':'UTF-8'}{/if}{if $tab.align == 1} align-right{/if}{if $tab.icon != ''} with-icon{/if} {if $tab.type == 1}has-submenu{/if} {if $tab.url == $base_dir}active{/if}{if $tab.hide_on_mobile} ph-hidden-mobile{/if}{if $tab.hide_on_desktop} ph-hidden-desktop{/if}">
							<a href="{$tab.url|escape:'htmlall':'UTF-8'}" title="{$tab.title|escape:'htmlall':'UTF-8'}" {if $tab.new_window}target="_blank"{/if}>
								{if $tab.id_prestahome_megamenu|intval == 1}
								<i class="fa fa-home"></i>
						{/if}
						{if $tab.icon != ''}
						<img src="https://www.semillaslowcost.com/img/iconos-menu/{$tab.icon|escape:'htmlall':'UTF-8'}" class="img-menu" alt="{$tab.title|escape:'htmlall':'UTF-8'}">						{/if}
						
						<span class="{if !$tab.display_title}hide{/if}">{$tab.title|escape:'htmlall':'UTF-8'}</span>

						
					</a>
					{* mega menu *}
					{if $tab.type == 1}
					<div class="mega-menu clear clearfix {if Configuration::get('PH_MM_USE_SLIDE_EFFECT')}with-effect{/if}" style="width: auto; {if $tab.align == 0}left: 0;{else}right: 0;{/if}">
						<div class="">
							{if $tab.content_before != ''}
								<div class="container-fluid menu-content-before">
									{$tab.content_before}{* HTML CONTENT *}
								</div><!-- .menu-content-before -->
							{/if}

							{if isset($tab.childrens) && sizeof($tab.childrens)}
								{foreach $tab.childrens as $megamenu name=mega_menu}
									<div class="ph-type-{$megamenu.type|intval} {if $megamenu.type != 6}ph-col ph-col-{$megamenu.columns|intval}{/if}{if $megamenu.new_row} ph-new-row{/if}{if $megamenu.hide_on_mobile} ph-hidden-mobile{/if}{if $megamenu.hide_on_desktop} ph-hidden-desktop{/if}{if $megamenu.class != ''} {$megamenu.class|escape:'htmlall':'UTF-8'}{/if}">
										{* Mega Categories *}
										{if $megamenu.type == 4}
											{include file="./types/mega-categories.tpl"}
										{/if}

										{* Custom HTML *}
										{if $megamenu.type == 5}
											{include file="./types/custom-html.tpl"}
										{/if}

										{* Product(s) *}
										{if $megamenu.type == 6}
											{include file="./types/mega-products.tpl"}
										{/if}
									</div><!-- .ph-type-{$megamenu.type|intval}.ph-col.ph-col-{$megamenu.columns|intval} -->
								{/foreach}
							{/if}

							{if $tab.content_after != ''}
								<div class="container-fluid menu-content-after">
									{$tab.content_after}{* HTML CONTENT *}
								</div><!-- .menu-content-after -->
							{/if}
						</div><!-- . -->
					</div><!-- .mega-menu -->
					{/if}

					{* dropdown *}
					{if $tab.type == 0 && isset($tab.childrens) && sizeof($tab.childrens)}
						<ul class="dropdown">
							{foreach $tab.childrens as $dropdown}
							<li class="menu_link_dropdown_{$dropdown.id_prestahome_megamenu|intval} {if $dropdown.class != ''} {$dropdown.class|escape:'htmlall':'UTF-8'}{/if}{if $dropdown.icon != ''} with-icon{/if}">
								<a href="{$dropdown.url|escape:'htmlall':'UTF-8'}">
									{if $dropdown.icon != ''}
										<img href="https://www.semillaslowcost.com/img/iconos-menu/{$dropdown.icon|escape:'htmlall':'UTF-8'}" class="img-menu" alt="{$dropdown.title|escape:'htmlall':'UTF-8'}">
									{/if}

									{$dropdown.title|escape:'htmlall':'UTF-8'}
								</a>
							</li>
							{/foreach}
						</ul>
					{/if}

					{* dropdown from categories *}
					{if $tab.type == 2 && isset($tab.dropdown) && sizeof($tab.dropdown)}
						{include file="./types/dropdown-categories.tpl"}
					{/if}

				</li>
			{/foreach}
		</ul>
	</nav>
</div><!-- #ph_megamenu -->
<div id="search_block_top_mobile" class="hidden">
	<form method="get" action="{$link->getPageLink('search', null, null, null, false, null, true)|escape:'html':'UTF-8'}" id="searchbox" class="row">
		<div style="margin: 0px;">
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query" type="text" id="search_query_top" name="search_query" placeholder="¿Qué estás buscando?" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
			<span>
				
                <input class="main-color" style="text-indent:0px; text-indent:0px; width:30%; float:right; line-height: 0px; height: 37px;" type="submit" value="BUSCAR" />
				
			</span>
		</div>
	</form>
</div>
<script>
$(function() {
	$('.ph_megamenu').ph_megamenu();
	if(typeof $.fn.fitVids !== 'undefined') {
		$('.ph_megamenu').fitVids();
	}
});
</script>
{/if}
