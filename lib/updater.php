<?php

if ( ! class_exists( 'Shoestrap_Updater' ) ) {

	/**
	* The Shoestrap_Updater_Field class
	*/
	class Shoestrap_Updater {
		private $args;

		function __construct( $args ) {
			global $ss_settings;

			require_once locate_template( 'lib/edd-licensing/EDD_SL_Plugin_Updater.php' );
			require_once locate_template( 'lib/edd-licensing/EDD_SL_Theme_Updater.php' );

			// Is this a plugin or a theme? (defaults to plugin)
			if ( isset( $args['mode'] ) && ! is_null( $args['mode'] ) && ! empty( $args['mode'] ) ) {
				$this->mode = $args['mode'];
			} else {
				$this->mode = 'plugin';
			}


			// The unique ID of the field
			$this->id = $args['id'];


			// If this is a plugin, get its plugin data
			if ( 'plugin' == $this->mode ) {

				// Check if get_plugin_data has been loaded. If not, load it now.
				if ( ! function_exists( 'get_plugin_data' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				$plugin_data = get_plugin_data( $args['path'] );
			}


			// Do we have a default license?
			if ( isset( $args['license'] ) && ! is_null( $args['license'] ) && ! empty( $args['license'] ) ) {
				// If there is a hard-coded license, get it.
				$default_license = $args['license'];

				if ( $args['license'] != $ss_settings[$this->id] && ! empty( $ss_settings[$this->id] ) ) {
					$this->license = trim( $ss_settings[$this->id] );
				} else {
					$this->license = $default_license;
				}
			} else {
				// Get the license from the settings.
				$this->license = trim( $ss_settings[$this->id] );
			}


			// If this is a plugin and the item_name has not been set, get the plugin name from the plugin file.
			if ( 'plugin' == $args['mode'] && ( ! isset( $args['item_name'] ) || is_null( $args['item_name'] ) || empty( $args['item_name'] ) ) ) {
				$this->item_name = $plugin_data['Name'];
			} else {
				$this->item_name = $args['item_name'];
			}


			// The title of the field that will be created in redux
			if ( isset( $args['title'] ) && ! is_null( $args['title'] ) && ! empty( $args['title'] ) ) {
				$this->title = $args['title'];
			} else {
				$this->title = $this->item_name . ' ' . __( 'License Key', 'shoestrap' );
			}


			// Get the remote API URL.
			// If none is set, then define a default.
			if ( isset( $args['remote_api_url'] ) && ! is_null( $args['remote_api_url'] ) && ! empty( $args['remote_api_url'] ) ) {
				$this->remote_api_url = $args['remote_api_url'];
			} else {
				$this->remote_api_url = 'http://shoestrap.org';
			}


			// Get the item's version.
			// If this is a plugin and no version argument is present, get the version from the plugin headers.
			if ( 'theme' == $this->mode || ( isset( $args['version'] ) && ! is_null( $args['version'] ) && ! empty( $args['version'] ) ) ) {
				$this->version = $args['version'];
			} else {
				$this->version = $plugin_data['Version'];
			}


			// Get the item's author.
			// If this is a plugin and no version argument is present, get the author from the plugin headers.
			if ( 'theme' == $this->mode || ( isset( $args['version'] ) && ! is_null( $args['version'] ) && ! empty( $args['version'] ) ) ) {
				$this->author = $args['author'];
			} else {
				$this->author = $plugin_data['Author'];
			}


			// Get the filepath to the item (required on plugins.)
			if ( isset( $args['path'] ) ) {
				$this->path     = $args['path'];
			}


			add_filter( 'shoestrap_licensing_options_modifier', array( $this, 'options' ) );
			add_action( 'admin_init', array( $this, 'updater' ) );
			add_action( 'admin_init', array( $this, 'activate_license' ) );
			add_action( 'switch_theme', array( $this, 'deactivate_license' ) );
		}


		function options( $options ) {

			// Build the text field for redux.
			$options[] = array(
				'title'          => $this->title,
				'id'             => $this->id,
				'default'        => '',
				'type'           => 'text',
			);

			return $options;

		}

		function updater() {

			// Setup the theme updater
			if ( 'theme' == $this->mode ) {

				$updater = new EDD_SL_Theme_Updater( array(
					'remote_api_url' => $this->remote_api_url,
					'version'        => $this->version,
					'license'        => $this->license,
					'item_name'      => $this->item_name,
					'author'         => $this->author,
				) );

			}

			// Setup the plugin updater
			if ( 'plugin' == $this->mode ) {

				$edd_updater = new EDD_SL_Plugin_Updater( $this->remote_api_url, $this->path, array(
					'version' 	=> $this->version,
					'license'   => $this->license,
					'item_name' => $this->item_name,
					'author'    => $this->author,
				) );

			}
		}


		function activate_license() {
			global $wp_version;

			// If the licence is already activated, we don't need to re-activate it.
			if ( get_transient( $this->id . '_license_status' ) == 'valid' ) {
				return;
			}

			// Check the status of the license
			// data to send in our API request in order to activate the license
			$api_params_check = array(
				'edd_action' => 'check_license',
				'license'    => $this->license,
				'item_name'  => urlencode( $this->item_name ),
			);

			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params_check, $this->remote_api_url ), array( 'timeout' => 15, 'sslverify' => false ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// If the license is active, set the transient for 6 more hours and return.
			if( $license_data->license == 'valid' ) {
				set_transient( $this->id . '_license_status', $license_data->license, 6 * 60 * 60 );
				return;
			}

			// If we have all the above tests, continue with the actual activation.
			// data to send in our API request in order to activate the license
			$api_params_activate = array(
				'edd_action' => 'activate_license',
				'license'    => $this->license,
				'item_name'  => urlencode( $this->item_name ),
			);

			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params_activate, $this->remote_api_url ), array( 'timeout' => 15, 'sslverify' => false ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		}

		function deactivate_license() {

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $this->license,
				'item_name'  => urlencode( $this->item_name ),
			);

			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $this->remote_api_url ), array( 'timeout' => 15, 'sslverify' => false ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		}
	}
}

function shoestrap_theme_updater_init() {
	$args = array(
		'title'          => 'Shoestrap 3 License',
		'id'             => 'shoestrap_license',
		'mode'           => 'theme',
		'item_name'      => 'Shoestrap 3',
		'version'        => '3.1',
		'author'         => 'aristath, fovoc, dovy',
		'remote_api_url' => 'http://src.wordpress-develop.dev',
		'license'        => '15d51458d7c09a29447a859e837bca47'

	);
	$updater = new Shoestrap_Updater( $args );
}
add_action( 'shoestrap_updater_init', 'shoestrap_theme_updater_init' );
