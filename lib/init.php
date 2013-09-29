<?php
/**
 * Roots initial setup and constants
 */
function roots_setup() {
  // Make theme available for translation
  load_theme_textdomain('atkore', get_template_directory() . '/lang');

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
    'primary_navigation' => __('Primary Navigation', 'atkore'),
    'mini_navigation' => __('Mini Navigation', 'atkore'),
    'footer_navigation' => __('Footer Navigation', 'atkore'),
    'social_nav' => __('Social Nav', 'atkore'),
    'user_menu' => __('User Menu', 'atkore'),
    'products' => __('Products', 'atkore'),
  ));

  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(250, 250, false);
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
