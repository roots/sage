<?php

function shoestrap_variables_branding() {
	$brand_primary = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_primary', true ) ) );
	$brand_success = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_success', true ) ) );
	$brand_warning = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_warning', true ) ) );
	$brand_danger  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_danger', true ) ) );
	$brand_info    = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_info', true ) ) );

	$link_hover_color = ( shoestrap_get_brightness( $brand_primary ) > 50 ) ? 'darken(@link-color, 15%)' : 'lighten(@link-color, 15%)';

	$brand_primary_brightness = shoestrap_get_brightness( $brand_primary );
	$brand_success_brightness = shoestrap_get_brightness( $brand_success );
	$brand_warning_brightness = shoestrap_get_brightness( $brand_warning );
	$brand_danger_brightness  = shoestrap_get_brightness( $brand_danger );
	$brand_info_brightness    = shoestrap_get_brightness( $brand_info );

	// Button text colors
	$btn_primary_color  = $brand_primary_brightness < 195 ? '#fff' : '333';
	$btn_success_color  = $brand_success_brightness < 195 ? '#fff' : '333';
	$btn_warning_color  = $brand_warning_brightness < 195 ? '#fff' : '333';
	$btn_danger_color   = $brand_danger_brightness  < 195 ? '#fff' : '333';
	$btn_info_color     = $brand_info_brightness    < 195 ? '#fff' : '333';

	// Button borders
	$btn_primary_border = $brand_primary_brightness < 195 ? 'darken(@btn-primary-bg, 5%)' : 'lighten(@btn-primary-bg, 5%)';
	$btn_success_border = $brand_success_brightness < 195 ? 'darken(@btn-success-bg, 5%)' : 'lighten(@btn-success-bg, 5%)';
	$btn_warning_border = $brand_warning_brightness < 195 ? 'darken(@btn-warning-bg, 5%)' : 'lighten(@btn-warning-bg, 5%)';
	$btn_danger_border  = $brand_danger_brightness  < 195 ? 'darken(@btn-danger-bg, 5%)'  : 'lighten(@btn-danger-bg, 5%)';
	$btn_info_border    = $brand_info_brightness    < 195 ? 'darken(@btn-info-bg, 5%)'    : 'lighten(@btn-info-bg, 5%)';

	$input_border_focus = ( shoestrap_get_brightness( $brand_primary ) < 195 ) ? 'lighten(@brand-primary, 10%);' : 'darken(@brand-primary, 10%);';
	$navbar_border      = ( shoestrap_get_brightness( $brand_primary ) < 50 ) ? 'lighten(@navbar-default-bg, 6.5%)' : 'darken(@navbar-default-bg, 6.5%)';


	$variables = '';

	// Branding colors
	$variables .= '@brand-primary: ' . $brand_primary . ';';
	$variables .= '@brand-success: ' . $brand_success . ';';
	$variables .= '@brand-info:    ' . $brand_info . ';';
	$variables .= '@brand-warning: ' . $brand_warning . ';';
	$variables .= '@brand-danger:  ' . $brand_danger . ';';

	// Link-hover
	$variables .= '@link-hover-color: ' . $link_hover_color . ';';

	$variables .= '@btn-default-color:  @gray-dark;';
	$variables .= '@btn-primary-color:  ' . $btn_primary_color . ';';
	$variables .= '@btn-primary-border: ' . $btn_primary_border . ';';
	$variables .= '@btn-success-color:  ' . $btn_success_color . ';';
	$variables .= '@btn-success-border: ' . $btn_success_border . ';';
	$variables .= '@btn-info-color:     ' . $btn_info_color . ';';
	$variables .= '@btn-info-border:    ' . $btn_info_border . ';';
	$variables .= '@btn-warning-color:  ' . $btn_warning_color . ';';
	$variables .= '@btn-warning-border: ' . $btn_warning_border . ';';
	$variables .= '@btn-danger-color:   ' . $btn_danger_color . ';';
	$variables .= '@btn-danger-border:  ' . $btn_danger_border . ';';

	$variables .= '@input-border-focus: ' . $input_border_focus . ';';

	$variables .= '@state-success-text: mix(@gray-darker, @brand-success, 20%);';
	$variables .= '@state-success-bg:   mix(@body-bg, @brand-success, 50%);';

	$variables .= '@state-info-text:    mix(@gray-darker, @brand-info, 20%);';
	$variables .= '@state-info-bg:      mix(@body-bg, @brand-info, 50%);';

	$variables .= '@state-warning-text: mix(@gray-darker, @brand-warning, 20%);';
	$variables .= '@state-warning-bg:   mix(@body-bg, @brand-warning, 50%);';

	$variables .= '@state-danger-text:  mix(@gray-darker, @brand-danger, 20%);';
	$variables .= '@state-danger-bg:    mix(@body-bg, @brand-danger, 50%);';

	return $variables;
}


function shoestrap_variables_branding_filter( $variables ) {
	return $variables . shoestrap_variables_branding();
}
add_filter( 'shoestrap_compiler', 'shoestrap_variables_branding_filter' );