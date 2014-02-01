<?php

function shoestrap_variables_background() {
	$bg      = shoestrap_getVariable( 'color_body_bg', true );
	$body_bg = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $bg ) );

	// Calculate the gray shadows based on the body background.
	// We basically create 2 "presets": light and dark.
	if ( shoestrap_get_brightness( $body_bg ) > 80 ) {
		$gray_darker  = 'lighten(#000, 13.5%)';
		$gray_dark    = 'lighten(#000, 20%)';
		$gray         = 'lighten(#000, 33.5%)';
		$gray_light   = 'lighten(#000, 60%)';
		$gray_lighter = 'lighten(#000, 93.5%)';
	} else {
		$gray_darker  = 'darken(#fff, 13.5%)';
		$gray_dark    = 'darken(#fff, 20%)';
		$gray         = 'darken(#fff, 33.5%)';
		$gray_light   = 'darken(#fff, 60%)';
		$gray_lighter = 'darken(#fff, 93.5%)';
	}

	$bg_brightness = shoestrap_get_brightness( $body_bg );

	$table_bg_accent      = $bg_brightness > 50 ? 'darken(@body-bg, 2.5%)'    : 'lighten(@body-bg, 2.5%)';
	$table_bg_hover       = $bg_brightness > 50 ? 'darken(@body-bg, 4%)'      : 'lighten(@body-bg, 4%)';
	$table_border_color   = $bg_brightness > 50 ? 'darken(@body-bg, 13.35%)'  : 'lighten(@body-bg, 13.35%)';
	$input_border         = $bg_brightness > 50 ? 'darken(@body-bg, 20%)'     : 'lighten(@body-bg, 20%)';
	$dropdown_divider_top = $bg_brightness > 50 ? 'darken(@body-bg, 10.2%)'   : 'lighten(@body-bg, 10.2%)';

	$variables = '';

	// Calculate grays
	$variables .= '@gray-darker:            ' . $gray_darker . ';';
	$variables .= '@gray-dark:              ' . $gray_dark . ';';
	$variables .= '@gray:                   ' . $gray . ';';
	$variables .= '@gray-light:             ' . $gray_light . ';';
	$variables .= '@gray-lighter:           ' . $gray_lighter . ';';

	// The below are declared as #fff in the default variables.
	$variables .= '@body-bg:                     ' . $body_bg . ';';
	$variables .= '@component-active-color:          @body-bg;';
	$variables .= '@btn-default-bg:                  @body-bg;';
	$variables .= '@dropdown-bg:                     @body-bg;';
	$variables .= '@pagination-bg:                   @body-bg;';
	$variables .= '@progress-bar-color:              @body-bg;';
	$variables .= '@list-group-bg:                   @body-bg;';
	$variables .= '@panel-bg:                        @body-bg;';
	$variables .= '@panel-primary-text:              @body-bg;';
	$variables .= '@pagination-active-color:         @body-bg;';
	$variables .= '@pagination-disabled-bg:          @body-bg;';
	$variables .= '@tooltip-color:                   @body-bg;';
	$variables .= '@popover-bg:                      @body-bg;';
	$variables .= '@popover-arrow-color:             @body-bg;';
	$variables .= '@label-color:                     @body-bg;';
	$variables .= '@label-link-hover-color:          @body-bg;';
	$variables .= '@modal-content-bg:                @body-bg;';
	$variables .= '@badge-color:                     @body-bg;';
	$variables .= '@badge-link-hover-color:          @body-bg;';
	$variables .= '@badge-active-bg:                 @body-bg;';
	$variables .= '@carousel-control-color:          @body-bg;';
	$variables .= '@carousel-indicator-active-bg:    @body-bg;';
	$variables .= '@carousel-indicator-border-color: @body-bg;';
	$variables .= '@carousel-caption-color:          @body-bg;';
	$variables .= '@close-text-shadow:       0 1px 0 @body-bg;';
	$variables .= '@input-bg:                        @body-bg;';
	$variables .= '@nav-open-link-hover-color:       @body-bg;';

	// These are #ccc
	// We re-calculate the color based on the gray values above.
	$variables .= '@btn-default-border:            mix(@gray-light, @gray-lighter);';
	$variables .= '@input-border:                  mix(@gray-light, @gray-lighter);';
	$variables .= '@popover-fallback-border-color: mix(@gray-light, @gray-lighter);';
	$variables .= '@breadcrumb-color:              mix(@gray-light, @gray-lighter);';
	$variables .= '@dropdown-fallback-border:      mix(@gray-light, @gray-lighter);';

	$variables .= '@table-bg-accent:    ' . $table_bg_accent . ';';
	$variables .= '@table-bg-hover:     ' . $table_bg_hover . ';';
	$variables .= '@table-border-color: ' . $table_border_color . ';';

	$variables .= '@legend-border-color: @gray-lighter;';
	$variables .= '@dropdown-divider-bg: @gray-lighter;';

	$variables .= '@dropdown-link-hover-bg: @table-bg-hover;';
	$variables .= '@dropdown-caret-color:   @gray-darker;';

	$variables .= '@nav-tabs-border-color:                   @table-border-color;';
	$variables .= '@nav-tabs-active-link-hover-border-color: @table-border-color;';
	$variables .= '@nav-tabs-justified-link-border-color:    @table-border-color;';

	$variables .= '@pagination-border:          @table-border-color;';
	$variables .= '@pagination-hover-border:    @table-border-color;';
	$variables .= '@pagination-disabled-border: @table-border-color;';

	$variables .= '@tooltip-bg: darken(@gray-darker, 15%);';

	$variables .= '@popover-arrow-outer-fallback-color: @gray-light;';

	$variables .= '@modal-content-fallback-border-color: @gray-light;';
	$variables .= '@modal-backdrop-bg:                   darken(@gray-darker, 15%);';
	$variables .= '@modal-header-border-color:           lighten(@gray-lighter, 12%);';

	$variables .= '@progress-bg: ' . $table_bg_hover . ';';

	$variables .= '@list-group-border:   ' . $table_border_color . ';';
	$variables .= '@list-group-hover-bg: ' . $table_bg_hover . ';';

	$variables .= '@list-group-link-color:         @gray;';
	$variables .= '@list-group-link-heading-color: @gray-dark;';

	$variables .= '@panel-inner-border:       @list-group-border;';
	$variables .= '@panel-footer-bg:          @list-group-hover-bg;';
	$variables .= '@panel-default-border:     @table-border-color;';
	$variables .= '@panel-default-heading-bg: @panel-footer-bg;';

	$variables .= '@thumbnail-border: @list-group-border;';

	$variables .= '@well-bg: @table-bg-hover;';

	$variables .= '@breadcrumb-bg: @table-bg-hover;';

	$variables .= '@close-color: darken(@gray-darker, 15%);';

	return $variables;
}


function shoestrap_variables_background_filter( $variables ) {
	return $variables . shoestrap_variables_background();
}
add_filter( 'shoestrap_compiler', 'shoestrap_variables_background_filter' );