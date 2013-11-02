<?php

if ( !function_exists( 'shoestrap_user_css' ) ) :
/*
 * echo any custom CSS the user has written to the <head> of the page
 */
function shoestrap_user_css() {
  $header_scripts = shoestrap_getVariable( 'user_css' );
  
  if ( trim( $header_scripts ) != '' ) :
    wp_add_inline_style( 'shoestrap_css', $header_scripts );
  endif;
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_user_css', 101 );


if ( !function_exists( 'shoestrap_user_js' ) ) :
/*
 * echo any custom JS the user has written to the footer of the page
 */
function shoestrap_user_js() {
  $footer_scripts = shoestrap_getVariable( 'user_js' );

  if ( trim( $footer_scripts ) != '' ) :
    echo '<script id="core.advanced-user-js">' . $footer_scripts . '</script>';
  endif;
}
endif;
add_action( 'wp_footer', 'shoestrap_user_js', 200 );


if ( !function_exists( 'shoestrap_enable_widget_shortcodes' ) ) :
/*
 * enable widget shortcodes
 */
function shoestrap_enable_widget_shortcodes() {
  if ( shoestrap_getVariable( 'enable_widget_shortcodes' ) == 1 ) :
    add_filter( 'widget_text', 'do_shortcode' );
  endif;
}
endif;
add_action( 'wp_head', 'shoestrap_enable_widget_shortcodes', 200 );


if ( !function_exists( 'shoestrap_change_upload_folder' ) ) :
/*
 * change upload folder to /media
 * NOTICE: by that any media in 'wp-content/uploads' won't be accessible
 */
function shoestrap_change_upload_folder() {
  $option_name    = 'upload_path';
  $default_value  = 'wp-content/uploads';

  if ( shoestrap_getVariable( 'upload_folder' ) == 1 ) :
    update_option( 'uploads_use_yearmonth_folders', 0 );
    $new_value = 'media';
    
    if ( ( get_option( $option_name ) !== false ) && ( !is_multisite() ) ) :
      update_option( $option_name, $new_value );
    endif;

  else :
    update_option( $option_name, $default_value );
  endif;
}
endif;
add_action( 'wp', 'shoestrap_change_upload_folder' );


// Show or hide the adminbar
if ( shoestrap_getVariable( 'advanced_wordpress_disable_admin_bar_toggle' ) == 0 ) :
  show_admin_bar( false );
else :
  show_admin_bar( true );
endif;

if ( !function_exists( 'shoestrap_core_blog_comments_toggle' ) ) :
/*
 * Disable comments support on blog posts
 */
function shoestrap_core_blog_comments_toggle() {
  if ( shoestrap_getVariable( 'blog_comments_toggle' ) == 1 ) :
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'post', 'trackbacks' );
    add_filter( 'get_comments_number', '__return_false', 10, 3 );
  endif;
}
endif;
add_action( 'init','shoestrap_core_blog_comments_toggle', 1 );