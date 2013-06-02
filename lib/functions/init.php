<?php

require_once locate_template('/admin/index.php'); // Slightly Modified Options Framework
require_once locate_template('/lib/admin/init.php'); // Shoestrap SMOF modifications

require_once locate_template('/lib/functions/lessc.inc.php');                // Include the less compiler
require_once locate_template('/lib/functions/image_resize/resize.php');      // Include the Image Resizer
require_once locate_template('/lib/functions/breadcrumbs.php');              // The Breadcrumbs Class and function

require_once locate_template('/lib/functions/helper.functions.php');         // Helper functions for the customizer
require_once locate_template('/lib/functions/bootstrap-compiler.php');       // LESSPHP Compiler for Bootstrap 3

// Include all modules
$modules_path = new RecursiveDirectoryIterator(locate_template('/lib/modules/'));
$recIterator  = new RecursiveIteratorIterator($modules_path);
$regex        = new RegexIterator($recIterator, '/\/module.php$/i');

foreach($regex as $item) {
  require_once $item->getPathname();
}

// Add extra features
if (locate_template('/lib/extensions/init.php')) {
 require_once locate_template('/lib/extensions/init.php');
}
