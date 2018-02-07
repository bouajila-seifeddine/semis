{if $reviews}
<div class="reviews carousel-style">
	<div class="heading_block margin-bottom">
		<h4 class="pull-left">
			<i class="icon icon-plus-circle main-color"></i>
			<strong>{l s='latest' mod='ph_reviewscarousel'}</strong> {l s='product reviews' mod='ph_reviewscarousel'}
			{if Configuration::get('PH_REVIEWSCAROUSEL_REVIEWS_NB_DISPLAY')}
			<span class="heading-counter">
				- {l s='There are' mod='ph_reviewscarousel'} {$reviews|@sizeof|intval} {l s='review(s)' mod='ph_reviewscarousel'}.
			</span>
			{/if}
		</h4>
		<div class="arrow_container pull-right">
			<a href="#" class="arrow-ph arrow-prev" title="{l s='Previous' mod='ph_reviewscarousel'}"><i class="icon icon-angle-left"></i></a>
			<a href="#" class="arrow-ph arrow-next" title="{l s='Next' mod='ph_reviewscarousel'}"><i class="icon icon-angle-right"></i></a>
		</div>
	</div>
	<div class="row">
		<div class="reviews-carousel-wrapper owl-carousel-ph clearBoth" id="ph_reviewscarousel">
			{foreach $reviews as $review name='reviewsCarousel'}
			<div class="col-md-4 col-sm-4 col-xs-12 item">
				<div class="reviews-carousel-item">
					<h4><span>{$review.customer_name|escape:'htmlall':'UTF-8'}</span> {l s='on' mod='ph_reviewscarousel'} <a href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}">{$review.name|escape:'htmlall':'UTF-8'}</a></h4>
					<div class="star_content">
					{section name="i" start=0 loop=5 step=1}
						{if $review.grade le $smarty.section.i.index}
							<div class="star"></div>
						{else}
							<div class="star star_on"></div>
						{/if}
					{/section}
					</div>
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<div class="text-center">
								<a href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}">
									<img src="{$review.product_image|escape:'htmlall':'UTF-8'}" alt="" />
								</a>
								{if Configuration::get('PH_REVIEWSCAROUSEL_PRICE')}
								<span class="price">{displayPrice price=$review.product_price}</span>
								{/if}
							</div>
						</div>
						<div class="col-md-9 col-sm-6">
							<blockquote>
								<b>{$review.title|escape:'htmlall':'UTF-8'}</b>
								{$review.content|truncate:145:'...'|escape:'html':'UTF-8'}
							</blockquote>
						</div>
					</div>
					<p class="text-center row">
						<a data-fancybox-type="inline" data-fancybox-width="600" data-fancybox-height="400" class="see-more-btn read-review-btn" href="#full-review-{$review.id_product_comment|intval}" title="{l s='Review by' mod='ph_reviewscarousel'} {$review.customer_name|escape:'htmlall':'UTF-8'}">
							<span>{l s='Read review' mod='ph_reviewscarousel'}</span>
						</a>
						<a class="see-more-btn" href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{l s='View product' mod='ph_reviewscarousel'}">
							<span>{l s='View product' mod='ph_reviewscarousel'} &raquo;</span>
						</a>
					</p>
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
				</div><!-- .reviews-carousel-item -->
			</div><!-- .item -->
			{/foreach}
		</div><!-- .reviews-carousel-wrapper -->
	</div>
</div>
<script>
var reviewsAutoPlay = {if Configuration::get('PH_REVIEWSCAROUSEL_AUTOPLAY')}{Configuration::get('PH_REVIEWSCAROUSEL_AUTOPLAY')|intval}{else}false{/if};
var nbReviewsCarouselDesktop = {Configuration::get('PH_REVIEWSCAROUSEL_ITEMS_DESKTOP')|intval};
var nbReviewsCarouselTablet = {Configuration::get('PH_REVIEWSCAROUSEL_ITEMS_TABLET')|intval};
var nbReviewsCarouselMobile = {Configuration::get('PH_REVIEWSCAROUSEL_ITEMS_MOBILE')|intval};
var nbReviewsCarouselNavigation = {if Configuration::get('PH_REVIEWSCAROUSEL_NAVIGATION')}true{else}false{/if};

$(function() {
    $("#ph_reviewscarousel").owlCarousel({
        autoPlay 			: reviewsAutoPlay,
        stopOnHover 		: true,
        navigation			: nbReviewsCarouselNavigation,
        pagination			: false,
        transitionStyle		: "fade",
        items 				: nbReviewsCarouselDesktop,
        itemsDesktop 		: nbReviewsCarouselDesktop,
        itemsDesktopSmall 	: nbReviewsCarouselTablet,
        itemsTablet 		: [981,nbReviewsCarouselTablet],
        itemsMobile			: [550,nbReviewsCarouselMobile]
    });

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