<?php

global $ss_settings;

// Include the framework class
include_once( dirname( __FILE__ ) . '/class-SS_Framework_Bootstrap.php' );

if ( ! is_null( $ss_settings['framework'] ) && $ss_settings['framework'] == 'bootstrap' ) {
	define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

/**
 * Define the framework.
 * These will be used in the redux admin option to choose a framework.
 */
function shoestrap_define_framework_bootstrap() {
	$framework = array(
		'shortname' => 'bootstrap',
		'name'      => 'Bootstrap',
		'classname' => 'SS_Framework_Bootstrap',
		'compiler'  => 'less_php'
	);

	return $framework;
}

/**
 * Add the framework to redux
 */
function shoestrap_add_framework_bootstrap( $frameworks ) {
	$frameworks[] = shoestrap_define_framework_bootstrap();

	return $frameworks;
}
add_filter( 'shoestrap_frameworks_array', 'shoestrap_add_framework_bootstrap' );

include_once( SS_FRAMEWORK_PATH . '/includes/customizer.php' ); // Customizer mods
