<?php

/**
 * Functions of BuddyPress's Legacy theme
 *
 * @package BuddyPress
 * @subpackage BP_Theme_Compat
 * @since BuddyPress (1.7)
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/** Theme Setup ***************************************************************/

if ( !class_exists( 'BP_Legacy' ) ) :

/**
 * Loads BuddyPress Legacy Theme functionality
 *
 * This is not a real theme by WordPress standards, and is instead used as the
 * fallback for any WordPress theme that does not have BuddyPress templates in it.
 *
 * To make your custom theme BuddyPress compatible and customize the templates, you
 * can copy these files into your theme without needing to merge anything
 * together; BuddyPress should safely handle the rest.
 *
 * See @link BP_Theme_Compat() for more.
 *
 * @since BuddyPress (1.7)
 *
 * @package BuddyPress
 * @subpackage BP_Theme_Compat
 */
class BP_Legacy extends BP_Theme_Compat {

	/** Functions *************************************************************/

	/**
	 * The main BuddyPress (Legacy) Loader
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @uses BP_Legacy::setup_globals()
	 * @uses BP_Legacy::setup_actions()
	 */
	public function __construct() {
		parent::start();
	}

	/**
	 * Component global variables
	 *
	 * You'll want to customize the values in here, so they match whatever your
	 * needs are.
	 *
	 * @since BuddyPress (1.7)
	 * @access private
	 */
	protected function setup_globals() {
		$bp            = buddypress();
		$this->id      = 'legacy';
		$this->name    = __( 'BuddyPress Legacy', 'buddypress' );
		$this->version = bp_get_version();
		$this->dir     = trailingslashit( $bp->themes_dir . '/bp-legacy' );
		$this->url     = trailingslashit( $bp->themes_url . '/bp-legacy' );
	}

