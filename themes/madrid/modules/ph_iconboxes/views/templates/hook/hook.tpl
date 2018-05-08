<div class="boxes row clearBoth">
	{foreach $icon_boxes as $icon_box}
	<div class="col-md-{$icon_box.columns|intval} col-sm-4 col-xs-12{if !empty($icon_box.class)} {$icon_box.class|escape:'UTF-8'}{/if}">
		<div class="main-box">
			<span class="icon main-color">
				<i class="icon icon-{$icon_box.icon|escape:'UTF-8'}"></i>
			</span>
			<h3 class="main-color-txt">
				{if !empty($icon_box.url)}<a href="{$icon_box.url|escape:'UTF-8'}" title="{$icon_box.title|escape:'UTF-8'}">{/if}
				{$icon_box.title|escape:'UTF-8'}
				{if !empty($icon_box.url)}</a>{/if}
			</h3>
			{if !empty($icon_box.content)}
			<p>{$icon_box.content}</p>
			{/if}
		</div><!-- .main-box -->
	</div><!-- .col-md-4 -->
	{/foreach}
</div><!-- .boxes -->
<h1 id="titulo-index"> Bienvenidos a Semillas Low Cost<br /> si buscas semillas de marihuana baratas, este es tu sitio.</h1>
