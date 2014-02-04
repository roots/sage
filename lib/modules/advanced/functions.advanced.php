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
	add_action( 'shoestrap_after_wrap', 'shoestrap_pjax_close_container' );
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