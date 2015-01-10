<?php
/**
 * Clean up the_excerpt()
 */
function sage_excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', 'sage_excerpt_more');