	/**
	 * Setup the theme hooks
	 *
	 * @since BuddyPress (1.7)
	 * @access private
	 *
	 * @uses add_filter() To add various filters
	 * @uses add_action() To add various actions
	 */
	protected function setup_actions() {

		// Template Output
		//add_filter( 'bp_get_activity_action_pre_meta', array( $this, 'secondary_avatars' ), 10, 2 );

		/** Scripts ***********************************************************/

		add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_styles'   ) ); // Enqueue theme CSS
		add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts'  ) ); // Enqueue theme JS
		add_filter( 'bp_enqueue_scripts', array( $this, 'localize_scripts' ) ); // Enqueue theme script localization
		add_action( 'bp_head',            array( $this, 'head_scripts'     ) ); // Output some extra JS in the <head>

		/** Body no-js Class ********************************************************/
		add_filter( 'body_class', array( $this, 'add_nojs_body_class' ), 20, 1 );

		/** Buttons ***********************************************************/

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			// Register buttons for the relevant component templates
			// Friends button
			if ( bp_is_active( 'friends' ) )
				add_action( 'bp_member_header_actions',    'bp_add_friend_button',           5 );

			// Activity button
			if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() )
				add_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );

			// Messages button
			if ( bp_is_active( 'messages' ) )
				add_action( 'bp_member_header_actions',    'bp_send_private_message_button', 20 );

			// Group buttons
			if ( bp_is_active( 'groups' ) ) {
				add_action( 'bp_group_header_actions',     'bp_group_join_button',           5 );
				add_action( 'bp_group_header_actions',     'bp_group_new_topic_button',      20 );
				add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
			}

			// Blog button
			if ( bp_is_active( 'blogs' ) )
				add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );

		}

		/** Notices ***********************************************************/

		// Only hook the 'sitewide_notices' overlay if the Sitewide
		// Notices widget is not in use (to avoid duplicate content).
		if ( bp_is_active( 'messages' ) && ! is_active_widget( false, false, 'bp_messages_sitewide_notices_widget', true ) ) {
			add_action( 'wp_footer', array( $this, 'sitewide_notices' ), 9999 );
		}

		/** Ajax **************************************************************/

		$actions = array(

			// Directory filters
			'blogs_filter'    => 'bp_legacy_theme_object_template_loader',
			'forums_filter'   => 'bp_legacy_theme_object_template_loader',
			'groups_filter'   => 'bp_legacy_theme_object_template_loader',
			'members_filter'  => 'bp_legacy_theme_object_template_loader',
			'messages_filter' => 'bp_legacy_theme_messages_template_loader',

			// Friends
			'accept_friendship' => 'bp_legacy_theme_ajax_accept_friendship',
			'addremove_friend'  => 'bp_legacy_theme_ajax_addremove_friend',
			'reject_friendship' => 'bp_legacy_theme_ajax_reject_friendship',

			// Activity
			'activity_get_older_updates'  => 'bp_legacy_theme_activity_template_loader',
			'activity_mark_fav'           => 'bp_legacy_theme_mark_activity_favorite',
			'activity_mark_unfav'         => 'bp_legacy_theme_unmark_activity_favorite',
			'activity_widget_filter'      => 'bp_legacy_theme_activity_template_loader',
			'delete_activity'             => 'bp_legacy_theme_delete_activity',
			'delete_activity_comment'     => 'bp_legacy_theme_delete_activity_comment',
			'get_single_activity_content' => 'bp_legacy_theme_get_single_activity_content',
			'new_activity_comment'        => 'bp_legacy_theme_new_activity_comment',
			'post_update'                 => 'bp_legacy_theme_post_update',
			'bp_spam_activity'            => 'bp_legacy_theme_spam_activity',
			'bp_spam_activity_comment'    => 'bp_legacy_theme_spam_activity',

			// Groups
			'groups_invite_user' => 'bp_legacy_theme_ajax_invite_user',
			'joinleave_group'    => 'bp_legacy_theme_ajax_joinleave_group',

			// Messages
			'messages_autocomplete_results' => 'bp_legacy_theme_ajax_messages_autocomplete_results',
			'messages_close_notice'         => 'bp_legacy_theme_ajax_close_notice',
			'messages_delete'               => 'bp_legacy_theme_ajax_messages_delete',
			'messages_markread'             => 'bp_legacy_theme_ajax_message_markread',
			'messages_markunread'           => 'bp_legacy_theme_ajax_message_markunread',
			'messages_send_reply'           => 'bp_legacy_theme_ajax_messages_send_reply',
		);

		/**
		 * Register all of these AJAX handlers
		 *
		 * The "wp_ajax_" action is used for logged in users, and "wp_ajax_nopriv_"
		 * executes for users that aren't logged in. This is for backpat with BP <1.6.
		 */
		foreach( $actions as $name => $function ) {
			add_action( 'wp_ajax_'        . $name, $function );
			add_action( 'wp_ajax_nopriv_' . $name, $function );
		}

		add_filter( 'bp_ajax_querystring', 'bp_legacy_theme_ajax_querystring', 10, 2 );

		/** Override **********************************************************/

		do_action_ref_array( 'bp_theme_compat_actions', array( &$this ) );
	}

	/**
	 * Load the theme CSS
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @uses wp_enqueue_style() To enqueue the styles
	 */
	public function enqueue_styles() {

		// LTR or RTL
		$file = is_rtl() ? 'buddypress-rtl.css' : 'buddypress.css';

		// Locate the BP stylesheet
		$asset = $this->locate_asset_in_stack( $file, 'css' );

		// Enqueue BuddyPress-specific styling, if found
		if ( isset( $asset['location'], $asset['handle'] ) ) {
			wp_enqueue_style( $asset['handle'], $asset['location'], array(), $this->version, 'screen' );
		}
	}

	/**
	 * Enqueue the required Javascript files
	 *
	 * @since BuddyPress (1.7)
	 */
	public function enqueue_scripts() {

		$file = 'buddypress.js';

		// Locate the BP JS file
		$asset = $this->locate_asset_in_stack( $file, 'js' );

		// Enqueue the global JS, if found - AJAX will not work
		// without it
		if ( isset( $asset['location'], $asset['handle'] ) ) {
			wp_enqueue_script( $asset['handle'], $asset['location'], array( 'jquery' ), $this->version );
		}

		// Add words that we need to use in JS to the end of the page
		// so they can be translated and still used.
		$params = array(
			'accepted'            => __( 'Accepted', 'buddypress' ),
			'close'               => __( 'Close', 'buddypress' ),
			'comments'            => __( 'comments', 'buddypress' ),
			'leave_group_confirm' => __( 'Are you sure you want to leave this group?', 'buddypress' ),
			'mark_as_fav'	      => __( 'Favorite', 'buddypress' ),
			'my_favs'             => __( 'My Favorites', 'buddypress' ),
			'rejected'            => __( 'Rejected', 'buddypress' ),
			'remove_fav'	      => __( 'Remove Favorite', 'buddypress' ),
			'show_all'            => __( 'Show all', 'buddypress' ),
			'show_all_comments'   => __( 'Show all comments for this thread', 'buddypress' ),
			'show_x_comments'     => __( 'Show all %d comments', 'buddypress' ),
			'unsaved_changes'     => __( 'Your profile has unsaved changes. If you leave the page, the changes will be lost.', 'buddypress' ),
			'view'                => __( 'View', 'buddypress' ),
		);
		wp_localize_script( $asset['handle'], 'BP_DTheme', $params );

		// Maybe enqueue comment reply JS
		if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Get the URL and handle of a web-accessible CSS or JS asset
	 *
	 * We provide two levels of customizability with respect to where CSS
	 * and JS files can be stored: (1) the child theme/parent theme/theme
	 * compat hierarchy, and (2) the "template stack" of /buddypress/css/,
	 * /community/css/, and /css/. In this way, CSS and JS assets can be
	 * overloaded, and default versions provided, in exactly the same way
	 * as corresponding PHP templates.
	 *
	 * We are duplicating some of the logic that is currently found in
	 * bp_locate_template() and the _template_stack() functions. Those
	 * functions were built with PHP templates in mind, and will require
	 * refactoring in order to provide "stack" functionality for assets
	 * that must be accessible both using file_exists() (the file path)
	 * and at a public URI.
	 *
	 * This method is marked private, with the understanding that the
	 * implementation is subject to change or removal in an upcoming
	 * release, in favor of a unified _template_stack() system. Plugin
	 * and theme authors should not attempt to use what follows.
	 *
	 * @since BuddyPress (1.8)
	 * @access private
	 * @param string $file A filename like buddypress.cs
	 * @param string $type css|js
	 * @return array An array of data for the wp_enqueue_* function:
	 *   'handle' (eg 'bp-child-css') and a 'location' (the URI of the
	 *   asset)
	 */
	private function locate_asset_in_stack( $file, $type = 'css' ) {
		// Child, parent, theme compat
		$locations = array();

		// No need to check child if template == stylesheet
		if ( is_child_theme() ) {
			$locations['bp-child'] = array(
				'dir' => get_stylesheet_directory(),
				'uri' => get_stylesheet_directory_uri(),
			);
		}

		$locations['bp-parent'] = array(
			'dir' => get_template_directory(),
			'uri' => get_template_directory_uri(),
		);

		$locations['bp-legacy'] = array(
			'dir' => bp_get_theme_compat_dir(),
			'uri' => bp_get_theme_compat_url(),
		);

		// Subdirectories within the top-level $locations directories
		$subdirs = array(
			'buddypress/' . $type,
			'community/' . $type,
			$type,
		);

		$retval = array();

		foreach ( $locations as $location_type => $location ) {
			foreach ( $subdirs as $subdir ) {
				if ( file_exists( trailingslashit( $location['dir'] ) . trailingslashit( $subdir ) . $file ) ) {
					$retval['location'] = trailingslashit( $location['uri'] ) . trailingslashit( $subdir ) . $file;
					$retval['handle']   = $location_type . '-' . $type;
					break 2;
				}
			}
		}

		return $retval;
	}

	/**
	 * Put some scripts in the header, like AJAX url for wp-lists
	 *
	 * @since BuddyPress (1.7)
	 */
	public function head_scripts() {
	?>

		<script type="text/javascript" charset="utf-8">
			/* <![CDATA[ */
			var ajaxurl = '<?php echo bp_core_ajax_url(); ?>';
			/* ]]> */
		</script>

	<?php
	}

	/**
	 * Adds the no-js class to the body tag.
	 *
	 * This function ensures that the <body> element will have the 'no-js' class by default. If you're
	 * using JavaScript for some visual functionality in your theme, and you want to provide noscript
	 * support, apply those styles to body.no-js.
	 *
	 * The no-js class is removed by the JavaScript created in buddypress.js.
	 *
	 * @since BuddyPress (1.7)
	 */
	public function add_nojs_body_class( $classes ) {
		if ( ! in_array( 'no-js', $classes ) )
			$classes[] = 'no-js';

		return array_unique( $classes );
	}

	/**
	 * Load localizations for topic script
	 *
	 * These localizations require information that may not be loaded even by init.
	 *
	 * @since BuddyPress (1.7)
	 */
	public function localize_scripts() {
	}

	/**
	 * Outputs sitewide notices markup in the footer.
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/4802
	 */
	public function sitewide_notices() {
		// Do not show notices if user is not logged in
		if ( ! is_user_logged_in() )
			return;

		// add a class to determine if the admin bar is on or not
		$class = did_action( 'admin_bar_menu' ) ? 'admin-bar-on' : 'admin-bar-off';

		echo '<div id="sitewide-notice" class="' . $class . '">';
		bp_message_get_notices();
		echo '</div>';
	}

	/**
	 * Add secondary avatar image to this activity stream's record, if supported.
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @param string $action The text of this activity
	 * @param BP_Activity_Activity $activity Activity object
	 * @package BuddyPress Theme
	 * @return string
	 */
	function secondary_avatars( $action, $activity ) {
		switch ( $activity->component ) {
			case 'groups' :
			case 'friends' :
				// Only insert avatar if one exists
				if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
					$reverse_content = strrev( $action );
					$position        = strpos( $reverse_content, 'a<' );
					$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
				}
				break;
		}

		return $action;
	}
}
new BP_Legacy();
endif;

