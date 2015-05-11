<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Config;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
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
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

/**
 * Make tiled galleries wider
 */
if ( ! isset( $content_width ) )
  $content_width = 1060;

/**
 * Add class to next and previous post links
 */

function previous_post_link_attributes($output)
{
  return str_replace('<a', '<a class="change-day prev"', $output);
}

function next_post_link_attributes($output)
{
  return str_replace('<a', '<a class="change-day next"', $output);
}

add_filter('previous_post_link', __NAMESPACE__ . '\\previous_post_link_attributes');
add_filter('next_post_link', __NAMESPACE__ . '\\next_post_link_attributes');
