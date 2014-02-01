<?php

function shoestrap_variables_layout() {
	$screen_sm = filter_var( shoestrap_getVariable( 'screen_tablet', true ), FILTER_SANITIZE_NUMBER_INT );
	$screen_md = filter_var( shoestrap_getVariable( 'screen_desktop', true ), FILTER_SANITIZE_NUMBER_INT );
	$screen_lg = filter_var( shoestrap_getVariable( 'screen_large_desktop', true ), FILTER_SANITIZE_NUMBER_INT );
	$gutter    = filter_var( shoestrap_getVariable( 'layout_gutter', true ), FILTER_SANITIZE_NUMBER_INT );
	$gutter    = ( $gutter < 2 ) ? 2 : $gutter;

	$site_style = shoestrap_getVariable( 'site_style' );

	$screen_xs = ( $site_style == 'static' ) ? '50px' : '480px';
	$screen_sm = ( $site_style == 'static' ) ? '50px' : $screen_sm;
	$screen_md = ( $site_style == 'static' ) ? '50px' : $screen_md;

	$gfb = shoestrap_getVariable( 'grid_float_breakpoint' );
	$grid_float_breakpoint = ( isset( $gfb ) )           ? $gfb             : '@screen-sm-min';
	$grid_float_breakpoint = ( $gfb == 'min' )           ? '10px'           : $grid_float_breakpoint;
	$grid_float_breakpoint = ( $gfb == 'screen_xs_min' ) ? '@screen-xs-min' : $grid_float_breakpoint;
	$grid_float_breakpoint = ( $gfb == 'screen_sm_min' ) ? '@screen-sm-min' : $grid_float_breakpoint;
	$grid_float_breakpoint = ( $gfb == 'screen_md_min' ) ? '@screen-md-min' : $grid_float_breakpoint;
	$grid_float_breakpoint = ( $gfb == 'screen_lg_min' ) ? '@screen-lg-min' : $grid_float_breakpoint;
	$grid_float_breakpoint = ( $gfb == 'max' )           ? '9999px'         : $grid_float_breakpoint;

	$grid_float_breakpoint = ( $gfb == 'screen-lg-min' ) ? '0 !important' : $grid_float_breakpoint;

	$variables = '';

	$variables .= '@screen-sm: ' . $screen_sm . 'px;';
	$variables .= '@screen-md: ' . $screen_md . 'px;';
	$variables .= '@screen-lg: ' . $screen_lg . 'px;';

	$variables .= '@grid-gutter-width: ' . $gutter . 'px;';

	$variables .= '@grid-float-breakpoint: ' . $grid_float_breakpoint . ';';

	$variables .= '@jumbotron-padding: @grid-gutter-width;';

	$variables .= '@modal-inner-padding: ' . round( $gutter * 20 / 30 ) . 'px;';
	$variables .= '@modal-title-padding: ' . round( $gutter * 15 / 30 ) . 'px;';

	$variables .= '@modal-lg: ' . round( $screen_md - ( 3 * $gutter ) ) . 'px;';
	$variables .= '@modal-md: ' . round( $screen_sm - ( 3 * $gutter ) ) . 'px;';
	$variables .= '@modal-sm: ' . round( $screen_xs - ( 3 * $gutter ) ) . 'px;';

	$variables .= '@panel-body-padding: @modal-title-padding;';

	$variables .= '@container-tablet:        ' . ( $screen_sm - ( $gutter / 2 ) ). 'px;';
	$variables .= '@container-desktop:       ' . ( $screen_md - ( $gutter / 2 ) ). 'px;';
	$variables .= '@container-large-desktop: ' . ( $screen_lg - $gutter ). 'px;';

	if ( $site_style == 'static' ) {
		// disable responsiveness
		$variables .= '@screen-xs-max: 0 !important;
		.container { max-width: none !important; width: @container-large-desktop; }
		html { overflow-x: auto !important; }';
	}

	return $variables;
}