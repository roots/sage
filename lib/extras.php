<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

/**
 * Manage output of wp_title()
 */
function roots_wp_title($title) {
  if (is_feed()) {
    return $title;
  }

  $title .= get_bloginfo('name');

  return $title;
}
add_filter('wp_title', 'roots_wp_title', 10);

/**
 * Manage responsive Bootstrap embeds
 */
function roots_embed_wrap($html, $url, $attr) {
  return "<div class=\"embed-responsive embed-responsive-16by9\">" . $html . "</div>";
}
add_filter('embed_oembed_html', 'roots_embed_wrap', 10, 3);
