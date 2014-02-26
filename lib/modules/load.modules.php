<?php
/*
Plugin Name: Shoestrap Admin
Plugin URI: http://wpmu.io
Description: Add advanced controls to the Shoestrap theme
Version: 0.9
Author: Aristeides Stathopoulos
Author URI:  http://aristeides.com
GitHub Plugin URI: https://github.com/shoestrap/shoestrap-admin
*/

if ( !defined( 'SHOESTRAP_MODULES_PATH' ) ) {
	define( 'SHOESTRAP_MODULES_PATH', dirname( __FILE__ ) );
}

if ( !defined( 'SHOESTRAP_MODULES_URL' ) ) {
	define( 'SHOESTRAP_MODULES_URL', get_template_directory_uri() . '/lib/modules' );
}

/*
 * Use 'RecursiveDirectoryIterator' if PHP Version >= 5.2.11
 */
function shoestrap_include_modules() {
	// Include all modules from the shoestrap theme (NOT the child themes)
	$modules_path = new RecursiveDirectoryIterator( SHOESTRAP_MODULES_PATH . '/' );
	$recIterator  = new RecursiveIteratorIterator( $modules_path );
	$regex        = new RegexIterator( $recIterator, '/\/*.php$/i' );

	foreach( $regex as $item ) {
		require_once $item->getPathname();
	}
}

/*
 * Fallback to 'glob' if PHP Version < 5.2.11
 */
function shoestrap_include_modules_fallback() {
	// Include all modules from the shoestrap theme (NOT the child themes)
	foreach( glob( SHOESTRAP_MODULES_PATH . '/*/*.php' ) as $module ) {
		require_once $module;
	}
}

// PHP version control
$phpversion = phpversion();
if ( version_compare( $phpversion, '5.2.11', '<' ) ) {
	shoestrap_include_modules();
} else {
	shoestrap_include_modules_fallback();
}