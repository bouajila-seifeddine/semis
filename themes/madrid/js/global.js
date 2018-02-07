/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
//global variables
var responsiveflag = false;

$(document).ready(function(){
	highdpiInit();
	responsiveResize();
	$(window).resize(responsiveResize);
	if (navigator.userAgent.match(/Android/i))
	{
		var viewport = document.querySelector('meta[name="viewport"]');
		viewport.setAttribute('content', 'initial-scale=1.0,maximum-scale=1.0,user-scalable=0,width=device-width,height=device-height');
		window.scrollTo(0, 1);
	}
	if (typeof quickView !== 'undefined' && quickView)
		quick_view();
	dropDown();

	if (typeof page_name != 'undefined' && !in_array(page_name, ['index', 'product']))
	{
		//bindGrid();

 		$(document).on('change', '.selectProductSort', function(e){
			if (typeof request != 'undefined' && request)
				var requestSortProducts = request;
 			var splitData = $(this).val().split(':');
 			var url = '';
			if (typeof requestSortProducts != 'undefined' && requestSortProducts)
			{
				url += requestSortProducts ;
				if (typeof splitData[0] !== 'undefined' && splitData[0])
				{
					url += ( requestSortProducts.indexOf('?') < 0 ? '?' : '&') + 'orderby=' + splitData[0] + (splitData[1] ? '&orderway=' + splitData[1] : '');
					if (typeof splitData[1] !== 'undefined' && splitData[1])
						url += '&orderway=' + splitData[1];
				}
				document.location.href = url;
			}
    	});

		$(document).on('change', 'select[name="n"]', function(){
			$(this.form).submit();
		});

		$(document).on('change', 'select[name="currency_payment"]', function(){
			setCurrency($(this).val());
		});
	}

	$(document).on('change', 'select[name="manufacturer_list"], select[name="supplier_list"]', function(){
		if (this.value != '')
			location.href = this.value;
	});

	$(document).on('click', '.back', function(e){
		e.preventDefault();
		history.back();
	});

	jQuery.curCSS = jQuery.css;
	if (!!$.prototype.cluetip)
		$('a.cluetip').cluetip({
			local:true,
			cursor: 'pointer',
			dropShadow: false,
			dropShadowSteps: 0,
			showTitle: false,
			tracking: true,
			sticky: false,
			mouseOutClose: true,
			fx: {
		    	open:       'fadeIn',
		    	openSpeed:  'fast'
			}
		}).css('opacity', 0.8);

	if (!!$.prototype.fancybox)
		$.extend($.fancybox.defaults.tpl, {
			closeBtn : '<a title="' + FancyboxI18nClose + '" class="fancybox-item fancybox-close" href="javascript:;"></a>',
			next     : '<a title="' + FancyboxI18nNext + '" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
			prev     : '<a title="' + FancyboxI18nPrev + '" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
		});

	// Close Alert messages
	$(".alert.alert-danger").on('click', this, function(e){
		if (e.offsetX >= 16 && e.offsetX <= 39 && e.offsetY >= 16 && e.offsetY <= 34)
			$(this).fadeOut();
	});
});

function highdpiInit()
{
	if($('.replace-2x').css('font-size') == "1px")
	{
		var els = $("img.replace-2x").get();
		for(var i = 0; i < els.length; i++)
		{
			src = els[i].src;
			extension = src.substr( (src.lastIndexOf('.') +1) );
			src = src.replace("." + extension, "2x." + extension);

			var img = new Image();
			img.src = src;
			img.height != 0 ? els[i].src = src : els[i].src = els[i].src;
		}
	}
}


// Used to compensante Chrome/Safari bug (they don't care about scroll bar for width)
function scrollCompensate()
{
    var inner = document.createElement('p');
    inner.style.width = "100%";
    inner.style.height = "200px";

    var outer = document.createElement('div');
    outer.style.position = "absolute";
    outer.style.top = "0px";
    outer.style.left = "0px";
    outer.style.visibility = "hidden";
    outer.style.width = "200px";
    outer.style.height = "150px";
    outer.style.overflow = "hidden";
    outer.appendChild(inner);

    document.body.appendChild(outer);
    var w1 = inner.offsetWidth;
    outer.style.overflow = 'scroll';
    var w2 = inner.offsetWidth;
    if (w1 == w2) w2 = outer.clientWidth;

    document.body.removeChild(outer);

    return (w1 - w2);
}

