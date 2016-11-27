<?php

namespace App;

/**
 * Replace %category_name% in URLs. with the first category term.
 * @example
 * blog/%category%/my-post -> blog/action/my-post
 */
add_filter('post_type_link', function($post_link, $post, $leavename, $sample) {
    if (preg_match('/%([^%]+)%/', $post_link, $matches)) {
        list($replace, $category) = $matches;
        $term = get_the_terms($post->ID, $category);
        if ($term && is_array($term)) {
            $slug = array_pop($term)->slug;
            $post_link = str_replace($replace, $slug, $post_link);
        }
    }
    return $post_link;
}, 10, 4);

/**
 * Theme assets in addition to sage/main.css and sage/main.js
 * @see src/setup.php
 */
add_action('wp_enqueue_scripts', function () {
    // wp_enqueue_style('font_css', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700,800', false, null);

    // Scripts which are loaded synchronously
    // wp_enqueue_script('before/js', asset_path('scripts/before.js'), [], false, true);
}, 100);

/**
 * Load scripts asynchronously
 */
add_filter('script_loader_tag', function ($tag, $handle) {
    $async_handles = [
        'sage/js',
    ];
    if (in_array($handle, $async_handles)) {
        return str_replace(' src', ' async="async" src', $tag);
    }
    return $tag;
}, 10, 2);

/**
 * Dequeue scripts
 */
add_action('wp_print_scripts', function () {
    // wp_dequeue_script('sage/main.js');
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Use timber as a templating system.
     * @link https://github.com/generoi/wp-timber-extended
     */
    add_theme_support('timber-extended-templates', [
        // Use double dashes as the template variation separator.
        'bem_templates',
    ]);
    // If a post parent is password protected, so are it's children.
    add_theme_support('timber-extended-password-inheritance');
    // Add additional twig functions and filters.
    add_theme_support('timber-extended-twig-extensions', ['core', 'contrib', 'functional']);

    /**
     * Register navigation menus in addition to `primary_navigation` defined by
     * sage.
     * @see src/setup.php
     */
    // register_nav_menus([
    //     'footer_navigation' => __('Footer Navigation', 'theme-admin')
    // ]);
}, 10);

/**
 * Remove Sage sidebars as we customize them.
 * @see src/setup.php
 */
remove_action('after_setup_theme', 'App\\widget_init');

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'theme-admin'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'theme-admin'),
        'id'            => 'sidebar-footer'
    ] + $config);
    register_sidebar([
        'name'          => __('Below Content', 'theme-admin'),
        'id'            => 'sidebar-content-below'
    ] + $config);
});
