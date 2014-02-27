<?php

if ( !class_exists( 'SS_Framework_Foundation' ) ) {

	/**
	* The Foundation Framework module
	*/
	class SS_Framework_Foundation {

		/**
		 * Class constructor
		 */
		function __construct() {

			$this->defines = array(
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

			add_filter( 'shoestrap_foundation_scss', array( $this, 'styles_filter' ) );

			add_action( 'wp_enqueue_scripts',    array( $this, 'css'  ), 101 );
		}

		/**
		 * Makes a container
		 */
		function make_container() { }

		/**
		 * Creates a row using the framework definitions.
		 *
		 * @param string $element         Can be any valid dom element.
		 * @param string $id              The element ID.
		 * @param string $extra_classes   Any extra classes we want to add to the row. extra classes should be separated using a space.
		 * @param string $properties      Can be something like 'name="left_top"'.
		 */
		function make_row( $element = 'div', $id = null, $extra_classes = null, $properties = null ) {

			$classes = $this->defines['row'];

			if ( !is_null( $id ) ) {
				$id = ' id=' . $id . '"';
			}

			if ( !is_null( $extra_classes ) ) {
				$classes .= ' ' . $extra_classes;
			}

			if ( !is_null( $properties ) ) {
				$properties = ' ' . $properties;
			}

			return '<' . $element . $id . ' class="' . $classes . '"' . $properties . '>';
		}

		/**
		 * Creates a column using the framework definitions.
		 *
		 * @param string $element         Can be any valid dom element.
		 * @param array  $sizes           Format is size => columns. Example: array( 'mobile' => 12, 'tablet' => 12, 'medium' => 6, 'large' => 4 )
		 * @param string $id              The element ID.
		 * @param string $extra_classes   Any extra classes we want to add to the row. extra classes should be separated using a space.
		 * @param string $properties      Can be something like 'name="left_top"'.
		 */
		function make_col( $element = 'div', $sizes = array( 'medium' => 12 ), $id = null, $extra_classes = null, $properties = null ) {

			// Get the classes based on the $sizes array.
			$classes = $this->column_classes( $sizes );


			// If extra classes are defined, add them to the array of classes.
			if ( !is_null( $extra_classes ) ) {
				$extra_classes = explode( ' ', $extra_classes );

				foreach ( $extra_classes as $extra_class ) {
					$classes[] = $extra_class;
				}
			}

			// build the CSS classes from the array
			$css_classes = implode( ' ', $classes );

			// If an ID has been defined, format it properly.
			if ( !is_null( $id ) ) {
				$id = ' id=' . $id . '"';
			}

			// Are there any extra properties to add?
			if ( !is_null( $properties ) ) {
				$properties = ' ' . $properties;
			}

			return '<' . $element . $id . ' class="' . $css_classes . '"' . $properties . '>';
		}

		/**
		 * Column classes
		 */
		function column_classes( $sizes = array(), $return = 'array' ) {
			$classes = array();

			// Get the classes based on the $sizes array.
			foreach ( $sizes as $size => $columns ) {
				$classes[] = $this->defines['col-' . $size] . '-' . $columns;
			}
			$classes[] = 'columns';

			if ( $return == 'array' ) {
				return $classes;
			} else {
				return implode( ' ', $classes );
			}

		}

		/**
		 * Get the button classes
		 *
		 * @param string $color
		 * @param string $size
		 * @param string $type
		 */
		function button_classes( $color = 'primary', $size = 'medium', $type = null, $extra = null ) {

			$classes = array();

			$classes[] = $this->defines['button'];

			// Should we allow multiple colors?
			// Perhaps we should... you never know.
			if ( !is_null( $color ) ) {
				$colors = explode( ' ', $color );

				foreach ( $colors as $color ) {
					$classes[] = $this->defines['button-' . $color];
				}
			}

			// Get the proper class for button sizing from the framework definitions.
			if ( $size == 'extra-small' ) {
				$classes[] = $this->defines['button-extra-small'];
			} elseif ( $size == 'small' ) {
				$classes[] = $this->defines['button-small'];
			} elseif ( $size == 'medium' ) {
				$classes[] = $this->defines['button-medium'];
			} elseif ( $size == 'large' ) {
				$classes[] = $this->defines['button-large'];
			} elseif ( $size == 'extra-large' ) {
				$classes[] = $this->defines['button-extra-large'];
			} elseif ( $size == 'block' ) {
				$classes[] = $this->defines['button-block'];
			}

			if ( !is_null( $type ) ) {
				$types = explode( ' ', $type );

				foreach ( $types as $type ) {
					$classes[] = $type;
				}
			}

			if ( !is_null( $extra ) ) {
				$extras = explode( ' ', $extra );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
			}

			// build the CSS classes from the array
			$css_classes = implode( ' ', $classes );

			return $css_classes;
		}

		function button_group_classes( $size = null, $type = null, $extra_classes = null ) {

			$classes = array();

			$classes[] = $this->defines['button-group'];

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
			}

			if ( !is_null( $type ) ) {
				$types = explode( ' ', $type );

				foreach ( $types as $type ) {
					$classes[] = $type;
				}
			}


			$classes = implode( ' ', $classes );

			return $classes;
		}

		/**
		 * The framework's clearfix
		 */
		function clearfix() {
			return $this->defines['clearfix'];
		}

		function pagination_ul_class() {
			return 'pagination';
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

			if ( $ss_settings['minimize_css'] == 1 ) {
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

			$css .=  $scss->compile( apply_filters( 'shoestrap_foundation_scss', '@import "app.scss";' ) );

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
				$css .= ".row { max-width:" . $ss_settings['max-width'] . "px }";
			}

			wp_add_inline_style( 'shoestrap_css', $css );
		}

		function include_wrapper() {
			global $ss_layout;

			return $ss_layout->include_wrapper();
		}

	}
}