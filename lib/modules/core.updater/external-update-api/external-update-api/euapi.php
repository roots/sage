<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'EUAPI' ) ) :

/**
 * Main instance of the EUAPI plugin.
 */
class EUAPI {

	var $handlers = array();

	/**
	 * Class constructor. Sets up some actions and filters.
	 *
	 * @author John Blackbourn
	 */
	public function __construct() {

		add_filter( 'http_request_args',                     array( $this, 'http_request_args' ), 20, 2 );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_plugins' ) );
		add_filter( 'pre_set_site_transient_update_themes',  array( $this, 'check_themes' ) );

		add_filter( 'plugins_api',                           array( $this, 'get_plugin_info' ), 10, 3 );
		add_filter( 'themes_api',                            array( $this, 'get_theme_info' ), 10, 3 );

		add_filter( 'upgrader_pre_install',                  array( $this, 'upgrader_pre_install' ), 10, 2 );
		add_filter( 'upgrader_post_install',                 array( $this, 'upgrader_post_install' ), 10, 3 );

	}

	/**
	 * Filter the arguments for HTTP requests. If the request is to a URL that's part of
	 * something we're handling then filter the arguments accordingly.
	 *
	 * @author John Blackbourn
	 * @param  array  $args HTTP request arguments.
	 * @param  string $url  HTTP request URL.
	 * @return array        Updated array of arguments.
	 */
	function http_request_args( array $args, $url ) {

		if ( preg_match( '#://api\.wordpress\.org/(?P<type>plugins|themes)/update-check/(?P<version>[0-9.]+)/#', $url, $matches ) ) {

			switch ( $matches['type'] ) {

				case 'plugins':
					return $this->plugin_request( $args, floatval( $matches['version'] ) );
					break;

				case 'themes':
					return $this->theme_request( $args, floatval( $matches['version'] ) );
					break;

			}

		}

		$query = parse_url( $url, PHP_URL_QUERY );

		if ( empty( $query ) )
			return $args;

		parse_str( $query, $query );

		if ( !isset( $query['_euapi_type'] ) or !isset( $query['_euapi_file'] ) )
			return $args;

		if ( !( $handler = $this->get_handler( $query['_euapi_type'], $query['_euapi_file'] ) ) )
			return $args;

		$args['sslverify'] = $handler->config['sslverify'];
		$args['timeout']   = $handler->config['timeout'];

		return $args;

	}

	/**
	 * Filters the arguments for HTTP requests to the plugin update check API.
	 *
	 * Here we loop over each plugin in the update check request and remove ones for which we're
	 * handling or excluding updates.
	 *
	 * @author John Blackbourn
	 * @param  array $args    HTTP request arguments.
	 * @param  float $version The API request version number.
	 * @return array          Updated array of arguments.
	 */
	function plugin_request( array $args, $version ) {

		switch ( $version ) {

			case 1.0:
				$plugins = unserialize( $args['body']['plugins'] );
				break;

			case 1.1:
				$plugins = json_decode( $args['body']['plugins'] );
				break;

			default:
				return $args;
				break;

		}

		if ( empty( $plugins ) )
			return $args;

		foreach ( $plugins->plugins as $plugin => $data ) {

			if ( !is_array( $data ) )
				continue;

			$item    = new EUAPI_Item_Plugin( $plugin, $data );
			$handler = $this->get_handler( 'plugin', $plugin, $item );

			if ( is_null( $handler ) )
				continue;

			if ( is_a( $handler, 'EUAPI_Handler' ) )
				$handler->item = $item;

			unset( $plugins->plugins[$plugin] );

		}

		switch ( $version ) {

			case 1.0:
				$args['body']['plugins'] = serialize( $plugins );
				break;

			case 1.1:
				$args['body']['plugins'] = json_encode( $plugins );
				break;

		}

		return $args;

	}

	/**
	 * Filters the arguments for HTTP requests to the theme update check API.
	 *
	 * Here we loop over each theme in the update check request and remove ones for which we're
	 * handling or excluding updates.
	 *
	 * @author John Blackbourn
	 * @param  array $args    HTTP request arguments.
	 * @param  float $version The API request version number.
	 * @return array          Updated array of arguments.
	 */
	function theme_request( array $args, $version ) {

		switch ( $version ) {

			case 1.0:
				$themes = unserialize( $args['body']['themes'] );
				break;

			case 1.1:
				$themes = json_decode( $args['body']['themes'] );
				break;

			default:
				return $args;
				break;

		}

		if ( empty( $themes ) )
			return $args;

		foreach ( $themes as $theme => $data ) {

			if ( !is_array( $data ) )
				continue;

			# ThemeURI is missing from $data by default for some reason
			$data['ThemeURI'] = wp_get_theme( $data['Template'] )->get( 'ThemeURI' );

			$item    = new EUAPI_Item_Theme( $theme, $data );
			$handler = $this->get_handler( 'theme', $theme, $item );

			if ( is_null( $handler ) )
				continue;

			if ( is_a( $handler, 'EUAPI_Handler' ) )
				$handler->item = $item;

			unset( $themes[$theme] );

		}

		switch ( $version ) {

			case 1.0:
				$args['body']['themes'] = serialize( $themes );
				break;

			case 1.1:
				$args['body']['themes'] = json_encode( $themes );
				break;

		}

		return $args;

	}

