<?php

if ( !class_exists( 'SS_Framework_Foundation' ) ) {

	/**
	* The Foundation Framework module
	*/
	class SS_Framework_Foundation extends SS_Framework_Core {

		var $defines = array(
			// Layout
			'container'  => null,
			'row'        => 'row',
			'col-mobile' => 'small',
			'col-tablet' => 'small',
			'col-medium' => 'medium',
			'col-large'  => 'large',
			// Block Grid not supported

			// Buttons
			'button'         => 'button',
			'button-default' => null,
			'button-primary' => null,
			'button-success' => 'success',
			'button-info'    => 'secondary',
			'button-warning' => 'alert',
			'button-danger'  => 'alert',
			'button-link'    => null,
			'button-disabled'=> 'disabled',

			'button-extra-small' => 'tiny',
			'button-small'       => 'small',
			'button-medium'      => null,
			'button-large'       => 'large',
			'button-extra-large' => 'large',
			'button-block'			 => 'expand',

			// Button-Groups
			'button-group'             => 'button-group',
			'button-group-extra-small' => null,
			'button-group-small'       => null,
			'button-group-default'     => null,
			'button-group-large'       => null,
			'button-group-extra-large' => null,
			// Button Bar not supported

			// Alerts
			'alert'         => 'alert-box',
			'alert-success' => 'success',
			'alert-info'    => 'info',
			'alert-warning' => 'warning',
			'alert-danger'  => 'warning',

			// Miscelaneous
			'clearfix' => '<div class="clearfix"></div>',
		);
		/**
		 * Class constructor
		 */
		function __construct() {

			if ( ! defined( 'SS_FRAMEWORK_PATH' ) ) {
				define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );
			}

			// Branding
			include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Colors.php' );
			// Typography
			include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Typography.php' );
			// Comments Walker
			include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Walker_Comment.php' );
			// Social
			// include_once( dirname( __FILE__ ) . '/modules/class-Shoestrap_Social.php' );
			// layout
			include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Layout.php' );
			// Header
			include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Header.php' );
			// The menus module
			include_once( dirname( __FILE__ ) . '/modules/class-SS_Foundation_Menus.php' );
			// Widgets
			include_once( dirname( __FILE__ ) . '/modules/widgets.php' );
			// Specific classes for navbar
			include_once( dirname( __FILE__ ) . '/nav-foundation.php' );

			add_filter( 'foundation_scss',    array( $this, 'styles_filter' ) );
			add_filter( 'comments_template',  array( $this, 'comments_template' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'css'  ), 101 );
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 110 );
		}

		/**
		 * Enqueue scripts and stylesheets
		 */
		function enqueue_scripts() {
			wp_register_script( 'bootstrap-min', get_template_directory_uri() . '/framework/foundation/assets/js/foundation.min.js',              false, null, true  );
			wp_enqueue_script( 'bootstrap-min' );
		}

		function comments_template() {
			return dirname( __FILE__ ) . '/templates/comments.php';
		}

		/**
		 * The framework's alert boxes.
		 */
		function alert( $type = 'info', $content = '', $id = null, $extra_classes = null, $dismiss = false ) {
			$classes = array();

			$classes[] = $this->defines['alert'];
			$classes[] = $this->defines['alert-' . $type];

			if ( true == $dismiss ) {
				$dismiss = '<a href="#" class="close">&times;</a>';
			} else {
				$dismiss = null;
			}

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
			}

			// If an ID has been defined, format it properly.
			if ( !is_null( $id ) ) {
				$id = ' id=' . $id . '"';
			}

			$classes = implode( ' ', $classes );

			return '<div data-alert class="' . $classes . '"' . $id . '>' . $content . $dismiss . '</div>';
		}

		function make_panel( $extra_classes = null, $id = null  ) {

			$classes = array();

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
				$classes = ' ' . implode( ' ', $classes );
			} else {
				$classes = null;
			}

			// If an ID has been defined, format it properly.
			if ( !is_null( $id ) ) {
				$id = ' id=' . $id . '"';
			}

			return '<div class="panel ' . $classes . '"' . $id . '>';
		}

		function make_panel_heading( $extra_classes = null ) {

			$classes = array();

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
				$classes = ' ' . implode( ' ', $classes );
			} else {
				$classes = null;
			}

			return '<div class="panel-heading' . $classes . '">';
		}

		function make_panel_body( $extra_classes = null ) {
			$classes = array();

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
				$classes = ' ' . implode( ' ', $classes );
			} else {
				$classes = null;
			}

			return '<div class="panel-body' . $classes . '">';
		}

		function make_panel_footer( $extra_classes = null ) {

			$classes = array();

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
				$classes = ' ' . implode( ' ', $classes );
			} else {
				$classes = null;
			}

			return '<div class="panel-footer' . $classes . '">';
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		function styles() {
			global $ss_settings;
			$vars  = '';

			// Base font-size
			if ( isset( $ss_settings['base-font']['font-size'] ) && ! empty( $ss_settings['base-font']['font-size'] ) ) {
				$vars .= '$base-font-size:' . $ss_settings['base-font']['font-size'] . ';';
			}

			// Base font-color
			if ( isset( $ss_settings['base-font']['font-color'] ) && ! empty( $ss_settings['base-font']['font-color'] ) ) {
				$vars .= '$body-font-color:' . $ss_settings['base-font']['color'] . ';';
			}

			// Base font-family
			if ( isset( $ss_settings['base-font']['font-family'] ) && ! empty( $ss_settings['base-font']['font-family'] ) ) {
				$vars .= '$body-font-family:' . $ss_settings['base-font']['font-family'] . ';';
			}

			// Base font-weight
			if ( isset( $ss_settings['base-font']['font-weight'] ) && ! empty( $ss_settings['base-font']['font-weight'] ) ) {
				$vars .= '$body-font-weight:' . $ss_settings['base-font']['font-weight'] . ';';
			}

			// Headers font-family
			if ( isset( $ss_settings['header-font']['font-family'] ) && ! empty( $ss_settings['header-font']['font-family'] ) ) {
				$vars .= '$header-font-family: ' . $ss_settings['header-font']['font-family'] . ';';
			}

			// Headers font-color
			if ( isset( $ss_settings['header-font']['font-color'] ) && ! empty( $ss_settings['header-font']['font-color'] ) ) {
				$vars .= '$header-font-color: ' . $ss_settings['header-font']['color'] . ';';
			}

			// Primary Color
			if ( isset( $ss_settings['primary-color'] ) && ! empty( $ss_settings['primary-color'] ) ) {
				$vars .= '$primary-color: ' . $ss_settings['primary-color'] . ';';
			}

			// Secondary Color
			if ( isset( $ss_settings['secondary-color'] ) && ! empty( $ss_settings['secondary-color'] ) ) {
				$vars .= '$secondary-color: ' . $ss_settings['secondary-color'] . ';';
			}

			// Alert Color
			if ( isset( $ss_settings['alert-color'] ) && ! empty( $ss_settings['alert-color'] ) ) {
				$vars .= '$alert-color: ' . $ss_settings['alert-color'] . ';';
			}

			// Success Color
			if ( isset( $ss_settings['success-color'] ) && ! empty( $ss_settings['success-color'] ) ) {
				$vars .= '$success-color: ' . $ss_settings['success-color'] . ';';
			}

			// Warning Color
			if ( isset( $ss_settings['warning-color'] ) && ! empty( $ss_settings['warning-color'] ) ) {
				$vars .= '$warning-color: ' . $ss_settings['warning-color'] . ';';
			}

			// Info Color
			if ( isset( $ss_settings['info-color'] ) && ! empty( $ss_settings['info-color'] ) ) {
				$vars .= '$info-color: ' . $ss_settings['info-color'] . ';';
			}

			// top-bar background
			if ( isset( $ss_settings['navigation-bg'] ) && ! empty( $ss_settings['navigation-bg'] ) ) {
				$vars .= '$topbar-bg: ' . $ss_settings['navigation-bg'] . ';';

				if ( Shoestrap_Color::get_brightness( $ss_settings['navigation-bg'] ) > 150 ) {
					$vars .= '$topbar-link-bg-hover: ' . Shoestrap_Color::adjust_brightness( $ss_settings['navigation-bg'], -15 ) . ';';
				} else {
					$vars .= '$topbar-link-bg-hover: ' . Shoestrap_Color::adjust_brightness( $ss_settings['navigation-bg'], 15 ) . ';';
				}
			}

			return $vars;
		}

		/**
		 * Add styles to the compiler
		 */
		function styles_filter( $scss ) {
			return $this->styles() . $scss;
		}

		/*
		 * This function can be used to compile a less file to css using the lessphp compiler
		 */
		function compiler() {
			global $ss_settings;

			if ( isset( $ss_settings['minimize_css'] ) && $ss_settings['minimize_css'] == 1 ) {
				$compress = true;
			} else {
				$compress = false;
			}

			$options = array( 'compress' => $compress );

			$scss_location    = dirname( __FILE__ ) . '/assets/scss/';
			$webfont_location = get_template_directory_uri() . '/assets/fonts/';
			$custom_less_file = get_stylesheet_directory() . '/assets/less/custom.less';


			$scss = new scssc();
			$scss->setImportPaths( $scss_location );

			$css .=  $scss->compile( apply_filters( 'foundation_scss', '@import "app.scss";' ) );

			// Ugly hack to properly set the path to webfonts
			$css = str_replace( "url('Elusive-Icons", "url('" . $webfont_location . "Elusive-Icons", $css );

			return $css;
		}

		/**
		 * The inline icon links for social networks.
		 */
		function navbar_social_bar() {}

		/**
		 * Build the social links for the navbar
		 */
		function navbar_social_links() {}

		/**
		 * Additiona CSS that is not included in the compiler
		 */
		function css() {
			global $ss_settings;

			$css = '';

			if( $ss_settings != 1000 ) {
				$css .= ".row, .contain-to-grid .top-bar { max-width:" . $ss_settings['max-width'] . "px }";
			}

			wp_add_inline_style( 'shoestrap_css', $css );
		}

		function include_wrapper() {
			global $ss_layout;

			return $ss_layout->include_wrapper();
		}

	}
}