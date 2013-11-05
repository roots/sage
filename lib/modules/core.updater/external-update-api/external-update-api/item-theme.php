<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'EUAPI_Item_Theme' ) ) :

/**
 * EUAPI plugin item. A simple container for theme information, usually fetched priorly via
 * file headers or an external source.
 */
class EUAPI_Item_Theme extends EUAPI_Item {

	var $type = 'theme';

	function __construct( $theme, array $data ) {

		$this->file    = $theme;
		$this->url     = $data['ThemeURI'];
		$this->version = $data['Version'];
		$this->data    = $data;

	}

}

endif;
