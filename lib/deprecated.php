<?php
/**
 * This file adds compatibility with older functions, actions & filters that were used prior to version 3.1.1
 * Developers should NOT use these functions and actions and should instead migrate to the new ones.
 */

/**
 * Marks a function as deprecated and informs when it has been used.
 *
 * There is a hook shoestrap_deprecated_function_run that will be called that can be used
 * to get the backtrace up to what file and function called the deprecated
 * function.
 *
 * The current behavior is to trigger a user error if WP_DEBUG is true.
 *
 * This function is to be used in every function that is deprecated.
 *
 * @uses do_action() Calls 'shoestrap_deprecated_function_run' and passes the function name, what to use instead,
 *   and the version the function was deprecated in.
 * @uses apply_filters() Calls 'shoestrap_deprecated_function_trigger_error' and expects boolean value of true to do
 *   trigger or false to not trigger error.
 *
 * @param string  $function    The function that was called
 * @param string  $version     The version of WordPress that deprecated the function
 * @param string  $replacement Optional. The function that should have been called
 * @param array   $backtrace   Optional. Contains stack backtrace of deprecated function
 */
function _shoestrap_deprecated_function( $function, $version, $replacement = null, $backtrace = null ) {
	do_action( 'shoestrap_deprecated_function_run', $function, $replacement, $version );

	$show_errors = current_user_can( 'manage_options' );

	// Allow plugin to filter the output error trigger
	if ( WP_DEBUG && apply_filters( 'shoestrap_deprecated_function_trigger_error', $show_errors ) ) {
		if ( ! is_null( $replacement ) ) {
			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Shoestrap version %2$s! Use %3$s instead.', 'shoestrap' ), $function, $version, $replacement ) );
			trigger_error(  print_r( $backtrace ) ); // Limited to previous 1028 characters, but since we only need to move back 1 in stack that should be fine.
			// Alternatively we could dump this to a file.
		}
		else {
			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Shoestrap version %2$s with no alternative available.', 'shoestrap' ), $function, $version ) );
			trigger_error( print_r( $backtrace ) );// Limited to previous 1028 characters, but since we only need to move back 1 in stack that should be fine.
			// Alternatively we could dump this to a file.
		}
	}
}

/**
 * Color functions
 */
function shoestrap_sanitize_hex( $color ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::sanitize_hex()' );
	return Shoestrap_Color::sanitize_hex( $color );
}

function shoestrap_get_rgb( $hex, $implode = false ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::sanitize_hex()' );
	return Shoestrap_Color::get_rgb( $hex, $implode );
}

function shoestrap_get_rgba( $hex, $opacity, $echo ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::sanitize_hex()' );
	return Shoestrap_Color::get_rgba( $hex, $opacity, $echo );
}

function shoestrap_get_brightness( $hex ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::get_brightness()' );
	return Shoestrap_Color::get_brightness( $hex );
}

function shoestrap_adjust_brightness( $hex, $steps ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::adjust_brightness()' );
	return Shoestrap_Color::adjust_brightness( $hex, $steps );
}

function shoestrap_mix_colors( $hex1, $hex2, $percentage ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::mix_colors()' );
	return Shoestrap_Color::mix_colors( $hex1, $hex2, $percentage );
}

function shoestrap_hex_to_hsv( $hex ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::hex_to_hsv()' );
	return Shoestrap_Color::hex_to_hsv( $hex );
}

function shoestrap_rgb_to_hsv( $color = array() ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::rgb_to_hsv()' );
	return Shoestrap_Color::rgb_to_hsv( $rgb );
}

function shoestrap_brightest_color( $colors = array(), $context = 'key' ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::brightest_color()' );
	return Shoestrap_Color::brightest_color( $colors, $context );
}

function shoestrap_most_saturated_color( $colors = array(), $context = 'key' ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::most_saturated_color()' );
	return Shoestrap_Color::most_saturated_color( $colors, $context );
}

function shoestrap_most_intense_color( $colors = array(), $context = 'key' ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::most_intense_color()' );
	return Shoestrap_Color::most_intense_color( $colors, $context );
}

function shoestrap_brightest_dull_color( $colors = array(), $context = 'key' ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::brightest_dull_color()' );
	return Shoestrap_Color::brightest_dull_color( $colors, $context );
}

function shoestrap_brightness_difference( $hex1, $hex2 ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::brightness_difference()' );
	return Shoestrap_Color::brightness_difference( $hex1, $hex2 );
}

function shoestrap_color_difference( $hex1, $hex2 ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::color_difference()' );
	return Shoestrap_Color::color_difference( $hex1, $hex2 );
}

function shoestrap_lumosity_difference( $hex1, $hex2 ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Color::lumosity_difference()' );
	return Shoestrap_Color::lumosity_difference( $hex1, $hex2 );
}

/**
 * Layout functions
 */
function shoestrap_content_width_px( $echo = false ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Layout::content_width_px()' );
	return Shoestrap_Layout::content_width_px( $echo );
}

/**
 * Image functions
 */
