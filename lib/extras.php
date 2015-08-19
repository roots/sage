<?php

namespace Ensoul\Shaba\Extras;

use Ensoul\Shaba\Config;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $url = get_permalink();
      $path = explode("?",$url);
      $slug = basename($path[0]);
      $classes[] = $slug;
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
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'shaba') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');
