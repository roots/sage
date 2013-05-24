<?php

/*
 * echo any custom CSS the user has written to the <head> of the page
 */
function shoestrap_user_css() {
  $header_scripts = get_theme_mod( 'user_css' );
  echo '<style>' . $header_scripts . '</style>';
}
add_action( 'wp_head', 'shoestrap_custom_header_scripts', 200 );

/*
 * echo any custom JS the user has written to the footer of the page
 */
function shoestrap_user_js() {
  $footer_scripts = get_theme_mod( 'user_js' );
  echo '<script>' . $footer_scripts . '</script>';
}
add_action( 'wp_footer', 'shoestrap_custom_footer_scripts', 200 );
