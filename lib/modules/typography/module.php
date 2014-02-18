<?php


if ( !class_exists( 'ShoestrapTypography' ) ) {
	/**
	 * The "Typography" module
	 */
	class ShoestrapTypography {
		
		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 65 );
			add_action( 'wp_enqueue_scripts', array( $this, 'googlefont_links' ) );
			add_filter( 'shoestrap_compiler', array( $this, 'variables_filter' ) );
			add_filter( 'shoestrap_compiler', array( $this, 'styles'           ) );
		}

		/*
		 * The typography core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Typography Options
			$section = array(
				'title'   => __( 'Typography', 'shoestrap' ),
				'icon'    => 'el-icon-font icon-large',
			);

			$fields[] = array(
				'title'     => __( 'Base Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'id'        => 'font_base',
				'compiler'  => true,
				'units'     => 'px',
				'default'   => array(
					'font-family'   => 'Arial, Helvetica, sans-serif',
					'font-size'     => '14px',
					'google'        => 'false',
					'weight'        => 'inherit',
					'color'         => '#333333',
					'font-style'    => 400,
					'update_weekly' => true // Enable to force updates of Google Fonts to be weekly
				),
				'preview'   => array(
					'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'font-size'   => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
			);

			$fields[] = array(
				'title'     => __( 'Header Overrides', 'shoestrap' ),
				'desc'      => __( 'By enabling this you can specify custom values for each <h*> tag. Default: Off', 'shoestrap' ),
				'id'        => 'font_heading_custom',
				'default'   => 0,
				'compiler'  => true,
				'type'      => 'switch',
				'customizer'=> array(),
			);

			$fields[] = array(
				'title'     => __( 'H1 Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'id'        => 'font_h1',
				'compiler'  => true,
				'units'     => '%',
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => '260%',
					'color'       => '#333333',
					'google'      => 'false',
					'font-style'  => 400,

				),
				'preview'   => array(
					'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'font-size'   => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
				'required'  => array('font_heading_custom','=',array('1')),
			);

			$fields[] = array(
				'id'        => 'font_h2',
				'title'     => __( 'H2 Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'compiler'  => true,
				'units'     => '%',
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => '215%',
					'color'       => '#333333',
					'google'      => 'false',
					'font-style'  => 400,
				),
				'preview'   => array(
					'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'font-size'   => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
				'required'  => array('font_heading_custom','=',array('1')),
			);

			$fields[] = array(
				'id'        => 'font_h3',
				'title'     => __( 'H3 Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'compiler'  => true,
				'units'     => '%',
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => '170%',
					'color'       => '#333333',
					'google'      => 'false',
					'font-style'  => 400,
				),
				'preview'   => array(
					'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'font-size'   => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
				'required'  => array('font_heading_custom','=',array('1')),
			);

			$fields[] = array(
				'title'     => __( 'H4 Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'id'        => 'font_h4',
				'compiler'  => true,
				'units'     => '%',
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => '125%',
					'color'       => '#333333',
					'google'      => 'false',
					'font-style'  => 400,
				),
				'preview'   => array(
					'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'font-size'   => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
				'required'  => array('font_heading_custom','=',array('1')),
			);

			$fields[] = array(
				'title'     => __( 'H5 Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'id'        => 'font_h5',
				'compiler'  => true,
				'units'     => '%',
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => '100%',
					'color'       => '#333333',
					'google'      => 'false',
					'font-style'  => 400,
				),
				'preview'       => array(
					'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'font-size'   => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
				'required'  => array('font_heading_custom','=',array('1')),
			);

			$fields[] = array(
				'title'     => __( 'H6 Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'id'        => 'font_h6',
				'compiler'  => true,
				'units'     => '%',
				'default'   => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => '85%',
					'color'       => '#333333',
					'google'      => 'false',
					'font-weight' => 400,
					'font-style'  => 'normal',
				),
				'preview'   => array(
					'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
					'font-size'   => '30px' //this is the text size from preview box
				),
				'type'      => 'typography',
				'required'  => array('font_heading_custom','=',array('1')),
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'options_modifier', $section );

			$sections[] = $section;
			return $sections;
		}

		/*
		 * Helper function
		 */
		public static function getGoogleScript( $font ) {
			$data['link'] = '//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $font['font-family'] );
			$data['key'] = str_replace( ' ', '_', $font['font-family'] );

			if ( !empty( $font['font-weight'] ) )
				$data['link'] .= ':' . str_replace( '-', '', $font['font-weight'] );

			if ( !empty( $font['font-style'] ) )
				$data['key'] .= '-' . str_replace( '_', '', $font['font-style'] );

			if ( !empty( $font['subsets'] ) ) {
				$data['link'] .= '&subset=' . $font['subsets'];
				$data['key'] .= '-' . str_replace( '_', '', $font['subsets'] );
			}

			return $data;
		}

		/*
		 * The Google Webonts script
		 */
		function googlefont_links() {
			$font_base            = shoestrap_getVariable( 'font_base' );
			$font_navbar          = shoestrap_getVariable( 'font_navbar' );
			$font_brand           = shoestrap_getVariable( 'font_brand' );
			$font_jumbotron       = shoestrap_getVariable( 'font_jumbotron' );
			$font_heading         = shoestrap_getVariable( 'font_heading' );

			if ( !isset( $font_base['google'] ) || is_null( $font_base['google'] ) || empty( $font_base['google'] ) )
				$font_base['google'] = false;

			if ( !isset( $font_navbar['google'] ) || is_null( $font_navbar['google'] ) || empty( $font_navbar['google'] ) )
				$font_navbar['google'] = false;

			if ( !isset( $font_brand['google'] ) || is_null( $font_brand['google'] ) || empty( $font_brand['google'] ) )
				$font_brand['google'] = false;

			if ( !isset( $font_jumbotron['google'] ) || is_null( $font_jumbotron['google'] ) || empty( $font_jumbotron['google'] ) )
				$font_jumbotron['google'] = false;

			if ( !isset( $font_heading['google'] ) || is_null( $font_heading['google'] ) || empty( $font_heading['google'] ) )
				$font_heading['google'] = false;

			if ( shoestrap_getVariable( 'font_heading_custom' ) ) {
				$font_h1 = shoestrap_getVariable( 'font_h1' );
				$font_h2 = shoestrap_getVariable( 'font_h2' );
				$font_h3 = shoestrap_getVariable( 'font_h3' );
				$font_h4 = shoestrap_getVariable( 'font_h4' );
				$font_h5 = shoestrap_getVariable( 'font_h5' );
				$font_h6 = shoestrap_getVariable( 'font_h6' );
			}

			if (shoestrap_getVariable( 'font_jumbotron_heading_custom' ) == 1) {
				$font_jumbotron_headers = shoestrap_getVariable( 'font_jumbotron_headers' );
			}

			if ( $font_base['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_base );
				wp_register_style( $font['key'], $font['link'] );
				wp_enqueue_style( $font['key'] );
			}

			if ( $font_navbar['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_navbar );
				wp_register_style( $font['key'], $font['link'] );
				wp_enqueue_style( $font['key'] );
			}

			if ( $font_brand['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_brand );
				wp_register_style( $font['key'], $font['link'] );
				wp_enqueue_style( $font['key'] );
			}

			if ( $font_jumbotron['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_jumbotron );
				wp_register_style( $font['key'], $font['link'] );
				wp_enqueue_style( $font['key'] );
			}

			if ( shoestrap_getVariable( 'font_heading_custom' ) ) {

				if ( $font_h1['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h1 );
					wp_register_style( $font['key'], $font['link'] );
					wp_enqueue_style( $font['key'] );
				}

				if ( $font_h2['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h2 );
					wp_register_style( $font['key'], $font['link'] );
					wp_enqueue_style( $font['key'] );
				}

				if ( $font_h3['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h3 );
					wp_register_style( $font['key'], $font['link'] );
					wp_enqueue_style( $font['key'] );
				}

				if ( $font_h4['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h4 );
					wp_register_style( $font['key'], $font['link'] );
					wp_enqueue_style( $font['key'] );
				}

				if ( $font_h5['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h5 );
					wp_register_style( $font['key'], $font['link'] );
					wp_enqueue_style( $font['key'] );
				}

				if ( $font_h6['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h6 );
					wp_register_style( $font['key'], $font['link'] );
					wp_enqueue_style( $font['key'] );
				}
			} elseif ( isset( $font_heading['google'] ) && $font_heading['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_heading );
				wp_register_style( $font['key'], $font['link'] );
				wp_enqueue_style( $font['key'] );
			}

			if ( shoestrap_getVariable( 'font_jumbotron_heading_custom' ) == 1 ) {
				if ($font_jumbotron_headers['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_jumbotron_headers );
					wp_register_style( $font['key'], $font['link'] );
					wp_enqueue_style( $font['key'] );
				}
			}
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		function variables() {
			$font_base = shoestrap_process_font( shoestrap_getVariable( 'font_base', true ) );
			$font_h1   = shoestrap_process_font( shoestrap_getVariable( 'font_h1', true ) );
			$font_h2   = shoestrap_process_font( shoestrap_getVariable( 'font_h2', true ) );
			$font_h3   = shoestrap_process_font( shoestrap_getVariable( 'font_h3', true ) );
			$font_h4   = shoestrap_process_font( shoestrap_getVariable( 'font_h4', true ) );
			$font_h5   = shoestrap_process_font( shoestrap_getVariable( 'font_h5', true ) );
			$font_h6   = shoestrap_process_font( shoestrap_getVariable( 'font_h6', true ) );

			$text_color     = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_base['color'] ) );
			$sans_serif     = $font_base['font-family'];
			$font_size_base = $font_base['font-size'];

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
				$font_h1_color  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_h1['color'] ) );

				$font_h2_face   = $font_h2['font-family'];
				$font_h2_weight = $font_h2['font-weight'];
				$font_h2_style  = $font_h2['font-style'];
				$font_h2_color  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_h2['color'] ) );

				$font_h3_face   = $font_h3['font-family'];
				$font_h3_weight = $font_h3['font-weight'];
				$font_h3_style  = $font_h3['font-style'];
				$font_h3_color  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_h3['color'] ) );

				$font_h4_face   = $font_h4['font-family'];
				$font_h4_weight = $font_h4['font-weight'];
				$font_h4_style  = $font_h4['font-style'];
				$font_h4_color  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_h4['color'] ) );

				$font_h5_face   = $font_h5['font-family'];
				$font_h5_weight = $font_h5['font-weight'];
				$font_h5_style  = $font_h5['font-style'];
				$font_h5_color  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_h5['color'] ) );

				$font_h6_face   = $font_h6['font-family'];
				$font_h6_weight = $font_h6['font-weight'];
				$font_h6_style  = $font_h6['font-style'];
				$font_h6_color  = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $font_h6['color'] ) );
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
			return $bootstrap . '
			@import "' . SHOESTRAP_MODULES_PATH . '/typography/assets/less/styles.less";';
		}
	}
}

$typography = new ShoestrapTypography();