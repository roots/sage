<?php

function shoestrap_enqueue_affix() {
  $sidebar_affix = get_theme_mod( 'shoestrap_aside_affix' );
  
  if ( $sidebar_affix == 'affix' ) {
    
    wp_register_script( 'shoestrap_affix', get_template_directory_uri() . '/assets/js/sidebar-affix.js', false, null, false );
    wp_enqueue_script( 'shoestrap_affix' );
  }
}
add_action( 'wp_enqueue_scripts', 'shoestrap_enqueue_affix', 110 );
