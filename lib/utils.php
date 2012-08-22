<?php

/**
 * Theme Wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

function roots_template_path() {
  return Roots_Wrapping::$main_template;
}

class Roots_Wrapping {

  // Stores the full path to the main template file
  static $main_template;

  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  static $base;

  static function wrap($template) {
    self::$main_template = $template;

    self::$base = substr(basename(self::$main_template), 0, -4);

    if ('index' == self::$base) {
      self::$base = false;
    }

    $templates = array('base.php');

    if (self::$base) {
      array_unshift($templates, sprintf('base-%s.php', self::$base ));
    }

    return locate_template($templates);
  }
}

add_filter('template_include', array('Roots_Wrapping', 'wrap'), 99);

// returns WordPress subdirectory if applicable
function wp_base_dir() {
  preg_match('!(https?://[^/|"]+)([^"]+)?!', site_url(), $matches);
  if (count($matches) === 3) {
    return end($matches);
  } else {
    return '';
  }
}

// opposite of built in WP functions for trailing slashes
function leadingslashit($string) {
  return '/' . unleadingslashit($string);
}

function unleadingslashit($string) {
  return ltrim($string, '/');
}

function add_filters($tags, $function) {
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}
