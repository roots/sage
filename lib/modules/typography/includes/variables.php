<?php

function shoestrap_variables_typography() {
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

function shoestrap_variables_typography_filter( $variables ) {
	return $variables . shoestrap_variables_typography();
}
add_filter( 'shoestrap_compiler', 'shoestrap_variables_typography_filter' );