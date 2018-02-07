{if $reviews}
<div id="ph_reviewscarousel_module">
	<h1 class="page-heading">
		<span class="cat-name">
			{l s='Latest product reviews' mod='ph_reviewscarousel'}
		</span>
		{if Configuration::get('PH_REVIEWSCAROUSEL_REVIEWS_NB_DISPLAY')}
		<span class="heading-counter">
			{l s='There are' mod='ph_reviewscarousel'} {$reviews|@sizeof|intval} {l s='review(s)' mod='ph_reviewscarousel'}.
		</span>
		{/if}
	</h1>

	<div class="reviews-carousel-wrapper owl-carousel owl-theme clearfix clearBoth" id="ph_reviewscarousel">
		{foreach $reviews as $review name='reviewsCarousel'}
		<div class="reviews-carousel-item">
			<h4><span>{$review.customer_name|escape:'htmlall':'UTF-8'}</span> {l s='on' mod='ph_reviewscarousel'} <a href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}">{$review.name|escape:'htmlall':'UTF-8'}</a></h4>
			<div class="star_content row">
			{section name="i" start=0 loop=5 step=1}
				{if $review.grade le $smarty.section.i.index}
					<div class="star"></div>
				{else}
					<div class="star star_on"></div>
				{/if}
			{/section}
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="text-center">
						<a href="{$review.product_link|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}">
							<img src="{$review.product_image|escape:'htmlall':'UTF-8'}" alt="" />
						</a>
						{if Configuration::get('PH_REVIEWSCAROUSEL_PRICE')}
						<span class="price">{displayPrice price=$review.product_price}</span>
						{/if}
					</div>
				</div>
				<div class="col-md-9">
					<blockquote>
						<b>{$review.title|escape:'htmlall':'UTF-8'}</b>
						{$review.content|truncate:145:'...'|escape:'html':'UTF-8'}
					</blockquote>
				</div>
			</div>
			<p class="text-center">
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
		{/foreach}
	</div><!-- .reviews-carousel-wrapper -->
</div><!-- .container -->
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