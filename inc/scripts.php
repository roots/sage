<?php
/**
 * Scripts and stylesheets
 */

function roots_scripts() {
  wp_enqueue_style('roots_bootstrap', get_template_directory_uri() . '/css/bootstrap.css', false, null);

  if (current_theme_supports('bootstrap-responsive')) {
    wp_enqueue_style('roots_bootstrap_responsive', get_template_directory_uri() . '/css/bootstrap-responsive.css', array('roots_bootstrap'), null);
  }

  // If you're not using Bootstrap, include H5BP's main.css:
  // wp_enqueue_style('roots_h5bp', '/css/main.css', false, null);

  wp_enqueue_style('roots_app', get_template_directory_uri() . '/css/app.css', false, null);

  // Load style.css from child theme
  if (is_child_theme()) {
    wp_enqueue_style('roots_child', get_stylesheet_uri(), false, null);
  }

  if (!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '', '', '', false);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('roots_plugins', get_template_directory_uri() . '/js/plugins.js', false, null, false);
  wp_register_script('roots_main', get_template_directory_uri() . '/js/main.js', false, null, false);
  wp_enqueue_script('roots_plugins');
  wp_enqueue_script('roots_main');
}

add_action('wp_enqueue_scripts', 'roots_scripts', 100);
