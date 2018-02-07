{*
* 2007-2016 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
*  @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
*  @copyright 2007-2016 IQIT-COMMERCE.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
* 
*}


	<div  class="iqitmegamenu-wrapper col-xs-12 cbp-hor-width-{$menu_settings.hor_width|escape:'htmlall':'UTF-8'} clearfix">
		<div id="iqitmegamenu-horizontal" class="iqitmegamenu  cbp-nosticky {if $menu_settings.hor_s_transparent && $menu_settings.hor_sticky} cbp-sticky-transparent{/if}" role="navigation">
			<div class="container">
				
				{if isset($menu_settings_v) && ($menu_settings_v.ver_position==2 || $menu_settings_v.ver_position==3) }

					<div class="cbp-vertical-on-top {if $menu_settings_v.ver_position==2}cbp-homepage-expanded{/if}">
						{include file="./iqitmegamenu_vertical.tpl" ontop=1}  
					</div>
				{/if}
				{hook h='displayAfterIqitMegamenu'}
				<nav id="cbp-hrmenu" class="cbp-hrmenu cbp-horizontal cbp-hrsub-narrow  {if $menu_settings.hor_animation==1}cbp-fade{/if} {if $menu_settings.hor_animation==2}cbp-fade-slide-bottom{/if} {if $menu_settings.hor_animation==3}cbp-fade-slide-top{/if} {if $menu_settings.hor_s_arrow}cbp-arrowed{/if} {if !$menu_settings.hor_arrow} cbp-submenu-notarrowed{/if} {if !$menu_settings.hor_arrow} cbp-submenu-notarrowed{/if} {if $menu_settings.hor_center} cbp-menu-centered{/if} ">
					<ul>
						{foreach $horizontal_menu as $tab}
						<li class="cbp-hrmenu-tab cbp-hrmenu-tab-{$tab.id_tab|escape:'htmlall':'UTF-8'}{if $tab.active_label} cbp-onlyicon{/if}{if $tab.float} pull-right cbp-pulled-right{/if} {if $tab.submenu_type && !empty($tab.submenu_content)} cbp-has-submeu{/if}">
	{if $tab.url_type == 2}<a role="button" class="cbp-empty-mlink">{else}<a href="{$tab.url|escape:'htmlall':'UTF-8'}" {if $tab.new_window}target="_blank"{/if}>{/if}
								

								<span class="cbp-tab-title">{if $tab.icon_type && !empty($tab.icon_class)} <i class="icon fa {$tab.icon_class|escape:'htmlall':'UTF-8'} cbp-mainlink-icon"></i>{/if}

								{if !$tab.icon_type && !empty($tab.icon)} <img src="{$tab.icon|escape:'htmlall':'UTF-8'}" alt="{$tab.title|escape:'html':'UTF-8'}" class="cbp-mainlink-iicon" />{/if}{if !$tab.active_label}{$tab.title|escape:'html':'UTF-8'|replace:'/n':'<br />'}{/if}{if $tab.submenu_type} <i class="icon fa icon-angle-down cbp-submenu-aindicator"></i>{/if}</span>
								{if !empty($tab.label)}<span class="label cbp-legend cbp-legend-main">{if !empty($tab.legend_icon)} <i class="icon fa {$tab.legend_icon} cbp-legend-icon"></i>{/if} {$tab.label}
								<span class="cbp-legend-arrow"></span></span>{/if}
						</a>
							{if $tab.submenu_type && !empty($tab.submenu_content)}
							<div class="cbp-hrsub col-xs-{$tab.submenu_width|escape:'htmlall':'UTF-8'}">
								<div class="cbp-triangle-container"><div class="cbp-triangle-top"></div><div class="cbp-triangle-top-back"></div></div>
								<div class="cbp-hrsub-inner">
									{if $menu_settings.hor_s_width && !$menu_settings.hor_width && !$menu_settings.hor_sw_width}<div class="container">{/if}
									{if $tab.submenu_type==1}
									<div class="container-xs-height cbp-tabs-container">
									<div class="row row-xs-height">
									<div class="col-xs-2 col-xs-height">
										<ul class="cbp-hrsub-tabs-names cbp-tabs-names">
											{if isset($tab.submenu_content_tabs)}
											{foreach $tab.submenu_content_tabs as $innertab name=innertabsnames}
											<li class="innertab-{$innertab->id|escape:'htmlall':'UTF-8'} {if $smarty.foreach.innertabsnames.first}active{/if}">
												<a href="#{$innertab->id|escape:'htmlall':'UTF-8'}-innertab-{$tab.id_tab|escape:'htmlall':'UTF-8'}" {if $innertab->url_type != 2} data-link="{$innertab->url|escape:'htmlall':'UTF-8'}" {/if}>
												{if $innertab->icon_type && !empty($innertab->icon_class)} <i class="icon fa {$innertab->icon_class|escape:'htmlall':'UTF-8'} cbp-mainlink-icon"></i>{/if}
												{if !$innertab->icon_type && !empty($innertab->icon)} <img src="{$innertab->icon|escape:'htmlall':'UTF-8'}" alt="{$innertab->title|escape:'html':'UTF-8'}" class="cbp-mainlink-iicon" />{/if}
												{if !$innertab->active_label}{$innertab->title|escape:'html':'UTF-8'} {/if}
												{if !empty($innertab->label)}<span class="label cbp-legend cbp-legend-inner">{if !empty($innertab->legend_icon)} <i class="icon fa {$innertab->legend_icon|escape:'htmlall':'UTF-8'} cbp-legend-icon"></i>{/if} {$innertab->label}
												<span class="cbp-legend-arrow"></span></span>{/if}
											</a><i class="icon fa icon-angle-right cbp-submenu-it-indicator"></i><span class="cbp-inner-border-hider"></span></li>
											{/foreach}
											{/if}
										</ul>	
									</div>
								
										{if isset($tab.submenu_content_tabs)}
											{foreach $tab.submenu_content_tabs as $innertab name=innertabscontent}
											<div role="tabpanel" class="col-xs-10 col-xs-height tab-pane cbp-tab-pane {if $smarty.foreach.innertabscontent.first}active{/if} innertabcontent-{$innertab->id|escape:'htmlall':'UTF-8'}"  id="{$innertab->id|escape:'intval'}-innertab-{$tab.id_tab|escape:'htmlall':'UTF-8'}">

												{if !empty($innertab->submenu_content)}
												<div class="clearfix">
												{foreach $innertab->submenu_content as $element}
												{include file="./front_submenu_content.tpl" node=$element}               
												{/foreach}
												</div>
												{/if}

											</div>
											{/foreach}
										{/if}
									
									</div></div>
									{else}

										{if !empty($tab.submenu_content)}
											{foreach $tab.submenu_content as $element}
											{include file="./front_submenu_content.tpl" node=$element}               
											{/foreach}
										{/if}

									{/if}
									{if $menu_settings.hor_s_width && !$menu_settings.hor_width && !$menu_settings.hor_sw_width}</div>{/if}
								</div>
							</div>
							{/if}
						</li>
						{/foreach}
					</ul>
				</nav>
				

				
			</div>
			<div id="iqitmegamenu-mobile">

					<div id="iqitmegamenu-shower" class="clearfix"><div class="container">
						<div class="iqitmegamenu-icon"><i class="icon fa icon-reorder"></i></div>
						<span>{l s='Menu' mod='iqitmegamenu'}</span>
						</div>
					</div>
					<div class="cbp-mobilesubmenu"><div class="container">
					<ul id="iqitmegamenu-accordion" class="{if $mobile_menu_style}iqitmegamenu-accordion{else}cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left{/if}"> 
						{include file="./iqit_mobilemenu.tpl" menu=$mobile_menu}    
					</ul></div></div>
					{if !$mobile_menu_style}<div id="cbp-spmenu-overlay" class="cbp-spmenu-overlay"><div id="cbp-close-mobile" class="close-btn-ui"><i class="icon icon-times"></i></div></div>{/if}
				</div> 
		</div>
	</div>