	/**
	 * Called immediately before the plugin update check results are saved in a transient.
	 *
	 * We use this to fire off update checks to each of the plugins we're handling updates
	 * for, and populate the results in the update check object.
	 *
	 * @author John Blackbourn
	 * @param  object $update The plugin update check object.
	 * @return object         The updated update check object.
	 */
	function check_plugins( $update ) {
		if ( !isset( $this->handlers['plugin'] ) )
			return $update;
		return $this->check( $update, $this->handlers['plugin'] );
	}

	/**
	 * Called immediately before the theme update check results are saved in a transient.
	 *
	 * We use this to fire off update checks to each of the themes we're handling updates
	 * for, and populate the results in the update check object.
	 *
	 * @author John Blackbourn
	 * @param  object $update Theme update check object.
	 * @return object         Updated update check object.
	 */
	function check_themes( $update ) {
		if ( !isset( $this->handlers['theme'] ) )
			return $update;
		return $this->check( $update, $this->handlers['theme'] );
	}

	/**
	 * Fire off update checks for each of the handlers specified and populate the results in
	 * the update check object.
	 *
	 * @author John Blackbourn
	 * @param  object $update   Update check object.
	 * @param  array  $handlers Handlers that we're interested in.
	 * @return object           Updated update check object.
	 */
	public function check( $update, array $handlers ) {

		if ( empty( $update->checked ) )
			return $update;

		foreach ( array_filter( $handlers ) as $handler ) {

			$handler_update = $handler->get_update();

			if ( $handler_update->get_new_version() and 1 === version_compare( $handler_update->get_new_version(), $handler->get_current_version() ) ) {
				if ( 'plugin' == $handler->get_type() )
					$update->response[ $handler->get_file() ] = (object) $handler_update->get_data_to_store();
				else
					$update->response[ $handler->get_file() ] = $handler_update->get_data_to_store();
			}

		}

		return $update;

	}

	/**
	 * Get the update handler for the given item, if one is present.
	 *
	 * @author John Blackbourn
	 * @param  string             $type Handler type (either 'plugin' or 'theme').
	 * @param  string             $file Item base file name.
	 * @param  EUAPI_Item|null    $item Item object for the plugin/theme. Optional.
	 * @return EUAPI_Handler|null       Update handler object, or null if no update handler is present.
	 */
	function get_handler( $type, $file, $item = null ) {

		if ( isset( $this->handlers[$type] ) and array_key_exists( $file, $this->handlers[$type] ) )
			return $this->handlers[$type][$file];

		if ( !$item )
			$item = $this->populate_item( $type, $file );

		if ( !$item )
			$handler = null;
		else
			$handler = apply_filters( "euapi_{$type}_handler", null, $item );

		$this->handlers[$type][$file] = $handler;

		return $handler;

	}

	/**
	 * Returns the item data for a given item, typically by reading the item file header
	 * and populating its data.
	 *
	 * @author John Blackbourn
	 * @param  string          $type Handler type (either 'plugin' or 'theme').
	 * @param  string          $file Item base file name.
	 * @return EUAPI_Item|null       Item object or null on failure.
	 */
	function populate_item( $type, $file ) {

		switch ( $type ) {

			case 'plugin':
				if ( $data = self::get_plugin_data( $file ) )
					return new EUAPI_Item_Plugin( $file, $data );
				break;

			case 'theme':
				if ( $data = self::get_theme_data( $file ) )
					return new EUAPI_Item_Theme( $file, $data );
				break;

		}

		return null;

	}

	/**
	 * Get data for a plugin by reading its file header.
	 *
	 * @param  string      $file Plugin base file name.
	 * @return array|false       Array of plugin data, or false on failure.
	 */
	public static function get_plugin_data( $file ) {

		require_once ABSPATH . '/wp-admin/includes/plugin.php';

		if ( file_exists( $plugin =  WP_PLUGIN_DIR . '/' . $file ) )
			return get_plugin_data( $plugin );

		return false;

	}

