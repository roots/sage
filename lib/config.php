<?php
/**
 * Enable theme features
 */
add_theme_support('post-thumbnails');
if ( get_theme_mod( 'root_relative_urls' ) == 1  )
  add_theme_support('root-relative-urls');    // Enable relative URLs

if ( get_theme_mod( 'rewrites' ) == 1 )
  add_theme_support('rewrites');              // Enable URL rewrites

if ( get_theme_mod( 'navbar_toggle' ) == 1 )
  add_theme_support('bootstrap-top-navbar');  // Enable Bootstrap's top navbar

add_theme_support('bootstrap-gallery');     // Enable Bootstrap's thumbnails component on [gallery]

if ( get_theme_mod( 'nice_search' ) == 1 )
  add_theme_support('nice-search');           // Enable /?s= to /search/ redirect

add_theme_support('jquery-cdn');            // Enable to load jQuery from the Google CDN

/**
 * Configuration values
 */
define('GOOGLE_ANALYTICS_ID', get_theme_mod('analytics_id')); // UA-XXXXX-Y
define('POST_EXCERPT_LENGTH', get_theme_mod('post_excerpt_length'));

/**
 * .main classes
 */
function roots_main_class() {
  if (roots_display_sidebar()) {
    // Classes on pages with the sidebar
    $class = 'span8';
  } else {
    // Classes on full width pages
    $class = 'span12';
  }

  return $class;
}

/**
 * .sidebar classes
 */
function roots_sidebar_class() {
  return 'span4';
}

/**
 * Define which pages shouldn't have the sidebar
 *
 * See lib/sidebar.php for more details
 */
function roots_display_sidebar() {
  $sidebar_config = new Roots_Sidebar(
    /**
     * Conditional tag checks (http://codex.wordpress.org/Conditional_Tags)
     * Any of these conditional tags that return true won't show the sidebar
     *
     * To use a function that accepts arguments, use the following format:
     *
     * array('function_name', array('arg1', 'arg2'))
     *
     * The second element must be an array even if there's only 1 argument.
     */
    array(
      'is_404',
      'is_front_page'
    ),
    /**
     * Page template checks (via is_page_template())
     * Any of these page templates that return true won't show the sidebar
     */
    array(
      'template-custom.php'
    )
  );

  return apply_filters('roots_display_sidebar', $sidebar_config->display);
}

/**
 * $content_width is a global variable used by WordPress for max image upload sizes
 * and media embeds (in pixels).
 *
 * Example: If the content area is 640px wide, set $content_width = 620; so images and videos will not overflow.
 * Default: 940px is the default Bootstrap container width.
 */
if (!isset($content_width)) { $content_width = 940; }

/**
 * Define helper constants
 */
$get_theme_name = explode('/themes/', get_template_directory());

define('RELATIVE_PLUGIN_PATH',  str_replace(home_url() . '/', '', plugins_url()));
define('RELATIVE_CONTENT_PATH', str_replace(home_url() . '/', '', content_url()));
define('THEME_NAME',            next($get_theme_name));
define('THEME_PATH',            RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);
