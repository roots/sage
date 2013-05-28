<?php

/*
 * Adds the featured image on post archives
 */
function shoestrap_add_featured_image_on_archives() {
  // Get the customizer options
  $archive_feat_img_toggle  = shoestrap_getVariable( 'feat_img_archive' );
  $archive_feat_img_width   = shoestrap_getVariable( 'feat_img_archive_width' );
  $archive_feat_img_height  = shoestrap_getVariable( 'feat_img_archive_height' );

  $url    = wp_get_attachment_url( get_post_thumbnail_id() );
  $width  = $archive_feat_img_width;
  $height = $archive_feat_img_height;
  $crop   = true;
  $retina = false;

  if ( $archive_feat_img_toggle == 1 ) {
    if ( '' != get_the_post_thumbnail() ) {
      // Call the resizing function (returns an array)
      $image = matthewruddy_image_resize( $url, $width, $height, $crop, $retina );

      echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
    }
  }
}
add_action( 'shoestrap_entry_summary_begin', 'shoestrap_add_featured_image_on_archives', 40 );

/*
 * Adds the featured image on single posts
 */
function shoestrap_add_featured_image_on_posts() {
  $url    = wp_get_attachment_url( get_post_thumbnail_id() );
  $width  = shoestrap_getVariable( 'feat_img_post_width' );
  $height = shoestrap_getVariable( 'feat_img_post_height' );
  $crop   = true;
  $retina = false;


  if ( shoestrap_getVariable( 'feat_img_post' ) == 1 && get_the_post_thumbnail() != '' ) {
    echo 'width: ' . $width . ' height: ' . $height . ' crop: ' . $crop . ' retina: ' . $retina . ' url: ' . $url;
    // Call the resizing function (returns an array)
    $image = matthewruddy_image_resize( $url, $width, $height, $crop, $retina );
    echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
  }
}
add_action( 'shoestrap_before_the_content', 'shoestrap_add_featured_image_on_posts', 40 );
