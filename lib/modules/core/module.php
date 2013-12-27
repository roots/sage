<?php

define( 'themeURI', get_template_directory_uri() );
define( 'themeFOLDER', get_template() );
define( 'themePATH', get_theme_root() );
define( 'themeNAME', wp_get_theme() );

if ( !function_exists( 'shoestrap_get_rgb' ) ) :
/*
 * Gets the rgb value of the $hex color.
 * Returns an array.
 */
function shoestrap_get_rgb( $hex, $implode = false ) {
  // Remove any trailing '#' symbols from the color value
  $hex = str_replace( '#', '', $hex );

  if ( strlen( $hex ) == 3 ) {
    // If the color is entered using a short, 3-character format,
    // then find the rgb values from them
    $red    = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
    $green  = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
    $blue   = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
  } else {
    // If the color is entered using a 6-character format,
    // then find the rgb values from them
    $red    = hexdec( substr( $hex, 0, 2 ) );
    $green  = hexdec( substr( $hex, 2, 2 ) );
    $blue   = hexdec( substr( $hex, 4, 2 ) );
  }

  // rgb is an array
  $rgb = array( $red, $green, $blue );
  if ( $implode )
    return implode( ',', $rgb );
  else
    return $rgb;
}
endif;


if ( !function_exists( 'shoestrap_get_rgba' ) ) :
/*
 * Gets the rgba value of a color.
 */
function shoestrap_get_rgba( $hex = '#fff', $opacity = 100, $echo = false ) {
  // Make sure that opacity is properly formatted :
  // Set the opacity to 100 if a larger value has been entered by mistake.
  // If a negative value is used, then set to 0.
  // If an opacity value is entered in a decimal form (for example 0.25), then multiply by 100.
  if ( $opacity >= 100 )
    $opacity = 100;
  elseif ( $opacity < 0 )
    $opacity = 0;
  elseif ( $opacity < 1 && $opacity != 0 )
    $opacity = ( $opacity * 100 );
  else
    $opacity = $opacity;

  // Divide the opacity by 100 to end-up with a CSS value for the opacity
  $opacity = ( $opacity / 100 );

  $color = 'rgba(' . shoestrap_get_rgb( $hex, true ) . ', ' . $opacity . ')';

  // Echo or Return the value
  if ( $echo == true )
    echo $color;
  else
    return $color;

}
endif;


if ( !function_exists( 'shoestrap_get_brightness' ) ) :
/*
 * Gets the brightness of the $hex color.
 * Returns a value between 0 and 255
 */
function shoestrap_get_brightness( $hex ) {
  // returns brightness value from 0 to 255
  // strip off any leading #
  $hex = str_replace( '#', '', $hex );

  $red    = hexdec( substr( $hex, 0, 2 ) );
  $green  = hexdec( substr( $hex, 2, 2 ) );
  $blue   = hexdec( substr( $hex, 4, 2 ) );

  return ( ( $red * 299 ) + ( $green * 587 ) + ( $blue * 114 ) ) / 1000;
}
endif;


if ( !function_exists( 'shoestrap_adjust_brightness' ) ) :
/*
 * Adjexts brightness of the $hex color.
 * the $steps variable is a value between -255 (darken) and 255 (lighten)
 */
function shoestrap_adjust_brightness( $hex, $steps ) {
  // Steps should be between -255 and 255. Negative = darker, positive = lighter
  $steps = max( -255, min( 255, $steps ) );

  // Format the hex color string
  $hex = str_replace( '#', '', $hex );
  $hex = ( strlen( $hex ) == 3 ) ? str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 ) : $hex;

  // Get decimal values
  $red    = hexdec( substr( $hex, 0, 2 ) );
  $green  = hexdec( substr( $hex, 2, 2 ) );
  $blue   = hexdec( substr( $hex, 4, 2 ) );

  // Adjust number of steps and keep it inside 0 to 255
  $red    = max( 0, min( 255, $red + $steps ) );
  $green  = max( 0, min( 255, $green + $steps ) );
  $blue   = max( 0, min( 255, $blue + $steps ) );

  $red_hex    = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
  $green_hex  = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
  $blue_hex   = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );

  return '#' . $red_hex . $green_hex . $blue_hex;
}
endif;


