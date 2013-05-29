<?php

function shoestrap_background_css( ) {
  // $background is the saved custom image, or the default image.
  if ( shoestrap_getVariable( 'background_image_toggle' ) == 1 ) {
    if ( shoestrap_getVariable( 'background_custom_image' ) != "" )
      $background = set_url_scheme( shoestrap_getVariable( 'background_custom_image' ) );
    else if ( shoestrap_getVariable( 'background_image' ) != "" )
      $background = set_url_scheme( shoestrap_getVariable( 'background_image' ) );
  } else if ( shoestrap_getVariable( 'background_pattern_toggle' ) == 1 && shoestrap_getVariable( 'background_pattern' ) != "" ) {
    $background = shoestrap_getVariable( 'background_pattern' );
  }

  if (shoestrap_getVariable( 'color_body_bg' ) != "") {
    $color = '#' . str_replace( '#', '', shoestrap_getVariable( 'color_body_bg' ) );
  }

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: $color;" : '';

  if ( shoestrap_getVariable( 'background_fixed_toggle' ) == 1 ) {
    $style .= "background-attachment: fixed;";
  }

  if ( $background ) {
    $image .= "background-image: url( '$background' );";
  }

  if ( shoestrap_getVariable( 'background_image_toggle' ) == 1 && ( shoestrap_getVariable( 'background_custom_image' ) != "" || shoestrap_getVariable( 'background_image' ) != "" ) ) {
    if ( shoestrap_getVariable( 'background_image_position_toggle' ) == 0 ) {
      if ( shoestrap_getVariable( 'background_fixed_toggle' ) == 1 ) {
        $style .= "background-position: 50% 50%;";
        $style .= "background-size: cover;";
      } else {
        $style .= "background-position: 50% 0%;";
        $style .= "background-size: contain;";
        $style .= "background-repeat: no-repeat;";
      }
    } else { // Not fixed position, custom
      $repeat = shoestrap_getVariable( 'background_repeat' );
      if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) {
        $repeat = 'repeat';
      }
      if ($repeat == "no-repeat") {
        $style .= "background-size: auto;";
      }
      $repeat = " background-repeat: $repeat;";
      $position = shoestrap_getVariable( 'background_position_x', 'left' );
      if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) ) {
        $position = 'left';
      }
      $position = " background-position: top $position;";
    }
  } 

  $style .= $image . $repeat . $position;
  
  echo '<style type="text/css" id="background">';
  echo 'body {' . trim( $style ) . '}';
  echo $color ? ".wrap.main-section{background: $color;}" : '';
  echo '</style>';
}
if ( shoestrap_getVariable( 'background_image_toggle' ) == 1 || shoestrap_getVariable( 'background_pattern_toggle' ) == 1 )
  add_action( 'wp_head', 'shoestrap_background_css' );