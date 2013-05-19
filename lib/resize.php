<?php

function shoestrap_image_resize( $url, $width = NULL, $height = NULL, $crop = true, $retina = false ) {
  matthewruddy_image_resize( $url, $width, $height, $crop, $retina );
}

function shoestrap_custom_image_resize( $width = '', $height = '', $crop = true, $retina = false, $echo = true ) {

  $url    = wp_get_attachment_url( get_post_thumbnail_id() );

  if ( '' != get_the_post_thumbnail() ) {
    // Call the resizing function (returns an array)
    $image = shoestrap_image_resize( $url, $width, $height, $crop, $retina );

    $imagelink = '<a href="' . get_permalink() . '"><img src="' . $image['url'] . '" /></a>';

    if ( $echo == true )
      echo $imagelink;
    else
      return $imagelink;
  }
}
