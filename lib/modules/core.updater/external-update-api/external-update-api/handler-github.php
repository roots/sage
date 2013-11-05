<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'EUAPI_Handler_GitHub' ) ) :

/**
 * EUAPI handler for plugins and themes hosted on GitHub.com.
 * 
 * Supports public and private repos.
 * 
 * If a repo is private then a valid OAuth access token must be passed in the 'access_token' argument.
 * See http://developer.github.com/v3/oauth/ for details.
 */
class EUAPI_Handler_GitHub extends EUAPI_Handler {

	/**
	 * Class constructor
	 *
	 * @param  array $config Configuration for the handler.
	 * @return void
	 */
	public function __construct( array $config = array() ) {

		if ( !isset( $config['github_url'] ) or !isset( $config['file'] ) )
			return;

		$defaults = array(
			'type'         => 'plugin',
			'access_token' => null,
			'folder_name'  => dirname( $config['file'] ),
			'file_name'    => basename( $config['file'] ),
			'sslverify'    => true,
		);

		$path = trim( parse_url( $config['github_url'], PHP_URL_PATH ), '/' );
		list( $username, $repo ) = explode( '/', $path, 2 );

		$defaults['base_url'] = sprintf( 'https://raw.github.com/%1$s/%2$s/master',
			$username,
			$repo
		);
		$defaults['zip_url'] = sprintf( 'https://api.github.com/repos/%1$s/%2$s/zipball',
			$username,
			$repo
		);

		$config = wp_parse_args( $config, $defaults );

		parent::__construct( $config );

	}

	/**
	 * Fetch the latest version number from the GitHub repo. Does this by fetching the plugin
	 * file and then parsing the header to get the version number.
	 *
	 * @author John Blackbourn
	 * @return string|false Version number, or false on failure.
	 */
	public function fetch_new_version() {

		$response = EUAPI::fetch( $this->get_file_url(), array(
			'sslverify' => $this->config['sslverify'],
			'timeout'   => $this->config['timeout'],
		) );

		if ( is_wp_error( $response ) )
			return false;

		$data = EUAPI::get_content_data( $response, array(
			'version' => 'Version'
		) );

		if ( empty( $data['version'] ) )
			return false;

		return $data['version'];

	}

	/**
	 * Returns the URL of the plugin or theme's homepage.
	 *
	 * @author John Blackbourn
	 * @return string URL of the plugin or theme's homepage.
	 */
	function get_homepage_url() {

		return $this->config['github_url'];

	}

	/**
	 * Returns the URL of the plugin or theme file on GitHub, with access token appended if relevant.
	 *
	 * @author John Blackbourn
	 * @param  string $file Optional file name. Defaults to base plugin file or theme stylesheet.
	 * @return string URL of the plugin file.
	 */
	function get_file_url( $file = null ) {

		if ( empty( $file ) )
			$file = $this->config['file_name'];

		$url = trailingslashit( $this->config['base_url'] ) . $file;

		if ( !empty( $this->config['access_token'] ) ) {
			$url = add_query_arg( array(
				'access_token' => $this->config['access_token']
			), $url );
		}

		return $url;
	}

	/**
	 * Returns the URL of the plugin or theme's ZIP package on GitHub, with access token appended if relevant.
	 *
	 * @author John Blackbourn
	 * @return string URL of the plugin or theme's ZIP package.
	 */
	function get_package_url() {

		$url = $this->config['zip_url'];

		if ( !empty( $this->config['access_token'] ) ) {
			$url = add_query_arg( array(
				'access_token' => $this->config['access_token']
			), $url );
		}

		return $url;

	}

	/**
	 * Fetch info about the latest version of the item.
	 *
	 * @author John Blackbourn
	 * @return EUAPI_info|WP_Error An EUAPI_Info object, or a WP_Error object on failure.
	 */
	function fetch_info() {

		$fields = array(
			'author'      => 'Author',
			'description' => 'Description'
		);

		switch ( $this->get_type() ) {

			case 'plugin':
				$file = $this->get_file_url();
				$fields['plugin_name'] = 'Plugin Name';
				break;

			case 'theme':
				$file = $this->get_file_url( 'style.css' );
				$fields['theme_name'] = 'Theme Name';
				break;

		}

		$response = EUAPI::fetch( $file, array(
			'sslverify' => $this->config['sslverify'],
			'timeout'   => $this->config['timeout'],
		) );

		if ( is_wp_error( $response ) )
			return $response;

		$data = EUAPI::get_content_data( $response, $fields );

		$info = array_merge( $data, array(

			'slug'          => $this->get_file(),
			'version'       => $this->get_new_version(),
			'homepage'      => $this->get_homepage_url(),
			'download_link' => $this->get_package_url(),
	#		'requires'      => '',
	#		'tested'        => '',
	#		'last_updated'  => '',
			'downloaded'    => 0,
			'sections'      => array(
				'description' => $data['description'],
			),

		) );

		return new EUAPI_Info( $info );

	}

}

endif; // endif class exists