/**
 * This function looks scarier than it actually is. :)
 * Each object loop (activity/members/groups/blogs/forums) contains default
 * parameters to show specific information based on the page we are currently
 * looking at.
 *
 * The following function will take into account any cookies set in the JS and
 * allow us to override the parameters sent. That way we can change the results
 * returned without reloading the page.
 *
 * By using cookies we can also make sure that user settings are retained
 * across page loads.
 *
 * @return string Query string for the component loops
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_querystring( $query_string, $object ) {
	if ( empty( $object ) )
		return '';

	// Set up the cookies passed on this AJAX request. Store a local var to avoid conflicts
	if ( ! empty( $_POST['cookie'] ) ) {
		$_BP_COOKIE = wp_parse_args( str_replace( '; ', '&', urldecode( $_POST['cookie'] ) ) );
	} else {
		$_BP_COOKIE = &$_COOKIE;
	}

	$qs = array();

	/**
	 * Check if any cookie values are set. If there are then override the
	 * default params passed to the template loop.
	 */

	// Activity stream filtering on action
	if ( ! empty( $_BP_COOKIE['bp-' . $object . '-filter'] ) && '-1' != $_BP_COOKIE['bp-' . $object . '-filter'] ) {
		$qs[] = 'type='   . $_BP_COOKIE['bp-' . $object . '-filter'];
		$qs[] = 'action=' . $_BP_COOKIE['bp-' . $object . '-filter'];
	}

	if ( ! empty( $_BP_COOKIE['bp-' . $object . '-scope'] ) ) {
		if ( 'personal' == $_BP_COOKIE['bp-' . $object . '-scope'] ) {
			$user_id = ( bp_displayed_user_id() ) ? bp_displayed_user_id() : bp_loggedin_user_id();
			$qs[] = 'user_id=' . $user_id;
		}

		// Activity stream scope only on activity directory.
		if ( 'all' != $_BP_COOKIE['bp-' . $object . '-scope'] && ! bp_displayed_user_id() && ! bp_is_single_item() )
			$qs[] = 'scope=' . $_BP_COOKIE['bp-' . $object . '-scope'];
	}

	// If page and search_terms have been passed via the AJAX post request, use those.
	if ( ! empty( $_POST['page'] ) && '-1' != $_POST['page'] )
		$qs[] = 'page=' . absint( $_POST['page'] );

	// exludes activity just posted and avoids duplicate ids
	if ( ! empty( $_POST['exclude_just_posted'] ) ) {
		$just_posted = wp_parse_id_list( $_POST['exclude_just_posted'] );
		$qs[] = 'exclude=' . implode( ',', $just_posted );
	}

	$object_search_text = bp_get_search_default_text( $object );
 	if ( ! empty( $_POST['search_terms'] ) && $object_search_text != $_POST['search_terms'] && 'false' != $_POST['search_terms'] && 'undefined' != $_POST['search_terms'] )
		$qs[] = 'search_terms=' . $_POST['search_terms'];

	// Now pass the querystring to override default values.
	$query_string = empty( $qs ) ? '' : join( '&', (array) $qs );

	$object_filter = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-filter'] ) )
		$object_filter = $_BP_COOKIE['bp-' . $object . '-filter'];

	$object_scope = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-scope'] ) )
		$object_scope = $_BP_COOKIE['bp-' . $object . '-scope'];

	$object_page = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-page'] ) )
		$object_page = $_BP_COOKIE['bp-' . $object . '-page'];

	$object_search_terms = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-search-terms'] ) )
		$object_search_terms = $_BP_COOKIE['bp-' . $object . '-search-terms'];

	$object_extras = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-extras'] ) )
		$object_extras = $_BP_COOKIE['bp-' . $object . '-extras'];

	return apply_filters( 'bp_legacy_theme_ajax_querystring', $query_string, $object, $object_filter, $object_scope, $object_page, $object_search_terms, $object_extras );
}

