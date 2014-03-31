<?php

if ( ! defined( 'SS_FRAMEWORK' ) ) {
	// Define bootstrap as the default framework.
	// Other frameworks can be added via plugins and override this.
	define( 'SS_FRAMEWORK', 'bootstrap' );
}

// define the 'SHOESTRAP_ASSETS_URL' constant.
if ( ! defined( 'SHOESTRAP_ASSETS_URL' ) ) {
	$shoestrap_assets_url = str_replace( 'http:', '', get_template_directory_uri() . '/assets' );
	$shoestrap_assets_url = str_replace( 'https:', '', $shoestrap_assets_url );
	define( 'SHOESTRAP_ASSETS_URL', $shoestrap_assets_url );
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

require_once locate_template( '/lib/class-Shoestrap_Color.php' );
require_once locate_template( '/lib/class-Shoestrap_Image.php' );
require_once locate_template( '/lib/functions-core.php' );
require_once locate_template( '/lib/redux-init.php' );
// require_once locate_template( '/lib/updater.php' );

// Get the framework
require_once locate_template( '/framework/framework.php' );

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

require_once locate_template( '/lib/class-TGM_Plugin_Activation.php' ); // TGM_Plugin_Activation
require_once locate_template( '/lib/dependencies.php' );                // load our dependencies

// Setup our custom updater
if ( file_exists( locate_template( '/lib/updater/updater.php' ) ) ) {
	require_once locate_template( '/lib/updater/updater.php' );
}

if ( class_exists( 'bbPress' ) ) {
	require_once locate_template( '/lib/bbpress.php' );      // Scripts and stylesheets
}

do_action( 'shoestrap_include_files' );

/*
* Notice for Shoestrap Updater
*/
add_action( 'admin_notices', 'shoestrap_new_version_notice' );
function shoestrap_new_version_notice() {
	global $current_user ;

	$user_id = $current_user->ID;

	/* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta( $user_id, 'shoestrap_ignore_notice' ) ) {

		if ( ! class_exists( 'SS_EDD_SL_Updater' ) ) {

			echo '<div class="updated">';
				echo '<h3>' . __( 'Theme Notice', 'shoestrap' ) . '</h3>';
				echo '<a href="http://shoestrap.org/downloads/shoestrap-updater/" target="_blank">';
					_e( 'Please make sure you download and install the Shoestrap Updater plugin', 'shoestrap' );
				echo '</a>';
				_e( 'so that your shoestrap themes and plugin will be automatically updated when a new release is available.', 'shoestrap' );

			if ( class_exists( 'GitHub_Updater' ) ) {

				_e( 'The "Github Updater" plugin is no longer required by the Shoestrap theme, so you can deactivate it and delete it from your installation.', 'shoestrap' );

				printf(__( '<a href="%1$s">Hide Notice</a>' ), '?shoestrap_nag_ignore=0' );

			}

			echo '</div>';
		}
	}
}

add_action('admin_init', 'shoestrap_nag_ignore');
function shoestrap_nag_ignore() {
	global $current_user;

	$user_id = $current_user->ID;

	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset( $_GET['shoestrap_nag_ignore'] ) && '0' == $_GET['shoestrap_nag_ignore'] ) {
		add_user_meta( $user_id, 'shoestrap_ignore_notice', 'true', true );
	}
}
