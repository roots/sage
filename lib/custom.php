<?php
/**
 * Custom functions
 */


// Adds flex video container around oembed embeds
add_filter('embed_oembed_html', 'embed_oembed', 99, 4);
function embed_oembed($html, $url, $attr, $post_id) {
  return '<div class="flex-video">' . $html . '</div>';
}