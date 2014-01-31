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
	$variables = '';

	$variables .= '@gray-darker:            ' . $gray_darker . ';';
	$variables .= '@gray-dark:              ' . $gray_dark . ';';
	$variables .= '@gray:                   ' . $gray . ';';
	$variables .= '@gray-light:             ' . $gray_light . ';';
	$variables .= '@gray-lighter:           ' . $gray_lighter . ';';

	$variables .= '@body-bg:                                 ' . $body_bg . ';';
	$variables .= '@component-active-color:                      @body-bg;';
	$variables .= '@btn-default-bg:                              @body-bg;';
	$variables .= '@dropdown-bg:                                 @body-bg;';
	$variables .= '@pagination-bg:                               @body-bg;';
	$variables .= '@progress-bar-color:                          @body-bg;';
	$variables .= '@list-group-bg:                               @body-bg;';
	$variables .= '@panel-bg:                                    @body-bg;';
	$variables .= '@panel-primary-text:                          @body-bg;';
	$variables .= '@pagination-active-color:                     @body-bg;';
	$variables .= '@pagination-disabled-bg:                      @body-bg;';
	$variables .= '@tooltip-color:                               @body-bg;';
	$variables .= '@popover-bg:                                  @body-bg;';
	$variables .= '@popover-arrow-color:                         @body-bg;';
	$variables .= '@label-color:                                 @body-bg;';
	$variables .= '@label-link-hover-color:                      @body-bg;';
	$variables .= '@modal-content-bg:                            @body-bg;';
	$variables .= '@badge-color:                                 @body-bg;';
	$variables .= '@badge-link-hover-color:                      @body-bg;';
	$variables .= '@badge-active-bg:                             @body-bg;';
	$variables .= '@carousel-control-color:                      @body-bg;';
	$variables .= '@carousel-indicator-active-bg:                @body-bg;';
	$variables .= '@carousel-indicator-border-color:             @body-bg;';
	$variables .= '@carousel-caption-color:                      @body-bg;';
	$variables .= '@close-text-shadow:                   0 1px 0 @body-bg;';
	$variables .= '@input-bg:                                    @body-bg;';
	$variables .= '@nav-open-link-hover-color:                   @body-bg;';

	return $variables;
}