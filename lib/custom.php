<?php
/**
 * Atkore functions
 */
define( 'ACF_LITE' , true );
include_once('/wp-content/plugins/advanced-custom-fields/acf.php' );

require_once locate_template('/lib/custom/post-types/applications.php');
//require_once locate_template('/lib/custom/post-types/attachments.php');
require_once locate_template('/lib/custom/post-types/brands.php');
require_once locate_template('/lib/custom/post-types/carousel.php');
require_once locate_template('/lib/custom/post-types/events.php');
require_once locate_template('/lib/custom/post-types/locations.php');
require_once locate_template('/lib/custom/post-types/resources.php');
//require_once locate_template('/lib/custom/post-types/markets.php');
//require_once locate_template('/lib/custom/post-types/products.php');
//require_once locate_template('/lib/custom/post-types/services.php');

require_once locate_template('/lib/custom/options.php');
require_once locate_template('/lib/custom/attachments.php');
require_once locate_template('/lib/custom/categories.php');
require_once locate_template('/lib/custom/classes.php');
require_once locate_template('/lib/custom/content-filters.php');
require_once locate_template('/lib/custom/convert-to-slug.php');
require_once locate_template('/lib/custom/dashboard.php');
require_once locate_template('/lib/custom/dropdown.php');
require_once locate_template('/lib/custom/filter-ptags-on-images.php');
require_once locate_template('/lib/custom/login.php');
require_once locate_template('/lib/custom/widgets.php');
//require_once locate_template('/lib/custom/wp-advanced-search/wpas.php');

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<section id="main">Hello there tough guy';
}

function my_theme_wrapper_end() {
  echo '</section>';
}

function my_connection_types() {
  p2p_register_connection_type( array(
  	'name' => 'posts_to_pages',
  	'from' => 'product',
  	'to' => 'resource',
  	'sortable' => 'any',
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array(
  	'name' => 'posts_to_posts',
  	'from' => 'resource',
  	'to' => 'resource',
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array(
  	'name' => 'posts_to_posts',
  	'from' => 'pa_finish',
  	'to' => 'resource',
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array( 
  	'name' => 'Office',
  	'from' => 'distributor',
  	'to' => 'location',
  	'reciprocal' => true,
  	'title' => 'Office location',
  	'admin_dropdown' => 'any'
  ) );
  
  p2p_register_connection_type( array( 
  	'name' => 'atkore_manager',
  	'from' => 'distributor',
  	'to' => 'distributor',
  	'cardinality' => 'one-to-many',
  	'title' => array( 'from' => 'Managed by', 'to' => 'Manages' )
  ) );

}
add_action( 'p2p_init', 'my_connection_types' );