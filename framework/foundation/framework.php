<?php

global $ss_settings;

if ( $ss_settings['framework'] == 'foundation' ) {
	define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );

	add_filter( 'comments_template', 'shoestrap_foundation_comments_template' );

	// Framework class.
	include_once( dirname( __FILE__ ) . '/class-Shoestrap_Foundation.php' );

	// Branding
	include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Colors.php' );
	// Typography
	include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Typography.php' );
	// Comments Walker
	include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Walker_Comment.php' );
	// Social
	// include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Social.php' );
	// layout
	include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Layout.php' );
	// Header
	include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Header.php' );
	// The menus module
	include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Menus.php' );
	// Widgets
	include_once( dirname( __FILE__ ) . '/modules/widgets.php' );
	// Specific classes for navbar
	include_once( dirname( __FILE__ ) . '/nav-foundation.php' );
}

/**
 * Define the framework.
 * These will be used in the redux admin option to choose a framework.
 */
function shoestrap_define_framework_foundation() {
	$framework = array(
		'shortname' => 'foundation',
		'name'      => 'Foundation',
		'classname' => 'Shoestrap_Foundation',
		'compiler'  => 'sass_php'
	);

	return $framework;
}

/**
 * Add the framework to redux
 */
function shoestrap_add_framework_foundation( $frameworks ) {
	$frameworks[] = shoestrap_define_framework_foundation();

	return $frameworks;
}
add_filter( 'shoestrap_frameworks_array', 'shoestrap_add_framework_foundation' );

function shoestrap_foundation_comments_template() {
	return dirname( __FILE__ ) . '/templates/comments.php';
}