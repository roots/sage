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

	// Get the upload directory for this site.
	$upload_dir      = wp_upload_dir();
	// Hack to strip protocol
	if ( strpos( $upload_dir['basedir'], 'https' ) !== false ) :
	  $upload_dir = str_replace( 'https:', '', $upload_dir );
  	else :
  	  $upload_dir = str_replace( 'http:', '', $upload_dir );
  	endif;
	// Define a default folder for the stylesheets.
	$def_folder_path = get_template_directory() . '/assets/css';
	// The folder path for the stylesheet.
	// We try to first write to the uploads folder.
	// If we can write there, then use that folder for the stylesheet.
	// This helps so that the stylesheets don't get overwritten when the theme gets updated.
	$folder_path     = ( is_writable( $upload_dir['basedir'] ) ) ? $upload_dir['basedir'] : $def_folder_path;

	// If this is a multisite installation, append the blogid to the filename
	$cssid           = ( is_multisite() && $blog_id > 1 ) ? '_id-' . $blog_id : null;
	$file_name       = '/ss-style' . $cssid . '.css';

	// The complete path to the file.
	$file_path       = $folder_path . $file_name;

	// Get the URL directory of the stylesheet
	$css_uri_folder  = ( $folder_path == $upload_dir['basedir'] ) ? $upload_dir['baseurl'] : get_template_directory_uri() . '/assets/css';

	// If the CSS file does not exist, use the default file.
	$css_uri  = ( file_exists( $file_path ) ) ? $css_uri_folder . $file_name : get_template_directory_uri() . '/assets/css/style-default.css';

	// If a style.css file exists in the assets/css folder, use that file instead.
	// This is mostly for backwards-compatibility with previous versions.
	// Also if the stylesheet is compiled using grunt, this will make sure the correct file is used.
	if ( file_exists( $def_folder_path . $file_name) ) {
		$css_uri   = get_template_directory_uri() . '/assets/css/style' . $cssid . '.css';
		$file_path = $def_folder_path . '/style' . $cssid . '.css';
	}

	$css_path = $file_path;

	$value    = ( $target == 'url' ) ? $css_uri : $css_path;

	if ( $echo )
		echo $value;
	else
		return $value;
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
