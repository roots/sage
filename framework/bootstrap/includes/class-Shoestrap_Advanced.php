<?php


if ( !class_exists( 'Shoestrap_Advanced' ) ) {

	/**
	* The "Advanced" module
	*/
	class Shoestrap_Advanced {

		function __construct() {
			global $ss_settings;

			add_action( 'wp_enqueue_scripts', array( $this, 'user_css'           ), 101 );
			add_action( 'wp_footer',          array( $this, 'user_js'            ), 200 );
			add_filter( 'show_admin_bar',     array( $this, 'admin_bar'          )      );
			add_action( 'wp_footer',          array( $this, 'google_analytics'   ), 20  );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts'            ), 100 );

			 // Toggle activation of the jQuery CDN
			if ( isset( $ss_settings['jquery_cdn_toggler'] ) && $ss_settings['jquery_cdn_toggler'] == 1 ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'jquery_cdn' ), 101 );
				add_action( 'wp_head',            array( $this, 'jquery_local_fallback' ) );
			}

			if ( isset( $ss_settings['nice_search'] ) && $ss_settings['nice_search'] == 1 ) {
				add_action( 'template_redirect', array( $this, 'nice_search_redirect' ) );
			}

			/**
			 * Post Excerpt Length
			 * Length in words for excerpt_length filter (http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length)
			 */
			if ( isset( $ss_settings['post_excerpt_length'] ) ) {
				define( 'POST_EXCERPT_LENGTH', $ss_settings['post_excerpt_length'] );
			}

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

		/*
		 * echo any custom CSS the user has written to the <head> of the page
		 */
		function user_css() {
			$settings = get_option( SHOESTRAP_OPT_NAME );
			$header_scripts = $settings['user_css'];

			if ( trim( $header_scripts ) != '' ) {
				wp_add_inline_style( 'shoestrap_css', $header_scripts );
			}
		}

		/*
		 * echo any custom JS the user has written to the footer of the page
		 */
		function user_js() {
			$settings = get_option( SHOESTRAP_OPT_NAME );
			$footer_scripts = $settings['user_js'];

			if ( trim( $footer_scripts ) != '' ) {
				echo '<script id="core.advanced-user-js">' . $footer_scripts . '</script>';
			}
		}

		/**
		 * Switch the adminbar On/Off
		 */
		function admin_bar() {
			$settings = get_option( SHOESTRAP_OPT_NAME );
			if ( $settings['advanced_wordpress_disable_admin_bar_toggle'] == 0 ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * The Google Analytics code
		 */
		function google_analytics() {
			$settings = get_option( SHOESTRAP_OPT_NAME );
			$analytics_id = $settings['analytics_id'];

			if ( !is_null( $analytics_id ) && !empty( $analytics_id ) ) {
				echo "<script>(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;e=o.createElement(i);r=o.getElementsByTagName(i)[0];e.src='//www.google-analytics.com/analytics.js';r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));ga('create','" . $analytics_id . "');ga('send','pageview');</script>";
			}
		}

		/**
		 * Redirects search results from /?s=query to /search/query/, converts %20 to +
		 *
		 * @link http://txfx.net/wordpress-plugins/nice-search/
		 */
		function nice_search_redirect() {
			global $wp_rewrite;

			if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() ) {
				return;
			}

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

			if ( !isset( $matches[1] ) || !isset( $matches[2] ) ) {
				return $input;
			} elseif ( ( $matches[1] === $_SERVER['SERVER_NAME'] ) || $matches[1] === $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] ) {
				return wp_make_link_relative($input);
			} else {
				return $input;
			}
		}

		function enable_root_relative_urls() {
			return !( is_admin() || in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) );
		}

		/**
		 * Enqueue some extra scripts
		 */
		function scripts() {
			$settings = get_option( SHOESTRAP_OPT_NAME );

			if ( $settings['retina_toggle'] == 1 ) {
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

			if ( $handle === 'jquery' ) {
				$add_jquery_fallback = true;
			}

			return $src;
		}
	}
}
