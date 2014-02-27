<?php

global $ss_settings;

if ( $ss_settings['framework'] == 'bootstrap' ) {
	define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );

	include_once( dirname( __FILE__ ) . '/class-Shoestrap_Bootstrap.php' );            // Framework class.

	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Advanced.php' );     // Advanced
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Background.php' );   // Background
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Branding.php' );     // Branding
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Header.php' );       // Header
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Typography.php' );   // Typography
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Footer.php' );       // Footer
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Social.php' );       // Social
	include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Layout.php' );       // layout
	include_once( dirname( __FILE__ ) . '/modules/widgets.php' );                      // Widgets

	include_once( dirname( __FILE__ ) . '/menus/nav.php' );                            // NavWalker
	include_once( dirname( __FILE__ ) . '/gallery.php' );                              // Custom [gallery] modifications
	include_once( dirname( __FILE__ ) . '/menus/class-Shoestrap_Menus.php' );          // The menus module
	include_once( dirname( __FILE__ ) . '/jumbotron/class-Shoestrap_Jumbotron.php' );  // The Jumbotron module
}

/**
 * Define the framework.
 * These will be used in the redux admin option to choose a framework.
 */
function shoestrap_define_framework_bootstrap() {
	$framework = array(
		'shortname' => 'bootstrap',
		'name'      => 'Bootstrap',
		'classname' => 'Shoestrap_Bootstrap',
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

if ( $ss_settings['retina_toggle'] ) {
	add_theme_support( 'retina' );
}