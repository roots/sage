<?php

// Helper functions required BEFORE the modules are loaded
require_once locate_template('/lib/modules/helper.functions.php');

// Include all modules
$modules_path = new RecursiveDirectoryIterator( locate_template( '/lib/modules/' ) );
$recIterator  = new RecursiveIteratorIterator( $modules_path );
$regex        = new RegexIterator( $recIterator, '/\/module.php$/i' );

foreach( $regex as $item ) {
  require_once $item->getPathname();
}
