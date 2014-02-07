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