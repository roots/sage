<?php


if ( !class_exists( 'Shoestrap_Typography' ) ) {
	/**
	 * The "Typography" module
	 */
	class Shoestrap_Typography {
		
		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 65 );
			add_action( 'wp_enqueue_scripts', array( $this, 'googlefont_links' ) );
		}

		/*
		 * The typography core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Typography Options
			$section = array(
				'title'   => __( 'Typography', 'shoestrap' ),
				'icon'    => 'el-icon-font',
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
			global $ss_settings;

			$font_base            = $ss_settings['font_base'];
			$font_navbar          = $ss_settings['font_navbar'];
			$font_brand           = $ss_settings['font_brand'];
			$font_jumbotron       = $ss_settings['font_jumbotron'];
			$font_heading         = $ss_settings['font_heading'];

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

			if ( $ss_settings['font_heading_custom'] ) {
				$font_h1 = $ss_settings['font_h1'];
				$font_h2 = $ss_settings['font_h2'];
				$font_h3 = $ss_settings['font_h3'];
				$font_h4 = $ss_settings['font_h4'];
				$font_h5 = $ss_settings['font_h5'];
				$font_h6 = $ss_settings['font_h6'];
			}

			if ( $ss_settings['font_jumbotron_heading_custom'] == 1) {
				$font_jumbotron_headers = $ss_settings['font_jumbotron_headers'];
			}

			if ( $font_base['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_base );
				wp_register_style( 'ss-googlefont-base', $font['link'] );
				wp_enqueue_style( 'ss-googlefont-base' );
			}

			if ( $font_navbar['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_navbar );
				wp_register_style( 'ss-googlefont-navbar', $font['link'] );
				wp_enqueue_style( 'ss-googlefont-navbar' );
			}

			if ( $font_brand['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_brand );
				wp_register_style( 'ss-googlefont-brand', $font['link'] );
				wp_enqueue_style( 'ss-googlefont-brand' );
			}

			if ( $font_jumbotron['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_jumbotron );
				wp_register_style( 'ss-googlefont-jumbotron', $font['link'] );
				wp_enqueue_style( 'ss-googlefont-jumbotron' );
			}

			if ( $ss_settings['font_heading_custom'] ) {

				if ( $font_h1['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h1 );
					wp_register_style( 'ss-googlefont-h1', $font['link'] );
					wp_enqueue_style( 'ss-googlefont-h1' );
				}

				if ( $font_h2['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h2 );
					wp_register_style( 'ss-googlefont-h2', $font['link'] );
					wp_enqueue_style( 'ss-googlefont-h2' );
				}

				if ( $font_h3['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h3 );
					wp_register_style( 'ss-googlefont-h3', $font['link'] );
					wp_enqueue_style( 'ss-googlefont-h3' );
				}

				if ( $font_h4['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h4 );
					wp_register_style( 'ss-googlefont-h4', $font['link'] );
					wp_enqueue_style( 'ss-googlefont-h4' );
				}

				if ( $font_h5['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h5 );
					wp_register_style( 'ss-googlefont-h5', $font['link'] );
					wp_enqueue_style( 'ss-googlefont-h5' );
				}

				if ( $font_h6['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_h6 );
					wp_register_style( 'ss-googlefont-h6', $font['link'] );
					wp_enqueue_style( 'ss-googlefont-h6' );
				}
			} elseif ( isset( $font_heading['google'] ) && $font_heading['google'] === 'true' ) {
				$font = self::getGoogleScript( $font_heading );
				wp_register_style( 'ss-googlefont-heading', $font['link'] );
				wp_enqueue_style( 'ss-googlefont-heading' );
			}

			if ( $ss_settings['font_jumbotron_heading_custom'] == 1 ) {
				if ($font_jumbotron_headers['google'] === 'true' ) {
					$font = self::getGoogleScript( $font_jumbotron_headers );
					wp_register_style( 'ss-googlefont-jumbotron-headings', $font['link'] );
					wp_enqueue_style( 'ss-googlefont-jumbotron-headings' );
				}
			}
		}
	}
}

$typography = new Shoestrap_Typography();