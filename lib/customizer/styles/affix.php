<?php

function shoestrap_enqueue_affix() {
  $sidebar_affix  = get_theme_mod( 'shoestrap_aside_affix' );
  $aside_layout   = get_theme_mod( 'shoestrap_aside_layout' );
  
  if ( $sidebar_affix == 'affix' ) {
    
    wp_register_script( 'shoestrap_affix', get_stylesheet_directory_uri() . '/assets/js/sidebar-affix.js', false, null, false );
    wp_enqueue_script( 'shoestrap_affix' );
    
    if ( $aside_layout == 'right' ) {
      wp_register_script( 'shoestrap_affix_right', get_stylesheet_directory_uri() . '/assets/js/affix-right.js', false, null, false );
      wp_enqueue_script( 'shoestrap_affix_right' );
    }
  }
}
add_action( 'wp_enqueue_scripts', 'shoestrap_enqueue_affix', 110 );