if ( !function_exists( 'shoestrap_mix_colors' ) ) :
/*
 * Mixes 2 hex colors.
 * the "percentage" variable is the percent of the first color
 * to be used it the mix. default is 50 (equal mix)
 */
function shoestrap_mix_colors( $hex1, $hex2, $percentage ) {

  // Format the hex color string
  $hex1 = str_replace( '#', '', $hex1 );
  $hex1 = ( strlen( $hex1 ) == 3 ) ? str_repeat( substr( $hex1, 0, 1 ), 2 ) . str_repeat( substr( $hex1, 1, 1 ), 2 ) . str_repeat( substr( $hex1, 2, 1 ), 2 ) : $hex1;

  $hex2 = str_replace( '#', '', $hex2 );
  $hex2 = ( strlen( $hex2 ) == 3 ) ? str_repeat( substr( $hex2, 0, 1 ), 2 ) . str_repeat( substr( $hex2, 1, 1 ), 2 ) . str_repeat( substr( $hex2, 2, 1 ), 2 ) : $hex2;

  // Get decimal values
  $red_1    = hexdec( substr( $hex1, 0, 2 ) );
  $green_1  = hexdec( substr( $hex1, 2, 2 ) );
  $blue_1   = hexdec( substr( $hex1, 4, 2 ) );
  $red_2    = hexdec( substr( $hex2, 0, 2 ) );
  $green_2  = hexdec( substr( $hex2, 2, 2 ) );
  $blue_2   = hexdec( substr( $hex2, 4, 2 ) );

  $red      = ( $percentage * $red_1 + ( 100 - $percentage ) * $red_2 ) / 100;
  $green    = ( $percentage * $green_1 + ( 100 - $percentage ) * $green_2 ) / 100;
  $blue     = ( $percentage * $blue_1 + ( 100 - $percentage ) * $blue_2 ) / 100;

  $red_hex    = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
  $green_hex  = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
  $blue_hex   = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );

  return '#' . $red_hex . $green_hex . $blue_hex;
}
endif;


if ( !function_exists( 'shoestrap_getVariable' ) ) :
/*
 * Gets the current values from REDUX, and if not there, grabs the defaults
 */
function shoestrap_getVariable( $name, $key = false ) {
  global $redux;
  $options = $redux;

  // Set this to your preferred default value
  $var = '';

  if ( empty( $name ) && !empty( $options ) ) {
    $var = $options;
  } else {
    if ( !empty( $options[$name] ) ) {
      $var = ( !empty( $key ) && !empty( $options[$name][$key] ) && $key !== true ) ? $options[$name][$key] : $var = $options[$name];;
    }
  }
  return $var;
}
endif;


if ( !function_exists( 'shoestrap_password_form' ) ) :
/*
 * Replace the password forms with a bootstrap-formatted version.
 */
function shoestrap_password_form() {
  global $post;
  $label    = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
  $content  = '<form action="';
  $content .= esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) );
  $content .= '" method="post">';
  $content .= __( 'This post is password protected. To view it please enter your password below:', 'shoestrap' );
  $content .= '<div class="input-group">';
  $content .= '<input name="post_password" id="' . $label . '" type="password" size="20" />';
  $content .= '<span class="input-group-btn">';
  $content .= '<input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" class="btn btn-default" />';
  $content .= '</span></div></form>';

  return $content;
}
endif;
add_filter( 'the_password_form', 'shoestrap_password_form' );


if ( !function_exists( 'shoestrap_replace_reply_link_class' ) ) :
/*
 * Apply the proper classes to comment reply links
 */
function shoestrap_replace_reply_link_class( $class ) {
  $class = str_replace( "class='comment-reply-link", "class='comment-reply-link btn btn-primary btn-small", $class );
  return $class;
}
endif;
add_filter('comment_reply_link', 'shoestrap_replace_reply_link_class');


if ( !function_exists( 'shoestrap_init_filesystem' ) ) :
/*
 * Initialize the Wordpress filesystem, no more using file_put_contents function
 */
function shoestrap_init_filesystem() {
  if ( empty( $wp_filesystem ) ) {
    require_once(ABSPATH .'/wp-admin/includes/file.php');
    WP_Filesystem();
  }
}
endif;
add_filter('init', 'shoestrap_init_filesystem');