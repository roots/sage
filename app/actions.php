<?php

namespace App;

/**
 * Theme Options Section
 */
add_action('acf/init', function () {
  if (function_exists('acf_add_options_sub_page')) {
    acf_add_options_sub_page([
      'page_title'   => 'Theme Settings',
      'menu_title'  => 'Theme Settings',
      'parent_slug'  => 'themes.php',
    ]);
  }
});
