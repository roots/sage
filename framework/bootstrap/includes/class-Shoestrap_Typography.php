<?php


if ( ! class_exists( 'Shoestrap_Typography' ) ) {
	/**
	 * The "Typography" module
	 */
	class Shoestrap_Typography {

		function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'googlefont_links' ) );
		}

		/*
		 * Helper function
		 */
		public static function getGoogleScript( $font ) {
			$data['link'] = '//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $font['font-family'] );
			$data['key'] = str_replace( ' ', '_', $font['font-family'] );

			if ( ! empty( $font['font-weight'] ) ) {
				$data['link'] .= ':' . str_replace( '-', '', $font['font-weight'] );
			}

			if ( ! empty( $font['font-style'] ) ) {
				$data['key'] .= '-' . str_replace( '_', '', $font['font-style'] );
			}

			if ( ! empty( $font['subsets'] ) ) {
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
			if ( isset( $ss_settings['font_heading'] ) ) {
				$font_heading         = $ss_settings['font_heading'];
			}

			if ( ! isset( $font_base['google'] ) || is_null( $font_base['google'] ) || empty( $font_base['google'] ) ) {
				$font_base['google'] = false;
			}

			if ( ! isset( $font_navbar['google'] ) || is_null( $font_navbar['google'] ) || empty( $font_navbar['google'] ) ) {
				$font_navbar['google'] = false;
			}

			if ( ! isset( $font_brand['google'] ) || is_null( $font_brand['google'] ) || empty( $font_brand['google'] ) ) {
				$font_brand['google'] = false;
			}

			if ( ! isset( $font_jumbotron['google'] ) || is_null( $font_jumbotron['google'] ) || empty( $font_jumbotron['google'] ) ) {
				$font_jumbotron['google'] = false;
			}

			if ( ! isset( $font_heading['google'] ) || is_null( $font_heading['google'] ) || empty( $font_heading['google'] ) ) {
				$font_heading['google'] = false;
			}

			$font_h1 = $ss_settings['font_h1'];
			$font_h2 = $ss_settings['font_h2'];
			$font_h3 = $ss_settings['font_h3'];
			$font_h4 = $ss_settings['font_h4'];
			$font_h5 = $ss_settings['font_h5'];
			$font_h6 = $ss_settings['font_h6'];

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

			if ( isset( $font_heading['google'] ) && $font_heading['google'] === 'true' ) {
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
