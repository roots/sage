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