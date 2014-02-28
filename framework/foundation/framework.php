<?php

global $ss_settings;

// Framework class.
include_once( dirname( __FILE__ ) . '/class-SS_Framework_Foundation.php' );

if ( ! is_null( $ss_settings['framework'] ) && $ss_settings['framework'] == 'foundation' ) {
	define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

/**
 * Define the framework.
 * These will be used in the redux admin option to choose a framework.
 */
function shoestrap_define_framework_foundation() {
	$framework = array(
		'shortname' => 'foundation',
		'name'      => 'Foundation',
		'classname' => 'SS_Framework_Foundation',
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