/**
 * Load the template loop for the current object.
 *
 * @return string Prints template loop for the specified object
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_object_template_loader() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Bail if no object passed
	if ( empty( $_POST['object'] ) )
		return;

	// Sanitize the object
	$object = sanitize_title( $_POST['object'] );

	// Bail if object is not an active component to prevent arbitrary file inclusion
	if ( ! bp_is_active( $object ) )
		return;

 	/**
	 * AJAX requests happen too early to be seen by bp_update_is_directory()
	 * so we do it manually here to ensure templates load with the correct
	 * context. Without this check, templates will load the 'single' version
	 * of themselves rather than the directory version.
	 */
	if ( ! bp_current_action() )
		bp_update_is_directory( true, bp_current_component() );

	// Locate the object template
	bp_get_template_part( "$object/$object-loop" );
	exit();
}

/**
 * Load messages template loop when searched on the private message page
 *
 * @return string Prints template loop for the Messages component
 * @since BuddyPress (1.6)
 */
function bp_legacy_theme_messages_template_loader() {
	bp_get_template_part( 'members/single/messages/messages-loop' );
	exit();
}

/**
 * Load the activity loop template when activity is requested via AJAX,
 *
 * @return string JSON object containing 'contents' (output of the template loop
 * for the Activity component) and 'feed_url' (URL to the relevant RSS feed).
 *
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_activity_template_loader() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	$scope = '';
	if ( ! empty( $_POST['scope'] ) )
		$scope = $_POST['scope'];

	// We need to calculate and return the feed URL for each scope
	switch ( $scope ) {
		case 'friends':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/friends/feed/';
			break;
		case 'groups':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/groups/feed/';
			break;
		case 'favorites':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/feed/';
			break;
		case 'mentions':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/feed/';
			bp_activity_clear_new_mentions( bp_loggedin_user_id() );
			break;
		default:
			$feed_url = home_url( bp_get_activity_root_slug() . '/feed/' );
			break;
	}

	// Buffer the loop in the template to a var for JS to spit out.
	ob_start();
	bp_get_template_part( 'activity/activity-loop' );
	$result['contents'] = ob_get_contents();
	$result['feed_url'] = apply_filters( 'bp_legacy_theme_activity_feed_url', $feed_url, $scope );
	ob_end_clean();

	exit( json_encode( $result ) );
}

/**
 * Processes Activity updates received via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_post_update() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'post_update', '_wpnonce_post_update' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	if ( empty( $_POST['content'] ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'Please enter some content to post.', 'buddypress' ) . '</p></div>' );

	$activity_id = 0;
	if ( empty( $_POST['object'] ) && bp_is_active( 'activity' ) ) {
		$activity_id = bp_activity_post_update( array( 'content' => $_POST['content'] ) );

	} elseif ( $_POST['object'] == 'groups' ) {
		if ( ! empty( $_POST['item_id'] ) && bp_is_active( 'groups' ) )
			$activity_id = groups_post_update( array( 'content' => $_POST['content'], 'group_id' => $_POST['item_id'] ) );

	} else {
		$activity_id = apply_filters( 'bp_activity_custom_update', $_POST['object'], $_POST['item_id'], $_POST['content'] );
	}

	if ( empty( $activity_id ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'There was a problem posting your update, please try again.', 'buddypress' ) . '</p></div>' );

	if ( bp_has_activities ( 'include=' . $activity_id ) ) {
		while ( bp_activities() ) {
			bp_the_activity();
			bp_get_template_part( 'activity/entry' );
		}
	}

	exit;
}

/**
 * Posts new Activity comments received via a POST request.
 *
 * @global BP_Activity_Template $activities_template
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_new_activity_comment() {
	global $activities_template;

	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'new_activity_comment', '_wpnonce_new_activity_comment' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	if ( empty( $_POST['content'] ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'Please do not leave the comment area blank.', 'buddypress' ) . '</p></div>' );

	if ( empty( $_POST['form_id'] ) || empty( $_POST['comment_id'] ) || ! is_numeric( $_POST['form_id'] ) || ! is_numeric( $_POST['comment_id'] ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'There was an error posting that reply, please try again.', 'buddypress' ) . '</p></div>' );

	$comment_id = bp_activity_new_comment( array(
		'activity_id' => $_POST['form_id'],
		'content'     => $_POST['content'],
		'parent_id'   => $_POST['comment_id'],
	) );

	if ( ! $comment_id )
		exit( '-1<div id="message" class="error"><p>' . __( 'There was an error posting that reply, please try again.', 'buddypress' ) . '</p></div>' );

	// Load the new activity item into the $activities_template global
	bp_has_activities( 'display_comments=stream&hide_spam=false&include=' . $comment_id );

	// Swap the current comment with the activity item we just loaded
	if ( isset( $activities_template->activities[0] ) ) {
		$activities_template->activity = new stdClass();
		$activities_template->activity->id              = $activities_template->activities[0]->item_id;
		$activities_template->activity->current_comment = $activities_template->activities[0];
	}

	// get activity comment template part
	bp_get_template_part( 'activity/comment' );

	unset( $activities_template );
	exit;
}

/**
 * Deletes an Activity item received via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_delete_activity() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'bp_activity_delete_link' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	if ( empty( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) )
		exit( '-1' );

	$activity = new BP_Activity_Activity( (int) $_POST['id'] );

	// Check access
	if ( ! bp_activity_user_can_delete( $activity ) )
		exit( '-1' );

	// Call the action before the delete so plugins can still fetch information about it
	do_action( 'bp_activity_before_action_delete_activity', $activity->id, $activity->user_id );

	if ( ! bp_activity_delete( array( 'id' => $activity->id, 'user_id' => $activity->user_id ) ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'There was a problem when deleting. Please try again.', 'buddypress' ) . '</p></div>' );

	do_action( 'bp_activity_action_delete_activity', $activity->id, $activity->user_id );
	exit;
}

/**
 * Deletes an Activity comment received via a POST request
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_delete_activity_comment() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'bp_activity_delete_link' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	$comment = new BP_Activity_Activity( $_POST['id'] );

	// Check access
	if ( ! bp_current_user_can( 'bp_moderate' ) && $comment->user_id != bp_loggedin_user_id() )
		exit( '-1' );

	if ( empty( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) )
		exit( '-1' );

	// Call the action before the delete so plugins can still fetch information about it
	do_action( 'bp_activity_before_action_delete_activity', $_POST['id'], $comment->user_id );

	if ( ! bp_activity_delete_comment( $comment->item_id, $comment->id ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'There was a problem when deleting. Please try again.', 'buddypress' ) . '</p></div>' );

	do_action( 'bp_activity_action_delete_activity', $_POST['id'], $comment->user_id );
	exit;
}

/**
 * AJAX spam an activity item or comment
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.6)
 */
