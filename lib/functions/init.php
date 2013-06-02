<?php

require_once locate_template('/admin/index.php'); // Slightly Modified Options Framework
require_once locate_template('/lib/admin/init.php'); // Shoestrap SMOF modifications

require_once locate_template('/lib/functions/lessc.inc.php');                // Include the less compiler
require_once locate_template('/lib/functions/image_resize/resize.php');      // Include the Image Resizer
require_once locate_template('/lib/functions/breadcrumbs.php');              // The Breadcrumbs Class and function

require_once locate_template('/lib/functions/helper.functions.php');         // Helper functions for the customizer
require_once locate_template('/lib/functions/bootstrap-compiler.php');       // LESSPHP Compiler for Bootstrap 3
require_once locate_template('/lib/functions/functions.advanced.php');       // Extra functions for the "Advanced" Customizer section
require_once locate_template('/lib/functions/functions.featured-image.php'); // Extra functions for the "Featured Image" Customizer section
require_once locate_template('/lib/functions/functions.footer.php');         // Extra functions for the "Footer" Customizer section
require_once locate_template('/lib/functions/functions.header.php');         // Extra functions for the "Header" Customizer section
require_once locate_template('/lib/functions/functions.jumbotron.php');      // Extra functions for the "Jumbotron (Hero)" Customizer section
require_once locate_template('/lib/functions/functions.layout.php');         // Extra functions for the "Layout" Customizer section
require_once locate_template('/lib/functions/functions.logo.php');           // Extra functions for the "Logo" Customizer section
require_once locate_template('/lib/functions/functions.navbar.php');         // Extra functions for the "NavBar" Customizer section
require_once locate_template('/lib/functions/functions.background.php');     // Extra functions for the "Background" Customizer section
require_once locate_template('/lib/functions/functions.social.php');         // Initialize the social networks
require_once locate_template('/lib/functions/functions.icons.php');          // Favicon and Apple Icons
require_once locate_template('/lib/modules/core.background/module.php');
require_once locate_template('/lib/modules/core.branding/module.php');
require_once locate_template('/lib/modules/core.header/module.php');
require_once locate_template('/lib/modules/core.layout/module.php');
require_once locate_template('/lib/modules/core.jumbotron/module.php');
require_once locate_template('/lib/modules/core.footer/module.php');
require_once locate_template('/lib/modules/core.typography/module.php');
require_once locate_template('/lib/modules/core.blog/module.php');
require_once locate_template('/lib/modules/core.social/module.php');
require_once locate_template('/lib/modules/core.advanced/module.php');
require_once locate_template('/lib/modules/core.presets/module.php');
require_once locate_template('/lib/modules/core.backup/module.php');

// Add extra features
if (locate_template('/lib/extensions/init.php')) {
 require_once locate_template('/lib/extensions/init.php');
}
