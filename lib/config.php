<?php
/**
 * Enable theme features
 */
add_theme_support( 'post-thumbnails' );
if ( shoestrap_getVariable( 'root_relative_urls' ) == 1  )
	add_theme_support( 'root-relative-urls' );    // Enable relative URLs

add_theme_support( 'bootstrap-top-navbar' );  // Enable Bootstrap's top navbar
add_theme_support( 'bootstrap-gallery' );     // Enable Bootstrap's thumbnails component on [gallery]

if ( shoestrap_getVariable( 'nice_search' ) == 1 )
	add_theme_support( 'nice-search' );           // Enable /?s= to /search/ redirect

add_theme_support( 'jquery-cdn' );            // Enable to load jQuery from the Google CDN

/**
 * Configuration values
 */
define( 'GOOGLE_ANALYTICS_ID', shoestrap_getVariable( 'analytics_id' ) ); // UA-XXXXX-Y (Note: Universal Analytics only, not Classic Analytics)
define( 'POST_EXCERPT_LENGTH', shoestrap_getVariable( 'post_excerpt_length' ) ); // Length in words for excerpt_length filter (http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length)

/**
 * .main classes
 */
function shoestrap_main_class() {
	if ( shoestrap_display_primary_sidebar() ) {
		// Classes on pages with the sidebar
		$class = 'col-sm-8';
	} else {
		// Classes on full width pages
		$class = 'col-sm-12';
	}

	return $class;
}

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
