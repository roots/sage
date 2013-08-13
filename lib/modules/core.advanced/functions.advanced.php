<?php

/*
 * echo any custom CSS the user has written to the <head> of the page
 */
function shoestrap_user_css() {
  $header_scripts = shoestrap_getVariable( 'user_css' );
  if (trim($header_scripts) != "")
  	wp_add_inline_style( 'shoestrap_css', $header_scripts );
}
add_action( 'wp_enqueue_scripts', 'shoestrap_user_css', 101 );

/*
 * echo any custom JS the user has written to the footer of the page
 */
function shoestrap_user_js() {
  $footer_scripts = shoestrap_getVariable( 'user_js' );
  if (trim($footer_scripts) != "")
  	echo '<script id="core.advanced-user-js">' . $footer_scripts . '</script>';
}
add_action( 'wp_footer', 'shoestrap_user_js', 200 );


/*
 * enable widget shortcodes
 */
function shoestrap_enable_widget_shortcodes() {
  $enabled = shoestrap_getVariable( 'enable_widget_shortcodes' );
  if ($enabled == 1) {
	add_filter('widget_text', 'do_shortcode');
  }
  	
}
add_action( 'wp_head', 'shoestrap_enable_widget_shortcodes', 200 );

/*
 * change upload folder to /media
 * NOTICE: by that any media in 'wp-content/uploads' won't be accessible
 */
function shoestrap_change_upload_folder() {
  $rewrites = shoestrap_getVariable( 'upload_folder' );
  $option_name = 'upload_path';
  $default_value = 'wp-content/uploads';
  if ($rewrites == 1) {
    update_option('uploads_use_yearmonth_folders', 0);
    $new_value = 'media';
    if ( ( get_option( $option_name ) !== false ) && ( !is_multisite() ) ) {
      update_option( $option_name, $new_value );
    }
  } else {
    update_option( $option_name, $default_value );
  } 
}
add_action( 'wp', 'shoestrap_change_upload_folder' );
