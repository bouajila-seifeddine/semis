{*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*}
{foreach $megamenu.categories as $cat}
<div class="col-md-12">
	{if $megamenu.display_title == 1}
	<h3 class="ph-mega-categories-cat-title cat_{$cat.id_category|intval}">
		<a href="{if $cat.id_category > 2}{$link->getCategoryLink($cat.id_category|intval, $cat.link_rewrite|escape:'htmlall':'UTF-8')}{else}#{/if}" title="{$cat.name|escape:'htmlall':'UTF-8'}">
			{$cat.name|escape:'htmlall':'UTF-8'}
		</a>
	</h3>
	{/if}

	{if isset($cat.children)}
		<ul class="ph-mega-categories-list-lvl-1">
			{foreach $cat.children as $cat_child}
			{* level 1 *}
			<li class="cat_{$cat_child.id_category|intval} {if isset($smarty.get.id_category) && $smarty.get.id_category == $cat_child.id_category|intval}active{/if}">
				<a href="{$link->getCategoryLink($cat_child.id_category|intval, $cat_child.link_rewrite|escape:'htmlall':'UTF-8')}" title="{$cat_child.name|escape:'htmlall':'UTF-8'}">
					{$cat_child.name|escape:'htmlall':'UTF-8'}
				</a>

				{* level 2 *}
				{if isset($cat_child.children)}
					<ul class="ph-mega-categories-list-lvl-2 dropdown megamenu-dropdown">
						{foreach $cat_child.children as $cat_child_lvl_2}
							<li class="cat_{$cat_child_lvl_2.id_category|intval} {if isset($smarty.get.id_category) && $smarty.get.id_category == $cat_child_lvl_2.id_category|intval}active{/if}">

								<a href="{$link->getCategoryLink($cat_child_lvl_2.id_category|intval, $cat_child_lvl_2.link_rewrite|escape:'htmlall':'UTF-8')}">
									{$cat_child_lvl_2.name|escape:'htmlall':'UTF-8'}
								</a>

								{* level 3 *}
								{if isset($cat_child_lvl_2.children)}
									<ul class="ph-mega-categories-list-lvl-3 dropdown megamenu-dropdown">
										{foreach $cat_child_lvl_2.children as $cat_child_lvl_3}
											<li class="cat_{$cat_child_lvl_3.id_category|intval} {if isset($smarty.get.id_category) && $smarty.get.id_category == $cat_child_lvl_3.id_category|intval}active{/if}">
												<a href="{$link->getCategoryLink($cat_child_lvl_3.id_category|intval, $cat_child_lvl_3.link_rewrite|escape:'htmlall':'UTF-8')}">
													{$cat_child_lvl_3.name|escape:'htmlall':'UTF-8'}
												</a>
											</li>
										{/foreach}
									</ul>
								{/if}
								
							</li>
						{/foreach}
					</ul>
				{/if}
			</li>
			{/foreach}
		</ul>
	{/if}
</div><!-- .col-md-12 -->
{/foreach}
