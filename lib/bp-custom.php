<?php


/**
 * Remove the button class from activity delete link
 * 
 */
function customize_activity_delete_link($link) {
	$link = str_replace('button ', '', $link);

	return $link;
}
add_filter( 'bp_get_activity_delete_link', 'customize_activity_delete_link' );


/**
 * Return false to the function that checks if nested comments are available.
 * 
 * @return boolean false
 */
function disable_nested_comments($arg) {
	return false;
}
add_filter( 'bp_activity_can_comment_reply', 'disable_nested_comments' );


/**
 * Highjack the original BuddyPress members directory search form and return a shiny new one.
 * 
 * @author Tobias Møller Kjærsgaard
 * @since 1.0.0
 */
function customize_members_dir_search_form($search_form_html) {

	preg_match('/placeholder="(.*)"/', $search_form_html, $search_value);

	$new_search_form_html_orig = '
	<form action="#" method="get" id="search-members-form" class="form-inline">
		<div class="form-group">
			<label class="sr-only" for="members_search">Search in members directory</label>
			<input type="text" name="s" id="members_search" placeholder="'. $search_value[1] .'">
		</div>
		<button type="submit" id="members_search_submit" name="members_search_submit"><span class="glyphicon glyphicon-search"></span></button>
	</form>';

	$new_search_form_html = '
	<form action="#" method="get" id="search-members-form" class="form-inline">
		<div class="input-group">
		  <input type="text" class="form-control" name="s" id="members_search" placeholder="'. $search_value[1] .'">
		  <span class="input-group-btn">
		    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
		  </span>
		</div><!-- /input-group -->
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

	$new_search_form_html_orig = '
	<form action="#" method="get" id="search-groups-form" class="form-inline">
		<div class="form-group">
			<label class="sr-only" for="groups_search">Search in groups directory</label>
			<input type="text" name="s" id="groups_search" placeholder="'. $search_value[1] .'">
		</div>
		<button type="submit" id="groups_search_submit" name="groups_search_submit"><span class="glyphicon glyphicon-search"></span></button>
	</form>';

	$new_search_form_html = '
	<form action="#" method="get" id="search-groups-form" class="form-inline">
		<div class="input-group">
		  <input type="text" class="form-control" name="s" id="groups_search" placeholder="'. $search_value[1] .'">
		  <span class="input-group-btn">
		    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
		  </span>
		</div><!-- /input-group -->
	</form>';

	return $new_search_form_html;
}
add_filter( 'bp_directory_groups_search_form', 'customize_groups_dir_search_form' );



/**
 * Highjack the original BuddyPress blogs directory search form and return a shiny new one.
 * 
 * @author Tobias Møller Kjærsgaard
 * @since 1.0.0
 */
function customize_blogs_dir_search_form($search_form_html) {

	preg_match('/placeholder="(.*)"/', $search_form_html, $search_value);

	$new_search_form_html_orig = '
	<form action="#" method="get" id="blogs-groups-form" class="form-inline">
		<div class="form-group">
			<label class="sr-only" for="blogs_search">Search in groups directory</label>
			<input type="text" name="s" id="blogs_search" placeholder="'. $search_value[1] .'">
		</div>
		<button type="submit" id="groups_search_submit" name="groups_search_submit"><span class="glyphicon glyphicon-search"></span></button>
	</form>';

	$new_search_form_html = '
	<form action="#" method="get" id="search-blogs-form" class="form-inline">
		<div class="input-group">
		  <input type="text" class="form-control" name="s" id="blogs_search" placeholder="'. $search_value[1] .'">
		  <span class="input-group-btn">
		    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
		  </span>
		</div><!-- /input-group -->
	</form>';

	return $new_search_form_html;
}
add_filter( 'bp_directory_blogs_search_form', 'customize_blogs_dir_search_form' );






/**
 * Modify the ajax querystring to limit groups directory pages to 10 items each.
 * 
 * @since 1.0.0
 * @param string $query_string The passed ajax querystring from caller
 * @param string $object The passed object string from caller
 * @return string modified ajax querystring
 * 
 */
function ajax_querystring_modification($query_string, $object) {
	if($object == 'groups')
		$query_string .= '&per_page=10';

	return $query_string;
}
add_filter( 'bp_legacy_theme_ajax_querystring', 'ajax_querystring_modification', 10, 2 );


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


/**
 * 
 */
function customize_sites_pagination_links($pag_links) {
	global $blogs_template;

	$new_pag_links = paginate_links( array(
		'base'      => add_query_arg( 'bpage', '%#%' ),
		'format'    => '',
		'total'     => ceil( (int) $blogs_template->total_blog_count / (int) $blogs_template->pag_num ),
		'current'   => (int) $blogs_template->pag_page,
		'prev_text' => _x( '&laquo;', 'Blog pagination previous text', 'buddypress' ),
		'next_text' => _x( '&raquo;', 'Blog pagination next text', 'buddypress' ),
		'mid_size'  => 1,
		'type'		=> 'array'
	) );
	return make_pagination_list($new_pag_links);
}
add_filter( 'bp_get_blogs_pagination_links', 'customize_sites_pagination_links' );


/**
 * Return an HTML-string with pagination array items printed in an unordered list
 * 
 * @since 1.0.0
 * @author Tobias Møller Kjærsgaard
 * @param array $pagination_list array with strings for each pagination link
 * @return string Pagination links HTML
 */
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