<?php

/**
 * Add glyphicon to the Join Group-button
 * 
 */
function customize_group_join_button($button) {
	if ($button['id'] == 'join_group') {
		$button['link_text'] = "<span class=\"glyphicon glyphicon-plus-sign\"></span> " . __( 'Join Group', 'buddypress');
	}
	return $button;
}
//add_filter( 'bp_get_group_join_button', 'customize_group_join_button');


function customize_member_activity_update_header($update) {
	return "<p>" . $update . "</p>";
}
add_filter( 'bp_get_activity_latest_update', 'customize_member_activity_update_header' );






/**
 * Highjack the original BuddyPress members directory search form and return a shiny new one.
 * 
 * @author Tobias Møller Kjærsgaard
 * @since 1.0.0
 */
function customize_members_dir_search_form($search_form_html) {

	preg_match('/placeholder="(.*)"/', $search_form_html, $search_value);

	$new_search_form_html = '
	<form action="#" method="get" id="search-members-form" class="form-inline">
		<div class="form-group">
			<label class="sr-only" for="members_search">Search in members directory</label>
			<input type="text" name="s" id="members_search" placeholder="'. $search_value[1] .'">
		</div>
		<button type="submit" id="members_search_submit" name="members_search_submit"><span class="glyphicon glyphicon-search"></span></button>
	</form>';

	return $new_search_form_html;
}
add_filter( 'bp_directory_members_search_form', 'customize_members_dir_search_form' );



/**
 * Highjack the original BuddyPress groups directory search form and return a shiny new one.
 * 
 * @author Tobias Møller Kjærsgaard
 * @since 1.0.0
 */
function customize_groups_dir_search_form($search_form_html) {

	preg_match('/placeholder="(.*)"/', $search_form_html, $search_value);

	$new_search_form_html = '
	<form action="#" method="get" id="search-groups-form" class="form-inline">
		<div class="form-group">
			<label class="sr-only" for="groups_search">Search in groups directory</label>
			<input type="text" name="s" id="groups_search" placeholder="'. $search_value[1] .'">
		</div>
		<button type="submit" id="groups_search_submit" name="groups_search_submit"><span class="glyphicon glyphicon-search"></span></button>
	</form>';

	return $new_search_form_html;
}
add_filter( 'bp_directory_groups_search_form', 'customize_groups_dir_search_form' );






/*
 * Pagination!
 */


function customize_members_pagination_links($pag_links) {
	global $members_template;

	$new_pag_links = paginate_links( array(
		'base'      => add_query_arg( 'upage', '%#%' ),
		'format'    => '',
		'total'     => ceil( (int) $members_template->total_member_count / (int) $members_template->pag_num ),
		'current'   => (int) $members_template->pag_page,
		'prev_text' => _x( '&laquo;', 'Member pagination previous text', 'buddypress' ),
		'next_text' => _x( '&raquo;', 'Member pagination next text', 'buddypress' ),
		'mid_size'  => 1,
		'type'		=> 'array'
	));
	return make_pagination_list($new_pag_links);

}
add_filter( 'bp_get_members_pagination_links', 'customize_members_pagination_links' );


function customize_groups_pagination_links($pag_links) {
	global $groups_template;

	$new_pag_links = paginate_links( array(
			'base' 		=> add_query_arg( 'mlpage', '%#%' ),
			'format' 	=> '',
			'total' 	=> !empty( $groups_template->pag_num ) ? ceil( $groups_template->total_group_count / $groups_template->pag_num ) : $groups_template->total_group_count,
			'current' 	=> $groups_template->pag_page,
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
			'mid_size'	=> 1,
			'type'		=> 'array'
		));
	return make_pagination_list($new_pag_links);
	
}
add_filter( 'bp_get_groups_pagination_links', 'customize_groups_pagination_links' );


function make_pagination_list($pagination_links) {
	$pagination_list = '<ul class="pagination">';

	if (empty($pagination_links)) {
		return;
	}

	if (substr($pagination_links[0], 1, 4) == 'span') {
		$pagination_list .= '<li class="disabled"><span>&laquo;</span></li>';
	}

	foreach ($pagination_links as $link) {
		if (strstr($link, 'current')) {
			$pagination_list .= '<li class="active">' . $link . '</li>';
		} else {
			$pagination_list .= '<li>' . $link . '</li>';
		}
	}

	if (substr($pagination_links[count($pagination_links) - 1], 1, 4) == 'span' ) {
		$pagination_list .= '<li class="disabled"><span>&raquo;</span></li>';
	}

	$pagination_list .= '</ul>';

	return $pagination_list;
}