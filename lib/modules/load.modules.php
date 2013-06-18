<?php

// Helper functions required BEFORE the modules are loaded
require_once get_template_directory() . '/lib/modules/helper.functions.php';

// PHP version control
$php_ver_arr = explode( '.', phpversion() );
$php_ver_arr_2 = explode('-', $php_ver_arr[2]);

if ( ($php_ver_arr[0] >= 5) && ($php_ver_arr[1] > 2) ){
	add_action( 'init', 'shoestrap_include_modules');
}
elseif ( ($php_ver_arr[0] >= 5) && ($php_ver_arr[1] = 2) && ($php_ver_arr_2[0] >= 11)){
	add_action( 'init', 'shoestrap_include_modules');
	}
else {
	add_action( 'init', 'shoestrap_include_modules_fallback');
}

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

// Fallback in 'glob' if != 5.2.11
function shoestrap_include_modules_fallback(){
	// Include all modules from the shoestrap theme (NOT the child themes)
	foreach(glob(get_template_directory() . '/lib/modules/*/module.php') as $module) 
	{	
		require_once $module;
	}
}