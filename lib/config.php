<?php
/**
 * Roots configuration
 */

// Enable theme features
add_theme_support('root-relative-urls');    // Enable relative URLs
add_theme_support('rewrite-urls');          // Enable URL rewrites
add_theme_support('h5bp-htaccess');         // Enable HTML5 Boilerplate's .htaccess
add_theme_support('bootstrap-top-navbar');  // Enable Bootstrap's fixed navbar


/**
 * Define which pages shouldn't have the sidebar
 *
 * See lib/sidebar.php for more details
 */
function roots_display_sidebar() {
  $exclude = new Roots_Sidebar(
    /**
     * Conditionals tag checks (http://codex.wordpress.org/Conditional_Tags)
     * Any of these conditional tags that return true won't show the sidebar
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
      'page-custom.php'
    )
  );

  return $exclude->display;
}

// #main CSS classes
function roots_main_class() {
  if (roots_display_sidebar()) {
    echo 'span8';
  } else {
    echo 'span12';
  }
}

// #sidebar CSS classes
function roots_sidebar_class() {
  echo 'span4';
}

// Configuration values
define('GOOGLE_ANALYTICS_ID', ''); // UA-XXXXX-Y
define('POST_EXCERPT_LENGTH', 40);

/**
* $content_width is a global variable used by WordPress for max image upload sizes and media embeds (in pixels)
*
* Example: If the content area is 640px wide, set $content_width = 620; so images and videos will not overflow.
*
* Default: 940px is the default Bootstrap container width.
*
* This is not required or used by Roots.
*/
if (!isset($content_width)) { $content_width = 940; }
