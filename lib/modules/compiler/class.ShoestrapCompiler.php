<?php

if ( !class_exists( 'ShoestrapCompiler' ) ) {

	/**
	* The Shoestrap Compiler
	*/
	class ShoestrapCompiler {

		function __construct() {
			$settings = get_option( SHOESTRAP_OPT_NAME );

			add_filter( 'shoestrap_main_stylesheet_url', array( $this, 'stylesheet_url' ) );
			add_filter( 'shoestrap_main_stylesheet_ver', array( $this, 'stylesheet_ver' ) );
			add_action( 'admin_notices',                 array( $this, 'file_nag'       ) );

			// If the Custom LESS exists and has changed after the last compilation, trigger the compiler.
			if ( is_writable( get_stylesheet_directory() . '/assets/less/custom.less' ) ) {
				if ( filemtime( get_stylesheet_directory() . '/assets/less/custom.less' ) > filemtime( self::file() ) )
					self::makecss();
			}

			// If the less.php compiler is not found, force the use the less.js compiler.
			if ( !class_exists( 'Less_Cache' ) || !class_exists( 'Less_Parser' ) ) {
				if ( isset( $settings['lessjs'] ) || $settings['lessjs'] != 1 ) {
					$settings['lessjs'] = 1;
					update_option( SHOESTRAP_OPT_NAME, $settings );
				}
			}


			// If we are on the customizer then output the necessary elements to wp_head so that the less.js customizer kicks in.
			global $wp_customize;
			$lessjs = $settings['lessjs'];
			if ( isset( $wp_customize ) || $lessjs == 1 ) {
				add_action( 'wp_head', array( $this, 'less_js_stylesheet' ), 1  );
				add_action( 'wp_enqueue_scripts', array( $this, 'less_js_enqueue' ), 110 );
			}

			// Saving functions on import, etc
			// If a compiler field was altered or import or reset defaults
			if ( !isset( $wp_customize ) || $lessjs != 1 )
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

			if ( $target == 'ver' ) {
				if ( !get_transient( 'shoestrap_stylesheet_time' ) )
					set_transient( 'shoestrap_stylesheet_time', filemtime( $css_path ), 24 * 60 * 60 );

				$value = get_transient( 'shoestrap_stylesheet_time' );
			}

			if ( $echo )
				echo $value;
			else
				return $value;
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
				$url = self::stylesheet_url('url');

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

		/*
		 * This function can be used to compile a less file to css using the lessphp compiler
		 */
		public static function compiler() {
			$minimize_css = shoestrap_getVariable( 'minimize_css', true );
			$options = ( $minimize_css == 1 ) ? array( 'compress'=>true ) : array( 'compress'=>false );

			$bootstrap_location = get_template_directory() . '/assets/less/';
			$webfont_location   = get_template_directory() . '/assets/fonts/';
			$bootstrap_uri      = '';
			$custom_less_file   = get_template_directory() . '/assets/less/custom.less';

			$css = '';
			try {

				$parser = new Less_Parser( $options );

				// The main app.less file
				$parser->parseFile( $bootstrap_location . 'app.less', $bootstrap_uri );

				// Include the Elusive Icons
				$parser->parseFile( $webfont_location . 'elusive-webfont.less', $bootstrap_uri );

				// Enable gradients
				if ( shoestrap_getVariable( 'gradients_toggle' ) == 1 )
					$parser->parseFile( $bootstrap_location . 'gradients.less', $bootstrap_uri );

				// The custom.less file
				if ( is_writable( $custom_less_file ) )
					$parser->parseFile( $bootstrap_location . 'custom.less', $bootstrap_uri );

				// Parse any custom less added by the user
				$parser->parse( shoestrap_getVariable( 'user_less' ) );
				// Add a filter to the compiler
				$parser->parse( apply_filters( 'shoestrap_compiler', '' ) );

				$css = $parser->getCss();

			} catch( Exception $e ) {
				$error_message = $e->getMessage();
			}

			// Below is just an ugly hack
			$css = str_replace( '../', get_template_directory_uri() . '/assets/', $css );

			return apply_filters( 'shoestrap_compiler_output', $css );
		}

		public static function makecss() {
			global $wp_filesystem;
			$file = self::file();

			// Initialize the Wordpress filesystem.
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$content = '/********* Do not edit this file *********/

			';

			$content .= self::compiler();

			if ( is_writeable( $file ) || ( !file_exists( $file ) && is_writeable( dirname( $file ) ) ) ) {
				if ( !$wp_filesystem->put_contents( $file, $content, FS_CHMOD_FILE ) )
					return apply_filters( 'shoestrap_css_output', $content );
			}
			// Force re-building the stylesheet version transient
			delete_transient( 'shoestrap_stylesheet_time' );
		}

		function less_js_stylesheet() {
			// Get the variables from the settings
			$variables = apply_filters( 'shoestrap_compiler', '' );
			// Since this will be used for less.js, replace path with URI.
			$variables = str_replace( SHOESTRAP_MODULES_PATH, SHOESTRAP_MODULES_URL, $variables );

			// Get the main app.less file
			$app_less = file_get_contents( get_stylesheet_directory() . '/assets/less/app.less' );
			// Since this will be used for less.js, replace relative URIs.
			$app_less = str_replace( '@import "', '@import "' . get_stylesheet_directory_uri() . '/assets/less/', $app_less );

			echo '<style type="text/less">' . $app_less . $variables . '</style>';
		}

		function less_js_enqueue() {
			wp_register_script( 'less_js', SHOESTRAP_ASSETS_URL . '/js/vendor/less.min.js', false, '1.6.3' );
			wp_enqueue_script( 'less_js' );
			// remove the default stylesheet
			wp_dequeue_style( 'shoestrap_css' );
		}
	}
}

$compiler = new ShoestrapCompiler();