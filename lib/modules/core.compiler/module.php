<?php

// Load the less.php parser if it's not already loaded.
if ( !class_exists( 'Less_Cache' ) )
  require_once 'includes/less.php/Cache.php';

if ( !class_exists( 'Less_Parser' ) )
  require_once 'includes/less.php/Less.php';

require_once 'includes/functions.core.php';
require_once 'includes/functions.bootstrap.variables.php';
require_once 'includes/functions.compiler.php';