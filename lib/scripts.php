<?php
/**
 * Scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.min.css
 *
 * Enqueue scripts in the following order:
 * 1. /theme/assets/js/vendor/modernizr-2.6.2.min.js  (in head.php)
 * 2. jquery-1.8.2.min.js via Google CDN              (in head.php)
 * 3. /theme/assets/js/scripts.min.js
 */

function roots_scripts() {
  wp_enqueue_style('roots_main', get_template_directory_uri() . '/assets/css/main.min.css', false, '276c177e19c5e182a0258a0b5ec46d53');

  if (!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '', '', '1.8.2', true);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('roots_scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', false, '451f9387735e7c266d1fe20c1c58e94a', true);
  wp_enqueue_script('roots_scripts');
}

add_action('wp_enqueue_scripts', 'roots_scripts', 100);
