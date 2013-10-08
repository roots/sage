<?php
require_once locate_template('/lib/custom/post-types/applications.php');
require_once locate_template('/lib/custom/post-types/attachments.php');
require_once locate_template('/lib/custom/post-types/brands.php');
require_once locate_template('/lib/custom/post-types/carousel.php');
require_once locate_template('/lib/custom/post-types/events.php');
require_once locate_template('/lib/custom/post-types/locations.php');
require_once locate_template('/lib/custom/post-types/resources.php');
//require_once locate_template('/lib/custom/post-types/markets.php');
//require_once locate_template('/lib/custom/post-types/services.php');
require_once locate_template('/lib/custom/post-types/products.php');


add_filter( 'pre_get_posts', 'my_get_posts' );

function my_get_posts( $query ) {

	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', array( 'post', 'wprss_feed_item', ) );

	return $query;
}