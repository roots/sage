<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes)
{
    // Add page slug if it doesn't exist
    if (is_single() || is_page() && ! is_front_page()) {
        if ( ! in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    // Add class if sidebar is active
    if (Config\display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }


    return $classes;
}

add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more()
{
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}

add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');


// CUSTOM STUFF HERE

// load custom post types here
require_once('cpt.php');

// Remove Admin bar
add_filter('show_admin_bar', '__return_false');


// WOOCOMMERCE STUFF HERE

// Remove default WooCommerce styling
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Remove WooCommerce header
add_filter('woocommerce_show_page_title', '__return_false');
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

// Add WooCommerce theme support
add_theme_support('woocommerce');

add_filter('woocommerce_widget_cart_is_hidden', '__return_false');
//Show Mini Cart on Cart & Checkout pages
