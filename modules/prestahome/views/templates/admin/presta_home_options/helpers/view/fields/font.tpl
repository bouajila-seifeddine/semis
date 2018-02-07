<div class="col-lg-4">
	<select name="fields[{$field.id|escape:'UTF-8'}]" class="fixed-width-xl form-control previewGoogleFont" data-element="#fontPreview-{$field.id|escape:'UTF-8'} .preview-wrapper">
		{foreach from=$fonts key=font_name item=font}
		<option value="{$font_name|escape:'UTF-8'}" data-url="{$font.url|escape:'UTF-8'}" {if $options[$field.id] == $font_name}selected{/if}>{$font_name|escape:'UTF-8'}</option>
		{/foreach}
	</select>
</div>

<div class="col-lg-8">
	<div class="font-preview" id="fontPreview-{$field.id|escape:'UTF-8'}">

		<div class="font-size">
			<select class="googleFontPreviewSize fixed-width-xl form-control" data-element="#fontPreview-{$field.id|escape:'UTF-8'} .preview-wrapper">
				<option value="12px">{l s='Select font size for preview' mod='prestahome'}</option>
				{for $font_size=10 to 72}
				<option value="{$font_size|escape:'UTF-8'}px">{$font_size|escape:'UTF-8'}px</option>
				{/for}
			</select>
		</div>

		<blockquote class="preview-wrapper light-wrapper">
			Grumpy wizards make toxic brew for the evil Queen and Jack.<br />Latin-ext test: ę€óąśłżźćń
		</blockquote>

	</div><!-- .font-preview -->
</div>
<script>
$(function() {
	previewGoogleFont("{$fonts[$options[$field.id]]['url']|escape:'UTF-8'}", "{$options[$field.id]|escape:'UTF-8'}", "#fontPreview-{$field.id|escape:'UTF-8'} .preview-wrapper");
});
</script>
