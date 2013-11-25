<?php

// Prioritize loading of some necessary core modules
require_once get_template_directory() . '/lib/modules/core.redux/module.php';
require_once get_template_directory() . '/lib/modules/core/module.php';
require_once get_template_directory() . '/lib/modules/core.layout/module.php';
require_once get_template_directory() . '/lib/modules/core.images/module.php';

if ( !function_exists( 'shoestrap_include_modules' ) ) :
/*
 * Use 'RecursiveDirectoryIterator' if PHP Version >= 5.2.11
 */
function shoestrap_include_modules() {
  // Include all modules from the shoestrap theme (NOT the child themes)
  $modules_path = new RecursiveDirectoryIterator( get_template_directory() . '/lib/modules/' );
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
if ( version_compare( $phpversion, '5.2.11', '<' ) ) :
  shoestrap_include_modules();
else :
  shoestrap_include_modules_fallback();
endif;


if ( !function_exists( 'shoestrap_theme_active' ) ) :
/*
 * The following function adds a 'shoestrap_activated' option in the database
 * and sets it to true, AFTER all the modules have been loaded above.
 * This helps skip the compiler on activation.
 */
function shoestrap_theme_active() {
  if ( get_option( 'shoestrap_activated' ) != true )
    add_option( 'shoestrap_activated', true );
}
endif;
add_action( 'after_setup_theme', 'shoestrap_theme_active' );