function bp_legacy_theme_spam_activity() {
	$bp = buddypress();

	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check that user is logged in, Activity Streams are enabled, and Akismet is present.
	if ( ! is_user_logged_in() || ! bp_is_active( 'activity' ) || empty( $bp->activity->akismet ) )
		exit( '-1' );

	// Check an item ID was passed
	if ( empty( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) )
		exit( '-1' );

	// Is the current user allowed to spam items?
	if ( ! bp_activity_user_can_mark_spam() )
		exit( '-1' );

	// Load up the activity item
	$activity = new BP_Activity_Activity( (int) $_POST['id'] );
	if ( empty( $activity->component ) )
		exit( '-1' );

	// Check nonce
	check_admin_referer( 'bp_activity_akismet_spam_' . $activity->id );

	// Call an action before the spamming so plugins can modify things if they want to
	do_action( 'bp_activity_before_action_spam_activity', $activity->id, $activity );

	// Mark as spam
	bp_activity_mark_as_spam( $activity );
	$activity->save();

	do_action( 'bp_activity_action_spam_activity', $activity->id, $activity->user_id );
	exit;
}

/**
 * Mark an activity as a favourite via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_mark_activity_favorite() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( bp_activity_add_user_favorite( $_POST['id'] ) )
		_e( 'Remove Favorite', 'buddypress' );
	else
		_e( 'Favorite', 'buddypress' );

	exit;
}

/**
 * Un-favourite an activity via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_unmark_activity_favorite() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( bp_activity_remove_user_favorite( $_POST['id'] ) )
		_e( 'Favorite', 'buddypress' );
	else
		_e( 'Remove Favorite', 'buddypress' );

	exit;
}

/**
 * Fetches full an activity's full, non-excerpted content via a POST request.
 * Used for the 'Read More' link on long activity items.
 *
 * @return string HTML
 * @since BuddyPress (1.5)
 */
