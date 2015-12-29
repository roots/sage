<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

/**
 * Here's what's happening with these hooks:
 * 1. WordPress detects theme in themes/sage
 * 2. When we activate, we tell WordPress that the theme is actually in themes/sage/templates
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage
 *
 * We do this so that the Template Hierarchy will look in themes/sage/templates for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage
 *
 * themes/sage/index.php also contains some self-correcting code, just in case the template option gets reset
 */
add_filter('stylesheet', function ($stylesheet) {
  return dirname($stylesheet);
});
add_action('after_switch_theme', function () {
  $stylesheet = get_option('stylesheet');
  basename($stylesheet) == 'templates' || update_option('stylesheet', $stylesheet . '/templates');
});

/**
 * Require composer autoloader
 */
require_once __DIR__ . '/vendor/autoload.php';
