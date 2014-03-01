<?php

define( 'themeURI', get_template_directory_uri() );
define( 'themeFOLDER', get_template() );
define( 'themePATH', get_theme_root() );
define( 'themeNAME', wp_get_theme() );

if ( ! function_exists( 'shoestrap_getVariable' ) ) :
/*
 * Gets the current values from REDUX, and if not there, grabs the defaults
 */
function shoestrap_getVariable( $name, $key = false ) {
	global $redux;
	$options = $redux;

	// Set this to your preferred default value
	$var = '';

	if ( empty( $name ) && ! empty( $options ) ) {
		$var = $options;
	} else {
		if ( ! empty( $options[$name] ) ) {
			$var = ( ! empty( $key ) && ! empty( $options[$name][$key] ) && $key !== true ) ? $options[$name][$key] : $var = $options[$name];;
		}
	}
	return $var;
}
endif;


if ( ! function_exists( 'shoestrap_password_form' ) ) :
/*
 * Replace the password forms with a bootstrap-formatted version.
 */
function shoestrap_password_form() {
	global $post, $ss_framework;
	$label    = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$content  = '<form action="';
	$content .= esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) );
	$content .= '" method="post">';
	$content .= __( 'This post is password protected. To view it please enter your password below:', 'shoestrap' );
	$content .= '<div class="input-group">';
	$content .= '<input name="post_password" id="' . $label . '" type="password" size="20" />';
	$content .= '<span class="input-group-btn">';
	$content .= '<input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" class="' . $ss_framework->button_classes() . '" />';
	$content .= '</span></div></form>';

	return $content;
}
endif;
add_filter( 'the_password_form', 'shoestrap_password_form' );


if ( ! function_exists( 'shoestrap_replace_reply_link_class' ) ) :
/*
 * Apply the proper classes to comment reply links
 */
function shoestrap_replace_reply_link_class( $class ) {
	global $ss_framework;
	$class = str_replace( "class='comment-reply-link", "class='comment-reply-link " . $ss_framework->button_classes( 'success', 'small' ), $class );
	return $class;
}
endif;
add_filter('comment_reply_link', 'shoestrap_replace_reply_link_class');


if ( ! function_exists( 'shoestrap_init_filesystem' ) ) :
/*
 * Initialize the Wordpress filesystem, no more using file_put_contents function
 */
function shoestrap_init_filesystem() {
	if ( empty( $wp_filesystem ) ) {
		require_once(ABSPATH .'/wp-admin/includes/file.php');
		WP_Filesystem();
	}
}
endif;
add_filter('init', 'shoestrap_init_filesystem');


if ( ! function_exists( 'shoestrap_array_delete' ) ) :
/*
 * Unset a row from an array.
 */
function shoestrap_array_delete( $idx, $array ) {  
	unset( $array[$idx] );
	return ( is_array( $array ) ) ? array_values( $array ) : null;
}
endif;

/*
 * Canonical URLs
 */
function shoestrap_rel_canonical() {
	global $wp_the_query;

	if ( ! is_singular() ) {
		return;
	}

	if ( ! $id = $wp_the_query->get_queried_object_id() ) {
		return;
	}

	$link = get_permalink( $id );
	echo "\t<link rel=\"canonical\" href=\"$link\">\n";
}
add_action( 'init', 'shoestrap_head_cleanup' );

/**
 * Remove the WordPress version from RSS feeds
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Remove unnecessary dashboard widgets
 *
 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
 */
function shoestrap_remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
}
add_action( 'admin_init', 'shoestrap_remove_dashboard_widgets' );


function shoestrap_process_font( $font ) {

	if ( empty( $font['font-weight'] ) ) {
		$font['font-weight'] = "inherit";
	}

	if ( empty( $font['font-style'] ) ) {
		$font['font-style'] = "inherit";
	}

	if ( isset( $font['font-size'] ) ) {
		$font['font-size'] = filter_var( $font['font-size'], FILTER_SANITIZE_NUMBER_INT );
	}

	return $font;
}