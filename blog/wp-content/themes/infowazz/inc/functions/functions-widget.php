<?php

function infowazz_sidebar_widget_init() {

$mt_columns = get_option("infowazz_theme_options");

/* --------------------------------------------------------------------------------------- Page Widget Area 1 */

	register_sidebar( array(
		'name' => esc_html__( 'Default Sidebar', 'infowazz'),
		'id' => 'sidebar-widget-area-1',
		'description' => esc_html__( 'The page sidebar widget area 1', 'infowazz' ),
		'before_widget' => '<div class="widget">',
		'after_widget' => '<div class="clear"></div></div>',
				'before_title' => '<h2 class="heading"><span>',
				'after_title' => '</span></h4>',
	) );


/* --------------------------------------------------------------------------------------- Blog Widget Area */

	register_sidebar( array(
		'name' => esc_html__( 'Blog/Category Sidebar', 'infowazz'),
		'id' => 'sidebar-blog-widget-area',
		'description' => esc_html__( 'The blog sidebar widget area' , 'infowazz'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '<div class="clear"></div></div>',
				'before_title' => '<h2 class="heading"><span>',
				'after_title' => '</span></h4>',
	) );



/* --------------------------------------------------------------------------------------- Single Widget Area */

	register_sidebar( array(
		'name' => esc_html__( 'Post Sidebar', 'infowazz'),
		'id' => 'sidebar-single-widget-area',
		'description' => esc_html__( 'The single page sidebar widget area' , 'infowazz'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '<div class="clear"></div></div>',
				'before_title' => '<h2 class="heading"><span>',
				'after_title' => '</span></h4>',
	) );
	/* --------------------------------------------------------------------------------------- Single Buttom Widget Area */

		register_sidebar( array(
			'name' => esc_html__( 'Post Bottom (Before Comments)', 'infowazz'),
			'id' => 'sidebar-single-bottom-widget-area-before',
			'description' => esc_html__( 'The post bottom widget area' , 'infowazz'),
			'before_widget' => '<div class="widget">',
			'after_widget' => '<div class="clear"></div></div>',
					'before_title' => '<h2 class="heading"><span>',
					'after_title' => '</span></h4>',
		) );

	/* --------------------------------------------------------------------------------------- Single Buttom Widget Area */

			register_sidebar( array(
				'name' => esc_html__( 'Post Bottom (After Comments)', 'infowazz'),
				'id' => 'sidebar-single-bottom-widget-area-after',
				'description' => esc_html__( 'The post bottom widget area' , 'infowazz'),
				'before_widget' => '<div class="widget">',
				'after_widget' => '<div class="clear"></div></div>',
						'before_title' => '<h2 class="heading"><span>',
						'after_title' => '</span></h4>',
			) );


/* --------------------------------------------------------------------------------------- Search Widget Area */


	register_sidebar( array(
		'name' => esc_html__( 'Search Page Sidebar', 'infowazz'),
		'id' => 'sidebar-search-widget-area',
		'description' => esc_html__( 'The search page sidebar widget area' , 'infowazz'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '<div class="clear"></div></div>',
				'before_title' => '<h2 class="heading"><span>',
				'after_title' => '</span></h4>',
	) );

	/* --------------------------------------------------------------------------------------- Author widgets */

	register_sidebar( array(
		'name' => esc_html__( 'Author Page Sidebar', 'infowazz'),
		'id' => 'sidebar-author-widget-area',
		'description' => esc_html__( 'The Author page sidebar widget area' , 'infowazz'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '<div class="clear"></div></div>',
				'before_title' => '<h2 class="heading"><span>',
				'after_title' => '</span></h4>',
	) );


}

add_action( 'widgets_init', 'infowazz_sidebar_widget_init' );
?>
