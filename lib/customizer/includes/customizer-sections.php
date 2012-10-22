<?php

function shoestrap_register_sections($wp_customize){
  
  // remove default sections
  $sections = array();
  $sections[] = array( 'slug'=>'shoestrap_header',      'title' => __('Header & Logo', 'shoestrap'),         'priority' => 1);
  $sections[] = array( 'slug'=>'shoestrap_layout',      'title' => __('Layout', 'shoestrap'),                'priority' => 2);
  $sections[] = array( 'slug'=>'shoestrap_typography',  'title' => __('Typography', 'shoestrap'),            'priority' => 3);
  $sections[] = array( 'slug'=>'background_image',          'title' => __('General Colors', 'shoestrap'),        'priority' => 4);
  $sections[] = array( 'slug'=>'shoestrap_footer',      'title' => __('Footer', 'shoestrap'),                'priority' => 5);
  $sections[] = array( 'slug'=>'shoestrap_hero',        'title' => __('Hero', 'shoestrap'),      'priority' => 7);
  $sections[] = array( 'slug'=>'shoestrap_social',      'title' => __('Social Links', 'shoestrap'),          'priority' => 8);

  foreach($sections as $section){
    $wp_customize->add_section( $section['slug'], array( 'title' => $section['title'], 'priority' => $section['priority']));
  }

  if ( $wp_customize->is_preview() && ! is_admin() )
    add_action( 'wp_footer', 'shoestrap_preview', 21);
}
add_action( 'customize_register', 'shoestrap_register_sections' );
