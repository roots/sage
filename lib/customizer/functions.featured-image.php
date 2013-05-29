<?php

/*
 * Display featured images on individual posts
 */
function shoestrap_featured_image() {
  $toggle = shoestrap_getVariable( 'feat_img_post' );
  $url    = wp_get_attachment_url( get_post_thumbnail_id() );
  $width  = shoestrap_getVariable( 'feat_img_post_width' );
  $height = shoestrap_getVariable( 'feat_img_post_height' );
  $crop   = true;
  $retina = false;

  if ( has_post_thumbnail() && '' != get_the_post_thumbnail() ):
    $image = matthewruddy_image_resize( $url, $width, $height, $crop, $retina );
    echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
  endif;
}
add_action( 'shoestrap_before_the_content', 'shoestrap_featured_image' );

/*
 * Display featured images on post archives
 */
function shoestrap_featured_image_on_archives() {
  $toggle = shoestrap_getVariable( 'feat_img_archive' );
  $url    = wp_get_attachment_url( get_post_thumbnail_id() );
  $width  = shoestrap_getVariable( 'feat_img_archive_width' );
  $height = shoestrap_getVariable( 'feat_img_archive_height' );
  $crop   = true;
  $retina = false;

  if ( '' != get_the_post_thumbnail() ):
    $image = matthewruddy_image_resize( $url, $width, $height, $crop, $retina );
    echo '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';
  endif;
}
add_action( 'shoestrap_pre_entry_summary', 'shoestrap_featured_image_on_archives' );
