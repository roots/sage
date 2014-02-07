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



function shoestrap_admin_bar(){
if ( shoestrap_getVariable( 'advanced_wordpress_disable_admin_bar_toggle' ) == 0 )
	return false;
else
	return true;
}
add_filter( 'show_admin_bar' , 'shoestrap_admin_bar' );

// PJAX
if ( shoestrap_getVariable( 'pjax' ) == 1 ) {
	add_action( 'shoestrap_pre_wrap', 'shoestrap_pjax_open_container' );
	add_action( 'shoestrap_pre_footer', 'shoestrap_pjax_close_container' );
	add_action( 'wp_footer', 'shoestrap_pjax_trigger_script', 200 );
}


if ( !function_exists( 'shoestrap_pjax_open_container' ) ) :
function shoestrap_pjax_open_container() { ?>
	<div id="pjax-container">
	<?php
}
endif;
	

if ( !function_exists( 'shoestrap_pjax_close_container' ) ) :
function shoestrap_pjax_close_container() { ?>
	</div>
	<?php
}
endif;


if ( !function_exists( 'shoestrap_pjax_trigger_script' ) ) :
function shoestrap_pjax_trigger_script() { ?>
	<script>
	$(document).on('pjax:send', function() { $('.main').fadeToggle("fast", "linear") })
	$(document).pjax('nav a, aside a, .breadcrumb a', '#pjax-container')
	</script>
	<?php
}
endif;


// UA-XXXXX-Y (Note: Universal Analytics only, not Classic Analytics)
define( 'GOOGLE_ANALYTICS_ID', shoestrap_getVariable( 'analytics_id' ) );

function shoestrap_google_analytics() { ?>
<script>
	(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
	function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
	e=o.createElement(i);r=o.getElementsByTagName(i)[0];
	e.src='//www.google-analytics.com/analytics.js';
	r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
	ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>');ga('send','pageview');
</script>

<?php }
if ( GOOGLE_ANALYTICS_ID && !current_user_can('manage_options' ) ) {
	add_action( 'wp_footer', 'shoestrap_google_analytics', 20 );
}

/**
 * Post Excerpt Length
 */
define( 'POST_EXCERPT_LENGTH', shoestrap_getVariable( 'post_excerpt_length' ) ); // Length in words for excerpt_length filter (http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length)

if ( shoestrap_getVariable( 'root_relative_urls' ) == 1  )
	add_theme_support( 'root-relative-urls' );    // Enable relative URLs


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
