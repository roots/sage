<?php

/*
 * Adds the featured image on post archives
 */
function shoestrap_add_featured_image_on_archives() {
  // Get the customizer options
  $archive_feat_img_toggle  = get_theme_mod( 'feat_img_archive', 1 );
  $archive_feat_img_width   = get_theme_mod( 'feat_img_archive_width', 550 );
  $archive_feat_img_height  = get_theme_mod( 'feat_img_archive_height', 330 );

  $url    = wp_get_attachment_url( get_post_thumbnail_id() );
  $width  = $archive_feat_img_width;
  $height = $archive_feat_img_height;
  $crop   = true;
  $retina = false;

  if ( $archive_feat_img_toggle == 1 ) {
    if ( '' != get_the_post_thumbnail() ) {
      // Call the resizing function (returns an array)
      $image = shoestrap_image_resize( $url, $width, $height, $crop, $retina );

      echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
    }
  }
}
add_action( 'shoestrap_entry_summary_begin', 'shoestrap_add_featured_image_on_archives', 40 );

/*
 * Adds the featured image on single posts
 */
function shoestrap_add_featured_image_on_posts() {
  // Get the customizer options
  $post_feat_img_toggle = get_theme_mod( 'feat_img_post', 1 );
  $post_feat_img_width  = get_theme_mod( 'feat_img_post_width', 550 );
  $post_feat_img_height = get_theme_mod( 'feat_img_post_height', 330 );

  $url    = wp_get_attachment_url( get_post_thumbnail_id() );
  $width  = $post_feat_img_width;
  $height = $post_feat_img_height;
  $crop   = true;
  $retina = false;

  if ( $post_feat_img_toggle == 1 ) {
    if ( '' != get_the_post_thumbnail() ) {
      // Call the resizing function (returns an array)
      $image = shoestrap_image_resize( $url, $width, $height, $crop, $retina );

      echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
    }
  }
}
add_action( 'shoestrap_before_the_content', 'shoestrap_add_featured_image_on_posts', 40 );
