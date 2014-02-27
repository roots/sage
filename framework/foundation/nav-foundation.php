<?php

// Add "not-click has-dropdown" CSS classes to navigation menu items that have children in a submenu.
function shoestrap_parent_classes( $classes, $item ) {
	global $wpdb;

	$has_children = $wpdb->get_var( "SELECT COUNT(meta_id) FROM {$wpdb->prefix}postmeta WHERE meta_key='_menu_item_menu_item_parent' AND meta_value='" . $item->ID . "'" );

	if ( $has_children > 0 ) {
		array_push( $classes, 'not-click has-dropdown' );
	}

	return $classes;

}
add_filter( 'nav_menu_css_class', 'shoestrap_parent_classes', 10, 2 );

// Deletes empty classes and changes the sub menu class name
function shoestrap_submenu_classes( $menu ) {
	$menu = preg_replace('/ class="sub-menu"/',' class="dropdown"',$menu);
	return $menu;
}
add_filter ( 'wp_nav_menu', 'shoestrap_submenu_classes' );

// Use the active class of the ZURB Foundation for the current menu item
function shoestrap_active_class( $classes, $item ) {
	if ( $item->current == 1 || $item->current_item_ancestor == true ) {
		$classes[] = 'active';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'shoestrap_active_class', 10, 2 );