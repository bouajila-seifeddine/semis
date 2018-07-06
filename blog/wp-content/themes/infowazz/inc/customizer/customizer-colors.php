<?php
function infowazz_customize_colors($wp_customize){

  $wp_customize->add_panel( 'colors_settings', array(
    'priority'       => 300,
    'capability'     => 'edit_theme_options',
    'title'    	=> esc_html__('Style', 'infowazz'),
  ));

  $wp_customize->add_section('general_style_settings', array(
    'title'    	=> esc_html__('General', 'infowazz'),
    'panel'  => 'colors_settings'
  ));

  $wp_customize->add_section('background_settings', array(
    'title'    	=> esc_html__('Background', 'infowazz'),
    'panel'  => 'colors_settings'
  ));

  Kirki::add_field( 'infowazz_theme_options[background_image]', array(
    'type'        => 'image',
    'settings'    => 'infowazz_theme_options[background_image]',
    'label'       => esc_html__( 'Background Image', 'infowazz' ),
    'section'     => 'background_settings',
    'default'     => '',
    'option_type' => 'option',
    'priority'    => 10,
  ) );

  Kirki::add_field( 'infowazz_theme_options[background_color]', array(
    'type'        => 'color',
    'settings'    => 'infowazz_theme_options[background_color]',
    'label'       => esc_html__( 'Background Color', 'infowazz' ),
    'section'     => 'background_settings',
    'default'     => '',
    'option_type' => 'option',
    'priority'    => 10,
  ) );

  // GENERAL COLORS //
  $wp_customize->add_section('colors_general', array(
    'title'    	=> esc_html__('General', 'infowazz'),
    'panel'  => 'colors_settings'
  ));



  $wp_customize->add_setting('infowazz_theme_options[colors_default]', array(
      'capability'        => 'edit_theme_options',
      'type'           => 'option',
      'sanitize_callback' => 'sanitize_hex_color',
    ));
  $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'infowazz_theme_options[colors_default]', array(
      'label'    => esc_html__('Site Color', 'infowazz'),
      'section'  => 'general_style_settings',
      'settings' => 'infowazz_theme_options[colors_default]',
    )));

    Kirki::add_field( 'mt_colors_default', array(
      'type'        => 'multicolor',
      'settings'    => 'mt_colors_default',
      'label'       => esc_attr__( 'Site Color', 'infowazz' ),
      'section'     => 'general_style_settings',
      'option_type' => 'option',
      'priority'    => 1,
      'choices'     => array(
          'color'    => esc_attr__( 'Color', 'infowazz' ),
          'textinbackground'   => esc_attr__( 'Text If Background', 'infowazz' ),
      ),
      'default'     => array(
          'color'    => '',
          'textinbackground'    => '',
      ),
    ));


  $wp_customize->add_setting('infowazz_theme_options[colors_button]', array(
      'capability'        => 'edit_theme_options',
      'type'           => 'option',
      'sanitize_callback' => 'sanitize_hex_color',
    ));
  $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'infowazz_theme_options[colors_button]', array(
      'label'    => esc_html__('Form Button', 'infowazz'),
      'section'  => 'general_style_settings',
      'settings' => 'infowazz_theme_options[colors_button]',
  )));



  // MENU COLORS //
  $wp_customize->add_section('colors_menu', array(
    'title'    	=> esc_html__('Header & Menu Colors', 'infowazz'),
    'panel'  => 'colors_settings'
  ));

  Kirki::add_field( 'mt_colors_header', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_header',
    'label'       => esc_attr__( 'Top Bar', 'infowazz' ),
    'section'     => 'colors_menu',
    'option_type' => 'option',
    'priority'    => 1,
    'choices'     => array(
        'background'    => esc_attr__( 'Background', 'infowazz' ),
        'link'   => esc_attr__( 'Link', 'infowazz' ),
        'hover'  => esc_attr__( 'Hover', 'infowazz' ),
        'bold'  => esc_attr__( 'Bold', 'infowazz' ),
    ),
    'default'     => array(
        'background'    => '',
        'link'    => '',
        'hover'    => '',
        'bold'    => ''
    ),
  ));


  Kirki::add_field( 'mt_colors_menu_bg', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_menu_bg',
    'label'       => esc_attr__( 'Menu Background', 'infowazz' ),
    'section'     => 'colors_menu',
    'option_type' => 'option',
    'priority'    => 1,
    'choices'     => array(
        'in'    => esc_attr__( 'Background', 'infowazz' ),
    ),
    'default'     => array(
        'in'    => '',
    ),
  ));

  Kirki::add_field( 'mt_colors_menu_link', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_menu_link',
    'label'       => esc_attr__( 'Menu Links', 'infowazz' ),
    'section'     => 'colors_menu',
    'option_type' => 'option',
    'priority'    => 1,
    'choices'     => array(
        'text'    => esc_attr__( 'Links', 'infowazz' ),
        'text_hover'   => esc_attr__( 'Hover', 'infowazz' ),
        'border'  => esc_attr__( 'Background', 'infowazz' ),
    ),
    'default'     => array(
        'text'    => '',
        'text_hover'    => '',
        'border'    => '',
    ),
  ));

  Kirki::add_field( 'mt_colors_menu_link_sub', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_menu_link_sub',
    'label'       => esc_attr__( 'Menu Sub Links', 'infowazz' ),
    'section'     => 'colors_menu',
    'option_type' => 'option',
    'priority'    => 1,
    'choices'     => array(
        'text'    => esc_attr__( 'Lines', 'infowazz' ),
        'text_hover'   => esc_attr__( 'Hover', 'infowazz' ),
        'background'  => esc_attr__( 'Background', 'infowazz' ),
        'background_hover'  => esc_attr__( 'Hover', 'infowazz' ),
    ),
    'default'     => array(
        'text'    => '',
        'text_hover'    => '',
        'background'    => '',
        'background_hover'    => '',
    ),
  ));

  Kirki::add_field( 'mt_colors_menu_button', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_menu_button',
    'label'       => esc_attr__( 'Menu Smart Button', 'infowazz' ),
    'section'     => 'colors_menu',
    'option_type' => 'option',
    'priority'    => 1,
    'choices'     => array(
        'text'    => esc_attr__( 'Lines', 'infowazz' ),
        'text_hover'   => esc_attr__( 'Hover', 'infowazz' ),
    ),
    'default'     => array(
        'text'    => '',
        'text_hover'    => '',
    ),
  ));

  Kirki::add_field( 'mt_colors_menu_search', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_menu_search',
    'label'       => esc_attr__( 'Menu Search', 'infowazz' ),
    'section'     => 'colors_menu',
    'option_type' => 'option',
    'priority'    => 1,
    'choices'     => array(
        'text'    => esc_attr__( 'Text', 'infowazz' ),
        'text_hover'   => esc_attr__( 'Hover', 'infowazz' ),
        'background'  => esc_attr__( 'Background', 'infowazz' ),
        'background_hover'  => esc_attr__( 'Hover', 'infowazz' ),
    ),
    'default'     => array(
        'text'    => '',
        'text_hover'    => '',
        'background'    => '',
        'background_hover'    => '',
    ),
  ));


  Kirki::add_field( 'mt_colors_menu_smart', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_menu_smart',
    'label'       => esc_attr__( 'Smart Menu', 'infowazz' ),
    'section'     => 'colors_menu',
    'option_type' => 'option',
    'priority'    => 100,
    'choices'     => array(
        'background'    => esc_attr__( 'Background', 'infowazz' ),
        'link'   => esc_attr__( 'Link', 'infowazz' ),
        'hover'  => esc_attr__( 'Hover', 'infowazz' ),
        'out'  => esc_attr__( 'Out', 'infowazz' ),
    ),
    'default'     => array(
        'background'    => '',
        'link'    => '',
        'hover'    => '',
        'out'    => '',
    ),
  ));


  // FOOTER COLORS //
  $wp_customize->add_section('colors_footer', array(
    'title'    	=> esc_html__('Footer Colors', 'infowazz'),
    'panel'  => 'colors_settings'
  ));


