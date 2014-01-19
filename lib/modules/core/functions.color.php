<?php


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


if ( !function_exists( 'shoestrap_hex_to_hsv' ) ) :
/*
 * Convert a hex color to HSV
 */
function shoestrap_hex_to_hsv( $hex ) {
  $hex = str_replace( '#', '', $hex );
  $rgb = shoestrap_get_rgb( $hex );
  $hsv = shoestrap_rgb_to_hsv( $rgb );

  return $hsv;
}
endif;


if ( !function_exists( 'shoestrap_rgb_to_hsv' ) ) :
/*
 * Convert an RGB array to HSV
 */
function shoestrap_rgb_to_hsv( $color = array() ) {
  $r = $color[0];
  $g = $color[1];
  $b = $color[2];

  $hsl = array();

  $var_r = ( $r / 255 );
  $var_g = ( $g / 255 );
  $var_b = ( $b / 255 );

  $var_min = min( $var_r, $var_g, $var_b);
  $var_max = max( $var_r, $var_g, $var_b);
  $del_max = $var_max - $var_min;

  $v = $var_max;

   if ( $del_max == 0 ) {
    $h = 0;
    $s = 0;
  } else {
    $s = $del_max / $var_max;

    $del_r = ( ( ( $var_max - $var_r ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
    $del_g = ( ( ( $var_max - $var_g ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
    $del_b = ( ( ( $var_max - $var_b ) / 6 ) + ( $del_max / 2 ) ) / $del_max;

    if ( $var_r == $var_max )
      $h = $del_b - $del_g;
    elseif ( $var_g == $var_max)
      $h = ( 1 / 3 ) + $del_r - $del_b;
    elseif ( $var_b == $var_max )
      $h = ( 2 / 3 ) + $del_g - $del_r;

    if ( $h<0 )
      $h++;

    if ( $h>1 )
      $h--;
  }

  $hsl['h'] = $h;
  $hsl['s'] = $s;
  $hsl['v'] = $v;

  return $hsl;
}
endif;


if ( !function_exists( 'shoestrap_brightest_color' ) ) :
/*
 * Get the brightest color from an array of colors.
 * Return the key of the array if $context = 'key'
 * Return the hex value of the color if $context = 'value'
 */
function shoestrap_brightest_color( $colors = array(), $context = 'key' ) {
  $brightest = false;

  foreach ( $colors as $color ) {
    $hex = str_replace( '#', '', $color );
    $brightness = shoestrap_get_brightness( $hex );

    if ( !$brightest || shoestrap_get_brightness( $hex ) > shoestrap_get_brightness( $brightest ) )
      $brightest = $hex;
  }

  if ( $context == 'key' )
    return array_search( $brightest, $colors );
  elseif ( $context == 'value' )
    return $brightest;
}
endif;


if ( !function_exists( 'shoestrap_most_saturated_color' ) ) :
/*
 * Get the most saturated color from an array of colors.
 * Return the key of the array if $context = 'key'
 * Return the hex value of the color if $context = 'value'
 */
function shoestrap_most_saturated_color( $colors = array(), $context = 'key' ) {
  $most_saturated = false;

  foreach ( $colors as $color ) {
    $hex = str_replace( '#', '', $color );
    $hsv = shoestrap_hex_to_hsv( $hex );
    $saturation = $hsv['s'];

    if ( $most_saturated )
      $hsv_old = shoestrap_hex_to_hsv( $most_saturated );

    if ( !$most_saturated || $saturation > $hsv_old['s'] );
      $most_saturated = $hex;
  }

  if ( $context == 'key' )
    return array_search( $most_saturated, $colors );
  elseif ( $context == 'value' )
    return $most_saturated;
}
endif;


if ( !function_exists( 'shoestrap_most_intense_color' ) ) :
/*
 * Get the most intense color from an array of colors.
 * Return the key of the array if $context = 'key'
 * Return the hex value of the color if $context = 'value'
 */
function shoestrap_most_intense_color( $colors = array(), $context = 'key' ) {
  $most_intense = false;

  foreach ( $colors as $color ) {
    $hex = str_replace( '#', '', $color );
    $hsv = shoestrap_hex_to_hsv( $hex );
    $saturation = $hsv['s'];

    if ( $most_intense )
      $hsv_old = shoestrap_hex_to_hsv( $most_intense );

    if ( !$most_intense || $saturation > $hsv_old['s'] );
      $most_intense = $hex;
  }

  if ( $context == 'key' )
    return array_search( $most_intense, $colors );
  elseif ( $context == 'value' )
    return $most_intense;
}
endif;


if ( !function_exists( 'shoestrap_brightest_dull_color' ) ) :
/*
 * Get the brightest color from an array of colors.
 * Return the key of the array if $context = 'key'
 * Return the hex value of the color if $context = 'value'
 */
function shoestrap_brightest_dull_color( $colors = array(), $context = 'key' ) {
  $brightest_dull = false;

  foreach ( $colors as $color ) {
    $hex          = str_replace( '#', '', $color );
    $hsv          = shoestrap_hex_to_hsv( $hex );

    $brightness   = shoestrap_get_brightness( $hex );
    // Prevent "division by zero" messages.
    $hsv['s']     = ( $hsv['s'] == 0 ) ? 0.0001 : $hsv['s'];
    $dullness     = 1 / $hsv['s'];

    if ( $brightest_dull ) {
      $hsv_old      = shoestrap_hex_to_hsv( $brightest_dull );
      // Prevent "division by zero" messages.
      $hsv_old['s'] = ( $hsv_old['s'] == 0 ) ? 0.0001 : $hsv_old['s'];
      $dullness_old = 1 / $hsv_old['s'];
    }

    if ( !$brightest_dull || shoestrap_get_brightness( $hex ) * $dullness > shoestrap_get_brightness( $brightest_dull ) * $dullness_old )
      $brightest_dull = $hex;
  }

  if ( $context == 'key' )
    return array_search( $brightest_dull, $colors );
  elseif ( $context == 'value' )
    return $brightest_dull;
}
endif;