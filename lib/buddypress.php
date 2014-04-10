<?php

/**
 * Check if this is a BuddyPress page or not.
 * Returns true or false (boolean)
 */
function shoestrap_is_bp() {

	$bp = false;
	/** Pages *************************************************************/

	if ( ! $bp &&  bp_is_directory() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_single_item() ) {
		$bp = true;
	}

	/** Components ********************************************************/

	elseif ( ! $bp && bp_is_user_profile() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_activity_component() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_blogs_component() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_messages_component() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_friends_component() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_groups_component() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_settings_component()  ) {
		$bp = true;
	}

	/** User **************************************************************/

	elseif ( ! $bp && bp_is_user() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_blogs() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_groups() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_activity() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_my_profile() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_profile() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_friends() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_messages() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_recent_commments() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_recent_posts() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_change_avatar() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_profile_edit() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_friends_activity() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_groups_activity() ) {
		$bp = true;
	} elseif ( ! $bp && is_user_logged_in() ) {
		$bp = true;
	}

	/** Messages **********************************************************/

	elseif ( ! $bp && bp_is_messages_inbox() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_messages_sentbox() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_messages_compose_screen() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_notices() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_user_friend_requests() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_create_blog() ) {
		$bp = true;
	}

	/** Groups ************************************************************/

	elseif ( ! $bp && bp_is_group_leave() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_group_invites() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_group_members() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_group_forum_topic() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_group_forum_topic_edit() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_group_forum() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_group_admin_page() ) {
		$bp = true;

	} elseif ( ! $bp && bp_is_group_create() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_group_home() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_single_activity() ) {
		$bp = true;
	}

	/** Registration ******************************************************/

	elseif ( ! $bp && bp_is_register_page() ) {
		$bp = true;
	} elseif ( ! $bp && bp_is_activation_page() ) {
		$bp = true;
	}

	/** is_buddypress *****************************************************/

	// Add BuddyPress class if we are within a BuddyPress page
	elseif ( ! $bp && ! bp_is_blog_page() ) {
		$bp = true;
	}

	return $bp;
}
