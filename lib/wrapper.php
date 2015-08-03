<?php

namespace Ensoul\Shaba\Wrapper;

/**
 * Theme wrapper
 *
 * @link https://roots.io/sage/docs/theme-wrapper/
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

function template_path() {
  return ShabaWrapping::$main_template;
}

function sidebar_path() {
  return new ShabaWrapping('templates/sidebar.php');
}

class ShabaWrapping {
  // Stores the full path to the main template file
  public static $main_template;

  // Basename of template file
  public $slug;

  // Array of templates
  public $templates;

  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  public static $base;

  public function __construct($template = 'base.php') {
    $this->slug = basename($template, '.php');
    $this->templates = [$template];

    if (self::$base) {
      $str = substr($template, 0, -4);
      array_unshift($this->templates, sprintf($str . '-%s.php', self::$base));
    }
  }

  public function __toString() {
    $this->templates = apply_filters('shaba/wrap_' . $this->slug, $this->templates);
    return locate_template($this->templates);
  }

  public static function wrap($main) {
    // Check for other filters returning null
    if (!is_string($main)) {
      return $main;
    }

    self::$main_template = $main;
    self::$base = basename(self::$main_template, '.php');

    if (self::$base === 'index') {
      self::$base = false;
    }

    return new ShabaWrapping();
  }
}
add_filter('template_include', [__NAMESPACE__ . '\\ShabaWrapping', 'wrap'], 99);
