<?php

// define the 'SHOESTRAP_ASSETS_URL' constant.
if ( !defined( 'SHOESTRAP_ASSETS_URL' ) )
	define( 'SHOESTRAP_ASSETS_URL', get_template_directory_uri() . '/assets' );

// If modules exist, load them.
if ( file_exists( locate_template( '/lib/modules/load.modules.php' ) ) )
	require_once locate_template('/lib/modules/load.modules.php');

require_once locate_template( '/lib/utils.php' );      // Utility functions
require_once locate_template( '/lib/init.php' );       // Initial theme setup and constants
require_once locate_template( '/lib/wrapper.php' );    // Theme wrapper class
require_once locate_template( '/lib/sidebar.php' );    // Sidebar class
require_once locate_template( '/lib/footer.php' );     // Footer configuration
require_once locate_template( '/lib/config.php' );     // Configuration
require_once locate_template( '/lib/titles.php' );     // Page titles
require_once locate_template( '/lib/cleanup.php' );    // Cleanup
require_once locate_template( '/lib/nav.php' );        // Custom nav modifications
require_once locate_template( '/lib/gallery.php' );    // Custom [gallery] modifications
require_once locate_template( '/lib/comments.php' );   // Custom comments modifications
require_once locate_template( '/lib/widgets.php' );    // Sidebars and widgets
require_once locate_template( '/lib/scripts.php' );    // Scripts and stylesheets

do_action( 'shoestrap_include_files' );