<?php
/**
 * Enqueue scripts and stylesheets
 */
function shoestrap_scripts() {

	wp_enqueue_style( 'shoestrap_css', shoestrap_css( 'url' ), false, null );

	if ( is_single() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_register_script( 'modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.7.0.min.js', false, null, false );
	wp_register_script( 'shoestrap_plugins', get_template_directory_uri() . '/assets/js/bootstrap.min.js', false, null, true );
	wp_register_script( 'shoestrap_main', get_template_directory_uri() . '/assets/js/main.js', false, null, true );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'shoestrap_plugins' );
	wp_enqueue_script( 'shoestrap_main' );

	if ( shoestrap_getVariable( 'pjax' ) == 1 ) {
		wp_register_script( 'jquery_pjax', get_template_directory_uri() . '/assets/js/jquery.pjax.js', false, null, true );
		wp_enqueue_script( 'jquery_pjax' );
	}

	if ( shoestrap_getVariable( 'retina_toggle' ) == 1 ) {
		wp_register_script( 'retinajs', get_template_directory_uri() . '/assets/js/vendor/retina.js', false, null, true );
		wp_enqueue_script( 'retinajs' );
	}
	wp_register_script( 'fitvids', get_template_directory_uri() . '/assets/js/vendor/jquery.fitvids.js', false, null, true );
	wp_enqueue_script( 'fitvids' );
}
add_action( 'wp_enqueue_scripts', 'shoestrap_scripts', 100 );