<?php

namespace App;


/**
* Register navigation menus
* @link https://developer.wordpress.org/reference/functions/register_nav_menus/
*/
register_nav_menus([
	'primary_navigation' => __('Primary Navigation', 'sage')
]);

/**
* Define menus
*/
function primary_navigation() {
	wp_nav_menu([
		'container' => false,
		'menu' => __( 'Primary Navigation', 'sage' ),
		'menu_class' => 'menu horizontal',
		'theme_location' => 'primary_navigation',
		'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'link_before' => '<span class="link-wrap">',
		'link_after' => '</span>',
		'depth' => 5,
		'fallback_cb' => false,
		'walker' => new Topbar_Menu_Walker(),
	]);
}

/**
* Menu walker to indent submenu ul elements for 
* Foundation styles
* @link https://github.com/brettsmason
*/
class Topbar_Menu_Walker extends \Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = Array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"vertical menu\">\n";
	}
}

/**
* Add Foundation active class to menu
*/
add_filter( 'nav_menu_css_class', function($classes, $item){
	if ( $item->current == 1 || $item->current_item_ancestor == true ) {
		$classes[] = 'active';
	}
	return $classes;
}, 10, 2 );