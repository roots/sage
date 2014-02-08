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


if ( !function_exists( 'shoestrap_section_class' ) ) :
/*
 * Calculates the classes of the main area, main sidebar and secondary sidebar
 */
function shoestrap_section_class( $target, $echo = false ) {
	global $redux;
	// Disable the wrapper by default
	$wrapper = NULL;

	if ( shoestrap_display_primary_sidebar() ) {
		// Both sidebars are displayed
		if ( shoestrap_display_secondary_sidebar() ) {
			$main      = is_page_template( 'template-5.php' ) ? 'col-md-8' : 'col-md-7';
			$primary   = is_page_template( 'template-5.php' ) ? 'col-md-4' : 'col-md-3';
			$secondary = 'col-md-2';

			$wrapper = is_page_template( 'template-5.php' ) ? 'col-md-10 pull-right' : NULL;

		// Only the primary sidebar is displayed
		} else {
			$main    = 'col-md-8';
			$primary = 'col-md-4';
		}
	} else {
		// Only the secondary sidebar is displayed
		if ( shoestrap_display_secondary_sidebar() ) {
			$main      = 'col-md-8';
			$secondary = 'col-md-4';
		} else {
			// No sidebars displayed
			$main = 'col-md-12';
		}
	}

	// Add floats where needed.
	$main = ( is_page_template( 'template-2.php' ) || is_page_template( 'template-3.php' ) ) ? $main . ' pull-right' : $main;

	if ( $target == 'primary' )
		$class = apply_filters( 'shoestrap_section_class_primary', $primary );
	elseif ( $target == 'secondary' )
		$class = apply_filters( 'shoestrap_section_class_secondary', $secondary );
	elseif ( $target == 'wrapper' )
		$class = apply_filters( 'shoestrap_section_class_wrapper', $wrapper );
	else
		$class = apply_filters( 'shoestrap_section_class_main', $main );

	// echo or return the result.
	if ( $echo )
		echo $class;
	else
		return $class;
}
endif;


function shoestrap_mp_wrap_div_open() {
	echo '<div class="mp_wrap ' . shoestrap_section_class( 'wrapper' ) . '"><div class="row">';
}

function shoestrap_mp_wrap_div_close() {
	echo '</div></div>';
}

function shoestrap_mp_wrap_div_toggler() {
	$wrapper = shoestrap_section_class( 'wrapper' );
	if ( !is_null( $wrapper ) && !empty( $wrapper ) ) {
		add_action( 'shoestrap_pre_main', 'shoestrap_mp_wrap_div_open', 999 );
		add_action( 'shoestrap_post_main', 'shoestrap_mp_wrap_div_close', 999 );
	}
}
add_action( 'wp', 'shoestrap_mp_wrap_div_toggler' );

/**
 * $content_width is a global variable used by WordPress for max image upload sizes
 * and media embeds (in pixels).
 *
 * Example: If the content area is 640px wide, set $content_width = 620; so images and videos will not overflow.
 * Default: 1140px is the default Bootstrap container width.
 */
if (!isset($content_width)) { $content_width = 1140; }
