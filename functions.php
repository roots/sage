<?php
/**
 * Roots includes
 */
require_once locate_template( '/lib/utils.php' );           // Utility functions
require_once locate_template( '/lib/init.php' );            // Initial theme setup and constants
require_once locate_template( '/lib/wrapper.php' );         // Theme wrapper class
require_once locate_template( '/lib/sidebar.php' );         // Sidebar class
require_once locate_template( '/lib/config.php' );          // Configuration
require_once locate_template( '/lib/activation.php' );      // Theme activation
require_once locate_template( '/lib/titles.php' );          // Page titles
require_once locate_template( '/lib/cleanup.php' );         // Cleanup
require_once locate_template( '/lib/nav.php' );             // Custom nav modifications
require_once locate_template( '/lib/gallery.php' );         // Custom [gallery] modifications
require_once locate_template( '/lib/comments.php' );        // Custom comments modifications
require_once locate_template( '/lib/rewrites.php' );        // URL rewriting for assets
require_once locate_template( '/lib/relative-urls.php' );   // Root relative URLs
require_once locate_template( '/lib/widgets.php' );         // Sidebars and widgets
require_once locate_template( '/lib/scripts.php' );         // Scripts and stylesheets
require_once locate_template( '/lib/custom.php' );          // Custom functions
require_once locate_template( '/classes/mdg-generic.php' );     // MDG Generic functions much like custom.php except in a class
require_once locate_template( '/classes/mdg-meta-helper.php' ); // MDG Meta helper
require_once locate_template( '/classes/mdg-images.php' );      // MDG Images
require_once locate_template( '/classes/mdg-type-base.php' );   // MDG Post type base


/**
 * Removes unwanted meta boxes
 *
 * @return null
 */
function mdg_remove_metaboxes() {

	// hide stuff for all post types
	$post_types = get_post_types();
	foreach ( $post_types as $post_type ) {
		remove_meta_box( 'postcustom', $post_type, 'normal' );
	} // foreach()
} // mdg_remove_metaboxes()
add_action( 'admin_menu' , 'mdg_remove_metaboxes' );
