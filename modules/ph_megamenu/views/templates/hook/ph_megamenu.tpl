{*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*}
{if $menu}
<div class="ph_megamenu_mobile_toggle container">
	<a href="#" class="show_megamenu"><i class="fa fa-bars"></i>{l s='Show menu' mod='ph_megamenu'}</a>
	<a href="#" class="hide_megamenu"><i class="fa fa-times"></i>{l s='Hide menu' mod='ph_megamenu'}</a>
</div>
<div id="ph_megamenu_wrapper" class="clearBoth container">
	<nav role="navigation">
		<ul id="ph_megamenu" class="ph_megamenu">
			{foreach from=$menu item=tab key=key}
				<li class="menu_link_{$tab.id_prestahome_megamenu|intval}{if $tab.class != ''} {$tab.class|escape:'htmlall':'UTF-8'}{/if}{if $tab.align == 1} align-right{/if}{if $tab.icon != ''} with-icon{/if} {if $tab.type == 1}has-submenu{/if} {if $tab.url == $base_dir}active{/if}{if $tab.hide_on_mobile} ph-hidden-mobile{/if}{if $tab.hide_on_desktop} ph-hidden-desktop{/if}">
					<a href="{$tab.url|escape:'htmlall':'UTF-8'}" title="{$tab.title|escape:'htmlall':'UTF-8'}" {if $tab.new_window}target="_blank"{/if}>
						{if $tab.icon != ''}
							<i class="fa {$tab.icon|escape:'htmlall':'UTF-8'}"></i>
						{/if}
						
						<span class="{if !$tab.display_title}hide{/if}">{$tab.title|escape:'htmlall':'UTF-8'}</span>

						{if $tab.label_text}
							<span class="label" style="color:{$tab.label_bg|escape:'htmlall':'UTF-8'};background:{$tab.label_bg|escape:'htmlall':'UTF-8'};">
								<span style="color: {$tab.label_color|escape:'htmlall':'UTF-8'};">{$tab.label_text|escape:'htmlall':'UTF-8'}</span>
							</span>
						{/if}
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
										<i class="fa {$dropdown.icon|escape:'htmlall':'UTF-8'}"></i>
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
<script>
$(function() {
	$('.ph_megamenu').ph_megamenu();
	if(typeof $.fn.fitVids !== 'undefined') {
		$('.ph_megamenu').fitVids();
	}
});
</script>
{/if}
