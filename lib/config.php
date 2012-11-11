<?php
/**
 * Shoestrap configuration
 */

// Enable theme features
add_theme_support('root-relative-urls');    // Enable relative URLs
add_theme_support('bootstrap-top-navbar');  // Enable Bootstrap's fixed navbar


/**
 * Define which pages shouldn't have the sidebar
 *
 * See lib/sidebar.php for more details
 */
function shoestrap_display_sidebar() {
  if ( get_theme_mod( 'shoestrap_sidebar_on_front' ) != 'show') {
    $sidebar_config = new Roots_Sidebar(
      array(
        'is_404',
        'is_front_page'
      ),
      array(
        'page-custom.php'
      )
    );
  } else {
    $sidebar_config = new Roots_Sidebar(
      array(
        'is_404',
      ),
      array(
        'page-custom.php'
      )
    );
  }

  return $sidebar_config->display;
}

// #main CSS classes
function shoestrap_main_class() {
  if (shoestrap_display_sidebar()) {
    $class = shoestrap_sidebar_class_calc( 'main' );
  } else {
    $class = 'span12';
  }

  return $class;
}

// #sidebar CSS classes
function shoestrap_sidebar_class( $sidebar = 'primary' ) {
  if ( $sidebar == 'secondary' ) {
    return shoestrap_sidebar_class_calc( 'secondary' );
  } else {
    return shoestrap_sidebar_class_calc( 'primary' );
  }
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
* This is not required or used by Shoestrap.
*/
if (!isset($content_width)) { $content_width = 940; }