<?php

function shoestrap_variables_navbar() {

	$font_brand             = shoestrap_process_font( shoestrap_getVariable( 'font_brand', true ) );

	$font_navbar       = shoestrap_process_font( shoestrap_getVariable( 'font_navbar', true ) );
	$navbar_bg         = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'navbar_bg', true ) ) );
	$navbar_height     = filter_var( shoestrap_getVariable( 'navbar_height', true ), FILTER_SANITIZE_NUMBER_INT );
	$navbar_text_color = '#' . str_replace( '#', '', $font_navbar['color'] );
	$brand_text_color  = '#' . str_replace( '#', '', $font_brand['color'] );
	$navbar_border      = ( shoestrap_get_brightness( $navbar_bg ) < 50 ) ? 'lighten(@navbar-default-bg, 6.5%)' : 'darken(@navbar-default-bg, 6.5%)';

	if ( shoestrap_get_brightness( $navbar_bg ) < 165 ) {
		$navbar_link_hover_color    = 'darken(@navbar-default-color, 26.5%)';
		$navbar_link_active_bg      = 'darken(@navbar-default-bg, 6.5%)';
		$navbar_link_disabled_color = 'darken(@navbar-default-bg, 6.5%)';
		$navbar_brand_hover_color   = 'darken(@navbar-default-brand-color, 10%)';
	} else {
		$navbar_link_hover_color    = 'lighten(@navbar-default-color, 26.5%)';
		$navbar_link_active_bg      = 'lighten(@navbar-default-bg, 6.5%)';
		$navbar_link_disabled_color = 'lighten(@navbar-default-bg, 6.5%)';
		$navbar_brand_hover_color   = 'lighten(@navbar-default-brand-color, 10%)';
	}

	$variables = '';

	$variables .= '@navbar-height:         ' . $navbar_height . 'px;';

	$variables .= '@navbar-default-color:  ' . $navbar_text_color . ';';
	$variables .= '@navbar-default-bg:     ' . $navbar_bg . ';';
	$variables .= '@navbar-default-border: ' . $navbar_border . ';';

	$variables .= '@navbar-default-link-color:          @navbar-default-color;';
	$variables .= '@navbar-default-link-hover-color:    ' . $navbar_link_hover_color . ';';
	$variables .= '@navbar-default-link-active-color:   mix(@navbar-default-color, @navbar-default-link-hover-color, 50%);';
	$variables .= '@navbar-default-link-active-bg:      ' . $navbar_link_active_bg . ';';
	$variables .= '@navbar-default-link-disabled-color: ' . $navbar_link_disabled_color . ';';

	$variables .= '@navbar-default-brand-color:         @navbar-default-link-color;';
	$variables .= '@navbar-default-brand-hover-color:   ' . $navbar_brand_hover_color . ';';

	$variables .= '@navbar-default-toggle-hover-bg:     ' . $navbar_border . ';';
	$variables .= '@navbar-default-toggle-icon-bar-bg:  ' . $navbar_text_color . ';';
	$variables .= '@navbar-default-toggle-border-color: ' . $navbar_border . ';';

	// Shoestrap-specific variables
	// --------------------------------------------------

	$variables .= '@navbar-font-size:        ' . $font_navbar['font-size'] . 'px;';
	$variables .= '@navbar-font-weight:      ' . $font_navbar['font-weight'] . ';';
	$variables .= '@navbar-font-style:       ' . $font_navbar['font-style'] . ';';
	$variables .= '@navbar-font-family:      ' . $font_navbar['font-family'] . ';';
	$variables .= '@navbar-font-color:       ' . $navbar_text_color . ';';

	$variables .= '@brand-font-size:         ' . $font_brand['font-size'] . 'px;';
	$variables .= '@brand-font-weight:       ' . $font_brand['font-weight'] . ';';
	$variables .= '@brand-font-style:        ' . $font_brand['font-style'] . ';';
	$variables .= '@brand-font-family:       ' . $font_brand['font-family'] . ';';
	$variables .= '@brand-font-color:        ' . $brand_text_color . ';';

	$variables .= '@navbar-margin-top:       ' . shoestrap_getVariable( 'navbar_margin_top' ) . 'px;';

	return $variables;
}


function shoestrap_variables_navbar_filter( $variables ) {
	$variables = $variables . shoestrap_variables_navbar();
}
add_filter( 'shoestrap_variables', 'shoestrap_variables_navbar_filter' );