function bp_legacy_theme_get_single_activity_content() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	$activity_array = bp_activity_get_specific( array(
		'activity_ids'     => $_POST['activity_id'],
		'display_comments' => 'stream'
	) );

	$activity = ! empty( $activity_array['activities'][0] ) ? $activity_array['activities'][0] : false;

	if ( empty( $activity ) )
		exit; // @todo: error?

	do_action_ref_array( 'bp_legacy_theme_get_single_activity_content', array( &$activity ) );

	// Activity content retrieved through AJAX should run through normal filters, but not be truncated
	remove_filter( 'bp_get_activity_content_body', 'bp_activity_truncate_entry', 5 );
	$content = apply_filters( 'bp_get_activity_content_body', $activity->content );

	exit( $content );
}

/**
 * Invites a friend to join a group via a POST request.
 *
 * @since BuddyPress (1.2)
 * @todo Audit return types
 */
function bp_legacy_theme_ajax_invite_user() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_ajax_referer( 'groups_invite_uninvite_user' );

	if ( ! $_POST['friend_id'] || ! $_POST['friend_action'] || ! $_POST['group_id'] )
		return;

	if ( ! bp_groups_user_can_send_invites( $_POST['group_id'] ) )
		return;

	if ( ! friends_check_friendship( bp_loggedin_user_id(), $_POST['friend_id'] ) )
		return;

	$group_id = (int) $_POST['group_id'];
	$friend_id = (int) $_POST['friend_id'];

	if ( 'invite' == $_POST['friend_action'] ) {
		$group = groups_get_group( $group_id );

		// Users who have previously requested membership do not need
		// another invitation created for them
		if ( BP_Groups_Member::check_for_membership_request( $friend_id, $group_id ) ) {
			$user_status = 'is_pending';

		// Create the user invitation
		} else if ( groups_invite_user( array( 'user_id' => $friend_id, 'group_id' => $group_id ) ) ) {
			$user_status = 'is_invited';

		// Miscellaneous failure
		} else {
			return;
		}

		$user = new BP_Core_User( $friend_id );

		echo '<li id="uid-' . $user->id . '">';
		echo $user->avatar_thumb;
		echo '<h4>' . $user->user_link . '</h4>';
		echo '<span class="activity">' . esc_attr( $user->last_active ) . '</span>';
		echo '<div class="action">
				<a class="button remove" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_groups_slug() . '/' . $_POST['group_id'] . '/invites/remove/' . $user->id, 'groups_invite_uninvite_user' ) . '" id="uid-' . esc_attr( $user->id ) . '">' . __( 'Remove Invite', 'buddypress' ) . '</a>
			  </div>';

		if ( 'is_pending' == $user_status ) {
			echo '<p class="description">' . sprintf( __( '%s has previously requested to join this group. Sending an invitation will automatically add the member to the group.', 'buddypress' ), $user->user_link ) . '</p>';
		}

		echo '</li>';
		exit;

	} elseif ( 'uninvite' == $_POST['friend_action'] ) {
		// Users who have previously requested membership should not
		// have their requests deleted on the "uninvite" action
		if ( BP_Groups_Member::check_for_membership_request( $friend_id, $group_id ) ) {
			return;
		}

		// Remove the unsent invitation
		if ( ! groups_uninvite_user( $friend_id, $group_id ) ) {
			return;
		}

		exit;

	} else {
		return;
	}
}

