<?php

if ( !function_exists( 'shoestrap_user_css' ) ) :
/*
 * echo any custom CSS the user has written to the <head> of the page
 */
function shoestrap_user_css() {
	$header_scripts = shoestrap_getVariable( 'user_css' );
	
	if ( trim( $header_scripts ) != '' )
		wp_add_inline_style( 'shoestrap_css', $header_scripts );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_user_css', 101 );


if ( !function_exists( 'shoestrap_user_js' ) ) :
/*
 * echo any custom JS the user has written to the footer of the page
 */
function shoestrap_user_js() {
	$footer_scripts = shoestrap_getVariable( 'user_js' );

	if ( trim( $footer_scripts ) != '' )
		echo '<script id="core.advanced-user-js">' . $footer_scripts . '</script>';
}
endif;
add_action( 'wp_footer', 'shoestrap_user_js', 200 );


if ( !function_exists( 'shoestrap_admin_bar' ) ) :
/**
 * Switch the adminbar On/Off
 */
function shoestrap_admin_bar(){
if ( shoestrap_getVariable( 'advanced_wordpress_disable_admin_bar_toggle' ) == 0 )
	return false;
else
	return true;
}
endif;
add_filter( 'show_admin_bar' , 'shoestrap_admin_bar' );


if ( !function_exists( 'shoestrap_google_analytics' ) ) :
/**
 * The Google Analytics code
 */
function shoestrap_google_analytics() {
	$analytics_id = shoestrap_getVariable( 'analytics_id' );

	if ( !is_null( $analytics_id ) && !empty( $analytics_id ) )
		echo "<script>
	(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
	function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
	e=o.createElement(i);r=o.getElementsByTagName(i)[0];
	e.src='//www.google-analytics.com/analytics.js';
	r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
	ga('create','" . $analytics_id . "');ga('send','pageview');
	</script>";
}
endif;
add_action( 'wp_footer', 'shoestrap_google_analytics', 20 );


/**
 * Post Excerpt Length
 */
define( 'POST_EXCERPT_LENGTH', shoestrap_getVariable( 'post_excerpt_length' ) ); // Length in words for excerpt_length filter (http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length)


/**
 * Redirects search results from /?s=query to /search/query/, converts %20 to +
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 */
function shoestrap_nice_search_redirect() {
	global $wp_rewrite;

	if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
		return;

	$search_base = $wp_rewrite->search_base;
	if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
		wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
		exit();
	}
}
if ( shoestrap_getVariable( 'nice_search' ) == 1 )
	add_action( 'template_redirect', 'shoestrap_nice_search_redirect' );
