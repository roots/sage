<?php

global $ss_settings, $wp_customize;

global $ss_active_framework;
$ss_active_framework = array(
	'shortname' => 'bootstrap',
	'name'      => 'Bootstrap',
	'classname' => 'SS_Framework_Bootstrap',
	'compiler'  => 'less_php'
);

// Include the framework class
include_once( dirname( __FILE__ ) . '/class-SS_Framework_Bootstrap.php' );

if ( 'bootstrap' == SS_FRAMEWORK ) {
	define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

if ( isset( $wp_customize ) ) {
	include_once( SS_FRAMEWORK_PATH . '/includes/customizer.php' ); // Customizer mods
}
