<?php

/**
 * Theme filters.
 *
 * @copyright https://roots.io/ Roots
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});
