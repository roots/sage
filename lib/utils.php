<?php
/**
 * Utility functions
 */

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

function shoestrap_blank() {
	return '';
}