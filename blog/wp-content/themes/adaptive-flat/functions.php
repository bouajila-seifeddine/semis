<?php

//conditional to only include customizer code when needed
if (is_admin()) {
	include('lib/customizer.php');//Include code for customizer
}

include('lib/stylecolors.php');


if ( ! isset( $content_width ) ) { //set content width
	$content_width = 600;
}

add_theme_support( 'automatic-feed-links' );
	

function adaptive_flat_enqueue_sripts() { //enque scripts like css
	wp_enqueue_script( 'script-js', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0.1', true );

	if ( is_singular() ) { //if on single page load comment script
		wp_enqueue_script( "comment-reply" );
	} 
		
	
	wp_enqueue_style( 'style', get_stylesheet_uri(), array('dashicons'), '1.0' );
	
	
	//load google font
	wp_register_style( 'googleFonts', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,300,600' );
    wp_enqueue_style( 'googleFonts' );
	
}

add_action( 'wp_enqueue_scripts', 'adaptive_flat_enqueue_sripts' );


/**
*Filter for stylesheet uri. Sets it to uploaded stylesheet
*
*/
function adaptive_flat_register_my_menus() {
  register_nav_menus(
    array(
      'header-menu' => __( 'Header Menu', 'giga_games' ),
    )
  );
}
add_action( 'init', 'adaptive_flat_register_my_menus' );



// Show posts of 'game' and 'post' post type
add_action( 'pre_get_posts', 'adaptive_flat_add_my_post_types_to_query' );

function adaptive_flat_add_my_post_types_to_query( $query ) {
	if ($query->is_home() && $query->is_main_query() )
	{
	
		if( post_type_exists( 'giga_game' ) ) {
			$query->set( 'post_type', array( 'giga_game', 'post' ) ); //Add post type giga_game to main query for game plugin support
		}
		/* Get all sticky posts */
		$sticky = get_option( 'sticky_posts' );

		$numberstickies = adaptive_flat_sticky_counter();	//get number of stickies (max 4)
			
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
			
			if( $paged != 1 )
			{
				$numberstickies = 0;
			}
		
	/* Sort the stickies with the newest ones at the top */
		rsort( $sticky );

	/* Get the 4 newest stickies (change 2 for a different number) */
		$sticky = array_slice( $sticky, 4 );

	/* Query sticky posts */
		$query->set( 'post__not_in' , $sticky );
					
		
	}
		
	return $query;
}

/**
 * Sticky counter function
 *
 */

function adaptive_flat_sticky_counter() {
	$sticky = get_option( 'sticky_posts' );

		$numberstickies = count( $sticky );
		
			if( $numberstickies > 4 ) {
				$numberstickies = 4;
			}
			
			
			
	return $numberstickies;
}


//thumbail resize
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 480, 320, true );     
}
//add image size
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'prev-2', 200, 125, true );
}


//add link to post thumbnail

add_filter( 'post_thumbnail_html', 'adaptive_flat_post_image_html', 10, 3 );

function adaptive_flat_post_image_html( $html, $post_id, $post_image_id ) {

  $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $html . '</a>';
  return $html;

}


/**
 * Register our sidebars and widgetized areas.
 *
 */
function adaptive_flat_register_sidebars() {

	register_sidebar( array( //sidebar on home page
		'name' => 'Home Sidebar',
		'id' => 'home_right_1',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-head"><h4>',
		'after_title' => '</h4></div>',
	) );
	
	register_sidebar( array( //sidebar next to single game
		'name' => 'Single Game', 
		'id' => 'single_game_1',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-head"><h4>',
		'after_title' => '</h4></div>',
	) );
	register_sidebar( array( //sidebar next to pages and posts
		'name' => 'General Sidebar',
		'id' => 'general_1',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-head"><h4>',
		'after_title' => '</h4></div>',
	) );
	
	
	register_sidebar( array( //footer1
		'name' => 'Footer 1',
		'id' => 'footer_1',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-head"><h4>',
		'after_title' => '</h4></div>',
	) );
	
	register_sidebar( array( //footer2
		'name' => 'Footer 2',
		'id' => 'footer_2',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-head"><h4>',
		'after_title' => '</h4></div>',
	) );
	
	register_sidebar( array( //footer3
		'name' => 'Footer 3',
		'id' => 'footer_3',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-head"><h4>',
		'after_title' => '</h4></div>',
	) );
	
	register_sidebar( array( //footer4
		'name' => 'Footer 4',
		'id' => 'footer_4',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-head"><h4>',
		'after_title' => '</h4></div>',
	) );
	

	
}
add_action( 'widgets_init', 'adaptive_flat_register_sidebars' );


