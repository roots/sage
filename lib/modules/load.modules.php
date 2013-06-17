<?php

// Helper functions required BEFORE the modules are loaded
require_once get_template_directory() . '/lib/modules/helper.functions.php';

// Include all modules from the shoestrap theme (NOT the child themes)
$modules_path = new RecursiveDirectoryIterator( get_template_directory() . '/lib/modules/' );
$recIterator  = new RecursiveIteratorIterator( $modules_path );
$regex        = new RegexIterator( $recIterator, '/\/module.php$/i' );

foreach( $regex as $item ) {
  require_once $item->getPathname();
}
