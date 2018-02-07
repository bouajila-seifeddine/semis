<div class="form-group">
	<div class="col-lg-2">
		<div class="row">
			<div class="input-group" id="{$field.id|escape:'UTF-8'}_start">
				<div class="input-group-addon">{l s='Start' mod='prestahome'}</div>
				<input type="color"
				data-hex="true"
				class="color mColorPickerInput"
				name="fields[{$field.id|escape:'UTF-8'}][start]"
				value="{$options[$field.id]['start']|escape:'html':'UTF-8'}" />
			</div>
		</div>
		{if isset($field.defaults.start)}
		<div class="row" style="margin-top: 8px;">
			<div class="input-group">
				<select class="load-default-color fixed-width-xl form-control" data-wrapper="{$field.id|escape:'UTF-8'}_start">
					<option class="scheme-default" value="{$options[$field.id]['start']|escape:'html':'UTF-8'}">{l s='Load defaults from:' mod='prestahome'}</option>
					{foreach $field.defaults.start as $pallete => $color}
					<option class="scheme-{$pallete|escape:'UTF-8'}" value="{$color|escape:'UTF-8'}">{$pallete|@ucfirst|escape:'UTF-8'}</option>
					{/foreach}
				</select>
			</div>
		</div><!-- .row -->
		{/if}
	</div>
	<div class="col-lg-2 col-lg-offset-1">
		<div class="row">
			<div class="input-group" id="{$field.id|escape:'UTF-8'}_end">
				<div class="input-group-addon">{l s='End' mod='prestahome'}</div>
				<input type="color"
				data-hex="true"
				class="color mColorPickerInput"
				name="fields[{$field.id|escape:'UTF-8'}][end]"
				value="{$options[$field.id]['end']|escape:'html':'UTF-8'}" />
			</div>
		</div>
		{if isset($field.defaults.end)}
		<div class="row" style="margin-top: 8px;">
			<div class="input-group">
				<select class="load-default-color fixed-width-xl form-control" data-wrapper="{$field.id|escape:'UTF-8'}_end">
					<option class="scheme-default" value="{$options[$field.id]['end']|escape:'html':'UTF-8'}">{l s='Load defaults from:' mod='prestahome'}</option>
					{foreach $field.defaults.end as $pallete => $color}
					<option class="scheme-{$pallete|escape:'UTF-8'}" value="{$color|escape:'UTF-8'}">{$pallete|@ucfirst|escape:'UTF-8'}</option>
					{/foreach}
				</select>
			</div>
		</div><!-- .row -->
		{/if}
	</div>
</div>