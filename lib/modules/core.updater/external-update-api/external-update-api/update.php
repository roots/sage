<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'EUAPI_Update' ) ) :

/**
 * EUAPI update item. Contains information about an available update.
 */
class EUAPI_Update {

	function __construct( array $args ) {

		$this->slug           = $args['slug'];
		$this->new_version    = $args['new_version'];
		$this->upgrade_notice = '';
		$this->url            = $args['url'];
		$this->package        = $args['package'];

	}

	function get_data_to_store() {
		return get_object_vars( $this );
	}

	function get_new_version() {
		return $this->new_version;
	}

}

endif;
