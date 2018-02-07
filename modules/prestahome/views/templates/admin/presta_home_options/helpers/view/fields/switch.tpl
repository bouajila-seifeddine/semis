<span class="switch prestashop-switch fixed-width-lg">
	<input type="radio" name="fields[{$field.id|escape:'UTF-8'}]" id="{$field.id|escape:'UTF-8'}_on" value="1" {if $options[$field.id]}checked="checked"{/if} />
	<label for="{$field.id|escape:'UTF-8'}_on" class="radioCheck">
		{if isset($field.label_on)}
			{$field.label_on|escape:'UTF-8'}
		{else}
			{l s='Yes' mod='prestahome'}
		{/if}
	</label>

	<input type="radio" name="fields[{$field.id|escape:'UTF-8'}]" id="{$field.id|escape:'UTF-8'}_off" value="0" {if !$options[$field.id]}checked="checked"{/if} />
	<label for="{$field.id|escape:'UTF-8'}_off" class="radioCheck">
		{if isset($field.label_off)}
			{$field.label_off|escape:'UTF-8'}
		{else}
			{l s='No' mod='prestahome'}
		{/if}
	</label>

	<a class="slide-button btn"></a>
</span>
		