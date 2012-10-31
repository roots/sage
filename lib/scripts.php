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
  wp_enqueue_style('roots_main', get_template_directory_uri() . '/assets/css/main.min.css', false, 'f60c3bb06fe0e1fcf0f63cd76a4ec7c9');

  if (!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '', '', '1.8.2', true);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('roots_scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', false, 'f7de8c348d616069f4e6132e96518067', true);
  wp_enqueue_script('roots_scripts');
}

add_action('wp_enqueue_scripts', 'roots_scripts', 100);
