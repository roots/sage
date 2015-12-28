<?php namespace App;

use Roots\Sage\Template;
use Roots\Sage\Template\Wrapper;

/**
 * Determine which pages should NOT display the sidebar
 * @link https://codex.wordpress.org/Conditional_Tags
 */
add_filter('sage/display_sidebar', function ($display) {
  // The sidebar will NOT be displayed if ANY of the following return true
  return $display ? !in_array(true, [
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
  ]) : $display;
});

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Use theme wrapper
 */
add_filter('template_include', function ($main) {
  if (!is_string($main) || !(string) $main) {
    return $main;
  }
  return template_wrap(new Wrapper(basename($main)));
}, 109);
