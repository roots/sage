<?php

/*
 * Adds settings to the customizer
 */
function shoestrap_custom_builder_register_settings( $wp_customize ){
  $settings   = array();

  // Grays
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_black',        'default' => '#000000' );
  $settings[] = array( 'slug' => 'shoestrap_cb_graydarker',   'default' => '#222222' );
  $settings[] = array( 'slug' => 'shoestrap_cb_graydark',     'default' => '#333333' );
  $settings[] = array( 'slug' => 'shoestrap_cb_gray',         'default' => '#555555' );
  $settings[] = array( 'slug' => 'shoestrap_cb_graylight',    'default' => '#999999' );
  $settings[] = array( 'slug' => 'shoestrap_cb_graylighter',  'default' => '#eeeeee' );
  $settings[] = array( 'slug' => 'shoestrap_cb_white',        'default' => '#ffffff' );
  
  // Accent colors
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_blue',     'default' => '#049cdb' );
  $settings[] = array( 'slug' => 'shoestrap_cb_bluedark', 'default' => '#0064cd' );
  $settings[] = array( 'slug' => 'shoestrap_cb_green',    'default' => '46a546' );
  $settings[] = array( 'slug' => 'shoestrap_cb_red',      'default' => '9d261d' );
  $settings[] = array( 'slug' => 'shoestrap_cb_yellow',   'default' => 'ffc40d' );
  $settings[] = array( 'slug' => 'shoestrap_cb_orange',   'default' => 'f89406' );
  $settings[] = array( 'slug' => 'shoestrap_cb_pink',     'default' => 'c3325f' );
  $settings[] = array( 'slug' => 'shoestrap_cb_purple',   'default' => '7a43b6' );
  
  // Scaffolding
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_bodybackground', 'default' => '#ffffff' );
  $settings[] = array( 'slug' => 'shoestrap_cb_textcolor',      'default' => '#333333' );
  
  // Links
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_linkcolor',      'default' => '#0088cc' );
  $settings[] = array( 'slug' => 'shoestrap_cb_linkcolorhover', 'default' => '#005580' );

  // Typography
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_sansfontfamily',   'default' => '"Helvetica Neue", Helvetica, Arial, sans-serif' );
  $settings[] = array( 'slug' => 'shoestrap_cb_seriffontfamily',  'default' => 'Georgia, "Times New Roman", Times, serif' );
  $settings[] = array( 'slug' => 'shoestrap_cb_monofontfamily',   'default' => 'Monaco, Menlo, Consolas, "Courier New", monospace' );
  $settings[] = array( 'slug' => 'shoestrap_cb_basefontsize',     'default' => 14 );
  $settings[] = array( 'slug' => 'shoestrap_cb_baselineheight',   'default' => 20 );
  
  // Component sizing
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_fontsizelarge',    'default' => 1.25 );
  $settings[] = array( 'slug' => 'shoestrap_cb_fontsizesmall',    'default' => 0.85 );
  $settings[] = array( 'slug' => 'shoestrap_cb_fontsizemini',     'default' => 0.75 );
  $settings[] = array( 'slug' => 'shoestrap_cb_baseborderradius', 'default' => 4 );

  // Tables
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_tablebackground',        'default' => '' ); // transparent
  $settings[] = array( 'slug' => 'shoestrap_cb_tablebackgroundaccent',  'default' => '#f9f9f9' );
  $settings[] = array( 'slug' => 'shoestrap_cb_tablebackgroundhover',   'default' => '#f5f5f5' );
  $settings[] = array( 'slug' => 'shoestrap_cb_tableborder',            'default' => '#dddddd' );
  
  // Buttons
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_btnbackground',        'default' => '#ffffff' );
  $settings[] = array( 'slug' => 'shoestrap_cb_btnprimarybackground', 'default' => '#0088cc' );
  $settings[] = array( 'slug' => 'shoestrap_cb_btninfobackground',    'default' => '#5bc0de' );
  $settings[] = array( 'slug' => 'shoestrap_cb_btnsuccessbackground', 'default' => '#62c462' );
  $settings[] = array( 'slug' => 'shoestrap_cb_btnwarningbackground', 'default' => '#fbb450' );
  $settings[] = array( 'slug' => 'shoestrap_cb_btndangerbackground',  'default' => '#ee5f5b' );
  $settings[] = array( 'slug' => 'shoestrap_cb_btninversebackground', 'default' => '#444444' );

  // Forms
  // -------------------------
  $settings[] = array( 'slug' => 'shoestrap_cb_inputbackground',          'default' => '#ffffff' );
  $settings[] = array( 'slug' => 'shoestrap_cb_inputheight',              'default' => 30 );

  foreach( $settings as $setting ){
    $wp_customize->add_setting( $setting['slug'], array( 'default' => $setting['default'], 'type' => 'theme_mod', 'capability' => 'edit_theme_options' ) );
  }
}
add_action( 'customize_register', 'shoestrap_register_settings' );
