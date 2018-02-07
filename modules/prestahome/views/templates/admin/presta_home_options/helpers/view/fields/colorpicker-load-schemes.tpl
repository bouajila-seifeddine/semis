<select class="load-defaults-color fixed-width-xl form-control" data-wrapper="{$field.id|escape:'UTF-8'}">
	<option class="scheme-default" value="default">{l s='Load defaults from:' mod='prestahome'}</option>
	{foreach $schemes as $scheme}
	<option value="{$scheme|escape:'UTF-8'}">{$scheme|@ucfirst|escape:'UTF-8'}</option>
	{/foreach}
</select>