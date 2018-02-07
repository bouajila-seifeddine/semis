<div class="form-group">
	<div class="col-lg-2">
		<div class="row">
			<div class="input-group">
				<input type="color"
				{if isset($field.use_rgba)}data-rgba="true"{else}data-hex="true"{/if}
				class="color mColorPickerInput"
				name="fields[{$field.id|escape:'UTF-8'}]"
				value="{$options[$field.id]|escape:'html':'UTF-8'}" />
			</div>
		</div>
	</div>
	{if isset($field.defaults)}
	<div class="col-lg-2">
		<div class="input-group">
			<select class="load-default-color fixed-width-xl form-control" data-wrapper="{$field.id|escape:'UTF-8'}">
				<option class="scheme-default" value="{$options[$field.id]|escape:'UTF-8'}">{l s='Load defaults from:' mod='prestahome'}</option>
				{foreach $field.defaults as $pallete => $color}
				<option class="scheme-{$pallete|escape:'UTF-8'}" value="{$color|escape:'UTF-8'}">{$pallete|@ucfirst|escape:'UTF-8'}</option>
				{/foreach}
			</select>
		</div>
	</div>
	{/if}
</div>