<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'EUAPI_Handler' ) ) :

/**
 * Abstract class upon which to build update handlers.
 */
abstract class EUAPI_Handler {

	/**
	 * Class constructor
	 *
	 * @param  array $config Configuration for the handler.
	 * @return void
	 */
	public function __construct( array $config = array() ) {
		$defaults = array(
			'timeout' => 5,
		);
		$this->config = apply_filters( "euapi_{$config['type']}_handler_config", array_merge( $defaults, $config ) );
	}

	/**
	 * Return the URL of the item's homepage.
	 *
	 * @abstract
	 * @return string URL of the item's homepage.
	 */
	abstract public function get_homepage_url();

	/**
	 * Return the URL of the item's ZIP package.
	 *
	 * @abstract
	 * @return string URL of the item's ZIP package.
	 */
	abstract public function get_package_url();

	/**
	 * Fetch the latest version number of the item, typically from an external location.
	 *
	 * @abstract
	 * @return string|false Version number, or false on failure.
	 */
	abstract public function fetch_new_version();

	/**
	 * Fetch info about the latest version of the item.
	 *
	 * @abstract
	 * @return EUAPI_info|WP_Error An EUAPI_Info object, or a WP_Error object on failure.
	 */
	abstract public function fetch_info();

	/**
	 * Get the current item's base file name (eg. my-plugin/my-plugin.php or my-theme/style.css).
	 *
	 * @author John Blackbourn
	 * @return string File name
	 */
	final public function get_file() {
		return $this->config['file'];
	}

	/**
	 * Get the current installed version number of the item.
	 *
	 * @author John Blackbourn
	 * @return string|false Version number, or false on failure.
	 */
	final public function get_current_version() {

		if ( isset( $this->item ) )
			return $this->item->get_version();
		else
			return false;

	}

	/**
	 * Get the latest version number of the item.
	 *
	 * @author John Blackbourn
	 * @return string|false Version number, or false on failure.
	 */
	final public function get_new_version() {

		if ( !isset( $this->new_version ) )
			$this->new_version = $this->fetch_new_version();

		return $this->new_version;

	}

	/**
	 * Get the update object for the item.
	 *
	 * @author John Blackbourn
	 * @return EUAPI_Update Object containing various info about the latest update.
	 */
	final public function get_update() {

		if ( isset( $this->update ) )
			return $this->update;

		$package = add_query_arg( array(
			'_euapi_type' => $this->get_type(),
			'_euapi_file' => $this->get_file()
		), $this->get_package_url() );

		return $this->update = new EUAPI_Update( array(
			'slug'        => $this->get_file(),
			'new_version' => $this->get_new_version(),
			'url'         => $this->get_homepage_url(),
			'package'     => $package,
			'config'      => $this->get_config(),
		) );

	}

	/**
	 * Get the info object for the item.
	 *
	 * @author John Blackbourn
	 * @return EUAPI_info|WP_Error An EUAPI_Info object, or a WP_Error object on failure.
	 */
	final public function get_info() {

		if ( !isset( $this->info ) )
			$this->info = $this->fetch_info();

		return $this->info;

	}

	/**
	 * Helper function to get the current item config.
	 *
	 * @author John Blackbourn
	 * @return array Config array.
	 */
	final public function get_config() {
		return $this->config;
	}

	/**
	 * Helper function to get the handler type (either 'plugin' or 'theme').
	 *
	 * @author John Blackbourn
	 * @return string Handler type.
	 */
	final public function get_type() {
		return $this->config['type'];
	}

}

endif; // endif class exists
