{*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*}
<ul class="ph-dropdown-categories dropdown">
	{foreach $tab.dropdown as $dropdown_cat name='dropdown_category_loop'}
	<li class="dropdown_cat_{$dropdown_cat.id_category|intval}">
		<a href="{$link->getCategoryLink($dropdown_cat.id_category|intval, $dropdown_cat.link_rewrite|escape:'htmlall':'UTF-8')}">
			{$dropdown_cat.name|escape:'html':'UTF-8'}
		</a>
		{* level 1 *}
		{if isset($dropdown_cat.children)}
		<ul class="dropdown">
			{foreach $dropdown_cat.children as $dropdown_cat_child}
			<li class="dropdown_cat_child_{$dropdown_cat_child.id_category|intval}">
				<a href="{$link->getCategoryLink($dropdown_cat_child.id_category|intval, $dropdown_cat_child.link_rewrite|escape:'htmlall':'UTF-8')}">
					{$dropdown_cat_child.name|escape:'html':'UTF-8'}
				</a>
				{* level 2 *}
				{if isset($dropdown_cat_child.children)}
					<ul class="dropdown">
						{foreach $dropdown_cat_child.children as $dropdown_cat_child_lvl_2}
						<li>
							<a href="{$link->getCategoryLink($dropdown_cat_child_lvl_2.id_category|intval, $dropdown_cat_child_lvl_2.link_rewrite|escape:'htmlall':'UTF-8')}">
								{$dropdown_cat_child_lvl_2.name|escape:'html':'UTF-8'}
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
</ul><!-- .ph-dropdown-categories -->