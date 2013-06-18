<?php

/*
 * Display featured images on individual posts
 */
function shoestrap_featured_image() {
  if ( is_single() ) {
    if ( shoestrap_getVariable( 'feat_img_post' ) == 0 )
      return;
    $url      = wp_get_attachment_url( get_post_thumbnail_id() );
    if (shoestrap_getVariable( 'feat_img_post_custom_toggle' ) == 1) {
      $width  = shoestrap_getVariable( 'feat_img_post_width' );
    } else {
      $width  = shoestrap_content_width_px();
    }
    $height   = shoestrap_getVariable( 'feat_img_post_height' );
  } else {
    if ( shoestrap_getVariable( 'feat_img_archive' ) == 0 )
      return;
    $url      = wp_get_attachment_url( get_post_thumbnail_id() );
    if (shoestrap_getVariable( 'feat_img_archive_custom_toggle' ) == 1) {
      $width  = shoestrap_getVariable( 'feat_img_archive_width' );
    } else {
      $width  = shoestrap_content_width_px();
    }
    $height   = shoestrap_getVariable( 'feat_img_archive_height' );    
  }


  $crop     = true;
  if ( shoestrap_getVariable( 'retina_toggle' ) == 1 )
    $retina   = true;

  if ( has_post_thumbnail() && '' != get_the_post_thumbnail() ):
    $image = matthewruddy_image_resize( $url, $width, $height, $crop, $retina );
    echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
  endif;
}
add_action( 'shoestrap_before_the_content', 'shoestrap_featured_image' );
