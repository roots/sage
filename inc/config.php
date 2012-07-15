<?php
/**
 * Roots configuration and constants
 */

add_theme_support('root-relative-urls');    // Enable relative URLs
add_theme_support('rewrite-urls');          // Enable URL rewrites
add_theme_support('h5bp-htaccess');         // Enable HTML5 Boilerplate's .htaccess
add_theme_support('bootstrap-responsive');  // Enable Bootstrap's responsive CSS
add_theme_support('bootstrap-top-navbar');  // Enable Bootstrap's fixed navbar

// Set the content width based on the theme's design and stylesheet
if (!isset($content_width)) { $content_width = 940; }

define('POST_EXCERPT_LENGTH',       40);
define('WRAP_CLASSES',              'container');
define('CONTAINER_CLASSES',         'row');
define('MAIN_CLASSES',              'span8');
define('SIDEBAR_CLASSES',           'span4');
define('FULLWIDTH_CLASSES',         'span12');
define('GOOGLE_ANALYTICS_ID',       '');        // UA-XXXXX-Y

define('WP_BASE',                   wp_base_dir());
define('THEME_NAME',                next(explode('/themes/', get_template_directory())));
define('RELATIVE_PLUGIN_PATH',      str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH',     str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH',                RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);
