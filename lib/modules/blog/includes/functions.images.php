<?php

if ( !function_exists( 'shoestrap_featured_image' ) ) :
/*
 * Display featured images on individual posts
 */
function shoestrap_featured_image() {

	if ( !has_post_thumbnail() || '' == get_the_post_thumbnail() )
		return;

	$data['width']  = shoestrap_content_width_px();

	if ( is_single() || is_page() ) {
		if ( shoestrap_getVariable( 'feat_img_post' ) != 1 )
			return; // Do not process if we don't want images on single posts

		$data['url'] = wp_get_attachment_url( get_post_thumbnail_id() );
		
		if (shoestrap_getVariable( 'feat_img_post_custom_toggle' ) == 1) {
			$data['width']  = shoestrap_getVariable( 'feat_img_post_width' );
			$data['height'] = shoestrap_getVariable( 'feat_img_post_height' );
		}
	} else {
		if ( shoestrap_getVariable( 'feat_img_archive' ) == 0 )
			return; // Do not process if we don't want images on post archives

		$data['url'] = wp_get_attachment_url( get_post_thumbnail_id() );
		
		if (shoestrap_getVariable( 'feat_img_archive_custom_toggle' ) == 1) {
			$data['width']  = shoestrap_getVariable( 'feat_img_archive_width' );
			$data['height'] = shoestrap_getVariable( 'feat_img_archive_height' );
		}
	}
	
	$image = shoestrap_image_resize( $data );

	echo '<a href="' . get_permalink() . '"><img class="featured-image" src="' . $image['url'] . '" /></a>';
}
endif;


if ( !function_exists( 'shoestrap_remove_featured_image_per_post_type' ) ) :
function shoestrap_remove_featured_image_per_post_type() {
	$post_types = get_post_types( array( 'public' => true ), 'names' );
	$post_type_options = shoestrap_getVariable( 'feat_img_per_post_type' );

	foreach ( $post_types as $post_type ) {
		// Simply prevents "illegal string offset" messages
		if ( !isset( $post_type_options[$post_type] ) )
			$post_type_options[$post_type] = 0;

		if ( isset( $post_type ) && is_singular( $post_type ) ) {
			add_action( 'shoestrap_page_pre_content', 'shoestrap_featured_image' );
			add_action( 'shoestrap_single_pre_content', 'shoestrap_featured_image' );
		}
	}
}
endif;
add_action( 'wp', 'shoestrap_remove_featured_image_per_post_type' );
add_action( 'shoestrap_entry_meta', 'shoestrap_featured_image', 50 );


if ( !function_exists( 'shoestrap_image' ) ) :
/*
 * Proxy function to be called whenever an image is used. If you wish to resize, use shoestrap_image_resize()
 */
function shoestrap_image( $img ) {

	if ( empty( $img ) || ( empty( $img['id'] ) && empty( $img['url'] ) ) )
		return; // Nothing here to do!

	// We don't have an attachment id
	$img['id'] = ( empty( $img['id'] ) ) ? shoestrap_get_attachment_id_from_src( $img['url'] ) : $img['id'];

	// Get the full size attachment
	$image = wp_get_attachment_image_src( $img['id'], 'full' );

	$img['url'] = $image[0];
	
	$img['width'] = $image[1];
	$img['height'] = $image[2];

	return shoestrap_image_resize( $img );
}
endif;


if ( !function_exists( 'shoestrap_image_resize' ) ) :
/*
 * Call this even if you're not using the file
 */
function shoestrap_image_resize( $data ) {

	$defaults = array(
		"url"       => "",
		"width"     => "",
		"height"    => "",
		"crop"      => true,
		"retina"    => "",
		"resize"    => true,
	);

	$settings = wp_parse_args( $data, $defaults );

	if ( empty($settings['url']) )
		return;

	// Generate the @2x file if retina is enabled
	if ( shoestrap_getVariable( 'retina_toggle' ) == 1 && empty( $settings['retina'] ) )
		$results['retina'] = matthewruddy_image_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], true );

	return matthewruddy_image_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], false );    
}
endif;

if ( !function_exists( 'shoestrap_get_attachment_id_from_src' ) ) :
/*
 * Function to grab the image via URL to see if it's an attachmenet
 */
function shoestrap_get_attachment_id_from_src( $image_src ) {
	global $wpdb;
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	$id = $wpdb->get_var( $query );
	return $id;
}
endif;