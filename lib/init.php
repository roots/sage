<?php
/**
 * Initial setup and constants
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

  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(90, 90, false);
  // Custom Sizes
  add_image_size('mini', 90, 90, false);
  add_image_size('x-small', 125, 125, false);
  add_image_size('small-tall', 180, 210);
  add_image_size('small', 250, 250);
  add_image_size('medium', 380, 380);
  add_image_size('large', 870, 870);
  add_image_size('home-carousel', 1600, 450);

  // Add post formats (http://codex.wordpress.org/Post_Formats)
  add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('/assets/css/editor-style.css');
}
add_action('after_setup_theme', 'roots_setup');

// Backwards compatibility for older than PHP 5.3.0
if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

add_filter( 'image_size_names_choose', 'my_custom_sizes' );

function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'mini'          => __('Mini'),
        'x-small'       => __('Xtra Small'),
        'small-tall'    => __('Small-Tall'),
        'small'         => __('Small'),
        'medium'        => __('Medium'),
        'large'         => __('Large'),
        'home-carousel' => __('Homepage Carousel'),
    ) );
}