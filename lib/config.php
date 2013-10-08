<?php
/**
 * Enable theme features
 */
add_theme_support('root-relative-urls');    // Enable relative URLs
add_theme_support('rewrites');              // Enable URL rewrites
//add_theme_support('bootstrap-top-navbar');  // Enable Bootstrap's top navbar
add_theme_support('bootstrap-gallery');     // Enable Bootstrap's thumbnails component on [gallery]
add_theme_support('nice-search');           // Enable /?s= to /search/ redirect
add_theme_support('jquery-cdn');            // Enable to load jQuery from the Google CDN
add_theme_support( 'woocommerce' );

/**
 * Configuration values
 */
  $domain = $_SERVER[ 'SERVER_NAME' ];
  if ($domain == 'atkore.com')  {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-1'); // UA-XXXXX-Y
  }
  if ($domain == 'easternwire.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-2'); // UA-XXXXX-Y
  }
  if ($domain == 'atcfence.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-3'); // UA-XXXXX-Y
  }
  if ($domain == 'kaf-tech.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-4'); // UA-XXXXX-Y
  }
  if ($domain == 'alliedtube-sprinkler.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-5'); // UA-XXXXX-Y
  }
  if ($domain == 'unistrutfallprotection.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-6'); // UA-XXXXX-Y
  }
  if ($domain == 'afcweb.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-7'); // UA-XXXXX-Y
  }
  if ($domain == 'unistrut.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-8'); // UA-XXXXX-Y
  }
  if ($domain == 'atc-mechanical.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-9'); // UA-XXXXX-Y
  }


define('POST_EXCERPT_LENGTH', 20);

/**
 * .main classes
 */
function roots_main_class() {
  if (roots_display_sidebar()) {
    // Classes on pages with the sidebar
    $class = 'col-xs-12 col-sm-9 col-md-9 col-lg-9 col-sm-push-3 col-md-push-3 col-lg-push-3';
  } else {
    // Classes on full width pages
    $class = 'col-lg-12';
  }

  return $class;
}

/**
 * .sidebar classes
 */
function roots_sidebar_class() {
  return 'col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-pull-9 col-md-pull-9 col-lg-pull-9';
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
    ),
    /**
     * Page template checks (via is_page_template())
     * Any of these page templates that return true won't show the sidebar
     */
    array(
      'template-custom.php',
    ),
    array(

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
