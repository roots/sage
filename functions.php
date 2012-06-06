<?php
/**
 * Roots functions
 */

if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

locate_template('/inc/util.php', true, true);            // Utility functions
locate_template('/inc/config.php', true, true);          // Configuration and constants
locate_template('/inc/activation.php', true, true);      // Theme activation
locate_template('/inc/template-tags.php', true, true);   // Template tags
locate_template('/inc/cleanup.php', true, true);         // Cleanup
locate_template('/inc/scripts.php', true, true);         // Scripts and stylesheets
locate_template('/inc/htaccess.php', true, true);        // Rewrites for assets, H5BP .htaccess
locate_template('/inc/hooks.php', true, true);           // Hooks
locate_template('/inc/actions.php', true, true);         // Actions
locate_template('/inc/widgets.php', true, true);         // Sidebars and widgets
locate_template('/inc/custom.php', true, true);          // Custom functions

function roots_setup() {

  // Make theme available for translation
  load_theme_textdomain('roots', get_template_directory() . '/lang');

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
    'primary_navigation' => __('Primary Navigation', 'roots'),
  ));

  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  // set_post_thumbnail_size(150, 150, false);
  // add_image_size('category-thumb', 300, 9999); // 300px wide (and unlimited height)

  // Add post formats (http://codex.wordpress.org/Post_Formats)
  // add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('css/editor-style.css');

}

add_action('after_setup_theme', 'roots_setup');
