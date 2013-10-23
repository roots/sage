<?php
// https://gist.github.com/woogist/6379275
add_filter( 'woocommerce_catalog_settings', 'add_woocommerce_dimension_unit_league' );
 
/**
 * This adds the new unit to the WooCommerce admin
 */
function add_woocommerce_dimension_unit_league( $settings ) {
  foreach ( $settings as &$setting ) {
 
		if ( 'woocommerce_dimension_unit' == $setting['id'] ) {
			$setting['options']['league'] = __( 'League' );  // new unit
		}
	}
 
	return $settings;
}