function adaptive_flat_numeric_posts_nav() {

	if( is_singular() )
		return;

	global $wp_query;

	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );
	//$max = intval(($wp_query->found_posts + adaptive_flat_sticky_counter()) / get_option('posts_per_page '));
	
	
	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="navigation"><ul>' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link( get_theme_mod( 'pag_prev_text', '&laquo; Previous' ) ) );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>…</li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", get_next_posts_link( get_theme_mod( 'pag_next_text', 'Next &raquo;' ) ) );

	echo '</ul></div>' . "\n";

}

/**
* Code for displaying post meta
*
*/
add_action('adaptive_flat_post_meta', 'adaptive_flat_display_post_meta');

function adaptive_flat_display_post_meta() {
	the_tags('<div class="tag-icon"></div>');?>
	<div class="category-icon"></div><?php
	the_category(', ');
}






/**
* Filter the default title to be seo friendly but also compatible with seo plugins
*
*/
add_filter( 'wp_title', 'adaptive_flat_title_filter', 10, 2 );

function adaptive_flat_title_filter($title, $seperator)
{
	if(is_front_page())
	{
		
		$result = get_bloginfo( 'title' ) .  ' - ' . get_bloginfo( 'description' );
		
		
	}
	else
	{
		$result = get_the_title() . ' - ' . get_bloginfo( 'title' );
	}
	
	return $result;
		
}

/**
* REL next prev for optimal seo :)
*
*/

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
add_action ( 'wp_head', 'adaptive_flat_rel_next_prev' );

function adaptive_flat_rel_next_prev() { 
  global $paged;
  if ( get_previous_posts_link() ) { ?>
  <link rel="prev" href="<?php echo get_pagenum_link( $paged - 1 ); ?>">
  <?php
  }
  if ( get_next_posts_link() ) { ?>
  <link rel="next" href="<?php echo get_pagenum_link( $paged + 1 ); ?>">
  <?php
  }
}

/**
* content style function 
*
*/

function adaptive_flat_content_class() {
	
			if( is_archive() || is_front_page() || is_search() ) {	
				$style = get_theme_mod( 'layout_archive', 'cs' );
			}
			else {
				$style = get_theme_mod( 'layout_posts', 'cs' );
			}
		
			
			
			$class;
			
			if( $style == 'cs' ) {
				$class = 'right-sidebar';
			}
			
			else if( $style == 'c' ) {
				$class = 'full-width';
			}
			else {
				$class = 'left-sidebar';
			}
			
			echo( $class );
}

/**
* Special excerpt function
*
*/
function adaptive_flat_excerpt() {
		global $post;
		
		if( $post->post_content != "" ) {							  		 
			the_excerpt();
		}
}

function adaptive_flat_excerpt_more( $more ) {
	return ' <a href="'. get_permalink( get_the_ID() ) . '">' . get_theme_mod('readmore_text', '...Read more') . '</a>';
}
add_filter( 'excerpt_more', 'adaptive_flat_excerpt_more' );


add_filter('excerpt_length', 'adaptive_flat_excerpt_length', 999);

function adaptive_flat_excerpt_length( $length ) {
	return get_theme_mod( 'post_excerpt_length', '30' );
}
add_filter( 'excerpt_length', 'adaptive_flat_excerpt_length', 999 );

/**
* Special thumbnail function
*
*/

function adaptive_flat_thumbnail( $width, $height ) {
	
	global $post;

	if ( '' == has_post_thumbnail() && get_theme_mod( 'show_placeholder', '1' ) ) { // check if the post has a Post Thumbnail assigned to it.
										
			?>
			<a href="<?php the_permalink() ?>"><div class="img-placeholder"><div class="icon"></div></div></a><?php
			
		}
							

}





?>