<?php

if ( !defined( 'SHOESTRAP_MODULES_PATH' ) )
	define( 'SHOESTRAP_MODULES_PATH', get_template_directory() . '/lib/modules' );

if ( !defined( 'SHOESTRAP_MODULES_URL' ) )
	define( 'SHOESTRAP_MODULES_URL', get_template_directory_uri() . '/lib/modules' );


/*
 * The option that is used by Shoestrap in the database for all settings.
 *
 * This can be overriden by adding this in your wp-config.php:
 * define( 'SHOESTRAP_OPT_NAME', 'custom_option' )
 */
if ( !defined( 'SHOESTRAP_OPT_NAME' ) )
	define( 'SHOESTRAP_OPT_NAME', 'shoestrap' );


/*
 * Define 'REDUX_OPT_NAME' the same as 'SHOESTRAP_OPT_NAME'.
 *
 * This ensures compatibility with older add-on plugins and child themes.
 * If you are developing on Shoestrap you should change it on your plugin/theme as well.
 */
if ( !defined( 'REDUX_OPT_NAME' ) )
	define( 'REDUX_OPT_NAME', SHOESTRAP_OPT_NAME );

// Prioritize loading of some necessary core modules
require_once SHOESTRAP_MODULES_PATH . '/redux/module.php';
require_once SHOESTRAP_MODULES_PATH . '/core/module.php';
require_once SHOESTRAP_MODULES_PATH . '/layout/module.php';
require_once SHOESTRAP_MODULES_PATH . '/blog/module.php';

if ( !function_exists( 'shoestrap_include_modules' ) ) :
/*
 * Use 'RecursiveDirectoryIterator' if PHP Version >= 5.2.11
 */
function shoestrap_include_modules() {
	// Include all modules from the shoestrap theme (NOT the child themes)
	$modules_path = new RecursiveDirectoryIterator( SHOESTRAP_MODULES_PATH . '/' );
	$recIterator  = new RecursiveIteratorIterator( $modules_path );
	$regex        = new RegexIterator( $recIterator, '/\/module.php$/i' );

	foreach( $regex as $item ) {
		require_once $item->getPathname();
	}
}
endif;


if ( !function_exists( 'shoestrap_include_modules_fallback' ) ) :
/*
 * Fallback to 'glob' if PHP Version < 5.2.11
 */
function shoestrap_include_modules_fallback() {
	// Include all modules from the shoestrap theme (NOT the child themes)
	foreach( glob( get_template_directory() . '/lib/modules/*/module.php' ) as $module ) {
		require_once $module;
	}
}
endif;


// PHP version control
$phpversion = phpversion();
if ( version_compare( $phpversion, '5.2.11', '<' ) )
	shoestrap_include_modules();
else
	shoestrap_include_modules_fallback();