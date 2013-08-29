<?php

/**
	Display featured images on individual posts
**/
function shoestrap_featured_image() {
  add_theme_support('post-thumbnails');
	if ( !has_post_thumbnail() || '' == get_the_post_thumbnail() ) {
		return;
	}

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
  
	$image = shoestrap_image_resize($data);

  echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
  
}
add_action( 'shoestrap_page_pre_content', 'shoestrap_featured_image' );
add_action( 'shoestrap_single_pre_content', 'shoestrap_featured_image' );
add_action( 'shoestrap_after_entry_meta', 'shoestrap_featured_image' );


/**
	Proxy function to be called whenever an image is used. If you wish to resize, use shoestrap_image_resize()
**/
function shoestrap_image($img) {
	if (
			empty($img) 
			|| (empty($img['id']) && empty($img['url']))
		) {
		return; // Nothing here to do!
	}
	if (empty($img['id'])) { // We don't have an attachment id
		$img['id'] = shoestrap_get_attachment_id_from_src($img['url']);	
	}

	$image = wp_get_attachment_image_src( $img['id'], 'full' ); // Get the full size attachment	

	$img['url'] = $image[0];
	
	$img['width'] = $image[1];
	$img['height'] = $image[2];

	return shoestrap_image_resize($img);
}


/**
	Call this even if you're not using the file
**/
function shoestrap_image_resize($data) {

  $defaults = array(
    "url"       => "",
    "width"     => "",
    "height"    => "",
    "crop"			=> true,
    "retina"    => "",
    "resize"		=> true,
  );

  $settings = wp_parse_args( $data, $defaults );

  if ( empty($settings['url']) ) {
  	return;
  }

  // Generate the @2x file if retina is enabled
  if ( shoestrap_getVariable( 'retina_toggle' ) == 1 && empty($settings['retina']) ) {
  	$results['retina'] = matthewruddy_image_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], true );
  }
 	return matthewruddy_image_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], false );	  

}


/**
	Function to grab the image via URL to see if it's an attachmenet
**/
function shoestrap_get_attachment_id_from_src ($image_src) {

		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;

}


/**
	Function to see if image is remote, and if so add it as an attachment and return the right URL
		STILL IN DEVELOPMENT!
**/
	function shoestrap_check_if_remote_image($data) { // generating thumbnail for a single post
		global $post;
		
echo "here";		

		$wud = wp_upload_dir();
		$upload_parts = parse_url( $wud['baseurl'] );
		$image = $data['src'];

		$saved_in_wordpress = false;

		if ( strpos( $image, $wud['baseurl'] ) !== false || ( strpos( $image, 'http:' ) !== 0 && isset( $upload_parts['path'] ) && strpos( $image, $upload_parts['path'] ) === 0 ) ) { // image was uploaded on server in wordpress uploads directory
			$parts = pathinfo($image);
			$attachments = array();
			global $wpdb;
			$attachments = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_wp_attachment_metadata' AND meta_value like '%" . $parts['basename'] . "%'");
			if ( is_array($attachments) && count($attachments) > 0 && isset( $attachments[0]->post_id ) ) { // image was found in Wordpress database
				$saved_in_wordpress = true;
				$attachment_id = $attachments[0]->post_id;
				$thumbnail_html = wp_get_attachment_image( $attachment_id, 'thumbnail' );
				if ( !empty($thumbnail_html) ) {
					update_post_meta( $post->ID, '_thumbnail_id', $attachment_id );
				}
				echo "here";
				return wp_get_attachment_image_src($attachment_id);
			}
		}
		

		if ( !$saved_in_wordpress ) { // image is external
			// Check if it's still stored locally
			/* Restore original Post Data */


			include_once( ABSPATH . 'wp-admin/includes/image.php' );

			if ( ! ( ( $uploads = wp_upload_dir( current_time('mysql') ) ) && false === $uploads['error'] ) )
				return 102; // upload dir is not accessible

			$content = '';
			$image = rawurldecode( preg_replace('/\?.*/', '', $image) );
			$name_parts = pathinfo($image);
			$filename = wp_unique_filename( $uploads['path'], $name_parts['basename'] );
			$unique_name_parts = pathinfo($filename);
			$newfile = $uploads['path'] . "/$filename";
			// try to upload

			if ( ini_get( 'allow_url_fopen' ) ) { // check php setting for remote file access
				$content = @$wp_filesystem->get_contents( $image );
			}

			if ( empty( $content ) ) // nothing was found
				return 104;

			global $wp_filesystem;
			$wp_filesystem->put_contents(
			  $newfile,
			  $content,
			  FS_CHMOD_FILE // save image
			);

			if (! file_exists( $newfile ) ) // upload was not successful
				return 105;

			// Set correct file permissions
			$stat = stat( dirname( $newfile ) );
			$perms = $stat['mode'] & 0000666;
			@chmod( $newfile, $perms );
			// get file type
			$wp_filetype = wp_check_filetype( $newfile );
			extract($wp_filetype);

			// No file type! No point to proceed further
			if ( ( !$type || !$ext ) && !current_user_can( 'unfiltered_upload' ) )
				return 106;
			$title = $unique_name_parts['filename'];
			$content = '';

			// use image exif/iptc data for title and caption defaults if possible
			if ( $image_meta = @wp_read_image_metadata($newfile) ) {
				if ( trim($image_meta['title']) )
					$title = $image_meta['title'];
				if ( trim($image_meta['caption']) )
					$content = $image_meta['caption'];
			}

			// Compute the URL
			$url = $uploads['url'] . "/$filename";

			// Construct the attachment array
			$attachment = array(
								'post_mime_type' => $type,
								'guid' => $url,
								'post_parent' => $post_id,
								'post_title' => $title,
								'post_content' => $content,
								);
			$thumb_id = wp_insert_attachment( $attachment, $newfile, $post_id );
			
			if ( !is_wp_error($thumb_id) ) {
				$meta = wp_generate_attachment_metadata( $thumb_id, $newfile );
				wp_update_attachment_metadata( $thumb_id, $meta );
				update_post_meta( $thumb_id, 'remote', $data['src'] ); // Save the remote URL to attachment META
				update_post_meta( $post->ID, '_thumbnail_id', $thumb_id );
				return wp_get_attachment_url($thumb_id);
			}
		}
		return 200;
	} // endfunction process_images