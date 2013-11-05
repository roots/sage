<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'EUAPI_Item_Plugin' ) ) :

/**
 * EUAPI plugin item. A simple container for plugin information, usually fetched priorly via
 * file headers or an external source.
 */
class EUAPI_Item_Plugin extends EUAPI_Item {

	var $type = 'plugin';

	function __construct( $plugin, array $data ) {

		$this->file    = $plugin;
		$this->url     = $data['PluginURI'];
		$this->version = $data['Version'];
		$this->data    = $data;

	}

}

endif;
