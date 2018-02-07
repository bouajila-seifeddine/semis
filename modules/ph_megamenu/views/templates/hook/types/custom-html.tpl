{*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*}
{if $megamenu.display_title}
	<h3>
		{if $megamenu.url != ''}
		<a href="{$megamenu.url|escape:'htmlall':'UTF-8'}" title="{$megamenu.title|escape:'htmlall':'UTF-8'}">
		{/if}
			{if $megamenu.icon != ''}
				<i class="fa {$megamenu.icon|escape:'htmlall':'UTF-8'}"></i>
			{/if}
			
			{$megamenu.title|escape:'htmlall':'UTF-8'}
		{if $megamenu.url != ''}
		</a>
		{/if}
	</h3>
{/if}

{$megamenu.content}{* HTML CONTENT *}