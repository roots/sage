<?php

/**
 * Enqueue scripts and stylesheets
 */
function shoestrap_scripts_jquery() {

	// jQuery is loaded using the same method from HTML5 Boilerplate:
	// Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
	// It's kept in the header instead of footer to avoid conflicts with plugins.
	if ( !is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), null, false );
		add_filter( 'script_loader_src', 'shoestrap_jquery_local_fallback', 10, 2 );
	}
}
add_action( 'wp_enqueue_scripts', 'shoestrap_scripts_jquery', 100 );

// http://wordpress.stackexchange.com/a/12450
function shoestrap_jquery_local_fallback( $src, $handle = null ) {
	static $add_jquery_fallback = false;

	if ( $add_jquery_fallback ) {
		echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery-1.11.0.min.js"><\/script>\')</script>' . "\n";
		$add_jquery_fallback = false;
	}

	if ( $handle === 'jquery' )
		$add_jquery_fallback = true;

	return $src;
}
add_action( 'wp_head', 'shoestrap_jquery_local_fallback' );
