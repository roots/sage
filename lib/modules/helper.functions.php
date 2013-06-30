<?php

/*
 * Gets the rgb value of the $hex color.
 * Returns an array.
 */
function shoestrap_get_rgb($hex, $implode = false) {
  $hex = str_replace("#", "", $hex);

  if(strlen($hex) == 3) {
    $r = hexdec(substr($hex,0,1).substr($hex,0,1));
    $g = hexdec(substr($hex,1,1).substr($hex,1,1));
    $b = hexdec(substr($hex,2,1).substr($hex,2,1));
  } else {
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
  }
  $rgb = array($r, $g, $b);
  if ($implode)
    return implode(",", $rgb); // returns the rgb values separated by commas
  else
    return $rgb; // returns an array with the rgb values
}

/*
 * Gets the brightness of the $hex color.
 * Returns a value between 0 and 255
 */
function shoestrap_get_brightness( $hex ) {
  // returns brightness value from 0 to 255
  // strip off any leading #
  $hex = str_replace( '#', '', $hex );

  $c_r = hexdec( substr( $hex, 0, 2 ) );
  $c_g = hexdec( substr( $hex, 2, 2 ) );
  $c_b = hexdec( substr( $hex, 4, 2 ) );

  return ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;
}

/*
 * Adjexts brightness of the $hex color.
 * the $steps variable is a value between -255 (darken) and 255 (lighten)
 */
function shoestrap_adjust_brightness( $hex, $steps ) {
  // Steps should be between -255 and 255. Negative = darker, positive = lighter
  $steps = max( -255, min( 255, $steps ) );

  // Format the hex color string
  $hex = str_replace( '#', '', $hex );
  if ( strlen( $hex ) == 3 ) {
      $hex = str_repeat( substr( $hex, 0, 1 ), 2 ).str_repeat( substr( $hex, 1, 1 ), 2 ).str_repeat( substr( $hex, 2, 1 ), 2 );
  }

  // Get decimal values
  $r = hexdec( substr( $hex, 0, 2 ) );
  $g = hexdec( substr( $hex, 2, 2 ) );
  $b = hexdec( substr( $hex, 4, 2 ) );

  // Adjust number of steps and keep it inside 0 to 255
  $r = max( 0, min( 255, $r + $steps ) );
  $g = max( 0, min( 255, $g + $steps ) );
  $b = max( 0, min( 255, $b + $steps ) );

  $r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
  $g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
  $b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

  return '#'.$r_hex.$g_hex.$b_hex;
}

/*
 * Mixes 2 hex colors.
 * the "percentage" variable is the percent of the first color
 * to be used it the mix. default is 50 (equal mix)
 */
function shoestrap_mix_colors( $hex1, $hex2, $percentage ) {

  // Format the hex color string
  $hex1 = str_replace( '#', '', $hex1 );
  if ( strlen( $hex1 ) == 3 ) {
      $hex1 = str_repeat( substr( $hex1, 0, 1 ), 2 ).str_repeat( substr( $hex1, 1, 1 ), 2 ).str_repeat( substr( $hex1, 2, 1 ), 2 );
  }
  $hex2 = str_replace( '#', '', $hex2 );
  if ( strlen( $hex2 ) == 3 ) {
      $hex2 = str_repeat( substr( $hex2, 0, 1 ), 2 ).str_repeat( substr( $hex2, 1, 1 ), 2 ).str_repeat( substr( $hex2, 2, 1 ), 2 );
  }

  // Get decimal values
  $r1 = hexdec( substr( $hex1, 0, 2 ) );
  $g1 = hexdec( substr( $hex1, 2, 2 ) );
  $b1 = hexdec( substr( $hex1, 4, 2 ) );
  $r2 = hexdec( substr( $hex2, 0, 2 ) );
  $g2 = hexdec( substr( $hex2, 2, 2 ) );
  $b2 = hexdec( substr( $hex2, 4, 2 ) );

  $r  = ( $percentage * $r1 + ( 100 - $percentage ) * $r2 ) / 100;
  $g  = ( $percentage * $g1 + ( 100 - $percentage ) * $g2 ) / 100;
  $b  = ( $percentage * $b1 + ( 100 - $percentage ) * $b2 ) / 100;

  $r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
  $g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
  $b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

  return '#'.$r_hex.$g_hex.$b_hex;
}

// Gets the current values from SMOF, and if not there, grabs the defaults
function shoestrap_getVariable($key) {
  global $smof_details;
  $value = get_theme_mod($key);
  if ($value == "" && isset($smof_details[$key])) {
    $value = $smof_details[$key]['std'];
  }
  return $value;
}

// Show or hide the adminbar
if ( shoestrap_getVariable( 'advanced_wordpress_disable_admin_bar_toggle' ) == 0 )
  show_admin_bar( false );
else
  show_admin_bar( true );


define('themeURI', get_template_directory_uri());
define('themeFOLDER', get_template());
define('themePATH', get_theme_root());
define('themeNAME', wp_get_theme());

function shoestrap_getFilePaths($file, $trail="/") {
  $result['themeuri'] = themeURI;
  $result['folder'] = themeFOLDER;
  $result['name'] = themeNAME;  
  $result['themepath'] = themePATH.DIRECTORY_SEPARATOR;
  $parts = explode(themeFOLDER, $file);
  $result['path'] = $file.DIRECTORY_SEPARATOR;
  $result['relativepath'] = $parts[1].DIRECTORY_SEPARATOR; 
  $result['relativeuri'] = str_replace(DIRECTORY_SEPARATOR, $trail, $parts[1]).$trail;
  $result['uri'] = $result['themeuri'].$result['relativeuri'];
  return $result;
}
