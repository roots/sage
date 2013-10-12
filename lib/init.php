<?php
/**
 * Roots initial setup and constants
 */
function roots_setup() {
  // Make theme available for translation
  load_theme_textdomain('atkore', get_template_directory() . '/lang');

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
      'primary'   => __('Primary', 'atkore'),
      'mini'      => __('Mini', 'atkore'),
      'footer'    => __('Footer', 'atkore'),
      'social'    => __('Social', 'atkore'),
      'products'  => __('Products', 'atkore'),
      'account'   => __('Account', 'atkore'),
    ));

	// Add theme support for Custom Header
	$header_args = array(
		'default-image'          => '/media/header.png',
		'width'                  => 840,
		'height'                 => 140,
		'flex-width'             => true,
		'flex-height'            => true,
		'random-default'         => false,
		'header-text'            => true,
		'default-text-color'     => '#fff',
		'uploads'                => true,

	);
	add_theme_support( 'custom-header', $header_args );

	// Add theme support for Semantic Markup
	$markup = array( 'search-form', 'comment-form', 'comment-list', );
	add_theme_support( 'html5', $markup );	

  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(250, 250, false);
  // Custom Sizes
  add_image_size('small-tall', 180, 210);
  add_image_size('x-small', 125, 125);
  add_image_size('small', 250, 250);
  add_image_size('medium', 380, 380);
  add_image_size('large', 870, 870);

  // Add post formats (http://codex.wordpress.org/Post_Formats)
  add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('/assets/css/editor-style.css');
}
add_action('after_setup_theme', 'roots_setup');

// Backwards compatibility for older than PHP 5.3.0
if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }



