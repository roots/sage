<?php
/**
 * Roots includes
 */
require_once locate_template('/lib/utils.php');           // Utility functions
require_once locate_template('/lib/init.php');            // Initial theme setup and constants
require_once locate_template('/lib/sidebar.php');         // Sidebar class
require_once locate_template('/lib/config.php');          // Configuration
require_once locate_template('/lib/activation.php');      // Theme activation
require_once locate_template('/lib/cleanup.php');         // Cleanup
require_once locate_template('/lib/nav.php');             // Custom nav modifications
require_once locate_template('/lib/comments.php');        // Custom comments modifications
require_once locate_template('/lib/rewrites.php');        // URL rewriting for assets
require_once locate_template('/lib/widgets.php');         // Sidebars and widgets
require_once locate_template('/lib/scripts.php');         // Scripts and stylesheets
require_once locate_template('/lib/custom.php');          // Custom functions

require_once locate_template('/lib/lessphp/lessc.inc.php');   // Include the less compiler
require_once locate_template('/lib/image_resize/resize.php'); // Include the Image Resizer
require_once locate_template('/lib/resize.php');              // Adding helper image resizing functions
require_once locate_template('/lib/breadcrumbs.php');         // The Breadcrumbs Class and function

require_once locate_template('/lib/customizer/init.php');                     // Initialize the Customizer
require_once locate_template('/lib/customizer/helper.functions.php');         // Helper functions for the customizer
require_once locate_template('/lib/customizer/compiler.php');                 // LESSPHP Compiler for Bootstrap 3
require_once locate_template('/lib/customizer/functions.advanced.php');       // Extra functions for the "Advanced" Customizer section
require_once locate_template('/lib/customizer/functions.featured-image.php'); // Extra functions for the "Featured Image" Customizer section
require_once locate_template('/lib/customizer/functions.footer.php');         // Extra functions for the "Footer" Customizer section
require_once locate_template('/lib/customizer/functions.header.php');         // Extra functions for the "Header" Customizer section
require_once locate_template('/lib/customizer/functions.jumbotron.php');      // Extra functions for the "Jumbotron (Hero)" Customizer section
require_once locate_template('/lib/customizer/functions.layout.php');         // Extra functions for the "Layout" Customizer section
require_once locate_template('/lib/customizer/functions.logo.php');           // Extra functions for the "Logo" Customizer section
require_once locate_template('/lib/customizer/functions.navbar.php');         // Extra functions for the "NavBar" Customizer section
require_once locate_template('/lib/customizer/functions.typography.php');     // Extra functions for the "Typography" Customizer section

require_once locate_template('/admin/index.php'); // Slightly Modified Options Framework
