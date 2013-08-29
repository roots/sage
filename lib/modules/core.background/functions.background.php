<?php

function shoestrap_background_css( ) {

	if ( shoestrap_getVariable( 'background_image_toggle' ) == 0 && shoestrap_getVariable( 'background_pattern_toggle' ) == 0 )
		return;

  $style = '';
  $image = '';
  $repeat = '';
  $position = '';
  // $background is the saved custom image, or the default image.
  if ( shoestrap_getVariable( 'background_image_toggle' ) == 1 ) {
    if ( shoestrap_getVariable( 'background_custom_image' ) != "" ) {
    	$background = set_url_scheme( shoestrap_getVariable( 'background_custom_image' ) );
    	$background = $background['url'];
    }  else if ( shoestrap_getVariable( 'background_image' ) != "" ) {
    	$background = set_url_scheme( shoestrap_getVariable( 'background_image' ) );
    	$background = $background['url'];
    }
  } else if ( shoestrap_getVariable( 'background_pattern_toggle' ) == 1 && shoestrap_getVariable( 'background_pattern' ) != "" ) {
    $background = shoestrap_getVariable( 'background_pattern' );
  }

  if ( shoestrap_getVariable( 'color_body_bg' ) != "" ) {
    $color = '#' . str_replace( '#', '', shoestrap_getVariable( 'color_body_bg' ) ) . ';';
  }

  //$style = ($color) ? "background-color: $color;" : '';

  if ( shoestrap_getVariable( 'color_body_bg_opacity' ) != 100 ) {
    $rgb      = shoestrap_get_rgb( $color, true );
    $opacity  = ( shoestrap_getVariable( 'color_body_bg_opacity' ) ) / 100;
    $color   .= 'background: rgba(' . $rgb . ',' . $opacity . ');';
  }

  if ( !isset($background) && !isset($color) )
    return;

  if ( shoestrap_getVariable( 'background_fixed_toggle' ) == 1 ) {
    $style .= "background-attachment: fixed;";
  }

  if ( isset($background) ) {
    $image .= "background-image: url( '$background' );";
  }

  if ( shoestrap_getVariable( 'background_image_toggle' ) == 1 && ( shoestrap_getVariable( 'background_custom_image' ) != "" || shoestrap_getVariable( 'background_image' ) != "" ) ) {
    if ( shoestrap_getVariable( 'background_image_position_toggle' ) == 0 ) {
      $style .= "background-size: cover;";
      $style .= "-webkit-background-size: cover;";
      $style .= "-moz-background-size: cover;";
      $style .= "-o-background-size: cover;";
      $style .= "background-position: 50% 50%;";
      if ( shoestrap_getVariable( 'jumbotron_background_fixed_toggle' ) == 0 ) {
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

  $theCSS = 'body {' . trim( $style ) . '}';
  $theCSS .= $color ? ".wrap.main-section .content{background: $color;}" : '';

  wp_add_inline_style( 'shoestrap_css', $theCSS );

}
add_action( 'wp_enqueue_scripts', 'shoestrap_background_css', 101 );
