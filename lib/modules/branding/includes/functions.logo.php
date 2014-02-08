<?php

if ( !function_exists( 'shoestrap_logo' ) ) :
/*
 * The site logo.
 * If no custom logo is uploaded, use the sitename
 */
function shoestrap_logo() {
	$logo  = shoestrap_getVariable( 'logo' );

	if ( !empty( $logo['url'] ) )
		$branding = '<img id="site-logo" src="' . $logo['url'] . '" alt="' . get_bloginfo( 'name' ) . '">';
	else
		$branding = '<span class="sitename">' . get_bloginfo( 'name' ) . '</span>';

	return $branding;
}
endif;