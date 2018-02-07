{if isset($recent_posts) && count($recent_posts)}
</div><!-- .container -->
{assign var=is_category value=false} {* we want to show category on recent post but have the same code as on category post listing ;) *}
<div class="ph_simpleblog simpleblog-recent carousel-style animated" data-fx="fadeInUp">
	<div class="container">
		<div class="title">
			<div class="col-md-offset-5 col-sm-offset-4 col-md-4 col-sm-4 col-xs-12">
				<h3><strong>{$theme_options['ph_recentposts_title']}</strong> {$theme_options['ph_recentposts_title_two']}</h3>
			</div>

			{if $recent_posts|@count > 4}
			<div class="arrow_container pull-right">
				<a href="#" class="arrow-ph grey arrow-prev" title="Previous slide"><i class="icon icon-angle-left"></i></a>
				<a href="#" class="arrow-ph grey arrow-next" title="Next slide"><i class="icon icon-angle-right"></i></a>
			</div>
			{/if}
			<a href="{$link->getModuleLink('ph_simpleblog', 'list')}" title="{l s='Recent posts' mod='ph_recentposts'}" class="pull-right button-mini">{$theme_options['ph_recentposts_viewall']}</a>
		</div><!--. title -->
		<div class="row">
			<div class="simpleblog-posts recent_posts blog-main owl-carousel-ph" data-max-items="4" itemscope itemtype="http://schema.org/Blog">
			{foreach from=$recent_posts item=post}

				<div class="simpleblog-post-item simpleblog-post-type-{$post.post_type|escape:'html':'UTF-8'}
				{if $blogLayout eq 'grid' AND $columns eq '3'}
					col-md-4 col-sm-6 col-xs-12 col-ms-12 {cycle values="first-in-line,second-in-line,last-in-line"}
				{elseif $blogLayout eq 'grid' AND $columns eq '4'}
					col-md-3 col-sm-6 col-xs-12 col-ms-12 {cycle values="first-in-line,second-in-line,third-in-line,last-in-line"}
				{elseif $blogLayout eq 'grid' AND $columns eq '2'}
					col-md-6 col-sm-6 col-xs-12 col-ms-12 {cycle values="first-in-line,last-in-line"}
				{else}
				col-md-12
				{/if} item" itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">

					<div class="post-item">
						{assign var='post_type' value=$post.post_type}

						{* How it works? *}
						{* We slice post at few parts, thumbnail, title, description etc. we check if override for specific parts exists for current post type and if so we include this tpl file *}

						{if $post_type != 'post' && file_exists("$tpl_path./types/$post_type/thumbnail.tpl")}
							{include file="./types/$post_type/thumbnail.tpl"}
						{else}
							{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
							<div class="post-thumbnail" itemscope itemtype="http://schema.org/ImageObject">
								<span class="img_hover"></span>
								<a href="{$post.url|escape:'html':'UTF-8'}" title="{l s='Permalink to' mod='ph_recentposts'} {$post.title|escape:'html':'UTF-8'}" itemprop="contentUrl">
									{if $blogLayout eq 'full'}
										<img src="{$post.banner_wide|escape:'html':'UTF-8'}" alt="{$post.title|escape:'html':'UTF-8'}" class="img-responsive" itemprop="thumbnailUrl" />
									{else}
										<img src="{$post.banner_thumb|escape:'html':'UTF-8'}" alt="{$post.title|escape:'html':'UTF-8'}" class="img-responsive" itemprop="thumbnailUrl"/>
									{/if}
								</a>
							</div><!-- .post-thumbnail -->
							{/if}
						{/if}

						{if $post_type != 'post' && file_exists("$tpl_path./types/$post_type/title.tpl")}
							{include file="./types/$post_type/title.tpl"}
						{else}
							<div class="post-title">
								<h4 itemprop="headline">
									<a class="main-color-txt" href="{$post.url|escape:'html':'UTF-8'}" title="{l s='Permalink to' mod='ph_recentposts'} {$post.title|escape:'html':'UTF-8'}">
										{$post.title|escape:'html':'UTF-8'}
									</a>
								</h4>
							</div><!-- .post-title -->
						{/if}

						{if $post_type != 'post' && file_exists("$tpl_path./types/$post_type/meta.tpl")}
							{include file="./types/$post_type/meta.tpl"}
						{else}
							<div class="post-additional-info post-meta-info">
								{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
									<span class="post-date">
										<i class="fa fa-calendar"></i> <time itemprop="datePublished" datetime="{$post.date_add|date_format:'c'}">{$post.date_add|date_format:Configuration::get('PH_BLOG_DATEFORMAT')}</time>
									</span>
								{/if}

								{if $is_category eq false && Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
									<span class="post-category">
										<i class="fa fa-tags"></i> <a href="{$post.category_url}" title="{$post.category|escape:'html':'UTF-8'}" rel="category">{$post.category|escape:'html':'UTF-8'}</a>
									</span>
								{/if}

								{if isset($post.author) && !empty($post.author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
									<span class="post-author">
										<i class="fa fa-user"></i> <span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">{$post.author|escape:'html':'UTF-8'}</span>
									</span>
								{/if}
							</div><!-- .post-additional-info post-meta-info -->
						{/if}

						{if $post_type != 'post' && file_exists("$tpl_path./types/$post_type/description.tpl")}
							{include file="./types/$post_type/description.tpl"}
						{else}
							{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
							<div class="post-content" itemprop="text">
								{$post.short_content|strip_tags:'UTF-8'}
							</div><!-- .post-content -->	
							{/if}

							{if Configuration::get('PH_BLOG_DISPLAY_MORE')}
							<div class="post-read-more">
								<a class="main-color" href="{$post.url|escape:'html':'UTF-8'}" title="{l s='Read more' mod='ph_recentposts'}">
									{l s='read more' mod='ph_recentposts'} <i class="icon icon-arrow-circle-right"></i>
								</a>
							</div><!-- .post-read-more -->
							{/if}
						{/if}
					</div><!-- .post-item -->
				</div><!-- .simpleblog-post-item -->

			{/foreach}
			</div><!-- .simpleblog-posts -->
		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- .ph_simpleblog -->
<div class="container">
{/if}