/**
 * Friend/un-friend a user via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_addremove_friend() {

	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Cast fid as an integer
	$friend_id = (int) $_POST['fid'];

	// Trying to cancel friendship
	if ( 'is_friend' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
		check_ajax_referer( 'friends_remove_friend' );

		if ( ! friends_remove_friend( bp_loggedin_user_id(), $friend_id ) ) {
			echo __( 'Friendship could not be canceled.', 'buddypress' );
		} else {
			echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="add" rel="add" title="' . __( 'Add Friend', 'buddypress' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $friend_id, 'friends_add_friend' ) . '">' . __( 'Add Friend', 'buddypress' ) . '</a>';
		}

	// Trying to request friendship
	} elseif ( 'not_friends' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
		check_ajax_referer( 'friends_add_friend' );

		if ( ! friends_add_friend( bp_loggedin_user_id(), $friend_id ) ) {
			echo __(' Friendship could not be requested.', 'buddypress' );
		} else {
			echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="remove" rel="remove" title="' . __( 'Cancel Friendship Request', 'buddypress' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/cancel/' . $friend_id . '/', 'friends_withdraw_friendship' ) . '" class="requested">' . __( 'Cancel Friendship Request', 'buddypress' ) . '</a>';
		}

	// Trying to cancel pending request
	} elseif ( 'pending' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
		check_ajax_referer( 'friends_withdraw_friendship' );

		if ( friends_withdraw_friendship( bp_loggedin_user_id(), $friend_id ) ) {
			echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="add" rel="add" title="' . __( 'Add Friend', 'buddypress' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $friend_id, 'friends_add_friend' ) . '">' . __( 'Add Friend', 'buddypress' ) . '</a>';
		} else {
			echo __("Friendship request could not be cancelled.", 'buddypress');
		}

	// Request already pending
	} else {
		echo __( 'Request Pending', 'buddypress' );
	}

	exit;
}

/**
 * Accept a user friendship request via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_accept_friendship() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_admin_referer( 'friends_accept_friendship' );

	if ( ! friends_accept_friendship( (int) $_POST['id'] ) )
		echo "-1<div id='message' class='error'><p>" . __( 'There was a problem accepting that request. Please try again.', 'buddypress' ) . '</p></div>';

	exit;
}

/**
 * Reject a user friendship request via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_reject_friendship() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_admin_referer( 'friends_reject_friendship' );

	if ( ! friends_reject_friendship( (int) $_POST['id'] ) )
		echo "-1<div id='message' class='error'><p>" . __( 'There was a problem rejecting that request. Please try again.', 'buddypress' ) . '</p></div>';

	exit;
}

/**
 * Join or leave a group when clicking the "join/leave" button via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_joinleave_group() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Cast gid as integer
	$group_id = (int) $_POST['gid'];

	if ( groups_is_user_banned( bp_loggedin_user_id(), $group_id ) )
		return;

	if ( ! $group = groups_get_group( array( 'group_id' => $group_id ) ) )
		return;

	if ( ! groups_is_user_member( bp_loggedin_user_id(), $group->id ) ) {
		if ( 'public' == $group->status ) {
			check_ajax_referer( 'groups_join_group' );

			if ( ! groups_join_group( $group->id ) ) {
				_e( 'Error joining group', 'buddypress' );
			} else {
				echo '<a id="group-' . esc_attr( $group->id ) . '" class="leave-group" rel="leave" title="' . __( 'Leave Group', 'buddypress' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' ) . '">' . __( 'Leave Group', 'buddypress' ) . '</a>';
			}

		} elseif ( 'private' == $group->status ) {

			// If the user has already been invited, then this is
			// an Accept Invitation button
			if ( groups_check_user_has_invite( bp_loggedin_user_id(), $group->id ) ) {
				check_ajax_referer( 'groups_accept_invite' );

				if ( ! groups_accept_invite( bp_loggedin_user_id(), $group->id ) ) {
					_e( 'Error requesting membership', 'buddypress' );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="leave-group" rel="leave" title="' . __( 'Leave Group', 'buddypress' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' ) . '">' . __( 'Leave Group', 'buddypress' ) . '</a>';
				}

			// Otherwise, it's a Request Membership button
			} else {
				check_ajax_referer( 'groups_request_membership' );

				if ( ! groups_send_membership_request( bp_loggedin_user_id(), $group->id ) ) {
					_e( 'Error requesting membership', 'buddypress' );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="membership-requested" rel="membership-requested" title="' . __( 'Membership Requested', 'buddypress' ) . '" href="' . bp_get_group_permalink( $group ) . '">' . __( 'Membership Requested', 'buddypress' ) . '</a>';
				}
			}
		}

	} else {
		check_ajax_referer( 'groups_leave_group' );

		if ( ! groups_leave_group( $group->id ) ) {
			_e( 'Error leaving group', 'buddypress' );
		} elseif ( 'public' == $group->status ) {
			echo '<a id="group-' . esc_attr( $group->id ) . '" class="join-group" rel="join" title="' . __( 'Join Group', 'buddypress' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'join', 'groups_join_group' ) . '">' . __( 'Join Group', 'buddypress' ) . '</a>';
		} elseif ( 'private' == $group->status ) {
			echo '<a id="group-' . esc_attr( $group->id ) . '" class="request-membership" rel="join" title="' . __( 'Request Membership', 'buddypress' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'request-membership', 'groups_send_membership_request' ) . '">' . __( 'Request Membership', 'buddypress' ) . '</a>';
		}
	}

	exit;
}

/**
 * Close and keep closed site wide notices from an admin in the sidebar, via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_close_notice() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset( $_POST['notice_id'] ) ) {
		echo "-1<div id='message' class='error'><p>" . __( 'There was a problem closing the notice.', 'buddypress' ) . '</p></div>';

	} else {
		$user_id      = get_current_user_id();
		$notice_ids   = bp_get_user_meta( $user_id, 'closed_notices', true );
		$notice_ids[] = (int) $_POST['notice_id'];

		bp_update_user_meta( $user_id, 'closed_notices', $notice_ids );
	}

	exit;
}

/**
 * Send a private message reply to a thread via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_messages_send_reply() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_ajax_referer( 'messages_send_message' );

	$result = messages_new_message( array( 'thread_id' => (int) $_REQUEST['thread_id'], 'content' => $_REQUEST['content'] ) );

	if ( !empty( $result ) ) {

		// Get the zebra line classes correct on ajax requests
		global $thread_template;

		bp_thread_has_messages( array( 'thread_id' => (int) $_REQUEST['thread_id'] ) );

		if ( $thread_template->message_count % 2 == 1 ) {
			$class = 'odd';
		} else {
			$class = 'even alt';
		} ?>

		<div class="message-box new-message <?php echo $class; ?>">
			<div class="message-metadata">
				<?php do_action( 'bp_before_message_meta' ); ?>
				<?php echo bp_loggedin_user_avatar( 'type=thumb&width=30&height=30' ); ?>

				<strong><a href="<?php echo bp_loggedin_user_domain(); ?>"><?php bp_loggedin_user_fullname(); ?></a> <span class="activity"><?php printf( __( 'Sent %s', 'buddypress' ), bp_core_time_since( bp_core_current_time() ) ); ?></span></strong>

				<?php do_action( 'bp_after_message_meta' ); ?>
			</div>

			<?php do_action( 'bp_before_message_content' ); ?>

			<div class="message-content">
				<?php echo stripslashes( apply_filters( 'bp_get_the_thread_message_content', $_REQUEST['content'] ) ); ?>
			</div>

			<?php do_action( 'bp_after_message_content' ); ?>

			<div class="clear"></div>
		</div>
	<?php
	} else {
		echo "-1<div id='message' class='error'><p>" . __( 'There was a problem sending that reply. Please try again.', 'buddypress' ) . '</p></div>';
	}

	exit;
}

/**
 * Mark a private message as unread in your inbox via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_message_markunread() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset($_POST['thread_ids']) ) {
		echo "-1<div id='message' class='error'><p>" . __( 'There was a problem marking messages as unread.', 'buddypress' ) . '</p></div>';

	} else {
		$thread_ids = explode( ',', $_POST['thread_ids'] );

		for ( $i = 0, $count = count( $thread_ids ); $i < $count; ++$i ) {
			BP_Messages_Thread::mark_as_unread( (int) $thread_ids[$i] );
		}
	}

	exit;
}

/**
 * Mark a private message as read in your inbox via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_message_markread() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset($_POST['thread_ids']) ) {
		echo "-1<div id='message' class='error'><p>" . __('There was a problem marking messages as read.', 'buddypress' ) . '</p></div>';

	} else {
		$thread_ids = explode( ',', $_POST['thread_ids'] );

		for ( $i = 0, $count = count( $thread_ids ); $i < $count; ++$i ) {
			BP_Messages_Thread::mark_as_read( (int) $thread_ids[$i] );
		}
	}

	exit;
}

/**
 * Delete a private message(s) in your inbox via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_messages_delete() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset($_POST['thread_ids']) ) {
		echo "-1<div id='message' class='error'><p>" . __( 'There was a problem deleting messages.', 'buddypress' ) . '</p></div>';

	} else {
		$thread_ids = explode( ',', $_POST['thread_ids'] );

		for ( $i = 0, $count = count( $thread_ids ); $i < $count; ++$i ) {
			BP_Messages_Thread::delete( (int) $thread_ids[$i] );
		}

		_e( 'Messages deleted.', 'buddypress' );
	}

	exit;
}

/**
 * AJAX handler for autocomplete. Displays friends only, unless BP_MESSAGES_AUTOCOMPLETE_ALL is defined.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_messages_autocomplete_results() {

	// Include everyone in the autocomplete, or just friends?
	if ( bp_is_current_component( bp_get_messages_slug() ) )
		$autocomplete_all = buddypress()->messages->autocomplete_all;

	$pag_page = 1;
	$limit    = (int) $_GET['limit'] ? $_GET['limit'] : apply_filters( 'bp_autocomplete_max_results', 10 );

	// Get the user ids based on the search terms
	if ( ! empty( $autocomplete_all ) ) {
		$users = BP_Core_User::search_users( $_GET['q'], $limit, $pag_page );

		if ( ! empty( $users['users'] ) ) {
			// Build an array with the correct format
			$user_ids = array();
			foreach( $users['users'] as $user ) {
				if ( $user->id != bp_loggedin_user_id() ) {
					$user_ids[] = $user->id;
				}
			}

			$user_ids = apply_filters( 'bp_core_autocomplete_ids', $user_ids, $_GET['q'], $limit );
		}

	} else {
		if ( bp_is_active( 'friends' ) ) {
			$users = friends_search_friends( $_GET['q'], bp_loggedin_user_id(), $limit, 1 );

			// Keeping the bp_friends_autocomplete_list filter for backward compatibility
			$users = apply_filters( 'bp_friends_autocomplete_list', $users, $_GET['q'], $limit );

			if ( ! empty( $users['friends'] ) ) {
				$user_ids = apply_filters( 'bp_friends_autocomplete_ids', $users['friends'], $_GET['q'], $limit );
			}
		}
	}

	if ( ! empty( $user_ids ) ) {
		foreach ( $user_ids as $user_id ) {
			$ud = get_userdata( $user_id );
			if ( ! $ud ) {
				continue;
			}

			if ( bp_is_username_compatibility_mode() ) {
				// Sanitize for spaces. Use urlencode() rather
				// than rawurlencode() because %20 breaks JS
				$username = urlencode( $ud->user_login );
			} else {
				$username = $ud->user_nicename;
			}

			// Note that the final line break acts as a delimiter for the
			// autocomplete javascript and thus should not be removed
			echo '<span id="link-' . esc_attr( $username ) . '" href="' . bp_core_get_user_domain( $user_id ) . '"></span>' . bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 15, 'height' => 15, 'alt' => $ud->display_name ) ) . ' &nbsp;' . bp_core_get_user_displayname( $user_id ) . ' (' . esc_html( $username ) . ')' . "\n";
		}
	}

	exit;
}
