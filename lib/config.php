<?php
/**
 * Roots configuration
 */

// Enable theme features
add_theme_support('root-relative-urls');    // Enable relative URLs
add_theme_support('rewrite-urls');          // Enable URL rewrites
add_theme_support('h5bp-htaccess');         // Enable HTML5 Boilerplate's .htaccess
add_theme_support('bootstrap-top-navbar');  // Enable Bootstrap's fixed navbar

// Define which pages shouldn't have the sidebar
function roots_sidebar() {
  if (is_404() || is_page_template('page-custom.php')) {
    return false;
  } else {
    return true;
  }
}

// #main CSS classes
function roots_main_class() {
  if (roots_sidebar()) {
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

// Set the content width based on the theme's design and stylesheet
if (!isset($content_width)) { $content_width = 940; }
