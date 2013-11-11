<?php
/*
Plugin Name: Atkore Post Types: Media
Plugin URI: http://atkore.com
Description: Adds taxonomy to attachments (Media)
Version: 1.0
Author: Maintain Web
Author URI: http://maintainweb.co/
License: GPL
Copyright: Maintain Web
*/

if ( ! function_exists('atkore_post_type_attachment') ) {

// Register Custom Post Types
function atkore_post_type_attachment() {
    $admin_img_path = 'http://atkore.com/assets/img/atkore-admin-icon.png';
  	
  	$labels = array(
  		'name'                       => _x( 'Media Type', 'Taxonomy General Name', 'atkore' ),
  		'singular_name'              => _x( 'Media Type', 'Taxonomy Singular Name', 'atkore' ),
  		'menu_name'                  => __( 'Media Types', 'atkore' ),
  		'all_items'                  => __( 'All Media Types', 'atkore' ),
  		'parent_item'                => __( 'Parent Media Type', 'atkore' ),
  		'parent_item_colon'          => __( 'Parent Media Type:', 'atkore' ),
  		'new_item_name'              => __( 'New Media Type Name', 'atkore' ),
  		'add_new_item'               => __( 'Add New Media Type', 'atkore' ),
  		'edit_item'                  => __( 'Edit Media Type', 'atkore' ),
  		'update_item'                => __( 'Update Media Type', 'atkore' ),
  		'separate_items_with_commas' => __( 'Separate Media Types with commas', 'atkore' ),
  		'search_items'               => __( 'Search Media Types', 'atkore' ),
  		'add_or_remove_items'        => __( 'Add or remove Media Types', 'atkore' ),
  		'choose_from_most_used'      => __( 'Choose from the most used Media Types', 'atkore' ),
  	);
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => false,
  		'query_var'                  => 'media-type',
  	);

  	register_taxonomy( 'media-type', 'attachment', $args );

}

// Hook into the 'init' action
add_action( 'init', 'atkore_post_type_attachment', 0 );

}
// Bootstrap Popover on Gallery Images
function gallery_popover( $postID ) {
	$args = array(
		'numberposts' => 1,
		'order' => 'ASC',
		'post_mime_type' => 'image',
		'post_parent' => $postID,
		'post_status' => null,
		'post_type' => 'attachment',
	);

	$attachments = get_children( $args );

	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {
			$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' )  ? wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ) : wp_get_attachment_image_src( $attachment->ID, 'full' );

			echo '<img src="' . wp_get_attachment_thumb_url( $attachment->ID ) . '" class="current">';
		}
	}
}

// Get thumbnail image path
function get_thumbnail_path($post_ID) {
	$post_image_id = get_post_thumbnail_id($post_ID->ID);
	if ($post_image_id) {
		$thumbnail = wp_get_attachment_image_src( $post_image_id, 'post-thumbnail', false);
		if ($thumbnail) (string)$thumbnail = $thumbnail[0];
		return $thumbnail;
	}	
}

// Add SVG to upload options
function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );