<?php
/**
 * Enable theme features
 */
add_theme_support('root-relative-urls');    // Enable relative URLs
add_theme_support('rewrites');              // Enable URL rewrites
add_theme_support('bootstrap-top-navbar');  // Enable Bootstrap's top navbar
add_theme_support('bootstrap-gallery');     // Enable Bootstrap's thumbnails component on [gallery]
add_theme_support('nice-search');           // Enable /?s= to /search/ redirect

/**
 * Configuration values
 */
<<<<<<< HEAD
define('GOOGLE_ANALYTICS_ID', ''); // UA-XXXXX-Y
define('POST_EXCERPT_LENGTH', 40); // length in words for excerpt_length filter (ref: http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length)
=======

  if ($domain == 'atkore.local' || $domain == 'www.atkore.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'atkore.com')  {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-1'); // UA-XXXXX-Y
  }
  if ($domain == 'atcfence.local' || $domain == 'www.atcfence.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'atcfence.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-3'); // UA-XXXXX-Y
  }
  if ($domain == 'easternwire.local' || $domain == 'www.easternwire.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'easternwire.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-2'); // UA-XXXXX-Y
  }
  if ($domain == 'kaf-tech.local' || $domain == 'www.kaftech.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'kaf-tech.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-4'); // UA-XXXXX-Y
  }
  if ($domain == 'alliedtube-sprinkler.local' || $domain == 'www.alliedtube-sprinkler.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'alliedtube-sprinkler.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-5'); // UA-XXXXX-Y
  }
  if ($domain == 'unistrutfallprotection.local' || $domain == 'www.unistrutfallprotection.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'unistrutfallprotection.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-6'); // UA-XXXXX-Y
  }
  if ($domain == 'afcweb.local' || $domain == 'www.afcweb.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'afcweb.com') {
    define('GOOGLE_ANALYTICS_ID', 'UA-39634549-7'); // UA-XXXXX-Y
  }

define('POST_EXCERPT_LENGTH', 25);
>>>>>>> master

/**
 * .main classes
 */
function roots_main_class() {
  if (roots_display_sidebar()) {
    // Classes on pages with the sidebar
<<<<<<< HEAD
    $class = 'col-sm-8';
=======
    $class = 'span9';
>>>>>>> master
  } else {
    // Classes on full width pages
    $class = 'col-sm-12';
  }

  return $class;
}

/**
 * .sidebar classes
 */
function roots_sidebar_class() {
<<<<<<< HEAD
  return 'col-sm-4';
=======
  return 'span3';
>>>>>>> master
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
<<<<<<< HEAD
      'template-custom.php'
=======
      'page-custom.php',
      'page-fullwidth.php',
      'page-resources.php',
      'page-library.php',
      'page-rep-locator.php'
>>>>>>> master
    )
  );

  return apply_filters('roots_display_sidebar', $sidebar_config->display);
}

/**
 * $content_width is a global variable used by WordPress for max image upload sizes
 * and media embeds (in pixels).
 *
 * Example: If the content area is 640px wide, set $content_width = 620; so images and videos will not overflow.
 * Default: 1140px is the default Bootstrap container width.
 */
if (!isset($content_width)) { $content_width = 1140; }

/**
 * Define helper constants
 */
$get_theme_name = explode('/themes/', get_template_directory());

define('RELATIVE_PLUGIN_PATH',  str_replace(home_url() . '/', '', plugins_url()));
define('RELATIVE_CONTENT_PATH', str_replace(home_url() . '/', '', content_url()));
define('THEME_NAME',            next($get_theme_name));
define('THEME_PATH',            RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);
