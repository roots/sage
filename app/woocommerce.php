<?php

namespace App;

if(defined('WC_ABSPATH')) {
    add_action('after_setup_theme', function () {
        add_theme_support('woocommerce');
    });

    add_filter('template_include', function ($template) {
        if(strpos($template, WC_ABSPATH) === 0) {
            $template = locate_template('woocommerce/' . str_replace(WC_ABSPATH . 'templates/', '', $template)) ?: $template;
        }
        return $template;
    }, 100, 1);

    add_filter('wc_get_template_part', function ($template, $slug, $name) {
        $theme_template = locate_template([
            'woocommerce/' . str_replace(WC_ABSPATH . 'templates/', '', $template),
            'woocommerce/' . $slug . '-' . $name,
            'woocommerce/' . $name,
        ]);
        return $theme_template ? template_path($theme_template) : $template;
    }, 100, 3);

    add_filter('wc_get_template', function ($template, $template_name, $args) {
        $theme_template = locate_template('woocommerce/' . $template_name);
        return $theme_template ? template_path($theme_template, $args) : $template;
    }, 100, 3);
}
