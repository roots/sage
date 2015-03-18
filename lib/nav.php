<?php

namespace Roots\Sage\Nav;

use Roots\Sage\Utils;

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * SageNavWalker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
class NavWalker extends \Walker_Nav_Menu {
  private $cpt; // Boolean, is current post a custom post type
  private $archive; // Stores the archive page for current URL

  public function __construct() {
    add_filter('nav_menu_css_class', array($this, 'cssClasses'), 10, 2);
    add_filter('nav_menu_item_id', '__return_null');
    $cpt           = get_post_type();
    $this->cpt     = in_array($cpt, get_post_types(array('_builtin' => false)));
    $this->archive = get_post_type_archive_link($cpt);
  }

  public function checkCurrent($classes) {
    return preg_match('/(current[-_])|active|dropdown/', $classes);
  }

  // @codingStandardsIgnoreStart
  function start_lvl(&$output, $depth = 0, $args = []) {
    $output .= "\n<ul class=\"dropdown-menu\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
    $item_html = '';
    parent::start_el($item_html, $item, $depth, $args);

    if ($item->is_dropdown && ($depth === 0)) {
      $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
      $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
    } elseif (stristr($item_html, 'li class="divider')) {
      $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
    } elseif (stristr($item_html, 'li class="dropdown-header')) {
      $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
    }

    $item_html = apply_filters('sage/wp_nav_menu_item', $item_html);
    $output .= $item_html;
  }

  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
    $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

    if ($element->is_dropdown) {
      $element->classes[] = 'dropdown';

      foreach ($children_elements[$element->ID] as $child) {
        if ($child->current_item_parent || Utils\url_compare($this->archive, $child->url)) {
          $element->classes[] = 'active';
        }
      }
    }

    $element->is_active = strpos($this->archive, $element->url);

    if ($element->is_active) {
      $element->classes[] = 'active';
    }

    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
  // @codingStandardsIgnoreEnd

  public function cssClasses($classes, $item) {
    $slug = sanitize_title($item->title);

    if ($this->cpt) {
      $classes = str_replace('current_page_parent', '', $classes);

      if (Utils\url_compare($this->archive, $item->url)) {
        $classes[] = 'active';
      }
    }

    $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
    $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

    $classes[] = 'menu-' . $slug;

    $classes = array_unique($classes);

    return array_filter($classes, 'Roots\\Sage\\Utils\\is_element_empty');
  }
}

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Remove the id="" on nav menu items
 */
function nav_menu_args($args = '') {
  $nav_menu_args = [];
  $nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  if (!$args['depth']) {
    $nav_menu_args['depth'] = 2;
  }

  return array_merge($args, $nav_menu_args);
}
add_filter('wp_nav_menu_args', __NAMESPACE__ . '\\nav_menu_args');
add_filter('nav_menu_item_id', '__return_null');
