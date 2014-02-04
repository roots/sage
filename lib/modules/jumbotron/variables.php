<?php

function shoestrap_variables_jumbotron() {
	$font_jumbotron         = shoestrap_process_font( shoestrap_getVariable( 'font_jumbotron', true ) );
	$jumbotron_bg     = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'jumbotron_bg', true ) ) );
	$jumbotron_text_color   = '#' . str_replace( '#', '', $font_jumbotron['color'] );

	if ( shoestrap_getVariable( 'font_jumbotron_heading_custom', true ) == 1 ) {
		$font_jumbotron_headers = shoestrap_process_font( shoestrap_getVariable( 'font_jumbotron_headers', true ) );

		$font_jumbotron_headers_face   = $font_jumbotron_headers['font-family'];
		$font_jumbotron_headers_weight = $font_jumbotron_headers['font-weight'];
		$font_jumbotron_headers_style  = $font_jumbotron_headers['font-style'];
		$jumbotron_headers_text_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_jumbotron_headers['color'] ) );
	} else {
		$font_jumbotron_headers_face   = $font_jumbotron['font-family'];
		$font_jumbotron_headers_weight = $font_jumbotron['font-weight'];
		$font_jumbotron_headers_style  = $font_jumbotron['font-style'];
		$jumbotron_headers_text_color  = $jumbotron_text_color;
	}

	$variables = '';

	$variables .= '@jumbotron-color:         ' . $jumbotron_text_color . ';';
	$variables .= '@jumbotron-bg:            ' . $jumbotron_bg . ';';
	$variables .= '@jumbotron-heading-color: ' . $jumbotron_headers_text_color . ';';
	$variables .= '@jumbotron-font-size:     ' . $font_jumbotron['font-size'] . 'px;';

	// Shoestrap-specific variables
	// --------------------------------------------------

	$variables .= '@jumbotron-font-weight:       ' . $font_jumbotron['font-weight'] . ';';
	$variables .= '@jumbotron-font-style:        ' . $font_jumbotron['font-style'] . ';';
	$variables .= '@jumbotron-font-family:       ' . $font_jumbotron['font-family'] . ';';

	$variables .= '@jumbotron-headers-font-weight:       ' . $font_jumbotron_headers_weight . ';';
	$variables .= '@jumbotron-headers-font-style:        ' . $font_jumbotron_headers_style . ';';
	$variables .= '@jumbotron-headers-font-family:       ' . $font_jumbotron_headers_face . ';';

	return $variables;
}


function shoestrap_variables_jumbotron_filter( $variables ) {
	return $variables . shoestrap_variables_jumbotron();
}
add_filter( 'shoestrap_compiler', 'shoestrap_variables_jumbotron_filter' );