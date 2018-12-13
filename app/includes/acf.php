<?php

namespace App;

use WP_CLI;
use function env;

add_action('acf/init', function () {
    collect(glob(__DIR__ . '/acf-field-groups/*.php'))->map(function ($file_path) {
        return require_once $file_path;
    });
});

/**
 * Update ACF PRO license key automatically when using WP CLI search-replace command.
 */
if (defined('WP_CLI') && WP_CLI && env('acf_pro_license_key')) {
    WP_CLI::add_hook('after_invoke:search-replace', function () {
        if (function_exists('acf_pro_update_license')) {
            acf_pro_update_license(env('acf_pro_license_key'));
        }
    });
}

add_filter('acf/load_field/name=color', function ($field = []) {
    $field['choices'] = [
        'white' => esc_html__('White', 'sage'),
        'light' => esc_html__('Light Grey', 'sage'),
    ];

    return $field;
});

add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => esc_html__('Theme Settings', 'sage'),
            'menu_title' => esc_html__('Theme Settings', 'sage'),
            'menu_slug' => 'theme_options',
            'capability' => 'manage_options',
            'redirect' => false,
            'icon_url' => 'dashicons-welcome-widgets-menus',
            'position' => 27,
        ]);

        // acf_add_options_sub_page([
        //     'page_title' => esc_html__('Footer', 'sage'),
        //     'menu_title' => esc_html__('Footer', 'sage'),
        //     'capability' => 'manage_options',
        //     'menu_slug' => 'theme_options_footer',
        //     'parent' => 'theme_options',
        // ]);
    }
});

/**
 * Change select2 version
 */
add_filter('acf/settings/select2_version', function () {
    return 4;
});

/**
 * Default the target parameter of the ACF link field to the HTML default "_self".
 */
add_filter('acf/format_value/type=link', function ($value) {
    if (isset($value['target']) && empty($value['target'])) {
        $value['target'] = '_self';
    }

    return $value;
}, 20);

add_filter('acf/fields/google_map/api', function ($api) {
    if (env('GOOGLE_MAPS_API_KEY')) {
        $api['key'] = env('GOOGLE_MAPS_API_KEY');
    }

    return $api;
});

add_filter('sober/controller/acf/array', '__return_true');
