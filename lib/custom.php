<?php
/**
 * Custom functions
 */

/**
 * Dump the global variable wp_filter to debug action and filter queues
 * 
 * @return void
 */
function dump_wp_filter() {
	global $wp_filter;
	global $wp_styles;

		print "<pre>";
		print_r ($wp_filter);
		print "</pre>";	
}
add_shortcode( 'filter_dump', 'dump_wp_filter' );

/**
 * Dequeue all BuddyPress-related stylesheets
 * 
 * @since 1.0.0
 */
function dequeue_buddypress_styles() {
	if (current_theme_supports( 'dequeue-bp-styles' )) {
		wp_dequeue_style( 'bp-parent-css' );
		wp_deregister_style( 'bp-parent-css' );
		wp_dequeue_style( 'bp-legacy-css' );
		wp_deregister_style( 'bp-legacy-css' );
	}
}
add_action( 'wp_enqueue_scripts', 'dequeue_buddypress_styles', 12 );