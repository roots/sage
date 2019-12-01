<?php

/**
 * Theme filters.
 *
 * @copyright https://roots.io/ Roots
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Minimize inserter interactions.
 * @link https://developer.wordpress.org/block-editor/developers/filters/block-filters/#managing-block-categories
 */
add_filter( 'block_categories', function ($categories, $post) {
    return [[
        'slug'  => 'blocks',
        'title' => __('Blocks', 'sage'),
        'icon'  => '',
    ]];
}, 10, 2);
