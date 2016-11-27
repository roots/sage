<?php

namespace App;

use Genero\Sage\Hero;

/**
 * Use PostTypeConnection which adds a repeater field to the ACF Options page,
 * that connects post types to pages. This can be used to detect parent menu
 * items using the PostTypeConnection\Menu class.
 * @note https://github.com/generoi/acf-post-type-chooser is a dependency.
 */
add_filter('acf/init', ['Genero\Sage\PostTypeConnection', 'addAcfFieldgroup']);

/**
 * A banner field group available on posts and terms which can be used to
 * display slideshows in the header.
 */
add_filter('acf/init', ['Genero\Sage\Hero', 'addAcfFieldgroup']);

/**
 * Activate ACF Option Page.
 */
add_filter('acf/init', 'acf_add_options_page');
add_filter('acf/init', ['Genero\Sage\OptionsPage', 'addAcfFieldgroup']);
add_filter('timber/context', function ($context) {
    if (function_exists('get_fields')) {
        $context['options'] = get_fields('option');
    }
    return $context;
});

/**
 * Add foundation column classes for ACF fields named `grid_column`.
 */
add_filter('acf/load_field/name=grid_column', function ($field) {
    $field['choices'] = [
        'small-6', 'small-12',
        'medium-4', 'medium-6', 'medium-7', 'medium-8', 'medium-9', 'medium-10', 'medium-12',
        'large-4', 'large-5', 'large-6', 'large-7', 'large-8', 'large-9', 'large-10', 'large-12',
    ];
    $field['choices'] = array_combine($field['choices'], $field['choices']);
    return $field;
});

/**
 * Define custom sorting option for post listing ACF field groups.
 */
add_filter('acf/load_field/name=post_order', function ($field) {
    $field['choices'] = [
        'desc' => 'Newest first',
        'asc' => 'Oldest first',
    ];
    return $field;
});
