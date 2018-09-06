<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add a global class to everything.
     *  We want it to come first, so stuff its filter does can be overridden.
     */
    array_unshift($classes, 'app');

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    if ($template) {
        echo template($template, collect_template_data($template));
        return get_stylesheet_directory() . '/index.php';
    }
    return $template;
}, PHP_INT_MAX);

/**
 * Render comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );

    $theme_template = locate_template(["views/{$comments_template}", $comments_template]);

    if ($theme_template) {
        echo template($theme_template, collect_template_data($comments_template));
        return get_stylesheet_directory() . '/index.php';
    }

    return $comments_template;
}, 100);

/**
 * Render WordPress searchform using Blade
 */
add_filter('get_search_form', function () {
    return template('partials.searchform', collect_template_data('/partials/searchform.blade.php'));
});

/**
 * Collect data for searchform.
 */
add_filter('sage/template/app/data', function ($data) {
    return $data + [
        'sf_action' => esc_url(home_url('/')),
        'sf_screen_reader_text' => _x('Search for:', 'label', 'sage'),
        'sf_placeholder' => esc_attr_x('Search &hellip;', 'placeholder', 'sage'),
        'sf_current_query' => get_search_query(),
        'sf_submit_text' => esc_attr_x('Search', 'submit button', 'sage'),
    ];
});
