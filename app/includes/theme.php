<?php

namespace App;

add_action('after_setup_theme', function () {
    load_theme_textdomain('sage', get_template_directory() . '/lang');

    // add_image_size('hero', 1400);
});

add_filter('wp_get_attachment_image_attributes', function ($attr) {
    if (false === strpos($attr['class'], 'lazyload')) {
        return $attr;
    }

    $attr['data-src'] = $attr['src'];
    unset($attr['src']);

    if (! empty($attr['srcset'])) {
        $attr['data-srcset'] = $attr['srcset'];
        unset($attr['srcset']);
    }

    if (! empty($attr['sizes'])) {
        $attr['data-sizes'] = $attr['sizes'];
        unset($attr['sizes']);
    }

    return $attr;
});

/**
 * Unregisters all registered sidebars.
 * Uncomment this if you need sidebars.
 */
add_action('register_sidebar', function ($sidebar) {
    unregister_sidebar($sidebar['id']);

    _remove_theme_support('widgets');
}, 100);
