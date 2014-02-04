<?php
/*
 * Class Name: Shoestrap_Navlist_Walker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager. 
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Roots_Nav_Walker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
class Shoestrap_Navlist_Walker extends Walker_Nav_Menu {
	function check_current($classes) {
		return preg_match('/(current[-_])|active/', $classes);
	}

	function start_lvl(&$output, $depth = 2, $args = array()) {
		$output .= "\n<ul class=\"nav nav-sublist\">\n";
	}

	function start_el(&$output, $item, $depth = 2, $args = array(), $id = 0) {
		$item_html = '';
		parent::start_el($item_html, $item, $depth, $args);

		$item_html = apply_filters('roots_wp_nav_menu_item', $item_html);
		$output .= $item_html;
	}

	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		$element->has_children = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

		if ($element->has_children) {
			$element->classes[] = 'parent';
		}

		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}

}

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function shoestrap_nav_menu_css_class($classes, $item) {
	$slug = sanitize_title($item->title);
	$classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
	$classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

	$classes[] = 'menu-' . $slug;

	$classes = array_unique($classes);

	return array_filter($classes, 'is_element_empty');
}
add_filter('nav_menu_css_class', 'roots_nav_menu_css_class', 10, 2);
add_filter('nav_menu_item_id', '__return_null');

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Roots_Nav_Walker() by default
 */
function shoestrap_nav_menu_args($args = '') {
	$roots_nav_menu_args['container'] = false;

	if (!$args['items_wrap']) {
		$roots_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
	}

	if (current_theme_supports('bootstrap-top-navbar') && !$args['depth']) {
		$roots_nav_menu_args['depth'] = 3;
	}

	if (!$args['walker']) {
		$roots_nav_menu_args['walker'] = new Roots_Nav_Walker();
	}

	return array_merge($args, $roots_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'roots_nav_menu_args');
