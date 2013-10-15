<?php
/**
 * Atkore functions
 */
//define( 'ACF_LITE' , true );
//include_once('/wp-content/plugins/advanced-custom-fields/acf.php' );
// Add-ons 
//include_once('/wp-content/plugins/acf-repeater/acf-repeater.php');
//include_once('/wp-content/plugins/acf-gallery/acf-gallery.php');
//include_once('/wp-content/plugins/acf-flexible-content/acf-flexible-content.php');
//include_once('/wp-content/plugins/acf-options-page/acf-options-page.php' );

require_once locate_template('/lib/custom/post-types.php');
require_once locate_template('/lib/custom/acf/options-branding.php');
require_once locate_template('/lib/custom/acf/templates.php');
require_once locate_template('/lib/custom/acf/layout.php');

require_once locate_template('/lib/custom/admin.php');
require_once locate_template('/lib/custom/options.php');
require_once locate_template('/lib/custom/login.php');
require_once locate_template('/lib/custom/attachments.php');
require_once locate_template('/lib/custom/categories.php');
require_once locate_template('/lib/custom/connections.php');
require_once locate_template('/lib/custom/classes.php');
require_once locate_template('/lib/custom/content-filters.php');
require_once locate_template('/lib/custom/convert-to-slug.php');
require_once locate_template('/lib/custom/dashboard.php');
require_once locate_template('/lib/custom/dropdown.php');
require_once locate_template('/lib/custom/filter-ptags-on-images.php');
require_once locate_template('/lib/custom/widgets.php');
require_once locate_template('/lib/custom/wp-advanced-search/wpas.php');
require_once locate_template('/lib/custom/woocommerce/theme-wrapper.php');

//require_once locate_template('/lib/custom/pdfjs/wp_pdfjs.php');

add_filter( 'woocommerce_enqueue_styles', '__return_false' );
remove_action( 'woocommerce_product_tabs', 'woocommerce_product_reviews_tab', 30);
remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_reviews_panel', 30);

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