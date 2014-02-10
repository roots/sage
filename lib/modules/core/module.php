<?php

define( 'themeURI', get_template_directory_uri() );
define( 'themeFOLDER', get_template() );
define( 'themePATH', get_theme_root() );
define( 'themeNAME', wp_get_theme() );

include_once( dirname( __FILE__ ) . '/includes/functions.color.php' );
include_once( dirname( __FILE__ ) . '/includes/functions.scripts.php' );


if ( !function_exists( 'shoestrap_getVariable' ) ) :
/*
 * Gets the current values from REDUX, and if not there, grabs the defaults
 */
function shoestrap_getVariable( $name, $key = false ) {
	global $redux;
	$options = $redux;

	// Set this to your preferred default value
	$var = '';

	if ( empty( $name ) && !empty( $options ) ) {
		$var = $options;
	} else {
		if ( !empty( $options[$name] ) ) {
			$var = ( !empty( $key ) && !empty( $options[$name][$key] ) && $key !== true ) ? $options[$name][$key] : $var = $options[$name];;
		}
	}
	return $var;
}
endif;


if ( !function_exists( 'shoestrap_password_form' ) ) :
/*
 * Replace the password forms with a bootstrap-formatted version.
 */
function shoestrap_password_form() {
	global $post;
	$label    = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$content  = '<form action="';
	$content .= esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) );
	$content .= '" method="post">';
	$content .= __( 'This post is password protected. To view it please enter your password below:', 'shoestrap' );
	$content .= '<div class="input-group">';
	$content .= '<input name="post_password" id="' . $label . '" type="password" size="20" />';
	$content .= '<span class="input-group-btn">';
	$content .= '<input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" class="btn btn-default" />';
	$content .= '</span></div></form>';

	return $content;
}
endif;
add_filter( 'the_password_form', 'shoestrap_password_form' );


if ( !function_exists( 'shoestrap_replace_reply_link_class' ) ) :
/*
 * Apply the proper classes to comment reply links
 */
function shoestrap_replace_reply_link_class( $class ) {
	$class = str_replace( "class='comment-reply-link", "class='comment-reply-link btn btn-primary btn-small", $class );
	return $class;
}
endif;
add_filter('comment_reply_link', 'shoestrap_replace_reply_link_class');


if ( !function_exists( 'shoestrap_init_filesystem' ) ) :
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


if ( !function_exists( 'shoestrap_array_delete' ) ) :
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

	if ( !is_singular() )
		return;

	if ( !$id = $wp_the_query->get_queried_object_id() )
		return;

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

/**
 * Remove unnecessary self-closing tags
 */
function shoestrap_remove_self_closing_tags( $input ) {
	return str_replace( ' />', '>', $input );
}
add_filter( 'get_avatar',          'shoestrap_remove_self_closing_tags' ); // <img />
add_filter( 'comment_id_fields',   'shoestrap_remove_self_closing_tags' ); // <input />
add_filter( 'post_thumbnail_html', 'shoestrap_remove_self_closing_tags' ); // <img />

/**
 * Don't return the default description in the RSS feed if it hasn't been changed
 */
function shoestrap_remove_default_description( $bloginfo ) {
	$default_tagline = 'Just another WordPress site';
	return ( $bloginfo === $default_tagline ) ? '' : $bloginfo;
}
add_filter( 'get_bloginfo_rss', 'shoestrap_remove_default_description' );


