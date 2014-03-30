<?php
/**
 * This file adds compatibility with older functions, actions & filters that were used prior to version 3.1.1
 * Developers should NOT use these functions and actions and should instead migrate to the new ones.
 */


/**
 * Color functions
 */
function shoestrap_sanitize_hex( $color ) {
	return Shoestrap_Color::sanitize_hex( $color );
}

function shoestrap_get_rgb( $hex, $implode = false ) {
	return Shoestrap_Color::get_rgb( $hex, $implode );
}

function shoestrap_get_rgba( $hex, $opacity, $echo ) {
	return Shoestrap_Color::get_rgba( $hex, $opacity, $echo );
}

function shoestrap_get_brightness( $hex ) {
	return Shoestrap_Color::get_brightness( $hex );
}

function shoestrap_adjust_brightness( $hex, $steps ) {
	return Shoestrap_Color::adjust_brightness( $hex, $steps );
}

function shoestrap_mix_colors( $hex1, $hex2, $percentage ) {
	return Shoestrap_Color::mix_colors( $hex1, $hex2, $percentage );
}

function shoestrap_hex_to_hsv( $hex ) {
	return Shoestrap_Color::hex_to_hsv( $hex );
}

function shoestrap_rgb_to_hsv( $color = array() ) {
	return Shoestrap_Color::rgb_to_hsv( $rgb );
}

function shoestrap_brightest_color( $colors = array(), $context = 'key' ) {
	return Shoestrap_Color::brightest_color( $colors, $context );
}

function shoestrap_most_saturated_color( $colors = array(), $context = 'key' ) {
	return Shoestrap_Color::most_saturated_color( $colors, $context );
}

function shoestrap_most_intense_color( $colors = array(), $context = 'key' ) {
	return Shoestrap_Color::most_intense_color( $colors, $context );
}

function shoestrap_brightest_dull_color( $colors = array(), $context = 'key' ) {
	return Shoestrap_Color::brightest_dull_color( $colors, $context );
}

function shoestrap_brightness_difference( $hex1, $hex2 ) {
	return Shoestrap_Color::brightness_difference( $hex1, $hex2 );
}

function shoestrap_color_difference( $hex1, $hex2 ) {
	return Shoestrap_Color::color_difference( $hex1, $hex2 );
}

function shoestrap_lumosity_difference( $hex1, $hex2 ) {
	return Shoestrap_Color::lumosity_difference( $hex1, $hex2 );
}

/**
 * Layout functions
 */
function shoestrap_content_width_px( $echo = false ) {
	return Shoestrap_Layout::content_width_px( $echo );
}

/**
 * Image functions
 */
function shoestrap_image_resize( $data ) {
	return Shoestrap_Image::image_resize( $data );
}

/**
 * Actions & filters
 */
add_action( 'shoestrap_single_top', 'shoestrap_in_article_top_deprecated' );
function shoestrap_in_article_top_deprecated() {
	if ( has_action( 'shoestrap_in_article_top' ) ) {
		do_action( 'shoestrap_in_article_top' );
	}
}

add_action( 'shoestrap_entry_meta', 'shoestrap_entry_meta_override_deprecated' );
function shoestrap_entry_meta_override_deprecated() {
	if ( has_action( 'shoestrap_entry_meta_override' ) ) {
		do_action( 'shoestrap_entry_meta_override' );
	}
}

add_action( 'shoestrap_entry_meta', 'shoestrap_after_entry_meta_deprecated', 99 );
function shoestrap_after_entry_meta_deprecated() {
	if ( has_action( 'shoestrap_after_entry_meta' ) ) {
		do_action( 'shoestrap_after_entry_meta' );
	}
}

add_action( 'shoestrap_do_navbar', 'shoestrap_pre_navbar_deprecated', 9 );
function shoestrap_pre_navbar_deprecated() {
	if ( has_action( 'shoestrap_pre_navbar' ) ) {
		do_action( 'shoestrap_pre_navbar' );
	}
}

add_action( 'shoestrap_do_navbar', 'shoestrap_post_navbar_deprecated', 15 );
function shoestrap_post_navbar_deprecated() {
	if ( has_action( 'shoestrap_post_navbar' ) ) {
		do_action( 'shoestrap_post_navbar' );
	}
}

add_action( 'shoestrap_pre_wrap', 'shoestrap_below_top_navbar_deprecated' );
function shoestrap_below_top_navbar_deprecated() {
	if ( has_action( 'shoestrap_below_top_navbar' ) ) {
		do_action( 'shoestrap_below_top_navbar' );
	}
}

add_action( 'shoestrap_pre_wrap', 'shoestrap_breadcrumbs_deprecated' );
function shoestrap_breadcrumbs_deprecated() {
	if ( has_action( 'shoestrap_breadcrumbs' ) ) {
		do_action( 'shoestrap_breadcrumbs' );
	}
}

add_action( 'shoestrap_pre_wrap', 'shoestrap_header_media_deprecated' );
function shoestrap_header_media_deprecated() {
	if ( has_action( 'shoestrap_header_media' ) )
		do_action( 'shoestrap_header_media' );
}

add_action( 'shoestrap_pre_footer', 'shoestrap_after_wrap_deprecated' );
function shoestrap_after_wrap_deprecated() {
	if ( has_action( 'shoestrap_after_wrap' ) ) {
		do_action( 'shoestrap_after_wrap' );
	}
}

add_action( 'shoestrap_in_loop_start', 'shoestrap_after_wrap_deprecated' );
function shoestrap_before_the_content_deprecated() {
	if ( has_action( 'shoestrap_before_the_content' ) ) {
		do_action( 'shoestrap_before_the_content' );
	}
}

add_action( 'shoestrap_in_loop_start', 'shoestrap_in_loop_start_action_deprecated' );
function shoestrap_in_loop_start_action_deprecated() {
	if ( has_action( 'shoestrap_in_loop_start_action' ) ) {
		do_action( 'shoestrap_in_loop_start_action' );
	}
}

add_action( 'shoestrap_in_loop_end', 'shoestrap_after_the_content_deprecated' );
function shoestrap_after_the_content_deprecated() {
	if ( has_action( 'shoestrap_after_the_content' ) ) {
		do_action( 'shoestrap_after_the_content' );
	}
}

/**
 * Alias of __return_true
 */
function shoestrap_return_true()  {
	return __return_true();
}

/**
 * Alias of __return_false
 */
function shoestrap_return_false() {
	return __return_false();
}

/**
 * Alias of __return_null
 */
function shoestrap_blank() {
	return __return_null();
}
