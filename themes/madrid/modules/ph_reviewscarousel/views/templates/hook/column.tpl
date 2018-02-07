{if isset($reviews) && $reviews}
<div id="ph_reviewscarousel-column" class="block">
	<p class="title_block">
		{l s='Product reviews' mod='ph_reviewscarousel'}
	</p>
	<div class="block_content products-block" style="">
		<ul>
			{foreach $reviews as $review name='reviewsCarousel'}
			<li class="clearfix">
				{if $review.product_image != ''}
				<a class="products-block-image" href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}">
					<img src="{$review.product_image|escape:'htmlall':'UTF-8'}" />
				</a>
				{/if}
				<div class="product-content">
					<h5>
						<a class="product-name" href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}">{$review.name|escape:'htmlall':'UTF-8'}</a>
					</h5>

					<div class="star_content row">
					{section name="i" start=0 loop=5 step=1}
						{if $review.grade le $smarty.section.i.index}
							<div class="star"></div>
						{else}
							<div class="star star_on"></div>
						{/if}
					{/section}
					</div>

					{if $review.title != ''}
					<p class="product-description">
						<b>{$review.customer_name|escape:'htmlall':'UTF-8'}:</b>
						<br />
						{$review.title|escape:'htmlall':'UTF-8'}...
					</p>
					{/if}
			        <a data-fancybox-type="inline" data-fancybox-width="600" data-fancybox-height="400" class="btn btn-default button button-small read-review-btn" href="#full-review-{$review.id_product_comment|intval}" title="{l s='Review by' mod='ph_reviewscarousel'} {$review.customer_name|escape:'htmlall':'UTF-8'}">
						<span>{l s='Read review' mod='ph_reviewscarousel'}</span>
					</a>
				</div>
				<!-- Review -->
				<div style="display: none;">
					<div class="full-review-popup" id="full-review-{$review.id_product_comment|intval}">
						<h4><span>{$review.customer_name|escape:'htmlall':'UTF-8'}</span> {l s='on' mod='ph_reviewscarousel'} <a href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}">{$review.name|escape:'htmlall':'UTF-8'}</a></h4>
						{l s='Grade:' mod='ph_reviewscarousel'}
						<div class="star_content clearfix clearBoth">
						{section name="i" start=0 loop=5 step=1}
							{if $review.grade le $smarty.section.i.index}
								<div class="star"></div>
							{else}
								<div class="star star_on"></div>
							{/if}
						{/section}
						</div>
						<blockquote>{$review.content|escape:'html':'UTF-8'}</blockquote>
					</div>
				</div>
			</li>
			{/foreach}
		</ul>
	</div>
</div><!-- #ph_reviewscarousel-column -->
<script>
$(function() {
	$('.read-review-btn').fancybox({
		'padding':  20,
		autoSize : false,
        beforeLoad : function() {         
            this.width  = parseInt(this.element.data('fancybox-width'));  
            this.height = parseInt(this.element.data('fancybox-height'));
        }
	});
});
</script>
{/if}