function responsiveResize()
{
	compensante = scrollCompensate();
	if (($(window).width()+scrollCompensate()) <= 767 && responsiveflag == false)
	{
		accordion('enable');
	    accordionFooter('enable');
		responsiveflag = true;
	}
	else if (($(window).width()+scrollCompensate()) >= 768)
	{
		accordion('disable');
		accordionFooter('disable');
	    responsiveflag = false;
	}
	blockHover();
}

function blockHover(status)
{
	var screenLg = $('body').find('.container').width() == 1170;

	if ($('.product_list').is('.grid'))
		if (screenLg)
			$('.product_list .button-container').hide();
		else
			$('.product_list .button-container').show();

	$(document).off('mouseenter').on('mouseenter', '.product_list.grid li.ajax_block_product .product-container', function(e){
		if (screenLg)
		{
			var pcHeight = $(this).parent().outerHeight();
			var pcPHeight = $(this).parent().find('.button-container').outerHeight() + $(this).parent().find('.comments_note').outerHeight() + $(this).parent().find('.functional-buttons').outerHeight();
			$(this).parent().addClass('hovered').css({'height':pcHeight + pcPHeight, 'margin-bottom':pcPHeight * (-1)});
			$(this).find('.button-container').show();
		}
	});

	$(document).off('mouseleave').on('mouseleave', '.product_list.grid li.ajax_block_product .product-container', function(e){
		if (screenLg)
		{
			$(this).parent().removeClass('hovered').css({'height':'auto', 'margin-bottom':'0'});
			$(this).find('.button-container').hide();
		}
	});
}

function quick_view()
{
	$(document).on('click', '.quick-view:visible, .quick-view-mobile:visible', function(e){
		e.preventDefault();
		var url = this.rel;
		var anchor = '';

		if (url.indexOf('#') != -1)
		{
			anchor = url.substring(url.indexOf('#'), url.length);
			url = url.substring(0, url.indexOf('#'));
		}
		if (url.indexOf('?') != -1)
			url += '&';
		else
			url += '?';

		if (!!$.prototype.fancybox)
			$.fancybox({
				'padding':  0,
				'width':    1087,
				'height':   610,
				'type':     'iframe',
				'href':     url + 'content_only=1' + anchor
			});
	});
}

function dropDown()
{
	elementClick = '#header .current';
	elementSlide =  'ul.toogle_content';
	activeClass = 'active';

	$(elementClick).on('click', function(e){
		e.stopPropagation();
		var subUl = $(this).next(elementSlide);
		if(subUl.is(':hidden'))
		{
			subUl.slideDown();
			$(this).addClass(activeClass);
		}
		else
		{
			subUl.slideUp();
			$(this).removeClass(activeClass);
		}
		$(elementClick).not(this).next(elementSlide).slideUp();
		$(elementClick).not(this).removeClass(activeClass);
		e.preventDefault();
	});

	$(elementSlide).on('click', function(e){
		e.stopPropagation();
	});

	$(document).on('click', function(e){
		e.stopPropagation();
		var elementHide = $(elementClick).next(elementSlide);
		$(elementHide).slideUp();
		$(elementClick).removeClass('active');
	});
}

function accordionFooter(status)
{
	if(status == 'enable')
	{
		$('.prefooter-blocks .block_footer h4').on('click', function(){
			$(this).toggleClass('active').parent().find('.toggle-footer').stop().slideToggle('medium');
		})
		$('.prefooter-blocks').addClass('accordion').find('.toggle-footer').slideUp('fast');
	}
	else
	{
		$('.block_footer h4').removeClass('active').off().parent().find('.toggle-footer').removeAttr('style').slideDown('fast');
		$('.prefooter-blocks').removeClass('accordion');
	}
}

function accordion(status)
{
	leftColumnBlocks = $('.sidebar');
	if(status == 'enable')
	{
		var accordion_selector = '.sidebar div.heading_block h4';

		$(accordion_selector).on('click', function(e){
			$(this).toggleClass('active').parent().parent().find('.block_content').stop().slideToggle('medium');
		});
		$('.sidebar').addClass('accordion').find('.block .block_content').slideUp('fast');
		if (typeof(ajaxCart) !== 'undefined')
			ajaxCart.collapse();
	}
	else
	{
		$('.sidebar div.heading_block h4').removeClass('active').off().parent().parent().find('.block_content').removeAttr('style').slideDown('fast');
		$('.sidebar').removeClass('accordion');
	}
}

