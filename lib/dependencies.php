<?php

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-TGM_Plugin_Activation.php';

add_action( 'tgmpa_register', 'shoestrap_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function shoestrap_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 */
	$plugins = array(
		array(
			'name'               => 'Redux Framework',
			'slug'               => 'redux-framework',
			'required'           => true,
			'force_activation'   => true,
		),
	);

	/**
	 * Array of configuration settings.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */

	$config = array(
		'domain'           => 'shoestrap',
		'default_path'     => '',
		'parent_menu_slug' => 'themes.php',
		'parent_url_slug'  => 'themes.php',
		'menu'             => 'install-required-plugins',
		'has_notices'      => true,
		'is_automatic'     => true,
		'message'          => '',
		'strings'          => array(
			'page_title'                      => __( 'Install Required Plugins', 'shoestrap' ),
			'menu_title'                      => __( 'Install Plugins', 'shoestrap' ),
			'installing'                      => __( 'Installing Plugin: %s', 'shoestrap' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'shoestrap' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                          => __( 'Return to Required Plugins Installer', 'shoestrap' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'shoestrap' ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', 'shoestrap' ),
			'nag_type'                        => 'updated'
		)
	);

	tgmpa( $plugins, $config );
}
