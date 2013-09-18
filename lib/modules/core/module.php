<?php

if ( !function_exists( 'shoestrap_get_rgb' ) ) :
/*
 * Gets the rgb value of the $hex color.
 * Returns an array.
 */
function shoestrap_get_rgb( $hex, $implode = false ) {
  $hex = str_replace( '#', '', $hex );

  if ( strlen( $hex ) == 3 ) :
    $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
    $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
    $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
  else :
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );
  endif;

  $rgb = array( $r, $g, $b );
  if ( $implode ) :
    // returns the rgb values separated by commas
    return implode( ',', $rgb );
  else :
    // returns an array with the rgb values
    return $rgb;
  endif;
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

  $c_r = hexdec( substr( $hex, 0, 2 ) );
  $c_g = hexdec( substr( $hex, 2, 2 ) );
  $c_b = hexdec( substr( $hex, 4, 2 ) );

  return ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;
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

  if ( strlen( $hex ) == 3 )
    $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );

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

  return '#' . $r_hex . $g_hex . $b_hex;
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
  if ( strlen( $hex1 ) == 3 ) :
    $hex1 = str_repeat( substr( $hex1, 0, 1 ), 2 ) . str_repeat( substr( $hex1, 1, 1 ), 2 ) . str_repeat( substr( $hex1, 2, 1 ), 2 );
  endif;

  $hex2 = str_replace( '#', '', $hex2 );
  if ( strlen( $hex2 ) == 3 ) :
    $hex2 = str_repeat( substr( $hex2, 0, 1 ), 2 ) . str_repeat( substr( $hex2, 1, 1 ), 2 ) . str_repeat( substr( $hex2, 2, 1 ), 2 );
  endif;

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

  return '#' . $r_hex . $g_hex . $b_hex;
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

  if ( $name == 'shoestrap_license_key_status' ) :
    return get_theme_mod( $name );
  endif;

  if ( empty( $name ) && !empty( $options ) ) :
    $var = $options;
  else :
    if ( !empty( $options[$name] ) ) :
      if ( !empty( $key ) && !empty( $options[$name][$key] ) && $key !== true ) :
        $var = $options[$name][$key];
      else :
        $var = $options[$name];
      endif;
    endif;
  endif;

  return $var;
}
endif;

define( 'themeURI', get_template_directory_uri() );
define( 'themeFOLDER', get_template() );
define( 'themePATH', get_theme_root() );
define( 'themeNAME', wp_get_theme() );

if ( !function_exists( 'shoestrap_getFilePaths' ) ) :
/**
 * Parses sections of given $file into named parts.
 * For cross platform compatiblity, please use PHP constant DIRECTORY_SEPERATOR 
 * instead of "/" for local paths. "/" can be used for all URI's as the 
 * forward slash is consistent across all platforms for URI's. 
 * @param $file File to disect 
 * @return array of path parts. [themeuri, (theme)folder, (theme)name, themepath, relativepath, relativeuri, uri]
 */
function shoestrap_getFilePaths( $file ) {
  $result['themeuri']   = themeURI;
  $result['folder']     = themeFOLDER;
  $result['name']       = themeNAME;  
  $result['themepath']  = shoestrap_prep_path( themePATH );
  $result['path']       = shoestrap_prep_path( $file );
  
  $parts = explode( strtolower( $result['themepath'] ) . strtolower( $result['folder'] ), strtolower( $file ) );
  $result['relativepath'] = shoestrap_prep_path( $parts[1] );
  $result['relativeuri']  = shoestrap_prep_uri( $parts[1] );
  $result['uri']          = $result['themeuri'] . $result['relativeuri'];
  
  return $result;
}
endif;

if ( !function_exists( 'shoestrap_prep_path' ) ) :
/**
 * Prepares a local path for string parsing when Directory separators need to be consistent for Windows and Linux.
 * Use for local server PATHS only. Use shoestrap_prep_uri() for a URI or URL.
 * Windows directories will be separated with a "\\"
 * Linux directories will be separated with a "/"
 * Also adds a trailing "/" or "\\" based on OS's PHP Constant DIRECTORY_SEPARATOR (http://php.net/manual/en/dir.constants.php) 
 * @param $path
 * @return string with trailing path separator
 */
function shoestrap_prep_path( $path ) {
    // Ensures proper separator for each OS.
    $path = str_replace( '/', DIRECTORY_SEPARATOR, $path );
    $path = str_replace( '\\', DIRECTORY_SEPARATOR, $path );
    // Removes if exists to ensure only one is added.
    return rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
}
endif;

if ( !function_exists( 'shoestrap_prep_uri' ) ) :
/**
 * Prepares a URI for string parsing when all separators should be "/"
 * Use for URIs and URLs only. Use shoestrap_prep_path() for local server paths.
 * @param $path 
 * @return $string 
 */
function shoestrap_prep_uri( $path ) {
  $path = str_replace( '\\', '/', $path );
  return rtrim( $path, '/' ) . '/';
}
endif;

if ( !function_exists( 'shoestrap_password_form' ) ) :
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
function shoestrap_replace_reply_link_class( $class ){
    $class = str_replace( "class='comment-reply-link", "class='comment-reply-link btn btn-primary btn-small", $class );
    return $class;
}
endif;
add_filter('comment_reply_link', 'shoestrap_replace_reply_link_class');

if ( !function_exists( 'shoestrap_contains_string' ) ) :
/*
 * Pass a straing and an array of possible values. Will return true if the straing contains it
 */
function shoestrap_contains_string( $str, array $arr ) {
  foreach( $arr as $a ) :
    if (stripos($str,$a) !== false) :
      return true;
    endif;
  endforeach;

  return false;
}
endif;

if ( !function_exists( 'shoestrap_init_filesystem' ) ) :
/*
 * Initialize the Wordpress filesystem, no more using file_put_contents function
 */
function shoestrap_init_filesystem() {
  if ( empty( $wp_filesystem ) ) :
    require_once(ABSPATH .'/wp-admin/includes/file.php');
    WP_Filesystem();
  endif;
}
endif;
add_filter('init', 'shoestrap_init_filesystem');