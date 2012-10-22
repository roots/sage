<?php

function bc_customizer_register_settings($wp_customize){

  $settings = array();
  $settings[] = array( 'slug'=>'bc_customizer_text_variation',            'default' => 'dark');
  $settings[] = array( 'slug'=>'bc_customizer_navbar_color',              'default' => '#ffffff');
  $settings[] = array( 'slug'=>'bc_customizer_aside_layout',              'default' => 'right');
  $settings[] = array( 'slug'=>'bc_customizer_link_color',                'default' => '#0088cc');
  $settings[] = array( 'slug'=>'bc_customizer_hero_title',                'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_hero_content',              'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_hero_cta_text',             'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_hero_cta_link',             'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_hero_cta_color',            'default' => 'primary');
  $settings[] = array( 'slug'=>'bc_customizer_hero_background',           'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_hero_background_color',     'default' => '#333333');
  $settings[] = array( 'slug'=>'bc_customizer_hero_textcolor',            'default' => '#ffffff');
  $settings[] = array( 'slug'=>'bc_customizer_hero_visibility',           'default' => 'front');
  $settings[] = array( 'slug'=>'bc_customizer_logo',                      'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_header_backgroundcolor',    'default' => '#0066bb');
  $settings[] = array( 'slug'=>'bc_customizer_header_textcolor',          'default' => '#ffffff');
  $settings[] = array( 'slug'=>'bc_customizer_footer_background_color',   'default' => '#ffffff');
  $settings[] = array( 'slug'=>'bc_customizer_google_webfonts',           'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_facebook_link',             'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_twitter_link',              'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_google_plus_link',          'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_pinterest_link',            'default' => '');
  $settings[] = array( 'slug'=>'bc_customizer_buttons_color',             'default' => '#0066bb');

  foreach($settings as $setting){
    $wp_customize->add_setting( $setting['slug'], array( 'default' => $setting['default'], 'type' => 'theme_mod', 'capability' => 'edit_theme_options' ));
  }
  if ( $wp_customize->is_preview() && ! is_admin() )
    add_action( 'wp_footer', 'bc_customizer_preview', 21);
}
add_action( 'customize_register', 'bc_customizer_register_settings' );
