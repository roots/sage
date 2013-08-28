<?php

// Helper functions required BEFORE the modules are loaded
//require_once get_template_directory() . '/lib/modules/simple.options.php';
require_once get_template_directory() . '/lib/modules/helper.functions.php';

// PHP version control
$phpversion = phpversion();
if ( version_compare( $phpversion, '5.2.11', '<' ) )
  shoestrap_include_modules();
else
  shoestrap_include_modules_fallback();

// Use 'RecursiveDirectoryIterator' if >= 5.2.11
function shoestrap_include_modules(){
  // Include all modules from the shoestrap theme (NOT the child themes)
  $modules_path = new RecursiveDirectoryIterator( get_template_directory() . '/lib/modules/' );
  $recIterator  = new RecursiveIteratorIterator( $modules_path );
  $regex        = new RegexIterator( $recIterator, '/\/module.php$/i' );

  foreach( $regex as $item ) {
    require_once $item->getPathname();
  }
}

// Fallback in 'glob' if < 5.2.11
function shoestrap_include_modules_fallback() {
  // Include all modules from the shoestrap theme (NOT the child themes)
  foreach( glob( get_template_directory() . '/lib/modules/*/module.php' ) as $module ) {
    require_once $module;
  }
}

/*
 * The following function adds a 'shoestrap_activated' option in the database
 * and sets it to true, AFTER all the modules have been loaded above.
 * This helps skip the compiler on activation.
 */
function shoestrap_theme_active() {
  if ( get_option( 'shoestrap_activated' ) != true ) {
  	add_option( 'shoestrap_activated', true );
  }
}
add_action( 'after_setup_theme', 'shoestrap_theme_active' );
