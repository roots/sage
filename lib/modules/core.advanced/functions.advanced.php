<?php

/*
 * echo any custom CSS the user has written to the <head> of the page
 */
function shoestrap_user_css() {
  $header_scripts = shoestrap_getVariable( 'user_css' );
  echo '<style>' . $header_scripts . '</style>';
}
add_action( 'wp_head', 'shoestrap_user_css', 200 );

/*
 * echo any custom JS the user has written to the footer of the page
 */
function shoestrap_user_js() {
  $footer_scripts = shoestrap_getVariable( 'user_js' );
  echo '<script>' . $footer_scripts . '</script>';
}
add_action( 'wp_footer', 'shoestrap_user_js', 200 );
