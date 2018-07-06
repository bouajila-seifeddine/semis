<?php
/**
* Code for theme customizer
*
*/

add_action( 'customize_register', 'adaptive_flat_customize_register' );

function adaptive_flat_customize_register( $wp_customize ) {

//text color

   $wp_customize->add_setting( 'text_color' , array(
    'default'     => '#323a46',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
	'label'        => __( 'Main Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'text_color',
	'priority' => 10, 
	) ) );
	
	
//content link color

   $wp_customize->add_setting( 'content_link_color' , array(
    'default'     => '#f37934',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_link_color', array(
	'label'        => __( 'Content Link', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'content_link_color',
	'priority' => 20, 
	) ) );
	
	
//content link hover color

   $wp_customize->add_setting( 'content_link_hover_color' , array(
    'default'     => '#d65a2c',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_link_hover_color', array(
	'label'        => __( 'Content Link Hover', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'content_link_hover_color',
	'priority' => 30, 
	) ) );

//body color

   $wp_customize->add_setting( 'body_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_color', array(
	'label'        => __( 'Body', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'body_color',
	'priority' => 40, 
	) ) );
	
//top bar color

	$wp_customize->add_setting( 'top_bar_color' , array(
    'default'     => '#1fbca7',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_color', array(
	'label'        => __( 'Top Bar', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'top_bar_color',
	'priority' => 50, 
	) ) );
	
//header color

	$wp_customize->add_setting( 'header_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_color', array(
	'label'        => __( 'Header', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'header_color',
	'priority' => 60, 
	) ) );
	
	

//site title color

	$wp_customize->add_setting( 'site_title_color' , array(
    'default'     => '#f37934',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_title_color', array(
	'label'        => __( 'Site Title', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'site_title_color',
	'priority' => 70, 
	) ) );
	
//site description color

	$wp_customize->add_setting( 'site_description_color' , array(
    'default'     => '#323a46',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_description_color', array(
	'label'        => __( 'Site Description', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'site_description_color',
	'priority' => 80, 
	) ) );
	
//menu bar background

	$wp_customize->add_setting( 'menu_background' , array(
    'default'     => '#323a46',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_background', array(
	'label'        => __( 'Menu Background', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'menu_background',
	'priority' => 90, 
	) ) );
	
//menu text color
	$wp_customize->add_setting( 'menu_text_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_text_color', array(
	'label'        => __( 'Menu Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'menu_text_color',
	'priority' => 100, 
	) ) );
	
//menu text hover color
	$wp_customize->add_setting( 'menu_text_hover_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_text_hover_color', array(
	'label'        => __( 'Menu Text Hover', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'menu_text_hover_color',
	'priority' => 110, 
	) ) );

//menu hover color
	$wp_customize->add_setting( 'menu_hover_color' , array(
    'default'     => '#1fbca7',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hover_color', array(
	'label'        => __( 'Menu Hover', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'menu_hover_color',
	'priority' => 120, 
	) ) );
	
//search button color
	$wp_customize->add_setting( 'search_button_color' , array(
    'default'     => '#f37934',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_button_color', array(
	'label'        => __( 'Search Button', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'search_button_color',
	'priority' => 130, 
	) ) );
	
//search button logo color
	$wp_customize->add_setting( 'search_button_logo_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_button_logo_color', array(
	'label'        => __( 'Search Button Logo', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'search_button_logo_color',
	'priority' => 140, 
	) ) );


//search border color
	$wp_customize->add_setting( 'search_border_color' , array(
    'default'     => '#EEEEEE',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_border_color', array(
	'label'        => __( 'Search Border', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'search_border_color',
	'priority' => 150, 
	) ) );
	
	
//search input color
	$wp_customize->add_setting( 'search_input_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_input_color', array(
	'label'        => __( 'Search Input', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'search_input_color',
	'priority' => 160, 
	) ) );
	
//search text color
	$wp_customize->add_setting( 'search_text_color' , array(
    'default'     => '#000000',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
    
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_text_color', array(
	'label'        => __( 'Search Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'search_text_color',
	'priority' => 170, 
	) ) );
	
	
//search placeholder color
	$wp_customize->add_setting( 'search_placeholder_color' , array(
    'default'     => '#c6c6c6',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_placeholder_color', array(
	'label'        => __( 'Search Placeholder', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'search_placeholder_color',
	'priority' => 180, 
	) ) );
	
	
//widget head background color
	$wp_customize->add_setting( 'widget_head_background_color' , array(
    'default'     => '#f37934',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widget_head_background_color', array(
	'label'        => __( 'Widget Head Background', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'widget_head_background_color',
	'priority' => 190, 
	) ) );
	
//widget head text color
	$wp_customize->add_setting( 'widget_head_text_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widget_head_text_color', array(
	'label'        => __( 'Widget Head Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'widget_head_text_color',
	'priority' => 200, 
	) ) );

//widget list seperator
	$wp_customize->add_setting( 'widget_list_seperator_color' , array(
    'default'     => '#ECECEC',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widget_list_seperator_color', array(
	'label'        => __( 'Widget List Seperator', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'widget_list_seperator_color',
	'priority' => 210, 
	) ) );
	
//widget list marker
	$wp_customize->add_setting( 'widget_list_marker_color' , array(
    'default'     => '#323a46',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widget_list_marker_color', array(
	'label'        => __( 'Widget List Marker', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'widget_list_marker_color',
	'priority' => 220, 
	) ) );



//image placeholder
	$wp_customize->add_setting( 'thumb_placeholder_color' , array(
    'default'     => '#1fbca7',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'thumb_placeholder_color', array(
	'label'        => __( 'Thumb Placeholder', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'thumb_placeholder_color',
	'priority' => 230, 
	) ) );
	
//image placeholder logo
	$wp_customize->add_setting( 'thumb_placeholder_logo_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'thumb_placeholder_logo_color', array(
	'label'        => __( 'Thumb Placeholder Logo', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'thumb_placeholder_logo_color',
	'priority' => 240, 
	) ) );
	
//image placeholder logo hover
	$wp_customize->add_setting( 'thumb_placeholder_logo_hover_color' , array(
    'default'     => '#f4f4f4',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'thumb_placeholder_logo_hover_color', array(
	'label'        => __( 'Thumb Placeholder Logo Hover', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'thumb_placeholder_logo_hover_color',
	'priority' => 250, 
	) ) );
	
	
//post prev title
	$wp_customize->add_setting( 'post_prev_title_color' , array(
    'default'     => '#6b6b6b',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_prev_title_color', array(
	'label'        => __( 'Post Preview Title', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'post_prev_title_color',
	'priority' => 260, 
	) ) );
	
//post prev title hover
	$wp_customize->add_setting( 'post_prev_title_hover_color' , array(
    'default'     => '#f37934',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_prev_title_hover_color', array(
	'label'        => __( 'Post Preview Title Hover', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'post_prev_title_hover_color',
	'priority' => 270, 
	) ) );
	
//post prev bottom border
	$wp_customize->add_setting( 'post_prev_bottom_border_color' , array(
    'default'     => '#EEEEEE',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_prev_bottom_border_color', array(
	'label'        => __( 'Post Preview Bottom Border', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'post_prev_bottom_border_color',
	'priority' => 280, 
	) ) );
	
//pagination color
	$wp_customize->add_setting( 'pagination_color' , array(
    'default'     => '#1fbca7',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pagination_color', array(
	'label'        => __( 'Pagination Block', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'pagination_color',
	'priority' => 290, 
	) ) );
	
//pagination text color
	$wp_customize->add_setting( 'pagination_text_color' , array(
    'default'     => '#fff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pagination_text_color', array(
	'label'        => __( 'Pagination Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'pagination_text_color',
	'priority' => 300, 
	) ) );
	
	
//pagination active color
	$wp_customize->add_setting( 'pagination_active_color' , array(
    'default'     => '#f37934',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pagination_active_color', array(
	'label'        => __( 'Pagination Block Active', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'pagination_active_color',
	'priority' => 310, 
	) ) );
	
//pagination active text color
	$wp_customize->add_setting( 'pagination_active_text_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pagination_active_text_color', array(
	'label'        => __( 'Pagination Text Active', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'pagination_active_text_color',
	'priority' => 320, 
	) ) );


//page title color
	$wp_customize->add_setting( 'page_title_color' , array(
    'default'     => '#707070',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_title_color', array(
	'label'        => __( 'Page Title Color', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'page_title_color',
	'priority' => 330, 
	) ) );
	
//page title underline color
	$wp_customize->add_setting( 'page_title_underline_color' , array(
    'default'     => '#f37934',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_title_underline_color', array(
	'label'        => __( 'Page Title Underline Color', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'page_title_underline_color',
	'priority' => 340, 
	) ) );	
	
//headings color
	$wp_customize->add_setting( 'headings_color' , array(
    'default'     => '#707070',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'headings_color', array(
	'label'        => __( 'Headings (h1, h2, h3, ...)', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'headings_color',
	'priority' => 345, 
	) ) );
	
//footer top
	$wp_customize->add_setting( 'footer_top_color' , array(
    'default'     => '#1fbca7',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_top_color', array(
	'label'        => __( 'Footer Top', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_top_color',
	'priority' => 350, 
	) ) );

//footer top text
	$wp_customize->add_setting( 'footer_top_text_color' , array(
    'default'     => '#ffffff',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_top_text_color', array(
	'label'        => __( 'Footer Top Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_top_text_color',
	'priority' => 360, 
	) ) );
	
	
//footer widget head
	$wp_customize->add_setting( 'footer_widget_head_color' , array(
    'default'     => '#1aad99',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_widget_head_color', array(
	'label'        => __( 'Footer Widget Head', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_widget_head_color',
	'priority' => 370, 
	) ) );
	
//footer widget head text
	$wp_customize->add_setting( 'footer_widget_head_text_color' , array(
    'default'     => '#ffffff',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'   => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_widget_head_text_color', array(
	'label'        => __( 'Footer Widget Head Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_widget_head_text_color',
	'priority' => 380, 
	) ) );
	

//footer widget list seperator
	$wp_customize->add_setting( 'footer_list_seperator_color' , array(
    'default'     => '#ececec',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_list_seperator_color', array(
	'label'        => __( 'Footer List Seperator', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_list_seperator_color',
	'priority' => 390, 
	) ) );
	
	
//footer bottom
	$wp_customize->add_setting( 'footer_bottom_color' , array(
    'default'     => '#323a46',
    'transport'   => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bottom_color', array(
	'label'        => __( 'Footer Bottom', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_bottom_color',
	'priority' => 400, 
	) ) );
	
//footer bottom text
	$wp_customize->add_setting( 'footer_bottom_text_color' , array(
    'default'     => '#ffffff',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'   => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bottom_text_color', array(
	'label'        => __( 'Footer Bottom Text', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_bottom_text_color',
	'priority' => 410, 
	) ) );

//footer bottom link
	$wp_customize->add_setting( 'footer_bottom_link_color' , array(
    'default'     => '#ffffff',
    'sanitize_callback' => 'sanitize_hex_color',
    'transport'   => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bottom_link_color', array(
	'label'        => __( 'Footer Bottom Link', 'giga_flat' ),
	'section'    => 'colors',
	'settings'   => 'footer_bottom_link_color',
	'priority' => 420, 
	) ) );
	
	
//header image

	$wp_customize->add_section( 'header_logo' , array(
    'title'      => __( 'Header Logo', 'giga_flat' ),
    'priority'   => 2,
	) );

	$wp_customize->add_setting( 'header_image' , array(
    'default'     => '',
    'transport'   => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_image', array(
	'label'        => __( 'Image Upload', 'giga_flat' ),
	'section'    => 'header_logo',
	'settings'   => 'header_image',
	'priority' => 2, 
	) ) );
	
/*
* Display options
*
*
*/
	$wp_customize->add_section( 'display_options' , array(
    'title'      => __( 'Display Options', 'giga_flat' ),
    'priority'   => 30,
	) );

//readmore text	
	$wp_customize->add_setting( 'readmore_text' , array(
    'default'     => '...Read more',
    'transport'   => 'refresh',
    'sanitize_callback' => 'esc_html',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'readmore_text', array(
	'label'        => __( 'Read More Text', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'readmore_text',
	'priority' => 2, 
	) ) );	
	
//posts per line
	$wp_customize->add_setting( 'posts_per_line' , array(
    'default'     => '2',
    'sanitize_callback' => 'absint',
    'transport'   => 'refresh',
	) );

	$wp_customize->add_control(
	    new WP_Customize_Control(
	        $wp_customize,
	        'posts_per_line',
	        array(
	            'label'          => __( 'Posts Per Line', 'giga_flat' ),
	            'section'        => 'display_options',
	            'settings'       => 'posts_per_line',
	            'type'           => 'radio',
	            'choices'        => array(
	                '1'   => __( '1', 'giga_flat' ),
	                '2'  => __( '2', 'giga_flat' )
	            )
	        )
	    )
	);
	
//show placeholder
	$wp_customize->add_setting( 'show_placeholder' , array(
    'default'     => '1',
    'transport'   => 'refresh',
    'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'show_placeholder', array(
	'label'        => __( 'Show Image Placeholder', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'show_placeholder',
	'type'		=> 'checkbox',
	'priority' => 2, 
	) ) );	
	
//layout archive
	$wp_customize->add_setting( 'layout_archive' , array(
    'default'     => 'cs',
    'transport'   => 'refresh',
	'sanitize_callback' => 'adaptive_flat_sanitize_layout',
	) );
	
	$wp_customize->add_control(
	    new WP_Customize_Control(
	        $wp_customize,
	        'layout_archive',
	        array(
	            'label'          => __( 'Layout Archive and Home', 'giga_flat' ),
	            'section'        => 'display_options',
	            'settings'       => 'layout_archive',
	            'type'           => 'select',
	            'choices'        => array(
	                'cs'  => __( 'Right Sidebar', 'giga_flat' ),
	                'sc'  => __( 'Left Sidebar', 'giga_flat' ),
	                'c'   => __( 'No Sidebar', 'giga_flat' ),
	            )
	        )
	    )
	);
	
//layout posts
	$wp_customize->add_setting( 'layout_posts' , array(
    'default'     => 'cs',
    'transport'   => 'refresh',
    'sanitize_callback' => 'adaptive_flat_sanitize_layout',
	) );
	
	$wp_customize->add_control(
	    new WP_Customize_Control(
	        $wp_customize,
	        'layout_posts',
	        array(
	            'label'          => __( 'Layout Posts', 'giga_flat' ),
	            'section'        => 'display_options',
	            'settings'       => 'layout_posts',
	            'type'           => 'select',
	            'choices'        => array(
	                'cs'  => __( 'Right Sidebar', 'giga_flat' ),
	                'sc'  => __( 'Left Sidebar', 'giga_flat' ),
	                'c'   => __( 'No Sidebar', 'giga_flat' ),
	            )
	        )
	    )
	);
	
//post excerpt length
	$wp_customize->add_setting( 'post_excerpt_length' , array(
    'default'     => '30',
    'transport'   => 'refresh',
    'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'post_excerpt_length', array(
	'label'        => __( 'Post Excerpt Words', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'post_excerpt_length',
	'priority' => 2, 
	) ) );
	
	
//search result text
	$wp_customize->add_setting( 'search_text' , array(
    'default'     => 'Search results for:',
    'transport'   => 'refresh',
    'sanitize_callback' => 'esc_html',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'search_text', array(
	'label'        => __( 'Search Results Text', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'search_text',
	'priority' => 2, 
	) ) );
	
	
//search placeholder text
	$wp_customize->add_setting( 'search_placeholder_text' , array(
    'default'     => 'Search',
    'transport'   => 'refresh',
    'sanitize_callback' => 'esc_html',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'search_placeholder_text', array(
	'label'        => __( 'Search Placeholder Text', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'search_placeholder_text',
	'priority' => 2, 
	) ) );
	
//pagination next text	
	$wp_customize->add_setting( 'pag_next_text' , array(
    'default'     => 'Next &raquo;',
    'transport'   => 'refresh',
    'sanitize_callback' => 'esc_html',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'pag_next_text', array(
	'label'        => __( 'Pagination Next Text', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'pag_next_text',
	'priority' => 2, 
	) ) );
	
	
//pagination Prev Text	
	$wp_customize->add_setting( 'pag_prev_text' , array(
    'default'     => '&laquo; Previous',
    'transport'   => 'refresh',
    'sanitize_callback' => 'esc_html',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'pag_prev_text', array(
	'label'        => __( 'Pagination Prev Text', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'pag_prev_text',
	'priority' => 2, 
	) ) );
	
//footer text	
	$wp_customize->add_setting( 'footer_text' , array(
    'default'     => '<p>Theme created by <a href="http://mickdegraaf.nl" rel="nofollow">Mick de Graaf</a></p>',
    'transport'   => 'refresh',
    'sanitize_callback' => 'wp_kses_data', //filter footer text just like a footer
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'footer_text', array(
	'label'        => __( 'Footer Bottom Text', 'giga_flat' ),
	'section'    => 'display_options',
	'settings'   => 'footer_text',
	'priority' => 2, 
	) ) );
	

/*
* custom css
*
*
*/
	$wp_customize->add_section( 'css' , array(
    'title'      => __( 'Custom CSS', 'giga_flat' ),
    'priority'   => 30,
	) );
	
	
	//custom css	
	$wp_customize->add_setting( 'custom_css' , array(
    'default'     => '',
    'transport'   => 'refresh',
    'capability' => 'edit_themes',//requires same level acces as editing theme files
	) );
	
	$wp_customize->add_control( new Adaptive_flat_Customize_Textarea_Control( $wp_customize, 'custom_css', array(
	'label'        => __( 'Custom CSS', 'giga_flat' ),
	'section'    => 'css',
	'settings'   => 'custom_css',
	'priority' => 2, 
	) ) );	


}

/*
* Sanitize layout
*
* Make sure the layout is set to a valid value
*/

function adaptive_flat_sanitize_layout($layout) {
	if($layout == 'cs' || $layout == 'sc' || $layout == 'c') {
		return $layout;
	}
	return "cs";
}


if ( class_exists( 'WP_Customize_Control' ) ) {
     class Adaptive_flat_Customize_Textarea_Control extends WP_Customize_Control {
         
         
    public function __construct( $manager, $id, $args = array() ) {

		$this->statuses = array( '' => __( 'Default', 'giga_flat') );
		parent::__construct( $manager, $id, $args );
	}
         
        public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea class="large-text" cols="20" rows="5" <?php $this->link(); ?>>
				<?php echo esc_textarea( $this->value() ); ?>
			</textarea>
		</label>
		<?php
		}
     }
}


?>