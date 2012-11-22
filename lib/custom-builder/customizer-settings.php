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
  
  foreach( $settings as $setting ){
    $wp_customize->add_setting( $setting['slug'], array( 'default' => $setting['default'], 'type' => 'theme_mod', 'capability' => 'edit_theme_options' ) );
  }
}
add_action( 'customize_register', 'shoestrap_register_settings' );
