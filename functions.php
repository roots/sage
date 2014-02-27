<?php

// define the 'SHOESTRAP_ASSETS_URL' constant.
if ( ! defined( 'SHOESTRAP_ASSETS_URL' ) ) {
	define( 'SHOESTRAP_ASSETS_URL', get_template_directory_uri() . '/assets' );
}

/*
 * The option that is used by Shoestrap in the database for all settings.
 *
 * This can be overriden by adding this in your wp-config.php:
 * define( 'SHOESTRAP_OPT_NAME', 'custom_option' )
 */
if ( ! defined( 'SHOESTRAP_OPT_NAME' ) ) {
	define( 'SHOESTRAP_OPT_NAME', 'shoestrap' );
}

global $ss_settings;
$ss_settings = get_option( SHOESTRAP_OPT_NAME );

require_once locate_template('/lib/class-Shoestrap_Color.php');
require_once locate_template('/lib/class-Shoestrap_Image.php');
require_once locate_template('/lib/functions-core.php');
require_once locate_template('/lib/redux-init.php');
require_once locate_template('/lib/modules/load.modules.php');

// Get the framework
require_once locate_template( '/framework/class-Shoestrap_Framework.php' );

require_once locate_template( '/lib/template.php' );     // Custom get_template_part function.
require_once locate_template( '/lib/utils.php' );        // Utility functions
require_once locate_template( '/lib/init.php' );         // Initial theme setup and constants
require_once locate_template( '/lib/wrapper.php' );      // Theme wrapper class
require_once locate_template( '/lib/sidebar.php' );      // Sidebar class
require_once locate_template( '/lib/footer.php' );       // Footer configuration
require_once locate_template( '/lib/config.php' );       // Configuration
require_once locate_template( '/lib/titles.php' );       // Page titles
require_once locate_template( '/lib/cleanup.php' );      // Cleanup
require_once locate_template( '/lib/comments.php' );     // Custom comments modifications
require_once locate_template( '/lib/meta.php' );         // Tags
require_once locate_template( '/lib/widgets.php' );      // Sidebars and widgets
require_once locate_template( '/lib/post-formats.php' ); // Sidebars and widgets
require_once locate_template( '/lib/scripts.php' );      // Scripts and stylesheets

do_action( 'shoestrap_include_files' );