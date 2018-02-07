<div class="ph_bannermanager ph_bannermanager_{$hook|escape:'html':'UTF-8'} row clearfix">
{foreach $banners as $banner}
	<div class="banner col-md-{$banner.columns|intval} {if !empty($banner.class)}{$banner.class|escape:'html':'UTF-8'}{/if}">
		{if !empty($banner.url)}<a href="{$banner.url|escape:'htmlall':'UTF-8'}" {if $banner.new_window}target="_blank"{/if} title="{$banner.title|escape:'html':'UTF-8'}">{/if}
			<img src="{$image_path|escape:'UTF-8'}{$banner.image|escape:'UTF-8'}" alt="{$banner.title|escape:'html':'UTF-8'}" class="img-responsive" />
		{if !empty($banner.url)}</a>{/if}
	</div>
{/foreach}
</div>