// added by Prestahome
// Cache
var contentWrapper = $('div.content');
var searchBox = $('#search_block_top i');
var searchBoxInput = $('#search_block_top input[type="submit"]');
var sliderRange = $('#slider-range');
var gridViewContainer = $('#gridview');
var listViewContainer = $('#listview');
var qtyWantedInput = $('#quantity_wanted');
var defaultSlider = $('.slider');
var miniSliderDiv = $('.mini_slider');
var productListContainer = $(".product_list_ph")

function get_grid(){
	gridViewContainer.addClass("active");
	listViewContainer.removeClass("active");
	productListContainer.removeClass("list");
	productListContainer.addClass("grid");
}

function get_list(){
	listViewContainer.addClass("active");
	gridViewContainer.removeClass("active");
	productListContainer.removeClass("grid");
	productListContainer.addClass("list");
}

function hideDD(){
	$('.topbar .select-options').removeClass('active');
}

// switches selected class on buttons
function changeSelectedLink($elem) {
	// remove selected class on previous item
	$elem.parents('.portfolio-filter').find('.btn-primary').removeClass('btn-primary');
	// set selected class on new item
	$elem.addClass('btn-primary');
}

jQuery(document).ready(function($) { 

	$('body.cms div.rte li').wrapInner('<span/>');
	$('.top-sticky').affix({});

	// blur effect when megamenu is active
	$("#ph_megamenu > li").on({
		mouseenter: function(){
			contentWrapper.addClass('blur');
		}, 
		mouseleave: function() {
			contentWrapper.removeClass('blur');
		}
	});

	// show language, currency options
	$('.topbar .select-options').on('click', function(event) {
		$('.topbar .select-options').not(this).removeClass('active');
		if ($(event.target).parent().parent().attr('class') == 'options' ) {
			hideDD();
		} else {
			if($(this).hasClass('active') &&  $(event.target).is( "p" )) {
				hideDD();
			} else {
				$(this).toggleClass('active');
			}
		}
		event.stopPropagation();
	});

	$(document).on('click', function() { hideDD(); });
	$('.topbar .select-options ul').on('click', function() {
		var opt = $(this);
		var text = opt.text();
		$('.topbar .select-options.active p').text(text);
		hideDD();
	});

	// search block
	searchBox.on('click', function() {
		searchBoxInput.trigger('click');
	});

	searchBox.on('mouseenter', function() {
		searchBoxInput.addClass('hover');
	});

	searchBox.on('mouseleave', function() {
		searchBoxInput.removeClass('hover');
	});

	// cart
	$(".shopping_cart").on({
		mouseenter: function(){
			$(this).find('a.cart-contents').addClass('active');
		}, 
		mouseleave: function() {
			$(this).find('a.cart-contents').removeClass('active');
		}
	});

	// categories
	$('#categories_block_left.simple ul li').on('mouseenter', function() {
		var submenuWidth = $(this).parent().width();
		$(this).addClass('active');
		if($('.sidebar').hasClass('right-column')) {
			$(this).hover().find('ul').first().css({'width': submenuWidth, 'right': +submenuWidth}).show();
		} else {
			$(this).hover().find('ul').first().css({'width': submenuWidth, 'right': -submenuWidth}).show();
		}
	});

	$('#categories_block_left.simple ul li').mouseleave(function() {
		$(this).removeClass('active');
		$(this).find('ul').first().css({'right': '0'}).hide();
	});

	// product lists
	if($('.list-style-buttons').length > 0) {
		var default_view = 'grid',
			switcher = $('a.switcher'),
			theid = switcher.attr("id"),
			classNames = switcher.attr('class').split(' ');
		
	    if($.cookie('view') !== 'undefined'){
	        $.cookie('view', default_view, { expires: 7, path: '/' });
	    }

	    if($.cookie('view') == 'list'){ 
			get_list();
	    }

	    if($.cookie('view') == 'grid'){ 
			get_grid();
	    }

	    listViewContainer.on('click', function(e){   
	        $.cookie('view', 'list'); 
	        get_list();
			e.preventDefault();
	    })

	    gridViewContainer.on('click', function(e){ 
	        $.cookie('view', 'grid'); 
	        get_grid();
			e.preventDefault();
	    });
	}

	// tooltips
	if($('.contact-form-box').length == 0) {
  		$("[data-toggle='tooltip']").tooltip();
  	}
	// use select style css
	$('.form-date select, .date-select select').selectBox({
		menuTransition: 'slide',
		menuSpeed: 'fast',
		keepInViewport: false
	});

	// input file
	if($('input[type="file"]').length > 0) {
		$(":file").filestyle({
			buttonName: "button",
			icon: false
		});
	}

	// animations
	if ($().appear) {

        if ($.browser.mobile == true) {
            // disable animation on mobile
            $("body").removeClass("cssAnimate");
        } else {
        	var timer;

            $('.cssAnimate .animated').appear(function () {
                var $this = jQuery(this);

                $this.each(function () {
                    if ($this.data('time') != undefined) {
                        timer = setTimeout(function () {
                            $this.addClass('activate');
                            $this.addClass($this.data('fx'));
                        }, $this.data('time'));
                    } else {
                        $this.addClass('activate');
                        $this.addClass($this.data('fx'));
                    }
                });
            }, {accX: 50, accY: -150});

            clearTimeout(timer);
        }
    }

	// quantity buttons
	$('#quantity_wanted_p a.more').on('click', function(e){
        e.preventDefault();
        fieldName = $(this).attr('field');
        var currentVal = parseInt(qtyWantedInput.val(), 10);
        if (!isNaN(currentVal)) {
            qtyWantedInput.val(currentVal + 1);
        } else {
            qtyWantedInput.val(0);
        }
    });

    $("#quantity_wanted_p a.less").on('click', function(e) {
        e.preventDefault();
        fieldName = $(this).attr('field');
        var currentVal = parseInt(qtyWantedInput.val(), 10);
        if (!isNaN(currentVal) && currentVal > 0) {
            qtyWantedInput.val(currentVal - 1);
        } else {
            qtyWantedInput.val(0);
        }
    });

	// blog
	if (document.documentElement.clientWidth >= 991) {
		$('.recent_posts .post-content').equalHeights();
	}

	 // scroll to top btn
	 $('a.totop').on('click', function (e) {
        $('html, body').animate({scrollTop: '0px'},1500);
        e.preventDefault();
    });

	// start carousels with products, manufacturers etc.
	installCarousels();

	$(window).on('load', function(){
		//installCarouselsTabs();
	}); 
	
	$(window).on('resize', function(){
		// blog
		if (document.documentElement.clientWidth >= 991) {
			$('.recent_posts .post-content').equalHeights();
		}
	}); 
});

