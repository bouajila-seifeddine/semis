( function( $ ) {


// main text color
	
	wp.customize( 'text_color', function( value ) {
		value.bind( function( newval ) {
			$('body').css('color', newval );
		} );
	} );

//content link 

	wp.customize( 'content_link_color', function( value ) {
		value.bind( function( newval ) {
			$('article p a').css('color', newval );
		} );
	} );

//content link hover

	wp.customize( 'content_link_hover_color', function( value ) {
		value.bind( function( newval ) {
			
			var normalcolor = $('.menu-bar').css('color');
			
			$('article p a').hover(function(){
				if(normalcolor != $(this).css('color')) {
					normalcolor = $(this).css('color');
				}
				$(this).css('color', newval );
			},
			function(){
				$(this).css('color', normalcolor );
			});
			
			
			
		} );
	} );
	
	
//body color

	wp.customize( 'body_color', function( value ) {
		value.bind( function( newval ) {
			$('body').css('background', newval );
		} );
	} );


//top bar color

	wp.customize( 'top_bar_color', function( value ) {
		value.bind( function( newval ) {
			$('#top-bar').css('background', newval );
		} );
	} );
	

//header_color

	wp.customize( 'header_color', function( value ) {
		value.bind( function( newval ) {
			$('#header').css('background', newval );
		} );
	} );
	
//site_title_color

	wp.customize( 'site_title_color', function( value ) {
		value.bind( function( newval ) {
			$('.site-title').css('color', newval );
		} );
	} );

//site_description_color

	wp.customize( 'site_description_color', function( value ) {
		value.bind( function( newval ) {
			$('#header p').css('color', newval );
		} );
	} );

//menu_background

	wp.customize( 'menu_background', function( value ) {
		value.bind( function( newval ) {
			$('.menu-bar').css('background', newval );
		} );
	} );

//menu_text_color

	wp.customize( 'menu_text_color', function( value ) {
		value.bind( function( newval ) {
			$('.menu-bar').css('color', newval );
		} );
	} );
	
//menu_text_hover_color

	wp.customize( 'menu_text_hover_color', function( value ) {
		value.bind( function( newval ) {
			
			var normalcolor = $('.menu-bar').css('color');
			
			$('.header-menu ul li').hover(function(){
				if(normalcolor != $('.menu-bar').css('color')) {
					normalcolor = $('.menu-bar').css('color');
				}
				$(this).css('color', newval );
			},
			function(){
				$(this).css('color', normalcolor );
			});
			
		} );
	} );
	
//menu_hover_color

	wp.customize( 'menu_hover_color', function( value ) {
		value.bind( function( newval ) {
			
			var normalcolor = $('.menu-bar').css('background');
			
			$('.header-menu ul li').hover(function(){
				if(normalcolor != $('.menu-bar').css('background')) {
					normalcolor = $('.menu-bar').css('background');
				}
				$(this).css('background', newval );
			},
			function(){
				$(this).css('background', normalcolor );
			});
			
		} );
	} );
	
//search_button_color

	wp.customize( 'search_button_color', function( value ) {
		value.bind( function( newval ) {
			$('.searchsubmit').css('background', newval );
		} );
	} );
	
//search_button_logo_color

	wp.customize( 'search_button_logo_color', function( value ) {
		value.bind( function( newval ) {
			$('.searchsubmit').css('color', newval );
		} );
	} );	
	
//search_border_color

	wp.customize( 'search_border_color', function( value ) {
		value.bind( function( newval ) {
			$('.searchform').css('border-color', newval );
		} );
	} );
	
//search_input_color
	
	wp.customize( 'search_input_color', function( value ) {
		value.bind( function( newval ) {
			$('.s').css('background', newval );
		} );
	} );
	
//search_text_color

	wp.customize( 'search_text_color', function( value ) {
		value.bind( function( newval ) {
			$('.s').css('color', newval );
		} );
	} );
	
//search_placeholder_color

	wp.customize( 'search_placeholder_color', function( value ) {
		value.bind( function( newval ) {
			$('body').append("<style>.searchform .s::-webkit-input-placeholder { color: " + newval + ";}    .searchform .s:-moz-placeholder { color: " + newval + ";} .searchform .s::-moz-placeholder { color: " + newval + "; }   .searchform .s:-ms-input-placeholder { color: " + newval + "; }</style>");
			
		} );
	} );
	
//widget_head_background_color
	
	wp.customize( 'widget_head_background_color', function( value ) {
		value.bind( function( newval ) {
			$('#sidebar .widget-head').css('border-color', newval );
			$('#sidebar h4').css('background', newval );
		} );
	} );
	
//widget_head_text_color

	wp.customize( 'widget_head_text_color', function( value ) {
		value.bind( function( newval ) {
			$('#sidebar h4').css('color', newval );
		} );
	} );
	
//widget_list_seperator_color

	wp.customize( 'widget_list_seperator_color', function( value ) {
		value.bind( function( newval ) {
			$('#sidebar li').css('border-color', newval );
		} );
	} );
	
//widget_list_marker_color

	wp.customize( 'widget_list_marker_color', function( value ) {
		value.bind( function( newval ) {
			$('body').append("<style>#sidebar li:before { color: " + newval + ";}</style>");
		} );
	} );

//thumb_placeholder_color

	wp.customize( 'thumb_placeholder_color', function( value ) {
		value.bind( function( newval ) {
			$('.img-placeholder').css('background', newval );
		} );
	} );
	
//thumb_placeholder_logo_color

	wp.customize( 'thumb_placeholder_logo_color', function( value ) {
		value.bind( function( newval ) {
			$('body').append("<style>.img-placeholder .icon:before { color: " + newval + ";}</style>");
		} );
	} );
	
//thumb_placeholder_logo_hover_color

	wp.customize( 'thumb_placeholder_logo_hover_color', function( value ) {
		value.bind( function( newval ) {
			$('body').append("<style>.img-placeholder:hover .icon:before { color: " + newval + ";}</style>");
		} );
	} );
	
//post_prev_title_color

	wp.customize( 'post_prev_title_color', function( value ) {
		value.bind( function( newval ) {
			$('.prev-title a').css('color', newval );
		} );
	} );
	
//post_prev_title_hover_color

	wp.customize( 'post_prev_title_hover_color', function( value ) {
		value.bind( function( newval ) {
			
			var normalcolor = $('.prev-title a').css('color');
			
			$('.prev-title a').hover(function(){
				if(normalcolor != $(this).css('color')) {
					normalcolor = $(this).css('color');
				}
				$(this).css('color', newval );
			},
			function(){
				$(this).css('color', normalcolor );
			});

			
		} );
	} );
	
//post_prev_bottom_border_color

	wp.customize( 'post_prev_bottom_border_color', function( value ) {
		value.bind( function( newval ) {
			$('.post-prev').css('border-color', newval );
		} );
	} );		

//pagination_color

	wp.customize( 'pagination_color', function( value ) {
		value.bind( function( newval ) {
			$('.navigation ul li').not(".active").css('background', newval );
		} );
	} );
	
//pagination_text_color

	wp.customize( 'pagination_text_color', function( value ) {
		value.bind( function( newval ) {
			$('.navigation ul li').not(".active").css('color', newval );
		} );
	} );
	
//pagination_active_color
	
	wp.customize( 'pagination_active_color', function( value ) {
		value.bind( function( newval ) {
		
			var normalcolor = $('.navigation ul li').not('.active').css('background');
			
			$('.navigation ul li').not('.active').hover(function(){
				if(normalcolor != $(this).css('background')) {
					normalcolor = $(this).css('background');
				}
				$(this).css('background', newval );
			},
			function(){
				$(this).css('background', normalcolor );
			});

			
			$('.navigation ul li.active').css('background', newval );
		} );
	} );

//pagination_active_text_color

	wp.customize( 'pagination_active_text_color', function( value ) {
		value.bind( function( newval ) {
		
			var normalcolor = $('.navigation ul li').not('.active').css('color');
			
			$('.navigation ul li').not('.active').hover(function(){
				if(normalcolor != $(this).css('color')) {
					normalcolor = $(this).css('color');
				}
				$(this).css('color', newval );
			},
			function(){
				$(this).css('color', normalcolor );
			});

			
			$('.navigation ul li.active').css('color', newval );
		} );
	} );	
	
//page_title_color
	
	wp.customize( 'page_title_color', function( value ) {
		value.bind( function( newval ) {
			$('#title-container h1').css('color', newval );
		} );
	} );	
	
//page_title_underline_color	
	
	wp.customize( 'page_title_underline_color', function( value ) {
		value.bind( function( newval ) {
			$('#title-container').css('border-color', newval );
		} );
	} );	
	
//headings_color	
	
	wp.customize( 'headings_color', function( value ) {
		value.bind( function( newval ) {
			$('h1, h2, h3, h4, h5, h6').not('.site-title, #title-container h1, .widget-head h4').css('color', newval );
		} );
	} );
	
//footer_top_color

	wp.customize( 'footer_top_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-top').css('background', newval );
		} );
	} );
	
//footer_top_text_color

	wp.customize( 'footer_top_text_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-top').css('color', newval );
		} );
	} );
	
//footer_widget_head_color

	wp.customize( 'footer_widget_head_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-top .widget-head').css('border-color', newval );
			$('#footer-top .widget-head h4').css('background', newval );
		} );
	} );
	
//footer_widget_head_text_color

	wp.customize( 'footer_widget_head_text_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-top .widget-head h4').css('color', newval );
		} );
	} );

//footer_list_seperator_color

	wp.customize( 'footer_list_seperator_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-top .widget li').css('border-color', newval );
		} );
	} );
	
//footer_bottom_color

	wp.customize( 'footer_bottom_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-bottom').css('background', newval );
		} );
	} );
	
//footer_bottom_text_color

	wp.customize( 'footer_bottom_text_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-bottom').not('a').css('color', newval );
		} );
	} );
	
//footer_bottom_link_color

	wp.customize( 'footer_bottom_link_color', function( value ) {
		value.bind( function( newval ) {
			$('#footer-bottom a').css('color', newval );
		} );
	} );
	
	
})( jQuery );