Kirki::add_field( 'mt_colors_footer_social', array(
  'type'        => 'multicolor',
  'settings'    => 'mt_colors_footer_social',
  'label'       => esc_attr__( 'Footer Social Icons', 'infowazz' ),
  'section'     => 'colors_footer',
  'option_type' => 'option',
  'choices'     => array(
      'icon'    => esc_attr__( 'Icon', 'infowazz' ),
      'hover'   => esc_attr__( 'Hover', 'infowazz' ),
      'background'   => esc_attr__( 'Background', 'infowazz' ),
      'background_hover'  => esc_attr__( 'Hover', 'infowazz' ),
  ),
  'default'     => array(
      'icon'    => '',
      'hover'    => '',
      'background'    => '',
      'background_hover'    => '',
  ),
));

Kirki::add_field( 'mt_colors_footer_bottom', array(
  'type'        => 'multicolor',
  'settings'    => 'mt_colors_footer_bottom',
  'label'       => esc_attr__( 'Footer Colors', 'infowazz' ),
  'section'     => 'colors_footer',
  'option_type' => 'option',
  'choices'     => array(
      'background'    => esc_attr__( 'Background', 'infowazz' ),
      'text'   => esc_attr__( 'Text', 'infowazz' ),
      'link'  => esc_attr__( 'Link', 'infowazz' ),
      'hover'  => esc_attr__( 'Hover', 'infowazz' ),
  ),
  'default'     => array(
      'background'    => '',
      'text'    => '',
      'link'    => '',
      'hover'    => '',
  ),
));


  // MENU COLORS //
  $wp_customize->add_section('colors_other', array(
    'title'    	=> esc_html__('Other Colors', 'infowazz'),
    'panel'  => 'colors_settings'
  ));


  Kirki::add_field( 'colors_post_share', array(
    'type'        => 'multicolor',
    'settings'    => 'colors_post_share',
    'label'       => esc_attr__( 'Post Share Count', 'infowazz' ),
    'section'     => 'colors_other',
    'option_type' => 'option',
    'priority'    => 100,
    'choices'     => array(
        'text'    => esc_attr__( 'Text', 'infowazz' ),
        'text_dark'   => esc_attr__( 'Photo bg', 'infowazz' ),
        'icon'   => esc_attr__( 'Icon', 'infowazz' ),
        'icon_dark'   => esc_attr__( 'Photo bg', 'infowazz' ),
    ),
    'default'     => array(
        'text'    => '',
        'text_dark'    => '',
        'icon'    => '',
        'icon_dark'    => '',
    ),
  ));
  Kirki::add_field( 'colors_post_view', array(
    'type'        => 'multicolor',
    'settings'    => 'colors_post_view',
    'label'       => esc_attr__( 'Post View Count', 'infowazz' ),
    'section'     => 'colors_other',
    'option_type' => 'option',
    'priority'    => 100,
    'choices'     => array(
        'text'    => esc_attr__( 'Text', 'infowazz' ),
        'text_dark'   => esc_attr__( 'Photo bg', 'infowazz' ),
        'icon'   => esc_attr__( 'Icon', 'infowazz' ),
        'icon_dark'   => esc_attr__( 'Photo bg', 'infowazz' ),
    ),
    'default'     => array(
        'text'    => '',
        'text_dark'    => '',
        'icon'    => '',
        'icon_dark'    => '',
    ),
  ));

  Kirki::add_field( 'mt_colors_cat', array(
    'type'        => 'multicolor',
    'settings'    => 'mt_colors_cat',
    'label'       => esc_attr__( 'Post List Category', 'infowazz' ),
    'section'     => 'colors_other',
    'option_type' => 'option',
    'priority'    => 100,
    'choices'     => array(
        'text'    => esc_attr__( 'Text', 'infowazz' ),
        'background'   => esc_attr__( 'Background', 'infowazz' ),
    ),
    'default'     => array(
        'text'    => '',
        'background'    => '',
    ),
  ));




}

add_action('customize_register', 'infowazz_customize_colors');
?>
