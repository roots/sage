<?php

/**
 * Theme filters.
 */

namespace App;


add_filter('facetwp_template_use_archive', '__return_true');

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
  return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});
