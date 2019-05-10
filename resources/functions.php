<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

/**
 * Helper function for prettying up errors
 *
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7.1.3', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7.1.3 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (! file_exists($composer = __DIR__ . '/../vendor/autoload.php')) {
    $sage_error(
        __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
        __('Autoloader not found.', 'sage')
    );
}
require_once $composer;

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (! locate_template($file, true, true)) {
        $sage_error(
            sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file),
            __('File not found', 'sage')
        );
    }
}, ['helpers', 'setup', 'filters', 'admin']);

/**
 * Enable sage features
 * @link https://roots.io/acorn/
 */
add_theme_support('sage');
Roots\bootloader();
