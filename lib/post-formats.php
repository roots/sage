<?php

/*
 * If the post format is set to "aside", don't display a title.
 */
function shoestrap_post_formats_aside() {
	if ( get_post_format() == 'aside' )
		add_filter( 'shoestrap_title_section', 'shoestrap_blank' );
}
add_action( 'wp', 'shoestrap_post_formats_aside' );