<?php
function infowazz_customize_posts($wp_customize){


  Kirki::add_field( 'infowazz_theme_options[post_carousel]', array(
	'type'        => 'switch',
	'settings'    => 'infowazz_theme_options[post_carousel]',
	'label'       => esc_html__( 'Post Carousel', 'infowazz' ),
	'section'     => 'posts_default_settings',
	'default'     => 'yes',
  'option_type' => 'option',
	'priority'    => 10,
	'choices'     => array(
		'yes'  => esc_attr__( 'Enable', 'infowazz' ),
		'no' => esc_attr__( 'Disable', 'infowazz' ),
	),
  ) );

  Kirki::add_field( 'infowazz_theme_options[post_sidebar]', array(
  	'type'        => 'radio-image',
  	'settings'    => 'infowazz_theme_options[post_sidebar]',
  	'label'       => esc_html__( 'Sidebar Position', 'infowazz' ),
  	'section'     => 'posts_default_settings',
  	'default'     => 'left',
    'option_type' => 'option',
  	'priority'    => 10,
  	'choices'     => array(
    		'left'   => get_template_directory_uri() . '/inc/img/sidebar-left.png',
    		'right' => get_template_directory_uri() . '/inc/img/sidebar-right.png',
  	   ),
  ));

  Kirki::add_field( 'infowazz_theme_options[post_style]', array(
  	'type'        => 'radio-image',
  	'settings'    => 'infowazz_theme_options[post_style]',
  	'label'       => esc_html__( 'Post Style', 'infowazz' ),
  	'section'     => 'posts_default_settings',
  	'default'     => '1',
    'option_type' => 'option',
  	'priority'    => 10,
  	'choices'     => array(
    		'1'   => get_template_directory_uri() . '/inc/img/post_style_1.png',
    		'2' => get_template_directory_uri() . '/inc/img/post_style_2.png',
        '3'   => get_template_directory_uri() . '/inc/img/post_style_3.png',
    		'4' => get_template_directory_uri() . '/inc/img/post_style_4.png',
        '5'   => get_template_directory_uri() . '/inc/img/post_style_5.png',
    		'6' => get_template_directory_uri() . '/inc/img/post_style_6.png',
        '7' => get_template_directory_uri() . '/inc/img/post_style_7.png',
        '8' => get_template_directory_uri() . '/inc/img/post_style_8.png',
  	   ),
  ));

}

add_action('customize_register', 'infowazz_customize_posts');
?>
