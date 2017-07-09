<?php

namespace App;

/**
 * WooCommerce Support
 */
add_filter('woocommerce_template_loader_files', function ($search_files, $default_file) {
    return filter_templates(array_merge($search_files, [$default_file, 'woocommerce']));
}, 100, 2);
add_filter('woocommerce_locate_template', function ($template, $template_name, $template_path) {
    $theme_template = locate_template("{$template_path}{$template_name}");
    return $theme_template ? template_path($theme_template) : $template;
}, 100, 3);
add_filter('wc_get_template_part', function ($template, $slug, $name) {
    $theme_template = locate_template(["woocommerce/{$slug}-{$name}", "woocommerce/${name}"]);
    return $theme_template ? template_path($theme_template) : $template;
}, 100, 3);
