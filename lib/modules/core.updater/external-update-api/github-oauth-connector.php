<?php
/*
Plugin Name:  GitHub OAuth Connector
Description:  Provides an interface for fetching and storing an OAuth access token from a GitHub application.
Version:      1.6.2
Author:       Code for the People
Author URI:   http://codeforthepeople.com/
Text Domain:  github-oauth-connector
Domain Path:  /languages/
License:      GPL v2 or later

This plugin was originally based off of "WP Private GitHub Plugin Updater" plugin by Paul Clark (http://brainstormmedia.com/) and Joachim Kudish (http://jkudish.com). See https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/pull/15 for more details.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

/**
 * Configuration assistant for fetching and storing an OAuth access token from a GitHub application.
 */
class GitHub_OAuth_Connector {

	/**
	 * Class constructor. Set up some actions and filters.
	 *
	 * @return null
	 */
	function __construct() {

		add_action( 'admin_init',                               array( $this, 'settings_fields' ) );
		add_action( 'admin_menu',                               array( $this, 'add_page' ) );
		add_action( 'admin_notices',                            array( $this, 'admin_notices' ) );
		add_action( 'init',                                     array( $this, 'init' ) );
		add_action( 'wp_ajax_set_github_oauth_key',             array( $this, 'ajax_set_github_oauth_key') );
		add_action( 'load-plugins_page_github-oauth-connector', array( $this, 'maybe_authorise') );
	}

