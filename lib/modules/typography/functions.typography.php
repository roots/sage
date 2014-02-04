<?php

if ( !function_exists( 'getGoogleScript' ) ) :
/*
 * Helper function
 */
function getGoogleScript( $font ) {
	$data['link'] = 'http://fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $font['font-family'] );
	$data['key'] = str_replace( ' ', '_', $font['font-family'] );

	if ( !empty( $font['font-weight'] ) ) {
		$data['link'] .= ':' . str_replace( '-', '', $font['font-weight'] );
	}

	if ( !empty( $font['font-style'] ) ) {
		$data['key'] .= '-' . str_replace( '_', '', $font['font-style'] );
	}

	if ( !empty( $font['subsets'] ) ) {
		$data['link'] .= '&subset=' . $font['subsets'];
		$data['key'] .= '-' . str_replace( '_', '', $font['subsets'] );
	}

	return $data;
}
endif;


if ( !function_exists( 'shoestrap_module_typography_googlefont_links' ) ) :
/*
 * The Google Webonts script
 */
function shoestrap_module_typography_googlefont_links() {
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
		$font = getGoogleScript( $font_base );
		wp_register_style( $font['key'], $font['link'] );
		wp_enqueue_style( $font['key'] );
	}

	if ( $font_navbar['google'] === 'true' ) {
		$font = getGoogleScript( $font_navbar );
		wp_register_style( $font['key'], $font['link'] );
		wp_enqueue_style( $font['key'] );
	}

	if ( $font_brand['google'] === 'true' ) {
		$font = getGoogleScript( $font_brand );
		wp_register_style( $font['key'], $font['link'] );
		wp_enqueue_style( $font['key'] );
	}

	if ( $font_jumbotron['google'] === 'true' ) {
		$font = getGoogleScript( $font_jumbotron );
		wp_register_style( $font['key'], $font['link'] );
		wp_enqueue_style( $font['key'] );
	}

	if ( shoestrap_getVariable( 'font_heading_custom' ) ) {

		if ( $font_h1['google'] === 'true' ) {
			$font = getGoogleScript( $font_h1 );
			wp_register_style( $font['key'], $font['link'] );
			wp_enqueue_style( $font['key'] );
		}

		if ( $font_h2['google'] === 'true' ) {
			$font = getGoogleScript( $font_h2 );
			wp_register_style( $font['key'], $font['link'] );
			wp_enqueue_style( $font['key'] );
		}

		if ( $font_h3['google'] === 'true' ) {
			$font = getGoogleScript( $font_h3 );
			wp_register_style( $font['key'], $font['link'] );
			wp_enqueue_style( $font['key'] );
		}

		if ( $font_h4['google'] === 'true' ) {
			$font = getGoogleScript( $font_h4 );
			wp_register_style( $font['key'], $font['link'] );
			wp_enqueue_style( $font['key'] );
		}

		if ( $font_h5['google'] === 'true' ) {
			$font = getGoogleScript( $font_h5 );
			wp_register_style( $font['key'], $font['link'] );
			wp_enqueue_style( $font['key'] );
		}

		if ( $font_h6['google'] === 'true' ) {
			$font = getGoogleScript( $font_h6 );
			wp_register_style( $font['key'], $font['link'] );
			wp_enqueue_style( $font['key'] );
		}
	} elseif ( isset( $font_heading['google'] ) && $font_heading['google'] === 'true' ) {
		$font = getGoogleScript( $font_heading );
		wp_register_style( $font['key'], $font['link'] );
		wp_enqueue_style( $font['key'] );
	}

	if ( shoestrap_getVariable( 'font_jumbotron_heading_custom' ) == 1 ) {
		if ($font_jumbotron_headers['google'] === 'true' ) {
			$font = getGoogleScript( $font_jumbotron_headers );
			wp_register_style( $font['key'], $font['link'] );
			wp_enqueue_style( $font['key'] );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'shoestrap_module_typography_googlefont_links' );
endif;