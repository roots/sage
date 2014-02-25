<?php

if ( !class_exists( 'Shoestrap_Bootstrap' ) ) {

	/**
	* The Bootstrap Framework module
	*/
	class Shoestrap_Bootstrap {

		/**
		 * Class constructor
		 */
		function __construct() {

			$this->defines = array(
				// Layout
				'container'  => 'container',
				'row'        => 'row',
				'col-mobile' => 'col-xs',
				'col-tablet' => 'col-sm',
				'col-medium' => 'col-md',
				'col-large'  => 'col-lg',

				// Buttons
				'button'         => 'btn',
				'button-default' => 'btn-default',
				'button-primary' => 'btn-primary',
				'button-success' => 'btn-success',
				'button-info'    => 'btn-info',
				'button-warning' => 'btn-warning',
				'button-danger'  => 'btn-danger',
				'button-link'    => 'btn-link',

				'button-extra-small' => 'btn-xs',
				'button-small'       => 'btn-sm',
				'button-medium'      => null,
				'button-large'       => 'btn-lg',
				'button-extra-large' => 'btn-lg',

				// Button-Groups
				'button-group'             => 'btn-group',
				'button-group-extra-small' => 'btn-group-xs',
				'button-group-small'       => 'btn-group-sm',
				'button-group-default'     => null,
				'button-group-large'       => 'btn-group-lg',
				'button-group-extra-large' => 'btn-group-lg',

				// Alerts
				'alert'         => 'alert',
				'alert-success' => 'alert-success',
				'alert-info'    => 'alert-info',
				'alert-warning' => 'alert-warning',
				'alert-danger'  => 'alert-danger',

				// Miscelaneous
				'clearfix' => '<div class="clearfix"></div>',
			);

			add_filter( 'shoestrap_frameworks_array', array( $this, 'add_framework' ) );
			add_filter( 'shoestrap_compiler', array( $this, 'styles' ) );
		}

		/**
		 * Define the framework.
		 * These will be used in the redux admin option to choose a framework.
		 */
		function define_framework() {
			$framework = array(
				'shortname' => 'bootstrap',
				'name'      => 'Bootstrap',
				'classname' => 'Shoestrap_Bootstrap',
				'compiler'  => 'less_php'
			);

			return $framework;

		}

		/**
		 * Add the framework to redux
		 */
		function add_framework( $frameworks ) {
			$frameworks[] = $this->define_framework();

			return $frameworks;
		}

		/**
		 * Makes a container
		 */
		function make_container() {

		}

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

		function button_group_classes( $size = 'medium', $type = null, $extra_classes = null ) {

			$classes = array();

			$classes[] = $this->defines['button-group'];

			// Get the proper class for button sizing from the framework definitions.
			if ( $size == 'extra-small' ) {
				$classes[] = $this->defines['button-group-extra-small'];
			} elseif ( $size == 'small' ) {
				$classes[] = $this->defines['button-group-small'];
			} elseif ( $size == 'medium' ) {
				$classes[] = $this->defines['button-group-medium'];
			} elseif ( $size == 'large' ) {
				$classes[] = $this->defines['button-group-large'];
			} elseif ( $size == 'extra-large' ) {
				$classes[] = $this->defines['button-group-extra-large'];
			}

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

		/**
		 * The framework's alert boxes.
		 */
		function alert( $type = 'info', $content = '', $id = null, $extra_classes = null, $dismiss = false ) {
			$classes = array();

			$classes[] = $this->defines['alert'];
			$classes[] = $this->defines['alert-' . $type];

			if ( true == $dismiss ) {
				$classes[] = 'alert-dismissable';

				$dismiss = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
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

			return '<div class="' . $classes . '"' . $id . '>' . $dismiss . $content . '</div>';
		}

		function nav_template() {
			if ( !has_action( 'shoestrap_do_navbar' ) ) {
				get_template_part( 'lib/modules/framework/bootstrap/header-top-navbar' );
			} else {
				do_action( 'shoestrap_do_navbar' );
			}
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

			$bootstrap_location = dirname( __FILE__ ) . '/assets/less/';
			$webfont_location   = get_template_directory() . '/assets/fonts/';
			$bootstrap_uri      = '';
			$custom_less_file   = get_stylesheet_directory() . '/assets/less/custom.less';

			$css = '';
			try {

				$parser = new Less_Parser( $options );

				// The main app.less file
				$parser->parseFile( $bootstrap_location . 'app.less', $bootstrap_uri );

				// Include the Elusive Icons
				$parser->parseFile( $webfont_location . 'elusive-webfont.less', $bootstrap_uri );

				// Enable gradients
				if ( $ss_settings['gradients_toggle'] == 1 ) {
					$parser->parseFile( $bootstrap_location . 'gradients.less', $bootstrap_uri );
				}

				// The custom.less file
				if ( is_writable( $custom_less_file ) ) {
					$parser->parseFile( $bootstrap_location . 'custom.less', $bootstrap_uri );
				}

				// Parse any custom less added by the user
				$parser->parse( $ss_settings['user_less'] );
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

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		function variables() {
			global $ss_settings;

			/**
			 * LAYOUT
			 */
			$screen_sm = filter_var( $ss_settings['screen_tablet'], FILTER_SANITIZE_NUMBER_INT );
			$screen_md = filter_var( $ss_settings['screen_desktop'], FILTER_SANITIZE_NUMBER_INT );
			$screen_lg = filter_var( $ss_settings['screen_large_desktop'], FILTER_SANITIZE_NUMBER_INT );
			$gutter    = filter_var( $ss_settings['layout_gutter'], FILTER_SANITIZE_NUMBER_INT );
			$gutter    = ( $gutter < 2 ) ? 2 : $gutter;

			$site_style = $ss_settings['site_style'];

			$screen_xs = ( $site_style == 'static' ) ? '50px' : '480px';
			$screen_sm = ( $site_style == 'static' ) ? '50px' : $screen_sm;
			$screen_md = ( $site_style == 'static' ) ? '50px' : $screen_md;

			$variables = '';

			$variables .= '@screen-sm: ' . $screen_sm . 'px;';
			$variables .= '@screen-md: ' . $screen_md . 'px;';
			$variables .= '@screen-lg: ' . $screen_lg . 'px;';

			$variables .= '@grid-gutter-width: ' . $gutter . 'px;';

			$variables .= '@jumbotron-padding: @grid-gutter-width;';

			$variables .= '@modal-inner-padding: ' . round( $gutter * 20 / 30 ) . 'px;';
			$variables .= '@modal-title-padding: ' . round( $gutter * 15 / 30 ) . 'px;';

			$variables .= '@modal-lg: ' . round( $screen_md - ( 3 * $gutter ) ) . 'px;';
			$variables .= '@modal-md: ' . round( $screen_sm - ( 3 * $gutter ) ) . 'px;';
			$variables .= '@modal-sm: ' . round( $screen_xs - ( 3 * $gutter ) ) . 'px;';

			$variables .= '@panel-body-padding: @modal-title-padding;';

			$variables .= '@container-tablet:        ' . ( $screen_sm - ( $gutter / 2 ) ). 'px;';
			$variables .= '@container-desktop:       ' . ( $screen_md - ( $gutter / 2 ) ). 'px;';
			$variables .= '@container-large-desktop: ' . ( $screen_lg - $gutter ). 'px;';

			if ( $site_style == 'static' ) {
				// disable responsiveness
				$variables .= '@screen-xs-max: 0 !important;
				.container { max-width: none !important; width: @container-large-desktop; }
				html { overflow-x: auto !important; }';
			}

			/**
			 * TYPOGRAPHY
			 */
			$font_base = shoestrap_process_font( shoestrap_getVariable( 'font_base', true ) );
			$font_h1   = shoestrap_process_font( shoestrap_getVariable( 'font_h1', true ) );
			$font_h2   = shoestrap_process_font( shoestrap_getVariable( 'font_h2', true ) );
			$font_h3   = shoestrap_process_font( shoestrap_getVariable( 'font_h3', true ) );
			$font_h4   = shoestrap_process_font( shoestrap_getVariable( 'font_h4', true ) );
			$font_h5   = shoestrap_process_font( shoestrap_getVariable( 'font_h5', true ) );
			$font_h6   = shoestrap_process_font( shoestrap_getVariable( 'font_h6', true ) );

			$text_color       = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_base['color'] ) );
			$sans_serif       = $font_base['font-family'];
			$font_size_base   = $font_base['font-size'];
			$font_weight_base = $font_base['font-weight'];

			$font_h1_size   = ( ( filter_var( $font_h1['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h2_size   = ( ( filter_var( $font_h2['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h3_size   = ( ( filter_var( $font_h3['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h4_size   = ( ( filter_var( $font_h4['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h5_size   = ( ( filter_var( $font_h5['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h6_size   = ( ( filter_var( $font_h6['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );

			if ( shoestrap_getVariable( 'font_heading_custom', true ) != 1 ) {

				$font_h1_face = $font_h2_face = $font_h3_face = $font_h4_face = $font_h5_face = $font_h6_face = 'inherit';

				$font_h1_weight = $font_h2_weight = $font_h3_weight = $font_h5_weight = $font_h4_weight = $font_h6_weight = '500';

				$font_h1_style = $font_h2_style = $font_h3_style = $font_h4_style = $font_h5_style = $font_h6_style = 'inherit';

				$font_h1_color  = $font_h2_color  = $font_h3_color  = $font_h4_color  = $font_h5_color  = $font_h6_color  = 'inherit';

			} else {
				$font_h1_face   = $font_h1['font-family'];
				$font_h1_weight = $font_h1['font-weight'];
				$font_h1_style  = $font_h1['font-style'];
				$font_h1_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h1['color'] ) );

				$font_h2_face   = $font_h2['font-family'];
				$font_h2_weight = $font_h2['font-weight'];
				$font_h2_style  = $font_h2['font-style'];
				$font_h2_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h2['color'] ) );

				$font_h3_face   = $font_h3['font-family'];
				$font_h3_weight = $font_h3['font-weight'];
				$font_h3_style  = $font_h3['font-style'];
				$font_h3_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h3['color'] ) );

				$font_h4_face   = $font_h4['font-family'];
				$font_h4_weight = $font_h4['font-weight'];
				$font_h4_style  = $font_h4['font-style'];
				$font_h4_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h4['color'] ) );

				$font_h5_face   = $font_h5['font-family'];
				$font_h5_weight = $font_h5['font-weight'];
				$font_h5_style  = $font_h5['font-style'];
				$font_h5_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h5['color'] ) );

				$font_h6_face   = $font_h6['font-family'];
				$font_h6_weight = $font_h6['font-weight'];
				$font_h6_style  = $font_h6['font-style'];
				$font_h6_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h6['color'] ) );
			}

			$variables = '';

			$variables .= '@text-color:             ' . $text_color . ';';
			$variables .= '@font-family-sans-serif: ' . $sans_serif . ';';
			$variables .= '@font-size-base:         ' . $font_size_base . 'px;';

			$variables .= '@font-size-h1: floor((@font-size-base * ' . $font_h1_size . '));';
			$variables .= '@font-size-h2: floor((@font-size-base * ' . $font_h2_size . '));';
			$variables .= '@font-size-h3: ceil((@font-size-base * ' . $font_h3_size . '));';
			$variables .= '@font-size-h4: ceil((@font-size-base * ' . $font_h4_size . '));';
			$variables .= '@font-size-h5: ' . $font_h5_size . ';';
			$variables .= '@font-size-h6: ceil((@font-size-base * ' . $font_h6_size . '));';

			$variables .= '@caret-width-base:  ceil(@font-size-small / 3 );';
			$variables .= '@caret-width-large: ceil(@caret-width-base * (5/4) );';

			$variables .= '@table-cell-padding:           ceil((@font-size-small * 2) / 3 );';
			$variables .= '@table-condensed-cell-padding: ceil(((@font-size-small / 3 ) * 5) / 4);';

			$variables .= '@carousel-control-font-size: ceil((@font-size-base * 1.43));';

			// Shoestrap-specific variables
			// --------------------------------------------------

			$variables .= '@base-font-weight:        ' . $font_weight_base . ';';

			// H1
			$variables .= '@heading-h1-face:         ' . $font_h1_face . ';';
			$variables .= '@heading-h1-weight:       ' . $font_h1_weight . ';';
			$variables .= '@heading-h1-style:        ' . $font_h1_style . ';';
			$variables .= '@heading-h1-color:        ' . $font_h1_color . ';';

			// H2
			$variables .= '@heading-h2-face:         ' . $font_h2_face . ';';
			$variables .= '@heading-h2-weight:       ' . $font_h2_weight . ';';
			$variables .= '@heading-h2-style:        ' . $font_h2_style . ';';
			$variables .= '@heading-h2-color:        ' . $font_h2_color . ';';

			// H3
			$variables .= '@heading-h3-face:         ' . $font_h3_face . ';';
			$variables .= '@heading-h3-weight:       ' . $font_h3_weight . ';';
			$variables .= '@heading-h3-style:        ' . $font_h3_style . ';';
			$variables .= '@heading-h3-color:        ' . $font_h3_color . ';';

			// H4
			$variables .= '@heading-h4-face:         ' . $font_h4_face . ';';
			$variables .= '@heading-h4-weight:       ' . $font_h4_weight . ';';
			$variables .= '@heading-h4-style:        ' . $font_h4_style . ';';
			$variables .= '@heading-h4-color:        ' . $font_h4_color . ';';

			// H5
			$variables .= '@heading-h5-face:         ' . $font_h5_face . ';';
			$variables .= '@heading-h5-weight:       ' . $font_h5_weight . ';';
			$variables .= '@heading-h5-style:        ' . $font_h5_style . ';';
			$variables .= '@heading-h5-color:        ' . $font_h5_color . ';';

			// H6
			$variables .= '@heading-h6-face:         ' . $font_h6_face . ';';
			$variables .= '@heading-h6-weight:       ' . $font_h6_weight . ';';
			$variables .= '@heading-h6-style:        ' . $font_h6_style . ';';
			$variables .= '@heading-h6-color:        ' . $font_h6_color . ';';

			return $variables;
		}

		/**
		 * Add the variables to the compiler
		 */
		function variables_filter( $variables ) {
			return $variables . self::variables();
		}

		function styles( $bootstrap ) {
				$return  = $bootstrap;
				$return .= '@import "' . dirname( __FILE__ ) . '/assets/less/blog.less";';
				$return .= '@import "' . dirname( __FILE__ ) . '/assets/less/headers.less";';
				$return .= '@import "' . dirname( __FILE__ ) . '/assets/less/layout.less";';
				$return .= '@import "' . dirname( __FILE__ ) . '/assets/less/typography.less";';
				$return .= '@import "' . dirname( __FILE__ ) . '/assets/less/social.less";';
				$return .= '@import "' . dirname( __FILE__ ) . '/assets/less/menus.less";';
				$return .= '@import "' . dirname( __FILE__ ) . '/assets/less/widgets.less";';
		}
	}

	$bootstrap = new Shoestrap_Bootstrap();
}