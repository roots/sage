<?php

/**
 * Define which pages shouldn't have the primary sidebar
 *
 * See lib/sidebar.php for more details
 */
function shoestrap_display_primary_sidebar() {
	$sidebar_config = new Shoestrap_Sidebar(
		array(
			'is_404',
			'is_front_page'
		),
		array(
			'template-0.php'
		)
	);

	return apply_filters( 'shoestrap_display_primary_sidebar', $sidebar_config->display );
}

/**
 * Define which pages shouldn't have the secondary sidebar
 *
 * See lib/sidebar.php for more details
 */
function shoestrap_display_secondary_sidebar() {
	$sidebar_config = new Shoestrap_Sidebar(
		array(
			'is_404',
			'is_front_page'
		),
		array(
			'template-0.php',
			'template-1.php',
			'template-2.php'
		)
	);

	return apply_filters( 'shoestrap_display_secondary_sidebar', $sidebar_config->display );
}

/**
 * $content_width is a global variable used by WordPress for max image upload sizes
 * and media embeds (in pixels).
 *
 * Example: If the content area is 640px wide, set $content_width = 620; so images and videos will not overflow.
 * Default: 1140px is the default Bootstrap container width.
 */
if (!isset($content_width)) { $content_width = 1140; }