	/**
	 * Load localisation files.
	 *
	 * @return null
	 */
	function init() {
		load_plugin_textdomain( 'github-oauth-connector', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	function admin_notices() {
		if ( !isset( $_GET['page'] ) or ( 'github-oauth-connector' != $_GET['page'] ) )
			return;
		if ( !isset( $_GET['authorised'] ) or ( 'true' != $_GET['authorised'] ) )
			return;

		?>
		<div id="github-authorised" class="updated">
			<p><?php _e( 'GitHub connection successful. You can now safely deactivate the GitHub OAuth Connector plugin.', 'github-oauth-connector' ); ?></p>
		</div>
		<?php

	}

	/**
	 * Add the options page
	 *
	 * @return null
	 */
	function add_page() {
		add_plugins_page ( __( 'GitHub Connector', 'github-oauth-connector' ), __( 'GitHub Connector', 'github-oauth-connector' ), 'update_plugins', 'github-oauth-connector', array( $this, 'admin_page' ) );
	}

	/**
	 * Add fields and groups to the settings page
	 *
	 * @return null
	 */
	public function settings_fields() {

		register_setting( 'ghupdate', 'ghupdate', array( $this, 'settings_validate' ) );

		// Sections: ID, Label, Description callback, Page ID
		add_settings_section( 'ghupdate_private', 'Private Repositories', array( $this, 'private_description' ), 'github-oauth-connector' );

		// Private Repo Fields: ID, Label, Display callback, Menu page slug, Form section, callback arguements
		add_settings_field(
			'client_id', __( 'Client ID', 'github-oauth-connector' ), array( $this, 'input_field' ), 'github-oauth-connector', 'ghupdate_private',
			array(
				'id'          => 'client_id',
				'type'        => 'text',
				'description' => '',
			)
		);
		add_settings_field(
			'client_secret', __( 'Client Secret', 'github-oauth-connector' ), array( $this, 'input_field' ), 'github-oauth-connector', 'ghupdate_private',
			array(
				'id'          => 'client_secret',
				'type'        => 'text',
				'description' => '',
			)
		);
		add_settings_field(
			'access_token', __( 'Access Token', 'github-oauth-connector' ), array( $this, 'token_field' ), 'github-oauth-connector', 'ghupdate_private',
			array(
				'id' => 'access_token',
			)
		);

	}

	/**
	 * Output the description field for the settings screen.
	 *
	 * @return null
	 */
	public function private_description() {

		$name     = preg_replace( '|^https?://|', '', home_url() );
		$url      = home_url();
		$callback = get_site_url( null, '', 'admin' );

		?>
		<p><?php _e( 'Updating from private repositories on GitHub requires a one-time application setup and authorisation.', 'github-oauth-connector' ); ?></p>
		<p><?php _e( 'Follow these steps:', 'github-oauth-connector' ); ?></p>
		<ol>
			<li><?php printf( __( '<a href="%s" target="_blank">Create an application on GitHub</a> using the following values:', 'github-oauth-connector' ), 'https://github.com/settings/applications/new' ); ?>
				<ul>
					<li><?php printf( __( '<strong>Name:</strong> <code>%s</code>', 'github-oauth-connector' ), $name ); ?></li>
					<li><?php printf( __( '<strong>URL:</strong> <code>%s</code>', 'github-oauth-connector' ), $url ); ?></li>
					<li><?php printf( __( '<strong>Callback URL:</strong> <code>%s</code>', 'github-oauth-connector' ), $callback ); ?></li>
				</ul>
			</li>
			<li><?php _e( 'You&rsquo;ll be provided with a <strong>Client ID</strong> and a <strong>Client Secret</strong>. Copy the values into the fields below.', 'github-oauth-connector' ); ?></li>
			<li><?php _e( 'Click &lsquo;Authorise with GitHub&rsquo;.', 'github-oauth-connector' ); ?></li>
		</ol>
		<?php
	}

	/**
	 * Output a text input field for the settings screen.
	 *
	 * @param  array $args Arguments for this field
	 * @return null
	 */
	public function input_field( $args ) {
		extract( $args );
		$gh = get_option( 'ghupdate' );
		$value = $gh[$id];
		?>
		<input value="<?php esc_attr_e( $value ); ?>" name="<?php esc_attr_e( $id ); ?>" id="<?php esc_attr_e( $id ); ?>" type="text" class="regular-text" />
		<?php echo $description; ?>
		<?php
	}

	/**
	 * Output the access token field for the settings screen.
	 *
	 * @param  array $args Arguments for this field
	 * @return null
	 */
	public function token_field( $args ) {
		extract( $args );
		$gh = get_option( 'ghupdate' );
		$value = $gh[$id];

		if ( empty( $value ) ) {
			?>
			<p><?php _e( 'Input Client ID and Client Secret, then click &lsquo;Authorise with GitHub&rsquo;.', 'github-oauth-connector' ); ?></p>
			<input value="<?php esc_attr_e( $value ); ?>" name="<?php esc_attr_e( $id ); ?>" id="<?php esc_attr_e( $id ); ?>" type="hidden" />
			<?php
		}else{
			?>
			<input value="<?php esc_attr_e( $value ); ?>" name="<?php esc_attr_e( $id ); ?>" id="<?php esc_attr_e( $id ); ?>" type="text" class="regular-text" />
			<?php
		}
		?>
		<?php
	}

	/**
	 * Validate and sanitise the settings fields when they're saved.
	 *
	 * @param  array $input The user entered fields.
	 * @return array        The sanitised fields.
	 */
	public function settings_validate( $input ) {
		if ( empty( $input ) ) {
			$input = $_POST;
		}
		if ( !is_array( $input ) ) {
			return false;
		}
		$gh = get_option( 'ghupdate' );
		$valid = array();

		$valid['client_id']     = strip_tags( stripslashes( $input['client_id'] ) );
		$valid['client_secret'] = strip_tags( stripslashes( $input['client_secret'] ) );
		$valid['access_token']  = strip_tags( stripslashes( $input['access_token'] ) );

		if ( empty( $valid['client_id']) ) {
			add_settings_error( 'client_id', 'no-client-id', __( 'Please input a Client ID before authorising.', 'github-oauth-connector' ), 'error' );
		}
		if ( empty( $valid['client_secret']) ) {
			add_settings_error( 'client_secret', 'no-client-secret', __( 'Please input a Client Secret before authorising.', 'github-oauth-connector' ), 'error' );
		}

		return $valid;
	}

	/**
	 * Output the setup page
	 *
	 * @return null
	 */
	function admin_page() {
		?>
		<div class="wrap ghupdate-admin">

			<div class="head-wrap">
				<?php screen_icon('plugins'); ?>
				<h2><?php _e( 'Setup GitHub Connection' , 'github-oauth-connector' ); ?></h2>
			</div>

			<div class="postbox-container primary">
				<form method="post" id="ghupdate" action="options.php">
					<?php
						settings_errors();
						settings_fields('ghupdate'); // includes nonce
						do_settings_sections( 'github-oauth-connector' );
						submit_button( __( 'Authorise with GitHub &raquo;', 'github-oauth-connector' ) )
					?>
				</form>
			</div>

		</div>
		<?php
	}

	/**
	 * Redirect the user to the GitHub OAuth authorisation screen if necessary.
	 *
	 * @return null
	 */
	public function maybe_authorise() {
		$gh = get_option('ghupdate');
		if ( isset( $_GET['authorised'] ) and ( 'false' == $_GET['authorised'] ) )
			return;
		if ( !isset( $_GET['settings-updated'] ) or ( 'true' != $_GET['settings-updated'] ) )
			return;
		if ( empty( $gh['client_id'] ) || empty( $gh['client_secret'] ) )
			return;

		$redirect_uri = urlencode(admin_url('admin-ajax.php?action=set_github_oauth_key'));

		// Send user to GitHub for account authorisation
		$query = 'https://github.com/login/oauth/authorize';
		$query_args = array(
			'scope'        => 'repo',
			'client_id'    => $gh['client_id'],
			'redirect_uri' => $redirect_uri,
		);
		$query = add_query_arg($query_args, $query);
		wp_redirect( $query );

		exit;

	}

	/**
	 * Callback handler for the OAuth connection response. Saves the access token to the database.
	 *
	 * @return null
	 */
	public function ajax_set_github_oauth_key() {
		$gh = get_option('ghupdate');

		$query = admin_url( 'plugins.php' );
		$query = add_query_arg( array( 'page' => 'github-oauth-connector' ), $query );

		if ( isset($_GET['code']) ) {
			// Receive authorised token
			$query = 'https://github.com/login/oauth/access_token';
			$query_args = array(
				'client_id'     => $gh['client_id'],
				'client_secret' => $gh['client_secret'],
				'code'          => stripslashes( $_GET['code'] ),
			);
			$query = add_query_arg( $query_args, $query );
			$response = wp_remote_get( $query, array( 'sslverify' => false ) );
			parse_str( $response['body'] ); // populates $access_token, $token_type

			if ( isset( $access_token ) and !empty( $access_token ) ) {
				$gh['access_token'] = $access_token;
				update_option( 'euapi_github_access_token', $access_token );
				update_option('ghupdate', $gh );

				if ( function_exists( 'euapi_flush_transients' ) )
					euapi_flush_transients();

				$query = add_query_arg( array(
					'page'       => 'github-oauth-connector',
					'authorised' => 'true'
				), admin_url( 'plugins.php' ) );
				wp_redirect( $query );
				exit;
			}

		}

		$query = add_query_arg( array( 'authorised' => 'false' ), $query );
		wp_redirect($query);
		exit;

	}

}

add_action('init', create_function('', 'global $GitHub_OAuth_Connector; $GitHub_OAuth_Connector = new GitHub_OAuth_Connector();') );
