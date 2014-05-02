<?php
/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.min.css
 *
 * Enqueue scripts in the following order:
 * 1. jquery-1.11.0.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr-2.7.0.min.js
 * 3. /theme/assets/js/main.min.js (in footer)
 */
function roots_scripts() {
  wp_enqueue_style('roots_main', get_template_directory_uri() . '/assets/css/main.min.css', false);


  // if (is_single() && comments_open() && get_option('thread_comments')) {wp_enqueue_script('comment-reply'); }

  wp_register_script('roots_scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', array(), '0fc6af96786d8f267c8686338a34cd38', true);
  wp_enqueue_script('roots_scripts');
}

add_action('wp_enqueue_scripts', 'roots_scripts');

