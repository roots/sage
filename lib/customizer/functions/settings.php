<?php

/*
 * Adds settings to the customizer
 */
function shoestrap_register_settings( $wp_customize ){
  
  // Adds compatibility with wordpress's default background color control.
  $background_color = get_theme_mod( 'background_color' );
  $background_color = '#' . str_replace( '#', '', $background_color );
  
  // Compatibility hack for previous versions of Shoestrap.
  if ( get_theme_mod( 'shoestrap_header_mode' ) == 'header' ) {
    $shoestrap_extra_branding = 1;
  } else {
    $shoestrap_extra_branding = 0;
  }

  $settings   = array();
  
  // Logo Settings
  $settings[] = array( 'slug' => 'shoestrap_logo',                      'default' => '' );
    
  // NavBar Settings
  $settings[] = array( 'slug' => 'shoestrap_navbar_top',                'default' => '1' );
  $settings[] = array( 'slug' => 'shoestrap_navbar_branding',           'default' => '1' );
  $settings[] = array( 'slug' => 'shoestrap_navbar_logo',               'default' => '1' );
  $settings[] = array( 'slug' => 'shoestrap_navbar_color',              'default' => '#ffffff' );
  $settings[] = array( 'slug' => 'shoestrap_navbar_social',             'default' => '1' );
  
  // Extra Header Settings
  $settings[] = array( 'slug' => 'shoestrap_extra_branding',            'default' => $shoestrap_extra_branding );
  $settings[] = array( 'slug' => 'shoestrap_header_loginlink',          'default' => '1' );
  $settings[] = array( 'slug' => 'shoestrap_header_backgroundcolor',    'default' => '#0066bb' );
  $settings[] = array( 'slug' => 'shoestrap_header_textcolor',          'default' => '#ffffff' );
  $settings[] = array( 'slug' => 'shoestrap_header_social',             'default' => '0' );
    
  // Layout Settings
  $settings[] = array( 'slug' => 'shoestrap_layout',                    'default' => 'mp' );
  $settings[] = array( 'slug' => 'shoestrap_aside_affix',               'default' => 'normal' );
  $settings[] = array( 'slug' => 'shoestrap_aside_width',               'default' => '4' );
  $settings[] = array( 'slug' => 'shoestrap_secondary_width',           'default' => '3' );
  $settings[] = array( 'slug' => 'shoestrap_sidebar_on_front',          'default' => 'hide' );
  $settings[] = array( 'slug' => 'shoestrap_responsive',                'default' => '1' );
    
  // Color Settings
  $settings[] = array( 'slug' => 'shoestrap_background_color',          'default' => $background_color );
  $settings[] = array( 'slug' => 'shoestrap_link_color',                'default' => '#0088cc' );
  $settings[] = array( 'slug' => 'shoestrap_buttons_color',             'default' => '#0066bb' );
  
  // Hero Section Settings
  $settings[] = array( 'slug' => 'shoestrap_hero_title',                'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_hero_content',              'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_hero_cta_text',             'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_hero_cta_link',             'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_hero_cta_color',            'default' => '#0066bb' );
  $settings[] = array( 'slug' => 'shoestrap_hero_background',           'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_hero_background_color',     'default' => '#333333' );
  $settings[] = array( 'slug' => 'shoestrap_hero_textcolor',            'default' => '#ffffff' );
  $settings[] = array( 'slug' => 'shoestrap_hero_visibility',           'default' => 'front' );
  
  // Social Settings
  $settings[] = array( 'slug' => 'shoestrap_facebook_link',             'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_twitter_link',              'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_google_plus_link',          'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_pinterest_link',            'default' => '' );
  
  $settings[] = array( 'slug' => 'shoestrap_facebook_on_posts',         'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_twitter_on_posts',          'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_gplus_on_posts',            'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_linkedin_on_posts',         'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_pinterest_on_posts',        'default' => '' );
  
  $settings[] = array( 'slug' => 'shoestrap_single_social_position',    'default' => 'none' );  
  
  // Advanced Settings
  $settings[] = array( 'slug' => 'shoestrap_advanced_head',             'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_advanced_footer',           'default' => '' );
    
  // Typography Settings
  $settings[] = array( 'slug' => 'shoestrap_google_webfonts',           'default' => '' );
  $settings[] = array( 'slug' => 'shoestrap_webfonts_assign',           'default' => 'all' );
  
  // Footer Settings
  $settings[] = array( 'slug' => 'shoestrap_footer_background_color',   'default' => '#ffffff' );
  
  // Navigation Settings
  $settings[] = array( 'slug' => 'shoestrap_extra_display_navigation',  'default' => '0' );
  
  foreach( $settings as $setting ){
    $wp_customize->add_setting( $setting['slug'], array( 'default' => $setting['default'], 'type' => 'theme_mod', 'capability' => 'edit_theme_options' ) );
  }
}
add_action( 'customize_register', 'shoestrap_register_settings' );
