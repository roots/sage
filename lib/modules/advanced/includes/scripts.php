<?php
/**
 * Enqueue scripts and stylesheets
 */
function shoestrap_advanced_scripts() {

	if ( shoestrap_getVariable( 'pjax' ) == 1 ) {
		wp_register_script( 'jquery_pjax', SHOESTRAP_ASSETS_URL . '/js/jquery.pjax.js', false, null, true );
		wp_enqueue_script( 'jquery_pjax' );
	}

	if ( shoestrap_getVariable( 'retina_toggle' ) == 1 ) {
		wp_register_script( 'retinajs', SHOESTRAP_ASSETS_URL . '/js/vendor/retina.js', false, null, true );
		wp_enqueue_script( 'retinajs' );
	}
}
add_action( 'wp_enqueue_scripts', 'shoestrap_scripts', 100 );