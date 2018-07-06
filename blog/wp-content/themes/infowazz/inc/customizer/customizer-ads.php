<?php
function infowazz_customize_ads($wp_customize){

  //  =============================
  //  = AD 1 Image             =
  //  =============================
  $wp_customize->add_panel( 'infowazz_ads', array(
    'priority'       => 310,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'    	=> esc_html__('Ads', 'infowazz'),
    'description'    => '',
  ));

  $wp_customize->add_section('sidebar_ad_top', array(
    'title'    	=> esc_html__('Sidebar top ad', 'infowazz'),
    'priority' => 5,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[sidebar_ad_top]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[sidebar_ad_top]',
    'label'       => esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'sidebar_ad_top',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));


  $wp_customize->add_section('sidebar_ad_middle', array(
    'title'    	=> esc_html__('Sidebar middle ad', 'infowazz'),
    'priority' => 6,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[sidebar_ad_middle]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[sidebar_ad_middle]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'sidebar_ad_middle',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('sidebar_ad_bottom', array(
    'title'    	=> esc_html__('Sidebar bottom ad', 'infowazz'),
    'priority' => 7,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[sidebar_ad_bottom]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[sidebar_ad_bottom]',
    'label'       => esc_html__('YOUR AD CODE', 'infowazz' ),
    'section'     => 'sidebar_ad_bottom',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('article_ad_bottom', array(
    'title'    	=> esc_html__('Article bottom ad', 'infowazz'),
    'priority' => 8,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[article_ad_bottom]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[article_ad_bottom]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'article_ad_bottom',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));


  $wp_customize->add_section('footer_ad_top', array(
    'title'    	=> esc_html__('Footer top ad', 'infowazz'),
    'priority' => 9,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[footer_ad_top]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[footer_ad_top]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'footer_ad_top',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));


  $wp_customize->add_section('custom_ad_1', array(
    'title'    	=> esc_html__('Custom ad 1', 'infowazz'),
    'priority' => 11,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[custom_ad_1]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_1]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_1',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));


  $wp_customize->add_section('custom_ad_2', array(
    'title'    	=> esc_html__('Custom ad 2', 'infowazz'),
    'priority' => 12,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[custom_ad_2]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_2]',
    'label'       => esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_2',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('custom_ad_3', array(
    'title'    	=> esc_html__('Custom ad 3', 'infowazz'),
    'priority' => 13,
    'panel'  => 'infowazz_ads',
  ));


  Kirki::add_field( 'infowazz_theme_options[custom_ad_3]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_3]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_3',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('custom_ad_4', array(
    'title'    	=> esc_html__('Custom ad 4', 'infowazz'),
    'priority' => 14,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[custom_ad_4]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_4]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_4',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('custom_ad_5', array(
    'title'    	=> esc_html__('Custom ad 5', 'infowazz'),
    'priority' => 15,
    'panel'  => 'infowazz_ads',
  ));


  Kirki::add_field( 'infowazz_theme_options[custom_ad_5]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_5]',
    'label'       => esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_5',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('custom_ad_6', array(
    'title'    	=> esc_html__('Custom ad 6', 'infowazz'),
    'priority' => 16,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[custom_ad_6]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_6]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_6',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('custom_ad_7', array(
    'title'    	=> esc_html__('Custom ad 7', 'infowazz'),
    'priority' => 17,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[custom_ad_7]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_7]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_7',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('custom_ad_8', array(
    'title'    	=> esc_html__('Custom ad 8', 'infowazz'),
    'priority' => 18,
    'panel'  => 'infowazz_ads',
  ));


  Kirki::add_field( 'infowazz_theme_options[custom_ad_8]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_8]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_8',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));

  $wp_customize->add_section('custom_ad_9', array(
    'title'    	=> esc_html__('Custom ad 9', 'infowazz'),
    'priority' => 19,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[custom_ad_9]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[custom_ad_9]',
    'label'       =>  esc_html__('YOUR AD CODE', 'infowazz' ),
    'section'     => 'custom_ad_9',
    'default'     => '',
    'priority'    => 10,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));
  $wp_customize->add_section('header_ad_top', array(
    'title'    	=> esc_html__('Header Ad', 'infowazz'),
    'priority' => 1,
    'panel'  => 'infowazz_ads',
  ));

  Kirki::add_field( 'infowazz_theme_options[header_ad_top]', array(
    'type'        => 'code',
    'settings'    => 'infowazz_theme_options[header_ad_top]',
    'label'       =>  esc_html__( 'YOUR AD CODE', 'infowazz' ),
    'section'     => 'header_ad_top',
    'default'     => '',
    'priority'    => 1,
    'option_type' => 'option',
    'choices'     => array(
      'language' => 'css, html, javascript',
      'theme'    => 'monokai',
      'height'   => 250,
    ),
  ));


}

add_action('customize_register', 'infowazz_customize_ads');
?>
