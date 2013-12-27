<?php


if ( !function_exists( 'shoestrap_css' ) ) :
/*
 * Gets the css path or url to the stylesheet
 * If $target = 'path', return the path
 * If $target = 'url', return the url
 *
 * If echo = true then print the path or url.
 */
function shoestrap_css( $target = 'path', $echo = false ) {
  global $blog_id;
  $defaultfile = '/assets/css/style';

  // If this is a multisite installation, append the blogid to the filename
  $cssid    = ( is_multisite() && $blog_id > 1 ) ? '_id-' . $blog_id : null;

  $css_uri  = get_template_directory_uri() . $defaultfile . $cssid . '.css';
  $css_path = ( is_child_theme() && is_writable( get_stylesheet_directory() . $defaultfile . $cssid . '.css' ) ) ? get_stylesheet_directory() . $defaultfile . $cssid . '.css' : get_template_directory() . $defaultfile . $cssid . '.css';

  $child_style_writable = ( is_child_theme() && is_writable( get_stylesheet_directory() . $defaultfile . $cssid . '.css' ) ) ? true : false;

  $css_uri = ( !is_writable( $css_path ) ) ? get_template_directory_uri() . $defaultfile . '-default.css' : $css_uri;
  $css_uri = ( $child_style_writable ) ? get_stylesheet_directory_uri() . $defaultfile . $cssid . '.css' : $css_uri;
  $css_uri = ( !$child_style_writable && is_writable( get_stylesheet_directory() . $defaultfile . '-default.css' ) ) ? get_stylesheet_directory_uri() . '/assets/css/style-default.css' : $css_uri;

  $return = ( $target == 'url' ) ? $css_uri : $css_path;

  if ( $echo )
    echo $return;
  else
    return $return;
}
endif;


if ( !function_exists( 'shoestrap_css_not_writeable' ) ) :
/*
 * Admin notice if css is not writable
 */
function shoestrap_css_not_writeable( $array ) {
  global $current_screen, $wp_filesystem;

  if ( $current_screen->parent_base == 'themes' ) {
    $filename = shoestrap_css();
    $url = shoestrap_css('url');
    
    if ( !file_exists( $filename ) ) {
      if ( !$wp_filesystem->put_contents( $filename, ' ', FS_CHMOD_FILE ) ) {
        $content = __( 'The following file does not exist and must be so in order to utilise this theme. Please create this file.', 'shoestrap' );
        $content .= '<br>' . __( 'Try visiting the theme options and clicking the "Reset All" button to attempt automatically creating it.', 'shoestrap' );
        $content .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $filename . '" target="_blank">' . $filename . '</a>';
        add_settings_error( 'shoestrap', 'create_file', $content, 'error' );                  
        settings_errors();
      }
    } else {
      if ( !is_writable( $filename ) ) {
        $content = __( 'The following file is not writable and must be so in order to utilise this theme. Please update the permissions.', 'shoestrap' );
        $content .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $filename . '" target="_blank">' . $filename . '</a>';

        add_settings_error( 'shoestrap', 'create_file', $content, 'error' );                  
        settings_errors();
      }
    }
  }
}
endif;
add_action( 'admin_notices', 'shoestrap_css_not_writeable');


if ( !function_exists( 'shoestrap_process_font' ) ) :
function shoestrap_process_font( $font ) {
  
  if ( empty( $font['font-weight'] ) )
    $font['font-weight'] = "inherit";

  if ( empty( $font['font-style'] ) )
    $font['font-style'] = "inherit";

  if ( isset( $font['font-size'] ) )
    $font['font-size'] = filter_var( $font['font-size'], FILTER_SANITIZE_NUMBER_INT );

  return $font;
}
endif;

// If the Custom LESS exists and has changed after the last compilation, trigger the compiler.
if ( is_writable( get_template_directory() . '/assets/less/custom.less' ) ) {
  if ( filemtime( get_template_directory() . '/assets/less/custom.less' ) > filemtime( shoestrap_css() ) )
    shoestrap_makecss();
}