function shoestrap_image_resize( $data ) {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', 'Shoestrap_Image::image_resize()' );
	return Shoestrap_Image::image_resize( $data );
}

/**
 * Actions & filters
 */
add_action( 'shoestrap_single_top', 'shoestrap_in_article_top_deprecated' );
function shoestrap_in_article_top_deprecated() {
	if ( has_action( 'shoestrap_in_article_top' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_in_article_top', '3.2', 'shoestrap_single_top' );

		do_action( 'shoestrap_in_article_top' );
	}
}

add_action( 'shoestrap_entry_meta', 'shoestrap_entry_meta_override_deprecated' );
function shoestrap_entry_meta_override_deprecated() {
	if ( has_action( 'shoestrap_entry_meta_override' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_entry_meta_override', '3.2', 'shoestrap_entry_meta' );

		do_action( 'shoestrap_entry_meta_override' );
	}
}

add_action( 'shoestrap_entry_meta', 'shoestrap_after_entry_meta_deprecated', 99 );
function shoestrap_after_entry_meta_deprecated() {
	if ( has_action( 'shoestrap_after_entry_meta' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_after_entry_meta', '3.2', 'shoestrap_entry_meta' );

		do_action( 'shoestrap_after_entry_meta' );
	}
}

add_action( 'shoestrap_do_navbar', 'shoestrap_pre_navbar_deprecated', 9 );
function shoestrap_pre_navbar_deprecated() {
	if ( has_action( 'shoestrap_pre_navbar' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_pre_navbar', '3.2', 'shoestrap_do_navbar' );

		do_action( 'shoestrap_pre_navbar' );
	}
}

add_action( 'shoestrap_do_navbar', 'shoestrap_post_navbar_deprecated', 15 );
function shoestrap_post_navbar_deprecated() {
	if ( has_action( 'shoestrap_post_navbar' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_post_navbar', '3.2', 'shoestrap_do_navbar' );

		do_action( 'shoestrap_post_navbar' );
	}
}

add_action( 'shoestrap_pre_wrap', 'shoestrap_below_top_navbar_deprecated' );
function shoestrap_below_top_navbar_deprecated() {
	if ( has_action( 'shoestrap_below_top_navbar' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_below_top_navbar', '3.2', 'shoestrap_pre_wrap' );

		do_action( 'shoestrap_below_top_navbar' );
	}
}

add_action( 'shoestrap_pre_wrap', 'shoestrap_breadcrumbs_deprecated' );
function shoestrap_breadcrumbs_deprecated() {
	if ( has_action( 'shoestrap_breadcrumbs' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_breadcrumbs', '3.2', 'shoestrap_pre_wrap' );

		do_action( 'shoestrap_breadcrumbs' );
	}
}

add_action( 'shoestrap_pre_wrap', 'shoestrap_header_media_deprecated' );
function shoestrap_header_media_deprecated() {
	if ( has_action( 'shoestrap_header_media' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_header_media', '3.2', 'shoestrap_pre_wrap' );

		do_action( 'shoestrap_header_media' );
	}
}

add_action( 'shoestrap_pre_footer', 'shoestrap_after_wrap_deprecated' );
function shoestrap_after_wrap_deprecated() {
	if ( has_action( 'shoestrap_after_wrap' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_after_wrap', '3.2', 'shoestrap_pre_footer' );

		do_action( 'shoestrap_after_wrap' );
	}
}

add_action( 'shoestrap_in_loop_start', 'shoestrap_after_wrap_deprecated' );
function shoestrap_before_the_content_deprecated() {
	if ( has_action( 'shoestrap_before_the_content' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_before_the_content', '3.2', 'shoestrap_in_loop_start' );

		do_action( 'shoestrap_before_the_content' );
	}
}

add_action( 'shoestrap_in_loop_start', 'shoestrap_in_loop_start_action_deprecated' );
function shoestrap_in_loop_start_action_deprecated() {
	if ( has_action( 'shoestrap_in_loop_start_action' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_in_loop_start_action', '3.2', 'shoestrap_in_loop_start' );

		do_action( 'shoestrap_in_loop_start_action' );
	}
}

add_action( 'shoestrap_in_loop_end', 'shoestrap_after_the_content_deprecated' );
function shoestrap_after_the_content_deprecated() {
	if ( has_action( 'shoestrap_after_the_content' ) ) {
		_shoestrap_deprecated_function( 'shoestrap_after_the_content', '3.2', 'shoestrap_in_loop_end' );

		do_action( 'shoestrap_after_the_content' );
	}
}

/**
 * Alias of __return_true
 */
function shoestrap_return_true()  {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', '__return_true()' );
	return __return_true();
}

/**
 * Alias of __return_false
 */
function shoestrap_return_false() {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', '__return_false()' );
	return __return_false();
}

/**
 * Alias of __return_null
 */
function shoestrap_blank() {
	_shoestrap_deprecated_function( __FUNCTION__, '3.2', '__return_null()' );
	return __return_null();
}
