<?php
/**
 * Enqueue scripts and stylesheets
 */
function shoestrap_scripts() {
	global $wp_customize;

	$stylesheet_url = apply_filters( 'shoestrap_main_stylesheet_url', SHOESTRAP_ASSETS_URL . '/css/style-default.css' );
	$stylesheet_ver = apply_filters( 'shoestrap_main_stylesheet_ver', null );

	// Only load the stylesheet when not in the customizer.
	if ( !isset( $wp_customize ) )
		wp_enqueue_style( 'shoestrap_css', $stylesheet_url, false, $stylesheet_ver );

	// If we are in the customizer screen, load the less.js script
	if ( isset( $wp_customize ) ) {
		wp_register_script( 'less_js', SHOESTRAP_ASSETS_URL . '/js/vendor/less.min.js', false, '1.6.3' );
		wp_enqueue_script( 'less_js' );
	}

	wp_register_script( 'modernizr',         SHOESTRAP_ASSETS_URL . '/js/vendor/modernizr-2.7.0.min.js', false, null, false );
	wp_register_script( 'shoestrap_plugins', SHOESTRAP_ASSETS_URL . '/js/bootstrap.min.js',              false, null, true  );
	wp_register_script( 'shoestrap_main',    SHOESTRAP_ASSETS_URL . '/js/main.js',                       false, null, true  );
	wp_register_script( 'fitvids',           SHOESTRAP_ASSETS_URL . '/js/vendor/jquery.fitvids.js',      false, null, true  );

	wp_enqueue_script( 'jquery'            );

	wp_enqueue_script( 'modernizr'         );
	wp_enqueue_script( 'shoestrap_plugins' );
	wp_enqueue_script( 'shoestrap_main'    );
	wp_enqueue_script( 'fitvids' );

	if ( is_single() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'shoestrap_scripts', 100 );