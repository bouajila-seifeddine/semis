{foreach $languages as $language}
	{if isset($options[$field.id][$language.id_lang])}
		{assign var='value_text' value=$options[$field.id][$language.id_lang]}
	{else}
		{if isset($options[$field.id][$defaultFormLanguage])}
			{assign var='value_text' value=$options[$field.id][$defaultFormLanguage]}
		{else}
			{assign var='value_text' value=''}
		{/if}
	{/if}
	{if $languages|count > 1}
	<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
		<div class="col-lg-9">
	{/if}
			<textarea
				id="{if isset($field.id)}{$field.id|escape:'UTF-8'}_{$language.id_lang|intval}{else}{$field.id|escape:'UTF-8'}_{$language.id_lang|intval}{/if}"
				name="fields[{$field.id|escape:'UTF-8'}][{$language.id_lang|intval}]"
				class="{if isset($field.class)}{$field.class|escape:'UTF-8'}{/if}rte autoload_rte">{$value_text|escape:'html':'UTF-8'}</textarea>
	{if $languages|count > 1}
		</div><!-- .col-lg-9 -->
		<div class="col-lg-2">
			<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
				{$language.iso_code|escape:'UTF-8'}
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
				{foreach from=$languages item=language}
				<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'UTF-8'}</a></li>
				{/foreach}
			</ul>
		</div><!-- .col-lg-2 -->
	</div><!-- .translatable-field -->
	{/if}
{/foreach}