<?php

function shoestrap_variables_advanced() {
	$padding_base  = intval( shoestrap_getVariable( 'padding_base', true ) );
	$border_radius = filter_var( shoestrap_getVariable( 'general_border_radius', true ), FILTER_SANITIZE_NUMBER_INT );
	$border_radius = ( strlen( $border_radius ) < 1 ) ? 0 : $border_radius;

	$variables = '';

	$variables .= '@padding-base-vertical:    ' . round( $padding_base * 6 / 6 ) . 'px;';
	$variables .= '@padding-base-horizontal:  ' . round( $padding_base * 12 / 6 ) . 'px;';

	$variables .= '@padding-large-vertical:   ' . round( $padding_base * 10 / 6 ) . 'px;';
	$variables .= '@padding-large-horizontal: ' . round( $padding_base * 16 / 6 ) . 'px;';

	$variables .= '@padding-small-vertical:   ' . round( $padding_base * 5 / 6 ) . 'px;';
	$variables .= '@padding-small-horizontal: @padding-large-vertical;';

	$variables .= '@padding-xs-vertical:      ' . round( $padding_base * 1 / 6 ) . 'px;';
	$variables .= '@padding-xs-horizontal:    @padding-small-vertical;';

	$variables .= '@border-radius-base:  ' . round( $border_radius * 4 / 4 ) . 'px;';
	$variables .= '@border-radius-large: ' . round( $border_radius * 6 / 4 ) . 'px;';
	$variables .= '@border-radius-small: ' . round( $border_radius * 3 / 4 ) . 'px;';

	$variables .= '@pager-border-radius: ' . round( $border_radius * 15 / 4 ) . 'px;';

	$variables .= '@tooltip-arrow-width: @padding-small-vertical;';
	$variables .= '@popover-arrow-width: (@tooltip-arrow-width * 2);';

	$variables .= '@thumbnail-padding:         ' . round( $padding_base * 4 / 6 ) . 'px;';
	$variables .= '@thumbnail-caption-padding: ' . round( $padding_base * 9 / 6 ) . 'px;';

	$variables .= '@badge-border-radius: ' . round( $border_radius * 10 / 4 ) . 'px;';

	$variables .= '@breadcrumb-padding-vertical:   ' . round( $padding_base * 8 / 6 ) . 'px;';
	$variables .= '@breadcrumb-padding-horizontal: ' . round( $padding_base * 15 / 6 ) . 'px;';

	return $variables;
}


function shoestrap_variables_advanced_filter( $variables ) {
	return $variables . shoestrap_variables_advanced();
}
add_filter( 'shoestrap_compiler', 'shoestrap_variables_advanced_filter' );