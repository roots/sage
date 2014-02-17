<?php
/**
 * This file adds compatibility with older functions, actions & filters that were used prior to version 3.1.1
 * Developers should NOT use these functions and actions and should instead migrate to the new ones.
 */

function shoestrap_sanitize_hex( $color ) {
	return ShoestrapColor::sanitize_hex( $color );
}

function shoestrap_get_rgb( $hex, $implode = false ) {
	return ShoestrapColor::get_rgb( $hex, $implode );
}

function shoestrap_get_rgba( $hex, $opacity, $echo ) {
	return ShoestrapColor::get_rgba( $hex, $opacity, $echo );
}

function shoestrap_get_brightness( $hex ) {
	return ShoestrapColor::get_brightness( $hex );
}

function shoestrap_adjust_brightness( $hex, $steps ) {
	return ShoestrapColor::adjust_brightness( $hex, $steps );
}

function shoestrap_mix_colors( $hex1, $hex2, $percentage ) {
	return ShoestrapColor::mix_colors( $hex1, $hex2, $percentage );
}

function shoestrap_hex_to_hsv( $hex ) {
	return ShoestrapColor::hex_to_hsv( $hex );
}

function shoestrap_rgb_to_hsv( $color = array() ) {
	return ShoestrapColor::rgb_to_hsv( $rgb );
}

function shoestrap_brightest_color( $colors = array(), $context = 'key' ) {
	return ShoestrapColor::brightest_color( $colors, $context );
}

function shoestrap_most_saturated_color( $colors = array(), $context = 'key' ) {
	return ShoestrapColor::most_saturated_color( $colors, $context );
}

function shoestrap_most_intense_color( $colors = array(), $context = 'key' ) {
	return ShoestrapColor::most_intense_color( $colors, $context );
}

function shoestrap_brightest_dull_color( $colors = array(), $context = 'key' ) {
	return ShoestrapColor::brightest_dull_color( $colors, $context );
}

function shoestrap_brightness_difference( $hex1, $hex2 ) {
	return ShoestrapColor::brightness_difference( $hex1, $hex2 );
}

function shoestrap_color_difference( $hex1, $hex2 ) {
	return ShoestrapColor::color_difference( $hex1, $hex2 );
}

function shoestrap_lumosity_difference( $hex1, $hex2 ) {
	return ShoestrapColor::lumosity_difference( $hex1, $hex2 );
}