function installCarousels() {
	$('.owl-carousel-ph').each(function(){
		if($(this).parents('.tab-pane').length == 0) {
			/* Max items counting */
			var max_items = $(this).data('max-items');
			var md_items = 4;
			var sm_items = 2;
			
			$(this).owlCarousel({
				autoplay: autoplayInfo,
				autoplayTimeout: autoplay_speed,
				autoplayHoverPause:true,
				items: max_items,
				responsive: {
					0: {
						items: 1
					},
					768: {
						items: sm_items
					},
					991: {
						items: md_items
					},
					1199: {
						items: max_items
					}
				},
				pagination : false,
			});
		
			var owl = $(this).data('owlCarousel');
			
			/* Arrow next */
			$(this).parents('.carousel-style').find('.arrow-prev').on('click', function(e){
				owl.prev();
				e.preventDefault();
			});
			
			/* Arrow previous */
			$(this).parents('.carousel-style').find('.arrow-next').on('click', function(e){
				owl.next(); 
				e.preventDefault();
			});
		}
	});		
};

function installCarouselForTab(id_tab) {

	var owl = $('#tab'+id_tab).find('.owl-carousel-ph');
	/* Max items counting */
	var max_items = owl.data('max-items');
	var md_items = max_items - 1;
	var sm_items = max_items - 1;
	
	owl.owlCarousel({
		autoplay: autoplayInfo,
		autoplayTimeout: autoplay_speed,
		autoplayHoverPause:true,
		items: max_items,
		responsive: {
			0: {
				items: 1
			},
			768: {
				items: sm_items
			},
			991: {
				items: md_items
			},
			1199: {
				items: max_items
			}
		},
		pagination : false,
	});

	owl.trigger('refresh.owl.carousel');

	/* Arrow next */
	$('#tab' + id_tab).find('.arrow-prev').on('click', function(e){
		owl.trigger('prev.owl.carousel'); 
		e.preventDefault();
	});
	
	/* Arrow previous */
	$('#tab' + id_tab).find('.arrow-next').on('click', function(e){
		owl.trigger('next.owl.carousel'); 
		e.preventDefault();
	});
};