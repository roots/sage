<?php

if ( !function_exists( 'shoestrap_pagination_alter' ) ) :
/**
 * Use pagination instead of pagers
 */
function shoestrap_pagination_alter() {
	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 )
		return;

	$nav = '<nav class="pagination">';
	$nav .= shoestrap_paginate_links(
		apply_filters( 'pagination_args', array(
			'base'      => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
			'format'    => '',
			'current'     => max( 1, get_query_var('paged') ),
			'total'     => $wp_query->max_num_pages,
			'prev_text'   => '<i class="el-icon-chevron-left"></i>',
			'next_text'   => '<i class="el-icon-chevron-right"></i>',
			'type'      => 'list',
			'end_size'    => 3,
			'mid_size'    => 3
		) )
	);
	$nav .= '</nav>';

	return $nav;
}
endif;


if ( !function_exists( 'shoestrap_pagination_trigger_mod' ) ) :
/**
 * Trigger the pager change based on the user's selections.
 */
function shoestrap_pagination_trigger_mod() {
	if ( shoestrap_getVariable( 'pagination' ) != 'pager' )
		add_filter( 'shoestrap_pagination_format', 'shoestrap_pagination_alter' );
}
endif;
add_action( 'wp', 'shoestrap_pagination_trigger_mod' );