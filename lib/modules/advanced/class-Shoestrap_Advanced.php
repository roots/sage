<?php


if ( !class_exists( 'ShoestrapAdvanced' ) ) {

	/**
	* The "Advanced" module
	*/
	class ShoestrapAdvanced {

		function __construct() {
			global $ss_settings;

			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 95 );
			add_action( 'wp_enqueue_scripts', array( $this, 'user_css'           ), 101 );
			add_action( 'wp_footer',          array( $this, 'user_js'            ), 200 );
			add_filter( 'show_admin_bar',     array( $this, 'admin_bar'          )      );
			add_action( 'wp_footer',          array( $this, 'google_analytics'   ), 20  );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts'            ), 100 );

			 // Toggle activation of the jQuery CDN
			if ( $ss_settings['jquery_cdn_toggler'] == 1 ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'jquery_cdn' ), 101 );
				add_action( 'wp_head',            array( $this, 'jquery_local_fallback' ) );
			}

			if ( $ss_settings['nice_search'] == 1 )
				add_action( 'template_redirect', array( $this, 'nice_search_redirect' ) );

			/**
			 * Post Excerpt Length
			 * Length in words for excerpt_length filter (http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length)
			 */
			define( 'POST_EXCERPT_LENGTH', $ss_settings['post_excerpt_length'] );

			if ( self::enable_root_relative_urls() ) {
				$root_rel_filters = array(
					'bloginfo_url',
					'the_permalink',
					'wp_list_pages',
					'wp_list_categories',
					'shoestrap_wp_nav_menu_item',
					'the_content_more_link',
					'the_tags',
					'get_pagenum_link',
					'get_comment_link',
					'month_link',
					'day_link',
					'year_link',
					'tag_link',
					'the_author_posts_link',
					'script_loader_src',
					'style_loader_src'
				);

				self::add_filters( $root_rel_filters, array( $this, 'root_relative_url' ) );
			}
		}

		/**
		* Utility function
		*/
		public static function add_filters( $tags, $function ) {
			foreach( $tags as $tag ) {
				add_filter( $tag, $function );
			}
		}

		/**
		 * The advanced core options for the Shoestrap theme
		 */
		function options( $sections ) {
			global $ss_settings;

			// Advanced Settings
			$section = array(
				'title'   => __( 'Advanced', 'shoestrap' ),
				'icon'    => 'el-icon-cogs icon-large'
			);

			$fields[] = array(
				'title'     => __( 'Enable Retina mode', 'shoestrap' ),
				'desc'      => __( 'By enabling your site\'s featured images will be retina ready. Requires images to be uploaded at 2x the typical size desired. Default: On', 'shoestrap' ),
				'id'        => 'retina_toggle',
				'default'   => 1,
				'type'      => 'switch',
			);

			$fields[] = array(
				'title'     => __( 'Google Analytics ID', 'shoestrap' ),
				'desc'      => __( 'Paste your Google Analytics ID here to enable analytics tracking. Only Universal Analytics properties. Your user ID should be in the form of UA-XXXXX-Y.', 'shoestrap' ),
				'id'        => 'analytics_id',
				'default'   => '',
				'type'      => 'text',
			);

			if ( $ss_settings['framework'] != 'foundation' ) {

				$fields[] = array(
					'title'     => 'Border-Radius and Padding Base',
					'id'        => 'help2',
					'desc'      => __( 'The following settings affect various areas of your site, most notably buttons.', 'shoestrap' ),
					'type'      => 'info',
				);

				$fields[] = array(
					'title'     => __( 'Border-Radius', 'shoestrap' ),
					'desc'      => __( 'You can adjust the corner-radius of all elements in your site here. This will affect buttons, navbars, widgets and many more. Default: 4', 'shoestrap' ),
					'id'        => 'general_border_radius',
					'default'   => 4,
					'min'       => 0,
					'step'      => 1,
					'max'       => 50,
					'advanced'  => true,
					'compiler'  => true,
					'type'      => 'slider',
				);

				$fields[] = array(
					'title'     => __( 'Padding Base', 'shoestrap' ),
					'desc'      => __( 'You can adjust the padding base. This affects buttons size and lots of other cool stuff too! Default: 8', 'shoestrap' ),
					'id'        => 'padding_base',
					'default'   => 6,
					'min'       => 0,
					'step'      => 1,
					'max'       => 20,
					'advanced'  => true,
					'compiler'  => true,
					'type'      => 'slider',
				);
			}

			$fields[] = array(
				'title'     => __( 'Root Relative URLs', 'shoestrap' ),
				'desc'      => __( 'Return URLs such as <em>/assets/css/style.css</em> instead of <em>http://example.com/assets/css/style.css</em>. Default: ON', 'shoestrap' ),
				'id'        => 'root_relative_urls',
				'default'   => 0,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Enable Nice Search', 'shoestrap' ),
				'desc'      => __( 'Redirects /?s=query to /search/query/, convert %20 to +. Default: ON', 'shoestrap' ),
				'id'        => 'nice_search',
				'default'   => 1,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Custom CSS', 'shoestrap' ),
				'desc'      => __( 'You can write your custom CSS here. This code will appear in a script tag appended in the header section of the page.', 'shoestrap' ),
				'id'        => 'user_css',
				'default'   => '',
				'type'      => 'ace_editor',
				'mode'      => 'css',
				'theme'     => 'monokai',
			);

			if ( $ss_settings['framework'] != 'foundation' ) {
				$fields[] = array(
					'title'     => __( 'Custom LESS', 'shoestrap' ),
					'desc'      => __( 'You can write your custom LESS here. This code will be compiled with the other LESS files of the theme and be appended to the header.', 'shoestrap' ),
					'id'        => 'user_less',
					'default'   => '',
					'type'      => 'ace_editor',
					'mode'      => 'less',
					'theme'     => 'monokai',
					'compiler'  => true,
				);
			} else {
				$fields[] = array(
					'title'     => __( 'Custom SASS', 'shoestrap' ),
					'desc'      => __( 'You can write your custom SASS here. This code will be compiled with the other SASS files of the theme and be appended to the header.', 'shoestrap' ),
					'id'        => 'user_sass',
					'default'   => '',
					'type'      => 'ace_editor',
					'mode'      => 'sass',
					'theme'     => 'monokai',
					'compiler'  => true,
				);
			}

			$fields[] = array(
				'title'     => __( 'Custom JS', 'shoestrap' ),
				'desc'      => __( 'You can write your custom JavaScript/jQuery here. The code will be included in a script tag appended to the bottom of the page.', 'shoestrap' ),
				'id'        => 'user_js',
				'default'   => '',
				'type'      => 'ace_editor',
				'mode'      => 'javascript',
				'theme'     => 'monokai',
			);

			$fields[] = array(
				'title'     => __( 'Minimize CSS', 'shoestrap' ),
				'desc'      => __( 'Minimize the genearated CSS. This should be ON for production sites. Default: OFF.', 'shoestrap' ),
				'id'        => 'minimize_css',
				'default'   => 1,
				'compiler'  => true,
				'type'      => 'switch',
			);

			$fields[] = array(
				'title'     => __( 'Toggle adminbar On/Off', 'shoestrap' ),
				'desc'      => __( 'Turn the admin bar On or Off on the frontend. Default: Off.', 'shoestrap' ),
				'id'        => 'advanced_wordpress_disable_admin_bar_toggle',
				'default'   => 1,
				'type'      => 'switch',
			);

			$fields[] = array(
				'title'     => __( 'Use Google CDN for jQuery', 'shoestrap' ),
				'desc'      => '',
				'id'        => 'jquery_cdn_toggler',
				'default'   => 0,
				'type'      => 'switch',
			);

			// Do not show this option if the less.php compiler is not present.
			if ( class_exists( 'Less_Cache' ) && class_exists( 'Less_Parser' ) ) {
				$fields[] = array(
					'title'     => __( 'Use less.js instead of less.php compiler', 'shoestrap' ),
					'desc'      => __( 'The less.js compiler works by compiling the stylesheets on the browser, while the less.php compiler compiles the stylesheets on your server and users are then served the pre-compiled css file.', 'shoestrap' ),
					'id'        => 'lessjs',
					'default'   => 0,
					'type'      => 'switch',
				);
			}

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_advanced_options_modifier', $section );

			$sections[] = $section;

			return $sections;

		}

		/*
		 * echo any custom CSS the user has written to the <head> of the page
		 */
		function user_css() {
			global $ss_settings;

			$header_scripts = $ss_settings['user_css'];

			if ( trim( $header_scripts ) != '' ) {
				wp_add_inline_style( 'shoestrap_css', $header_scripts );
			}
		}

		/*
		 * echo any custom JS the user has written to the footer of the page
		 */
		function user_js() {
			global $ss_settings;

			$footer_scripts = $ss_settings['user_js'];

			if ( trim( $footer_scripts ) != '' ) {
				echo '<script id="core.advanced-user-js">' . $footer_scripts . '</script>';
			}
		}

		/**
		 * Switch the adminbar On/Off
		 */
		function admin_bar() {
			global $ss_settings;

			if ( $ss_settings['advanced_wordpress_disable_admin_bar_toggle'] == 0 ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * The Google Analytics code
		 */
		function google_analytics() {
			global $ss_settings;

			$analytics_id = $ss_settings['analytics_id'];

			if ( !is_null( $analytics_id ) && !empty( $analytics_id ) )
				echo "<script>
			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','" . $analytics_id . "');ga('send','pageview');
			</script>";
		}

		/**
		 * Redirects search results from /?s=query to /search/query/, converts %20 to +
		 *
		 * @link http://txfx.net/wordpress-plugins/nice-search/
		 */
		function nice_search_redirect() {
			global $wp_rewrite;

			if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
				return;

			$search_base = $wp_rewrite->search_base;
			if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
				wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
				exit();
			}
		}

		/**
		 * Root relative URLs
		 *
		 * WordPress likes to use absolute URLs on everything - let's clean that up.
		 * Inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
		 *
		 * @author Scott Walkinshaw <scott.walkinshaw@gmail.com>
		 */
		function root_relative_url( $input ) {
			preg_match( '|https?://([^/]+)(/.*)|i', $input, $matches );

			if ( !isset( $matches[1] ) || !isset( $matches[2] ) )
				return $input;
			elseif ( ( $matches[1] === $_SERVER['SERVER_NAME'] ) || $matches[1] === $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] )
				return wp_make_link_relative($input);
			else
				return $input;
		}

		function enable_root_relative_urls() {
			return !( is_admin() || in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) );
		}

		/**
		 * Enqueue some extra scripts
		 */
		function scripts() {
			global $ss_settings;

			if ( $ss_settings['retina_toggle'] == 1 ) {
				wp_register_script( 'retinajs', SHOESTRAP_ASSETS_URL . '/js/vendor/retina.js', false, null, true );
				wp_enqueue_script( 'retinajs' );
			}
		}

		/**
		 * Use a CDN for jQuery
		 */
		function jquery_cdn() {

			// jQuery is loaded using the same method from HTML5 Boilerplate:
			// Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
			// It's kept in the header instead of footer to avoid conflicts with plugins.
			if ( !is_admin() ) {
				wp_deregister_script( 'jquery' );
				wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), null, false );
				add_filter( 'script_loader_src', array( $this, 'jquery_local_fallback' ), 10, 2 );
			}
		}

		/**
		 * http://wordpress.stackexchange.com/a/12450
		 */
		function jquery_local_fallback( $src, $handle = null ) {
			static $add_jquery_fallback = false;

			if ( $add_jquery_fallback ) {
				echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery-1.11.0.min.js"><\/script>\')</script>' . "\n";
				$add_jquery_fallback = false;
			}

			if ( $handle === 'jquery' )
				$add_jquery_fallback = true;

			return $src;
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		public static function variables() {
			global $ss_settings;

			$padding_base  = intval( $ss_settings['padding_base'] );
			$border_radius = filter_var( $ss_settings['general_border_radius'], FILTER_SANITIZE_NUMBER_INT );
			$border_radius = ( strlen( $border_radius ) < 1 ) ? 0 : $border_radius;

			$variables = '';

			if ( $ss_settings['framework'] != 'foundation' ) {

				$variables .= '@padding-base-vertical:    ' . round( $padding_base * 6 / 6 ) . 'px;';
				$variables .= '@padding-base-horizontal:  ' . round( $padding_base * 12 / 6 ) . 'px;';

				$variables .= '@padding-large-vertical:   ' . round( $padding_base * 10 / 6 ) . 'px;';
				$variables .= '@padding-large-horizontal: ' . round( $padding_base * 16 / 6 ) . 'px;';

				$variables .= '@padding-small-vertical:   ' . round( $padding_base * 5 / 6 ) . 'px;';
				$variables .= '@padding-small-horizontal: @padding-large-vertical;';

				$variables .= '@padding-xs-vertical:      ' . round( $padding_base * 1 / 6 ) . 'px;';
				$variables .= '@padding-xs-horizontal:    @padding-small-vertical;';

				$variables .= '@border-radius-base:  ' . round( $border_radius * 4 / 4 ) . 'px;';
				$variables .= '@border-radius-large: ' . round( $border_radius * 6 / 4 ) . 'px;';
				$variables .= '@border-radius-small: ' . round( $border_radius * 3 / 4 ) . 'px;';

				$variables .= '@pager-border-radius: ' . round( $border_radius * 15 / 4 ) . 'px;';

				$variables .= '@tooltip-arrow-width: @padding-small-vertical;';
				$variables .= '@popover-arrow-width: (@tooltip-arrow-width * 2);';

				$variables .= '@thumbnail-padding:         ' . round( $padding_base * 4 / 6 ) . 'px;';
				$variables .= '@thumbnail-caption-padding: ' . round( $padding_base * 9 / 6 ) . 'px;';

				$variables .= '@badge-border-radius: ' . round( $border_radius * 10 / 4 ) . 'px;';

				$variables .= '@breadcrumb-padding-vertical:   ' . round( $padding_base * 8 / 6 ) . 'px;';
				$variables .= '@breadcrumb-padding-horizontal: ' . round( $padding_base * 15 / 6 ) . 'px;';
			}

			return $variables;
		}

		/**
		 * Add the variables to the compiler
		 */
		function variables_filter( $variables ) {
			return $variables . self::variables();
		}
	}
}

$advanced = new ShoestrapAdvanced();