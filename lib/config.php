<?php

namespace Ensoul\Shaba\Config;

use Ensoul\Shaba\ConditionalTagCheck;

/**
 * Enable theme features
 */
add_theme_support('soil-clean-up');                               // Enable clean up from Soil
add_theme_support('soil-nav-walker');                             // Enable cleaner nav walker from Soil
add_theme_support('soil-relative-urls');                          // Enable relative URLs from Soil
add_theme_support('soil-nice-search');                            // Enable nice search from Soil
add_theme_support('soil-jquery-cdn');                             // Enable to load jQuery from the Google CDN
add_theme_support('soil-disable-trackbacks');                     // Remove trackback/pingback functionality
add_theme_support('soil-disable-asset-versioning');               // Disable ver query string from all styles and scripts
add_theme_support('rankz-init');                                  // Rankz first setup
add_theme_support('rankz-clean-up');                              // Enable clean up from Rankz
add_theme_support('rankz-google-analytics', 'UA-XXXXX-Y');        // Enable H5BP's Google Analytics snippet
add_theme_support('rankz-disable-comments');                      // Disable comments
add_theme_support('rankz-disable-widgets');                       // Disable widgets
add_theme_support('rankz-remove-default-image-sizes');            // Remove default WordPress image sizes
add_theme_support('rankz-admin-login', 'ensoul.it', '#E41B44');   // Customize admin login page, remember to change login-logo.png in assets/images

/**
 * Configuration values
 */
if (!defined('WP_ENV')) {
  // Fallback if WP_ENV isn't defined in your WordPress config
  // Used in lib/assets.php to check for 'development' or 'production'
  define('WP_ENV', 'production');
}

if (!defined('DIST_DIR')) {
  // Path to the build directory for front-end assets
  define('DIST_DIR', '/dist/');
}

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  return false; // Disables the sidebar on whole website
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}
