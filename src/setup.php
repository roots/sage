<?php namespace App;

use Roots\Sage\Template;

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
  /**
   * Enable features from Soil when plugin is activated
   * @link https://roots.io/plugins/soil/
   */
  add_theme_support('soil-clean-up');
  add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-relative-urls');

  /**
   * Make theme available for translation
   * @link https://github.com/roots/sage-translations Community translations
   */
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  /**
   * Enable plugins to manage the document title
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
   */
  add_theme_support('title-tag');

  /**
   * Register wp_nav_menu() menus
   * @link http://codex.wordpress.org/Function_Reference/register_nav_menus
   */
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  /**
   * Enable post thumbnails
   * @link http://codex.wordpress.org/Post_Thumbnails
   * @link http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
   * @link http://codex.wordpress.org/Function_Reference/add_image_size
   */
  add_theme_support('post-thumbnails');

  /**
   * Enable post formats
   * @link http://codex.wordpress.org/Post_Formats
   */
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  /**
   * Enable HTML5 markup support
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
   */
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  /**
   * Use main stylesheet for visual editor
   * @see /assets/styles/layouts/_tinymce.scss
   */
  add_editor_style(asset_path('styles/main.css'));
});

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
  $config = function ($name, $id = '') {
    return [
      'name'          => __($name, 'sage'),
      'id'            => 'sidebar-' . $id ?: sanitize_title($name),
      'before_widget' => '<section class="widget %1$s %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h3>',
      'after_title'   => '</h3>'
    ];
  };

  register_sidebar($config('Primary'));
  register_sidebar($config('Footer'));
});

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('sage/css', asset_path('styles/main.css'), false, null);

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script('sage/js', asset_path('scripts/main.js'), ['jquery'], null, true);
}, 100);
