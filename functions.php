<?php
/**
 * Required by WordPress.
 *
 * Keep this file clean and only use it for requires.
 */

require_once locate_template('/lib/utils.php');                 // Utility functions
require_once locate_template('/lib/init.php');                  // Initial theme setup and constants
require_once locate_template('/lib/sidebar.php');               // Sidebar class
require_once locate_template('/lib/config.php');                // Configuration
require_once locate_template('/lib/activation.php');            // Theme activation
require_once locate_template('/lib/cleanup.php');               // Cleanup
require_once locate_template('/lib/nav.php');                   // Custom nav modifications
require_once locate_template('/lib/htaccess.php');              // Rewrites for assets, H5BP .htaccess
require_once locate_template('/lib/widgets.php');               // Sidebars and widgets
require_once locate_template('/lib/scripts.php');               // Scripts and stylesheets

if ( !class_exists( 'lessc' ) ) {
  require_once locate_template('/lib/less.php');                // Less to CSS PHP Compiler
}

require_once locate_template('/lib/customizer/customizer.php'); // Customizer functions
require_once locate_template('/lib/custom.php');                // Custom functions
require_once locate_template('/lib/admin/admin.php');           // Admin page
require_once locate_template('/lib/admin/licencing.php');       // Licencing to allow auto-updates

// require_once locate_template('/lib/custom-builder/custom-builder.php');