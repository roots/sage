<?php


if ( !class_exists( 'SS_Foundation_Typography' ) ) {
	/**
	 * The "Typography" module
	 */
	class SS_Foundation_Typography {

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
				'icon'    => 'el-icon-font icon-large',
			);

			$fields[] = array(
				'title'     => __( 'Base Font', 'shoestrap' ),
				'desc'      => __( 'The main font for your site.', 'shoestrap' ),
				'id'        => 'base-font',
				'compiler'  => true,
				'units'     => '%',
				'line-height' => false,
				'text-align'  => false,
				'default'   => array(
					'font-family'   => 'Arial, Helvetica, sans-serif',
					'font-size'     => '100',
					'google'        => 'false',
					'weight'        => 'inherit',
					'color'         => '#222222',
					'font-style'    => 400,
					'update_weekly' => true // Enable to force updates of Google Fonts to be weekly
				),
				'type'      => 'typography',
			);

			$fields[] = array(
				'title'       => __( 'Headers Font', 'shoestrap' ),
				'desc'        => __( 'Choose a font for your headers.', 'shoestrap' ),
				'id'          => 'header-font',
				'compiler'    => true,
				'font-style'  => false,
				'font-size'   => false,
				'font-weight' => false,
				'subset'      => false,
				'line-height' => false,
				'text-align'  => false,
				'default'     => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'color'       => '#222222'
				),
				'type'        => 'typography',
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

			if ( !isset( $ss_settings['base-font']['google'] ) || is_null( $ss_settings['base-font']['google'] ) || empty( $ss_settings['base-font']['google'] ) ) {
				$ss_settings['base-font']['google'] = false;
			}

			if ( $ss_settings['base-font']['google'] === 'true' ) {
				$font = self::getGoogleScript( $ss_settings['base-font'] );
				wp_register_style( 'ss-googlefont-base', $font['link'] );
				wp_enqueue_style( 'ss-googlefont-base' );
			}

			if ( $ss_settings['header-font']['google'] === 'true' ) {
				$font = self::getGoogleScript( $ss_settings['header-font'] );
				wp_register_style( 'ss-googlefont-h', $font['link'] );
				wp_enqueue_style( 'ss-googlefont-h' );
			}
		}
	}
}

$typography = new SS_Foundation_Typography();