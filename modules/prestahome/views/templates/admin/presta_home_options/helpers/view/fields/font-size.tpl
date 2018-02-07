<select class="fixed-width-xl form-control" name="fields[{$field.id|escape:'UTF-8'}]">
	{for $font_size=$field.size_from to $field.size_to}
	<option value="{$font_size|escape:'UTF-8'}px" {if $options[$field.id] == $font_size}selected{/if}>{$font_size|escape:'UTF-8'}px</option>
	{/for}
</select>