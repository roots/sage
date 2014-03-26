<?php

if ( ! class_exists( 'Shoestrap_Sass_PHP' ) ) {

	/**
	* The Shoestrap Compiler
	*/
	class Shoestrap_Sass_PHP {

		function __construct() {
			global $ss_framework;

			// Require the less parser
			if ( ! class_exists( 'scssc' ) ) {
				require_once( 'scss.inc.php' );
			}

			add_filter( 'shoestrap_main_stylesheet_url', array( $this, 'stylesheet_url' ) );
			add_filter( 'shoestrap_main_stylesheet_ver', array( $this, 'stylesheet_ver' ) );
			add_action( 'admin_notices',                 array( $this, 'file_nag'       ) );

			// If the Custom LESS exists and has changed after the last compilation, trigger the compiler.
			if ( is_writable( get_stylesheet_directory() . '/assets/less/custom.scss' ) ) {
				if ( filemtime( get_stylesheet_directory() . '/assets/less/custom.scss' ) > filemtime( self::file() ) ) {
					self::makecss();
				}
			}

			// Saving functions on import, etc
			// If a compiler field was altered or import or reset defaults
			add_action( 'redux/options/' . SHOESTRAP_OPT_NAME . '/compiler' , array( $this, 'makecss' ) );
		}

		/*
		 * Gets the css path or url to the stylesheet
		 * If $target = 'path', return the path
		 * If $target = 'url', return the url
		 *
		 * If echo = true then print the path or url.
		 */
		public static function file( $target = 'path', $echo = false ) {
			global $blog_id;

			// Get the upload directory for this site.
			$upload_dir      = wp_upload_dir();
			// Hack to strip protocol
			if ( strpos( $upload_dir['basedir'], 'https' ) !== false ) {
			  $upload_dir = str_replace( 'https:', '', $upload_dir );
			} else {
		  	  $upload_dir = str_replace( 'http:', '', $upload_dir );
			}

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

			if ( $target == 'ver' ) {
				if ( ! get_transient( 'shoestrap_stylesheet_time' ) ) {
					set_transient( 'shoestrap_stylesheet_time', filemtime( $css_path ), 24 * 60 * 60 );
				}

				$value = get_transient( 'shoestrap_stylesheet_time' );
			}

			if ( $echo ) {
				echo $value;
			} else {
				return $value;
			}
		}

		/**
		 * Get the URL of the stylesheet
		 */
		function stylesheet_url() {
			return self::file( 'url' );
		}

		/**
		 * Get the version of the stylesheet
		 */
		function stylesheet_ver() {
			return self::file( 'ver' );
		}

		/*
		 * Admin notice if css is not writable
		 */
		function file_nag( $array ) {
			global $current_screen, $wp_filesystem;

			if ( $current_screen->parent_base == 'themes' ) {
				$filename = self::file();
				$url = self::stylesheet_url( 'url' );

				if ( ! file_exists( $filename ) ) {
					if ( ! $wp_filesystem->put_contents( $filename, ' ', FS_CHMOD_FILE ) ) {
						$content = __( 'The following file does not exist and must be so in order to utilise this theme. Please create this file.', 'shoestrap' );
						$content .= '<br>' . __( 'Try visiting the theme options and clicking the "Reset All" button to attempt automatically creating it.', 'shoestrap' );
						$content .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $filename . '" target="_blank">' . $filename . '</a>';
						add_settings_error( 'shoestrap', 'create_file', $content, 'error' );
						settings_errors();
					}
				} else {
					if ( ! is_writable( $filename ) ) {
						$content = __( 'The following file is not writable and must be so in order to utilise this theme. Please update the permissions.', 'shoestrap' );
						$content .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $filename . '" target="_blank">' . $filename . '</a>';

						add_settings_error( 'shoestrap', 'create_file', $content, 'error' );
						settings_errors();
					}
				}
			}
		}

		public static function makecss() {
			global $wp_filesystem, $ss_framework;

			$file = self::file();

			// Initialize the Wordpress filesystem.
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$content = '/********* Do not edit this file *********/

			';

			$content .= $ss_framework->compiler();

			if ( is_writeable( $file ) || ( ! file_exists( $file ) && is_writeable( dirname( $file ) ) ) ) {
				if ( ! $wp_filesystem->put_contents( $file, $content, FS_CHMOD_FILE ) ) {
					return apply_filters( 'shoestrap_css_output', $content );
				}
			}
			// Force re-building the stylesheet version transient
			delete_transient( 'shoestrap_stylesheet_time' );
		}
	}
}