	/**
	 * Get data for a theme by reading its file header.
	 *
	 * @param  string      $file Theme directory name.
	 * @return array|false       Array of theme data, or false on failure.
	 */
	public static function get_theme_data( $file ) {

		$theme = wp_get_theme( $file );

		if ( !$theme->exists() )
			return false;

		$data = array(
			'Name'        => '',
			'ThemeURI'    => '',
			'Description' => '',
			'Author'      => '',
			'AuthorURI'   => '',
			'Version'     => '',
			'Template'    => '',
			'Status'      => '',
			'Tags'        => '',
			'TextDomain'  => '',
			'DomainPath'  => '',
		);

		foreach ( $data as $k => $v )
			$data[$k] = $theme->get( $k );

		return $data;

	}

	/**
	 * When the Plugin API performs an action, this callback is fired, allowing us to override the API method
	 * for a given action.
	 *
	 * Here, we override the action which fetches plugin information from the wp.org API
	 * and return our own plugin information if necessary.
	 *
	 * @param  bool|object              $default Default return value for this request. Usually boolean false.
	 * @param  string                   $action  API function being performed.
	 * @param  object                   $plugin  Plugin Info API object.
	 * @return bool|WP_Error|EUAPI_Info          EUAPI Info object, WP_Error object on failure, $default if we're not interfering.
	 */
	public function get_plugin_info( $default, $action, $plugin ) {

		if ( 'plugin_information' != $action )
			return $default;
		if ( false === strpos( $plugin->slug, '/' ) )
			return $default;

		if ( !( $handler = $this->get_handler( 'plugin', $plugin->slug ) ) )
			return $default;

		return $handler->get_info();

	}

	/**
	 * When the Theme API performs an action, this callback is fired, allowing us to override the API method
	 * for a given action.
	 *
	 * Here, we override the action which fetches theme information from the wp.org API
	 * and return our own theme information if necessary.
	 *
	 * @param  bool|object              $default Default return value for this request. Usually boolean false.
	 * @param  string                   $action  API function being performed.
	 * @param  object                   $theme   Theme Info API object.
	 * @return bool|WP_Error|EUAPI_Info          EUAPI Info object, WP_Error object on failure, $default if we're not interfering.
	 */
	public function get_theme_info( $default, $action, $theme ) {

		if ( 'theme_information' != $action )
			return $default;

		if ( !( $handler = $this->get_handler( 'theme', $theme->slug ) ) )
			return $default;

		return $handler->get_info();

	}

	/**
	 * Fetch the contents of a URL.
	 *
	 * @author John Blackbourn
	 * @param  string   $url   URL to fetch.
	 * @param  array    $args  Array of arguments passed to wp_remote_get().
	 * @return WP_Error|string WP_Error object on failure, string contents of file on success.
	 */
	public static function fetch( $url, array $args = null ) {

		$args = wp_parse_args( $args, array(
			'timeout' => 5
		) );

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) )
			return $response;

		if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'fetch_failed', sprintf( __( 'Received HTTP response code %s (%s).', 'euapi' ),
				esc_html( wp_remote_retrieve_response_code( $response ) ),
				esc_html( wp_remote_retrieve_response_message( $response ) )
			) );
		}

		return wp_remote_retrieve_body( $response );

	}

	public static function get_content_data( $content, array $all_headers ) {

		# @see WordPress' get_file_data()

		// Pull only the first 8kiB of the file in.
		if ( function_exists( 'mb_substr' ) )
			$file_data = mb_substr( $content, 0, 8192 );
		else
			$file_data = substr( $content, 0, 8192 );

		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );

		foreach ( $all_headers as $field => $regex ) {
			if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
				$all_headers[ $field ] = _cleanup_header_comment( $match[1] );
			else
				$all_headers[ $field ] = '';
		}

		return $all_headers;
	}

	public function upgrader_pre_install( $true, array $hook_extra ) {

		if ( isset( $hook_extra['plugin'] ) )
			$this->get_handler( 'plugin', $hook_extra['plugin'] );
		else if ( isset( $hook_extra['theme'] ) )
			$this->get_handler( 'theme', $hook_extra['theme'] );

		return $true;

	}

	public function upgrader_post_install( $true, array $hook_extra, array $result ) {

		global $wp_filesystem;

		if ( isset( $hook_extra['plugin'] ) )
			$handler = $this->get_handler( 'plugin', $hook_extra['plugin'] );
		else if ( isset( $hook_extra['theme'] ) )
			$handler = $this->get_handler( 'theme', $hook_extra['theme'] );
		else
			return $true;

		if ( !$handler )
			return $true;

		switch ( $handler->get_type() ) {

			case 'plugin':
				$proper_destination = WP_PLUGIN_DIR . '/' . $handler->config['folder_name'];
				break;
			case 'theme':
				$proper_destination = get_theme_root( $handler->config['file_name'] ) . '/' . $handler->config['file_name'];
				break;

		}

		// Move
		$move = $wp_filesystem->move( $result['destination'], $proper_destination );
		$result['destination'] = $proper_destination;

		return $result;

	}

}

endif; // endif class exists
