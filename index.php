<?php

if ( !has_action( 'shoestrap_page_header_override' ) ) {
	get_template_part( 'templates/page', 'header' );
} else {
	do_action( 'shoestrap_page_header_override' );
}

do_action( 'shoestrap_index_begin' );

if ( !have_posts() ) {
	echo '<div class="alert alert-warning">' . __( 'Sorry, no results were found.', 'shoestrap' ) . '</div>';
	get_search_form();
}

if ( !has_action( 'shoestrap_override_index_loop' ) ) {
	while (have_posts()) : the_post();
		do_action( 'shoestrap_in_loop_start' );

		if ( !has_action( 'shoestrap_content_override' ) ) {
			get_template_part( 'templates/content', get_post_format() );
		} else {
			do_action( 'shoestrap_content_override' );
		}

		do_action( 'shoestrap_in_loop_end' );
	endwhile;
} else {
	do_action( 'shoestrap_override_index_loop' );
}

do_action( 'shoestrap_index_end' );

$pager = shoestrap_pagers();
echo apply_filters( 'shoestrap_pagination_format', $pager );