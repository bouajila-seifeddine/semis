<select name="fields[{$field.id|escape:'UTF-8'}]" class="fixed-width-xl form-control">
	{foreach $field.options as $value => $label}
		<option value="{$value|escape:'UTF-8'}" {if $value == $options[$field.id]}selected{/if}>{$label|escape:'UTF-8'}</option>
	{/foreach}
</select>