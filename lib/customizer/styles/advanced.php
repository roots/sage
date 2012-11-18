<?php

/*
 * If the user has entered any scripts in the 'head' control
 * of the advanced section of the customizer, then his content will be 
 * echoed in the <head> of our page.
 * 
 * CAUTION:
 * Anything users enter in the advanced section will not be filtered.
 */
function shoestrap_custom_header_scripts() {
  $header_scripts = get_theme_mod( 'shoestrap_advanced_head' );
  echo $header_scripts;
}
add_action( 'wp_head', 'shoestrap_custom_header_scripts', 200 );

/*
 * If the user has entered any scripts in the 'head' control
 * of the advanced section of the customizer, then his content will be 
 * echoed in the footer of our page.
 * 
 * CAUTION:
 * Anything users enter in the advanced section will not be filtered.
 */
function shoestrap_custom_footer_scripts() {
  $footer_scripts = get_theme_mod( 'shoestrap_advanced_footer' );
  echo $footer_scripts;
}
add_action( 'shoestrap_after_footer', 'shoestrap_custom_footer_scripts', 200 );

