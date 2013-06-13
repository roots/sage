<?php

/*
 * echo any custom CSS the user has written to the <head> of the page
 */
function shoestrap_user_css() {
  $header_scripts = shoestrap_getVariable( 'user_css' );
  if (trim($header_scripts) != "")
  	echo '<style id="core.advanced-user-css">' . $header_scripts . '</style>';
}
add_action( 'wp_head', 'shoestrap_user_css', 200 );

/*
 * echo any custom JS the user has written to the footer of the page
 */
function shoestrap_user_js() {
  $footer_scripts = shoestrap_getVariable( 'user_js' );
  if (trim($footer_scripts) != "")
  	echo '<script id="core.advanced-user-js">' . $footer_scripts . '</script>';
}
add_action( 'wp_footer', 'shoestrap_user_js', 200 );



function shoestrap_enable_widget_shortcodes() {
  $enabled = shoestrap_getVariable( 'enable_widget_shortcodes' );
  if ($enabled == 1) {
	add_filter('widget_text', 'do_shortcode');
  }
  	
}
add_action( 'wp_head', 'shoestrap_enable_widget_shortcodes', 200 );

//enable_widget_shortcodes

