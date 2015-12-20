<?php namespace App;

/**
 * Add postMessage support
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
  $wp_customize->get_setting('blogname')->transport = 'postMessage';
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
  wp_enqueue_script('sage/customizer', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});
