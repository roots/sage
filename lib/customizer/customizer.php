<?php

add_theme_support( 'custom-background' );

require_once locate_template( '/lib/customizer/functions/sections.php' );     // Create Customizer Sections
require_once locate_template( '/lib/customizer/functions/settings.php' );     // Create Customizer Settings
require_once locate_template( '/lib/customizer/functions/controls.php' );     // Create Customizer Controls
require_once locate_template( '/lib/customizer/functions/extras.php' );       // Extra Functions for the customizer
require_once locate_template( '/lib/customizer/functions/logo.php' );         // Customizer Logo functions
require_once locate_template( '/lib/customizer/functions/social.php' );       // Customizer Social functions
require_once locate_template( '/lib/customizer/functions/login.php' );        // Login screen customizations

// Apply the selected styles:
require_once locate_template( '/lib/customizer/styles/navbar.php' );          // NavBar
require_once locate_template( '/lib/customizer/styles/branding.php' );        // Branding (header) region, containing the logo etc.
require_once locate_template( '/lib/customizer/styles/text.php' );            // General text and links styles
require_once locate_template( '/lib/customizer/styles/webfonts.php' );        // Webfonts
require_once locate_template( '/lib/customizer/styles/background.php' );      // Page and wrap background
require_once locate_template( '/lib/customizer/styles/layout.php' );          // Layout
require_once locate_template( '/lib/customizer/styles/buttons.php' );         // Buttons
require_once locate_template( '/lib/customizer/styles/hero.php' );            // Hero
require_once locate_template( '/lib/customizer/styles/footer.php' );          // Footer
require_once locate_template( '/lib/customizer/styles/advanced.php' );        // Custom CSS and/or JS on the head and the footer
require_once locate_template( '/lib/customizer/styles/affix.php' );           // 
require_once locate_template( '/lib/customizer/styles/sidebar.php' );         // Sidebar Class

//Templating changes
require_once locate_template( '/lib/customizer/templates/social-links.php' ); // Social Links
require_once locate_template( '/lib/customizer/templates/branding.php' );     // Customizer Branding functions
require_once locate_template( '/lib/customizer/templates/footer-icon.php' );  // Customizer footer icon
require_once locate_template( '/lib/customizer/templates/hero.php' );         // Hero Region
require_once locate_template( '/lib/customizer/templates/loginbutton.php' );  // Login button
