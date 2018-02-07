<section class="content-area bg2 animated" data-fx="fadeInDown">
	<div class="container">
		<div class="row">
			<div class="container">
				<div class="row">
					{foreach $icon_boxes as $icon_box}
					<div class="col-md-{$icon_box.columns|intval} col-sm-6 col-xs-12{if !empty($icon_box.class)} {$icon_box.class|escape:'UTF-8'}{/if}">
						<div class="iconBox type5">
							<div class="media">
								<span class="pull-left"><i class="fa fa-{$icon_box.icon|escape:'UTF-8'}"></i></span>
								<div class="media-body">
									<h4 class="media-heading">
										{if !empty($icon_box.url)}<a href="{$icon_box.url|escape:'UTF-8'}" title="{$icon_box.title|escape:'UTF-8'}">{/if}
											{$icon_box.title|escape:'UTF-8'}
										{if !empty($icon_box.url)}</a>{/if}
									</h4>
								</div><!-- .media-body -->
							</div><!-- .media -->
						</div><!-- .iconBox -->
					</div><!-- .col-md-3 -->
					{/foreach}
				</div><!-- .row -->
			</div><!-- .container -->
		</div><!-- .row -->
	</div><!-- .container -->
</section>