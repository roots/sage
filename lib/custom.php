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
		print_r ($wp_styles);
		print "</pre>";	
}
add_shortcode( 'filter_dump', 'dump_wp_filter' );

function override_buddypress_styles() {
	if (current_theme_supports( 'override_bp_styles' )) {
		wp_dequeue_style( 'bp-parent-css' );
		wp_deregister_style( 'bp-parent-css' );
		wp_dequeue_style( 'bp-legacy-css' );
		wp_deregister_style( 'bp-legacy-css' );
	}
}
add_action( 'wp_enqueue_scripts', 'override_buddypress_styles', 12 );
