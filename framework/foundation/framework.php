<?php

global $ss_settings;

if ( $ss_settings['framework'] == 'foundation' ) {
	define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );

	include_once( dirname( __FILE__ ) . '/class-Shoestrap_Foundation.php' );               // Framework class.

	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Branding.php' );         // Branding
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Typography.php' );       // Typography
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Social.php' );           // Social
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Layout.php' );           // layout
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Foundation_Menus.php' ); // The menus module
	include_once( dirname( __FILE__ ) . '/modules/widgets.php' );                          // Widgets
	include_once( dirname( __FILE__ ) . '/nav-foundation.php' );                           // Specific classes for navbar
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