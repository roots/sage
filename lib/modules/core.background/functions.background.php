<?php

if ( !function_exists( 'shoestrap_background_css' ) ) :
function shoestrap_background_css( ) {

  $image_toggle   = shoestrap_getVariable( 'background_image_toggle' );
  $bg_custom_img  = shoestrap_getVariable( 'background_custom_image' );
  $bg_img         = shoestrap_getVariable( 'background_image' );
  $pattern_toggle = shoestrap_getVariable( 'background_pattern_toggle' );
  $bg_pattern     = shoestrap_getVariable( 'background_pattern' );
  $bg_color       = shoestrap_getVariable( 'color_body_bg' );

	if ( $image_toggle == 0 && shoestrap_getVariable( 'background_pattern_toggle' ) == 0 ) :
		return;
  endif;

  $style    = '';
  $image    = '';
  $repeat   = '';
  $position = '';

  // $background is the saved custom image, or the default image.
  if ( $image_toggle == 1 ) :

    if ( $bg_custom_img != '' ) :
      $background = set_url_scheme( $bg_custom_img['url'] );
    elseif ( $bg_img != '' ) :
      $background = set_url_scheme( $bg_img['url'] );
    endif;

  elseif ( $pattern_toggle == 1 && $bg_pattern != '' ) :
    $background = set_url_scheme( $bg_pattern );
  endif;

  $color = '#' . str_replace( '#', '', $bg_color ) . ';';

  if ( shoestrap_getVariable( 'color_body_bg_opacity' ) != 100 ) :
    $rgb      = shoestrap_get_rgb( $color, true );
    $opacity  = ( shoestrap_getVariable( 'color_body_bg_opacity' ) ) / 100;
    $color   .= 'background: rgba(' . $rgb . ',' . $opacity . ');';
  endif;

  if ( shoestrap_getVariable( 'background_fixed_toggle' ) == 1 ) :
    $style .= 'background-attachment: fixed;';
  endif;

  if ( isset( $background ) ) :
    $image .= 'background-image: url( "' . $background . '" );';
  endif;

  if ( $image_toggle == 1 && ( $bg_custom_img != '' || $bg_img != '' ) ) :

    if ( shoestrap_getVariable( 'background_image_position_toggle' ) == 0 ) :
      $style .= 'background-size: cover;';
      $style .= '-webkit-background-size: cover;';
      $style .= '-moz-background-size: cover;';
      $style .= '-o-background-size: cover;';
      $style .= 'background-position: 50% 50%;';

    else :
      // Not fixed position, custom
      $repeat = shoestrap_getVariable( 'background_repeat' );

      if ( !in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) :
        $repeat = 'repeat';
      endif;

      if ( $repeat == 'no-repeat' ) :
        $style .= 'background-size: auto;';
      endif;

      $repeat = ' background-repeat: ' . $repeat . ';';
      $position = shoestrap_getVariable( 'background_position_x', 'left' );

      if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) ) :
        $position = 'left';
      endif;

      $position = ' background-position: top ' . $position . ';';
    endif;
  endif;

  $style .= $image . $repeat . $position;

  $theCSS = 'body { background-color: ' . $color . '; ' . trim( $style ) . '}';
  $theCSS .= $color ? '.wrap.main-section .content { background: ' . $color . '; }' : '';

  wp_add_inline_style( 'shoestrap_css', $theCSS );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_background_css', 101 );