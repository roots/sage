<?php
/**
 * Utility functions
 */
function add_filters( $tags, $function ) {
	foreach( $tags as $tag ) {
		add_filter( $tag, $function );
	}
}

function is_element_empty( $element ) {
	$element = trim( $element );
	return empty( $element ) ? false : true;
}

function shoestrap_return_true()  {
	return true;
}

function shoestrap_return_false() {
	return false;
}

function shoestrap_clearfix() {
	$clear = apply_filters( 'shoestrap_clearfix', '<div class="clearfix"></div>' );

	return $clear;
}

function shoestrap_alert( $class = 'alert alert-info', $content = '', $echo = true ) {
	$alert = '<div class="' . $class . '">' . $content . '</div>';

	if ( $echo )
		echo $alert;
	else
		return $alert;
}