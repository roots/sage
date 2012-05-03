<?php

function roots_scripts() {
  // Not included by default since Bootstrap's reset supersedes h5bp's. Include if you aren't using Bootstrap. 
  //wp_enqueue_style('roots_style', get_template_directory_uri() . '/css/style.css', false, null);
  wp_enqueue_style('roots_bootstrap_style', get_template_directory_uri() . '/css/bootstrap.css', false, null);

  if (BOOTSTRAP_RESPONSIVE) {
    wp_enqueue_style('roots_bootstrap_responsive_style', get_template_directory_uri() . '/css/bootstrap-responsive.css', array('roots_bootstrap_style'), null);
  }

  wp_enqueue_style('roots_app_style', get_template_directory_uri() . '/css/app.css', false, null);

  if (is_child_theme()) {
    wp_enqueue_style('roots_child_style', get_stylesheet_uri());
  }

  if (!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '', '', '', false);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('roots_plugins', get_template_directory_uri() . '/js/plugins.js', false, null, true);
  wp_register_script('roots_main', get_template_directory_uri() . '/js/main.js', false, null, true);
  wp_enqueue_script('roots_plugins');
  wp_enqueue_script('roots_main');
}

add_action('wp_enqueue_scripts', 'roots_scripts', 100);
