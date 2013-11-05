<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'EUAPI_Item' ) ) :

/**
 * Abstract EUAPI Item class upon which to build a plugin item or theme item.
 */
abstract class EUAPI_Item {

	function get_version() {
		return $this->version;
	}

	function get_url() {
		return $this->url;
	}

}

endif;
