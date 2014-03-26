<?php

function shoestrap_bbp_forum_class( $classes ) {
	$classes[] = 'row';
	$classes[] = 'list-unstyled';

	return $classes;
}
add_filter('bbp_get_forum_class', 'shoestrap_bbp_forum_class');

function shoestrap_bbp_topic_class( $classes ) {
	$classes[] = 'row';
	$classes[] = 'list-unstyled';
	$classes[] = shoestrap_bbps_get_topic_status();

	return $classes;
}
add_filter('bbp_get_topic_class', 'shoestrap_bbp_topic_class');

function shoestrap_bbp_reply_class( $classes ) {
	$classes[] = 'row';
	$classes[] = 'list-unstyled';

	return $classes;
}
add_filter('bbp_get_reply_class', 'shoestrap_bbp_reply_class');

function shoestrap_bbp_styles() { ?>
	<style type="text/css">
		a.bbp-author-avatar { display: inline-block; }
		.bbp-reply-author img,
		.bbp-topic-started-by-avatar-avatar img,
		.bbp-topic-freshness-author img  { border-radius: 50%; }
		.bbp-topic-freshness-author a { display: inline-block; }
		.bbp-topic-freshness-author a.bbp-author-name { display: none; }
		.bbp-topic-freshness-author p.bbp-topic-meta { display: inline-block; }
	</style>
	<?php
}
add_action( 'wp_head', 'shoestrap_bbp_styles' );

remove_action( 'wp_enqueue_scripts', 'bbp_enqueue_scripts', 10 );


/**
 * Displays topic type select box (normal/sticky/super sticky)
 *
 * @since bbPress (r5059)
 *
 * @param $args This function supports these arguments:
 *  - select_id: Select id. Defaults to bbp_stick_topic
 *  - tab: Tabindex
 *  - topic_id: Topic id
 *  - selected: Override the selected option
 */
function shoestrap_bbp_form_topic_type_dropdown( $args = '' ) {
	echo shoestrap_bbp_get_form_topic_type_dropdown( $args );
}
	/**
	 * Returns topic type select box (normal/sticky/super sticky)
	 *
	 * @since bbPress (r5059)
	 *
	 * @param $args This function supports these arguments:
	 *  - select_id: Select id. Defaults to bbp_stick_topic
	 *  - tab: Tabindex
	 *  - topic_id: Topic id
	 *  - selected: Override the selected option
	 * @uses bbp_get_topic_id() To get the topic id
	 * @uses bbp_is_single_topic() To check if we're viewing a single topic
	 * @uses bbp_is_topic_edit() To check if it is the topic edit page
	 * @uses bbp_is_topic_super_sticky() To check if the topic is a super sticky
	 * @uses bbp_is_topic_sticky() To check if the topic is a sticky
	 */
	function shoestrap_bbp_get_form_topic_type_dropdown( $args = '' ) {

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'select_id'    => 'bbp_stick_topic',
			'tab'          => bbp_get_tab_index(),
			'topic_id'     => 0,
			'selected'     => false
		), 'topic_type_select' );

		// No specific selected value passed
		if ( empty( $r['selected'] ) ) {

			// Post value is passed
			if ( bbp_is_post_request() && isset( $_POST[ $r['select_id'] ] ) ) {
				$r['selected'] = $_POST[ $r['select_id'] ];

			// No Post value passed
			} else {

				// Edit topic
				if ( bbp_is_single_topic() || bbp_is_topic_edit() ) {

					// Get current topic id
					$topic_id = bbp_get_topic_id( $r['topic_id'] );

					// Topic is super sticky
					if ( bbp_is_topic_super_sticky( $topic_id ) ) {
						$r['selected'] = 'super';

					// Topic is sticky or normal
					} else {
						$r['selected'] = bbp_is_topic_sticky( $topic_id, false ) ? 'stick' : 'unstick';
					}
				}
			}
		}

		// Used variables
		$tab = !empty( $r['tab'] ) ? ' tabindex="' . (int) $r['tab'] . '"' : '';

		// Start an output buffer, we'll finish it after the select loop
		ob_start(); ?>

		<select class="form-control" name="<?php echo esc_attr( $r['select_id'] ); ?>" id="<?php echo esc_attr( $r['select_id'] ); ?>_select"<?php echo $tab; ?>>

			<?php foreach ( bbp_get_topic_types() as $key => $label ) : ?>

				<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $r['selected'] ); ?>><?php echo esc_html( $label ); ?></option>

			<?php endforeach; ?>

		</select>

		<?php

		// Return the results
		return apply_filters( 'bbp_get_form_topic_type_dropdown', ob_get_clean(), $r );
	}


/**
 * Output value topic status dropdown
 *
 * @since bbPress (r5059)
 *
 * @param int $topic_id The topic id to use
 */
function shoestrap_bbp_form_topic_status_dropdown( $args = '' ) {
	echo shoestrap_bbp_get_form_topic_status_dropdown( $args );
}
	/**
	 * Returns topic status downdown
	 *
	 * This dropdown is only intended to be seen by users with the 'moderate'
	 * capability. Because of this, no additional capablitiy checks are performed
	 * within this function to check available topic statuses.
	 *
	 * @since bbPress (r5059)
	 *
	 * @param $args This function supports these arguments:
	 *  - select_id: Select id. Defaults to bbp_open_close_topic
	 *  - tab: Tabindex
	 *  - topic_id: Topic id
	 *  - selected: Override the selected option
	 */
	function shoestrap_bbp_get_form_topic_status_dropdown( $args = '' ) {

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'select_id' => 'bbp_topic_status',
			'tab'       => bbp_get_tab_index(),
			'topic_id'  => 0,
			'selected'  => false
		), 'topic_open_close_select' );

		// No specific selected value passed
		if ( empty( $r['selected'] ) ) {

			// Post value is passed
			if ( bbp_is_post_request() && isset( $_POST[ $r['select_id'] ] ) ) {
				$r['selected'] = $_POST[ $r['select_id'] ];

			// No Post value was passed
			} else {

				// Edit topic
				if ( bbp_is_topic_edit() ) {
					$r['topic_id'] = bbp_get_topic_id( $r['topic_id'] );
					$r['selected'] = bbp_get_topic_status( $r['topic_id'] );

				// New topic
				} else {
					$r['selected'] = bbp_get_public_status_id();
				}
			}
		}

		// Used variables
		$tab = ! empty( $r['tab'] ) ? ' tabindex="' . (int) $r['tab'] . '"' : '';

		// Start an output buffer, we'll finish it after the select loop
		ob_start(); ?>

		<select class="form-control" name="<?php echo esc_attr( $r['select_id'] ) ?>" id="<?php echo esc_attr( $r['select_id'] ); ?>_select"<?php echo $tab; ?>>

			<?php foreach ( bbp_get_topic_statuses( $r['topic_id'] ) as $key => $label ) : ?>

				<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $r['selected'] ); ?>><?php echo esc_html( $label ); ?></option>

			<?php endforeach; ?>

		</select>

		<?php

		// Return the results
		return apply_filters( 'bbp_get_form_topic_status_dropdown', ob_get_clean(), $r );
	}

/**
 * Output the link to subscribe/unsubscribe from a topic
 *
 * @since bbPress (r2668)
 *
 * @param mixed $args See {@link bbp_get_user_subscribe_link()}
 * @param int $user_id Optional. User id
 * @param bool $wrap Optional. If you want to wrap the link in <span id="subscription-toggle">.
 * @uses bbp_get_user_subscribe_link() To get the subscribe link
 */
function shoestrap_bbp_user_subscribe_link( $args = '', $user_id = 0, $wrap = true ) {
	echo shoestrap_bbp_get_user_subscribe_link( $args, $user_id, $wrap );
}
	/**
	 * Return the link to subscribe/unsubscribe from a topic
	 *
	 * @since bbPress (r2668)
	 *
	 * @param mixed $args This function supports these arguments:
	 *  - subscribe: Subscribe text
	 *  - unsubscribe: Unsubscribe text
	 *  - user_id: User id
	 *  - topic_id: Topic id
	 *  - before: Before the link
	 *  - after: After the link
	 * @param int $user_id Optional. User id
	 * @param bool $wrap Optional. If you want to wrap the link in <span id="subscription-toggle">.
	 * @uses bbp_get_user_id() To get the user id
	 * @uses current_user_can() To check if the current user can edit user
	 * @uses bbp_get_topic_id() To get the topic id
	 * @uses bbp_is_user_subscribed() To check if the user is subscribed
	 * @uses bbp_is_subscriptions() To check if it's the subscriptions page
	 * @uses bbp_get_subscriptions_permalink() To get subscriptions link
	 * @uses bbp_get_topic_permalink() To get topic link
	 * @uses apply_filters() Calls 'bbp_get_user_subscribe_link' with the
	 *                        link, args, user id & topic id
	 * @return string Permanent link to topic
	 */
	function shoestrap_bbp_get_user_subscribe_link( $args = '', $user_id = 0, $wrap = true ) {
		if ( !bbp_is_subscriptions_active() )
			return;

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'subscribe'   => __( 'Subscribe',   'bbpress' ),
			'unsubscribe' => __( 'Unsubscribe', 'bbpress' ),
			'user_id'     => 0,
			'topic_id'    => 0,
			'before'      => '&nbsp;|&nbsp;',
			'after'       => ''
		), 'get_user_subscribe_link' );

		// Validate user and topic ID's
		$user_id  = bbp_get_user_id( $r['user_id'], true, true );
		$topic_id = bbp_get_topic_id( $r['topic_id'] );
		if ( empty( $user_id ) || empty( $topic_id ) ) {
			return false;
		}

		// No link if you can't edit yourself
		if ( !current_user_can( 'edit_user', (int) $user_id ) ) {
			return false;
		}

		// Decide which link to show
		$is_subscribed = bbp_is_user_subscribed( $user_id, $topic_id );
		if ( !empty( $is_subscribed ) ) {
			$text       = $r['unsubscribe'];
			$query_args = array( 'action' => 'bbp_unsubscribe', 'topic_id' => $topic_id );
		} else {
			$text       = $r['subscribe'];
			$query_args = array( 'action' => 'bbp_subscribe', 'topic_id' => $topic_id );
		}

		// Create the link based where the user is and if the user is
		// subscribed already
		if ( bbp_is_subscriptions() ) {
			$permalink = bbp_get_subscriptions_permalink( $user_id );
		} elseif ( bbp_is_single_topic() || bbp_is_single_reply() ) {
			$permalink = bbp_get_topic_permalink( $topic_id );
		} else {
			$permalink = get_permalink();
		}

		$url  = esc_url( wp_nonce_url( add_query_arg( $query_args, $permalink ), 'toggle-subscription_' . $topic_id ) );
		$sub  = $is_subscribed ? ' class="is-subscribed"' : '';
		$html = sprintf( '%s<span id="subscribe-%d"  %s><a href="%s" class="btn btn-warning btn-xs subscription-toggle" data-topic="%d">%s</a></span>%s', $r['before'], $topic_id, $sub, $url, $topic_id, $text, $r['after'] );

		// Initial output is wrapped in a span, ajax output is hooked to this
		if ( !empty( $wrap ) ) {
			$html = '<span id="subscription-toggle">' . $html . '</span>';
		}

		// Return the link
		return apply_filters( 'bbp_get_user_subscribe_link', $html, $r, $user_id, $topic_id );
	}

/**
 * Output the link to make a topic favorite/remove a topic from favorites
 *
 * @since bbPress (r2652)
 *
 * @param mixed $args See {@link bbp_get_user_favorites_link()}
 * @param int $user_id Optional. User id
 * @param bool $wrap Optional. If you want to wrap the link in <span id="favorite-toggle">.
 * @uses bbp_get_user_favorites_link() To get the user favorites link
 */
function shoestrap_bbp_user_favorites_link( $args = array(), $user_id = 0, $wrap = true ) {
	echo shoestrap_bbp_get_user_favorites_link( $args, $user_id, $wrap );
}
	/**
	 * User favorites link
	 *
	 * Return the link to make a topic favorite/remove a topic from
	 * favorites
	 *
	 * @since bbPress (r2652)
	 *
	 * @param mixed $args This function supports these arguments:
	 *  - subscribe: Favorite text
	 *  - unsubscribe: Unfavorite text
	 *  - user_id: User id
	 *  - topic_id: Topic id
	 *  - before: Before the link
	 *  - after: After the link
	 * @param int $user_id Optional. User id
	 * @param int $topic_id Optional. Topic id
	 * @param bool $wrap Optional. If you want to wrap the link in <span id="favorite-toggle">. See ajax_favorite()
	 * @uses bbp_get_user_id() To get the user id
	 * @uses current_user_can() If the current user can edit the user
	 * @uses bbp_get_topic_id() To get the topic id
	 * @uses bbp_is_user_favorite() To check if the topic is user's favorite
	 * @uses bbp_get_favorites_permalink() To get the favorites permalink
	 * @uses bbp_get_topic_permalink() To get the topic permalink
	 * @uses bbp_is_favorites() Is it the favorites page?
	 * @uses apply_filters() Calls 'bbp_get_user_favorites_link' with the
	 *                        html, add args, remove args, user & topic id
	 * @return string User favorites link
	 */
	function shoestrap_bbp_get_user_favorites_link( $args = '', $user_id = 0, $wrap = true ) {
		if ( !bbp_is_favorites_active() )
			return false;

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'favorite'  => __( 'Favorite',  'bbpress' ),
			'favorited' => __( 'Favorited', 'bbpress' ),
			'user_id'   => 0,
			'topic_id'  => 0,
			'before'    => '',
			'after'     => ''
		), 'get_user_favorites_link' );

		// Validate user and topic ID's
		$user_id  = bbp_get_user_id( $r['user_id'], true, true );
		$topic_id = bbp_get_topic_id( $r['topic_id'] );
		if ( empty( $user_id ) || empty( $topic_id ) ) {
			return false;
		}

		// No link if you can't edit yourself
		if ( !current_user_can( 'edit_user', (int) $user_id ) ) {
			return false;
		}

		// Decide which link to show
		$is_fav = bbp_is_user_favorite( $user_id, $topic_id );
		if ( !empty( $is_fav ) ) {
			$text       = $r['favorited'];
			$query_args = array( 'action' => 'bbp_favorite_remove', 'topic_id' => $topic_id );
		} else {
			$text       = $r['favorite'];
			$query_args = array( 'action' => 'bbp_favorite_add',    'topic_id' => $topic_id );
		}

		// Create the link based where the user is and if the topic is
		// already the user's favorite
		if ( bbp_is_favorites() ) {
			$permalink = bbp_get_favorites_permalink( $user_id );
		} elseif ( bbp_is_single_topic() || bbp_is_single_reply() ) {
			$permalink = bbp_get_topic_permalink( $topic_id );
		} else {
			$permalink = get_permalink();
		}

		$url  = esc_url( wp_nonce_url( add_query_arg( $query_args, $permalink ), 'toggle-favorite_' . $topic_id ) );
		$sub  = $is_fav ? ' class="is-favorite"' : '';
		$html = sprintf( '%s<span id="favorite-%d"  %s><a href="%s" class="btn btn-success btn-xs favorite-toggle" data-topic="%d">%s</a></span>%s', $r['before'], $topic_id, $sub, $url, $topic_id, $text, $r['after'] );

		// Initial output is wrapped in a span, ajax output is hooked to this
		if ( !empty( $wrap ) ) {
			$html = '<span id="favorite-toggle">' . $html . '</span>';
		}

		// Return the link
		return apply_filters( 'bbp_get_user_favorites_link', $html, $r, $user_id, $topic_id );
	}


if ( function_exists( 'bbps_add_support_forum_features' ) ) :
remove_action('bbp_template_before_single_topic', 'bbps_add_support_forum_features');
add_action('bbp_template_before_single_topic', 'shoestrap_bbps_add_support_forum_features');
function shoestrap_bbps_add_support_forum_features(){	
	//only display all this stuff if the support forum option has been selected.
	if (bbps_is_support_forum(bbp_get_forum_id())){
		$can_edit = bbps_get_update_capabilities();
		$topic_id = bbp_get_topic_id();
		$status = bbps_get_topic_status($topic_id);
		$forum_id = bbp_get_forum_id();
		$user_id = get_current_user_id();
		
		
		?> <div id="bbps_support_forum_options"> <?php
		//get out the option to tell us who is allowed to view and update the drop down list.
		if ( $can_edit == true ){ ?>
			<?php bbps_generate_status_options($topic_id,$status);
		}else{
		?>
			This topic is: <?php echo $status ;
		}
		?> </div> <?php
		//has the user enabled the move topic feature?
		if( (get_option('_bbps_enable_topic_move') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator')) ) { 
		?>
		<div id ="bbps_support_forum_move">
			<form id="bbps-topic-move" class="form-horizontal" role="form" name="bbps_support_topic_move" action="" method="post">
				<div class="form-group">
					<label for="bbp_forum_id" class="control-label">Move topic to: </label><?php shoestrap_bbp_dropdown(); ?>
					<input type="hidden" value="bbps_move_topic" name="bbps_action"/>
					<input type="hidden" value="<?php echo $topic_id ?>" name="bbps_topic_id" />
					<input type="hidden" value="<?php echo $forum_id ?>" name="bbp_old_forum_id" />
				</div>
				<input type="submit" value="Move" name="bbps_topic_move_submit" class="btn btn-default" />
			</form>
		</div>  <?php
			
		}
	}
}
endif;

/**
 * Output a select box allowing to pick which forum/topic a new topic/reply
 * belongs in.
 *
 * Can be used for any post type, but is mostly used for topics and forums.
 *
 * @since bbPress (r2746)
 *
 * @param mixed $args See {@link bbp_get_dropdown()} for arguments
 */
function shoestrap_bbp_dropdown( $args = '' ) {
	echo shoestrap_bbp_get_dropdown( $args );
}
	/**
	 * Output a select box allowing to pick which forum/topic a new
	 * topic/reply belongs in.
	 *
	 * @since bbPress (r2746)
	 *
	 * @param mixed $args The function supports these args:
	 *  - post_type: Post type, defaults to bbp_get_forum_post_type() (bbp_forum)
	 *  - selected: Selected ID, to not have any value as selected, pass
	 *               anything smaller than 0 (due to the nature of select
	 *               box, the first value would of course be selected -
	 *               though you can have that as none (pass 'show_none' arg))
	 *  - orderby: Defaults to 'menu_order title'
	 *  - post_parent: Post parent. Defaults to 0
	 *  - post_status: Which all post_statuses to find in? Can be an array
	 *                  or CSV of publish, category, closed, private, spam,
	 *                  trash (based on post type) - if not set, these are
	 *                  automatically determined based on the post_type
	 *  - posts_per_page: Retrieve all forums/topics. Defaults to -1 to get
	 *                     all posts
	 *  - walker: Which walker to use? Defaults to
	 *             {@link BBP_Walker_Dropdown}
	 *  - select_id: ID of the select box. Defaults to 'bbp_forum_id'
	 *  - tab: Tabindex value. False or integer
	 *  - options_only: Show only <options>? No <select>?
	 *  - show_none: False or something like __( '(No Forum)', 'bbpress' ),
	 *                will have value=""
	 *  - none_found: False or something like
	 *                 __( 'No forums to post to!', 'bbpress' )
	 *  - disable_categories: Disable forum categories and closed forums?
	 *                         Defaults to true. Only for forums and when
	 *                         the category option is displayed.
	 * @uses BBP_Walker_Dropdown() As the default walker to generate the
	 *                              dropdown
	 * @uses current_user_can() To check if the current user can read
	 *                           private forums
	 * @uses bbp_get_forum_post_type() To get the forum post type
	 * @uses bbp_get_topic_post_type() To get the topic post type
	 * @uses walk_page_dropdown_tree() To generate the dropdown using the
	 *                                  walker
	 * @uses apply_filters() Calls 'bbp_get_dropdown' with the dropdown
	 *                        and args
	 * @return string The dropdown
	 */
	function shoestrap_bbp_get_dropdown( $args = '' ) {

		/** Arguments *********************************************************/

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'post_type'          => bbp_get_forum_post_type(),
			'post_parent'        => null,
			'post_status'        => null,
			'selected'           => 0,
			'exclude'            => array(),
			'numberposts'        => -1,
			'orderby'            => 'menu_order title',
			'order'              => 'ASC',
			'walker'             => '',

			// Output-related
			'select_id'          => 'bbp_forum_id',
			'tab'                => bbp_get_tab_index(),
			'options_only'       => false,
			'show_none'          => false,
			'none_found'         => false,
			'disable_categories' => true,
			'disabled'           => ''
		), 'get_dropdown' );

		if ( empty( $r['walker'] ) ) {
			$r['walker']            = new BBP_Walker_Dropdown();
			$r['walker']->tree_type = $r['post_type'];
		}

		// Force 0
		if ( is_numeric( $r['selected'] ) && $r['selected'] < 0 ) {
			$r['selected'] = 0;
		}

		// Force array
		if ( !empty( $r['exclude'] ) && !is_array( $r['exclude'] ) ) {
			$r['exclude'] = explode( ',', $r['exclude'] );
		}

		/** Setup variables ***************************************************/

		$retval = '';
		$posts  = get_posts( array(
			'post_type'          => $r['post_type'],
			'post_status'        => $r['post_status'],
			'exclude'            => $r['exclude'],
			'post_parent'        => $r['post_parent'],
			'numberposts'        => $r['numberposts'],
			'orderby'            => $r['orderby'],
			'order'              => $r['order'],
			'walker'             => $r['walker'],
			'disable_categories' => $r['disable_categories']
		) );

		/** Drop Down *********************************************************/

		// Items found
		if ( !empty( $posts ) ) {

			// Build the opening tag for the select element
			if ( empty( $r['options_only'] ) ) {

				// Should this select appear disabled?
				$disabled  = disabled( isset( bbpress()->options[ $r['disabled'] ] ), true, false );

				// Setup the tab index attribute
				$tab       = !empty( $r['tab'] ) ? ' tabindex="' . intval( $r['tab'] ) . '"' : '';

				// Build the opening tag
				$retval   .= '<select class="form-control" name="' . esc_attr( $r['select_id'] ) . '" id="' . esc_attr( $r['select_id'] ) . '"' . $disabled . $tab . '>' . "\n";
			}

			// Get the options
			$retval .= !empty( $r['show_none'] ) ? "\t<option value=\"\" class=\"level-0\">" . esc_html( $r['show_none'] ) . '</option>' : '';
			$retval .= walk_page_dropdown_tree( $posts, 0, $r );

			// Build the closing tag for the select element
			if ( empty( $r['options_only'] ) ) {
				$retval .= '</select>';
			}

		// No items found - Display feedback if no custom message was passed
		} elseif ( empty( $r['none_found'] ) ) {

			// Switch the response based on post type
			switch ( $r['post_type'] ) {

				// Topics
				case bbp_get_topic_post_type() :
					$retval = __( 'No topics available', 'bbpress' );
					break;

				// Forums
				case bbp_get_forum_post_type() :
					$retval = __( 'No forums available', 'bbpress' );
					break;

				// Any other
				default :
					$retval = __( 'None available', 'bbpress' );
					break;
			}
		}

		return apply_filters( 'bbp_get_dropdown', $retval, $r );
	}


function shoestrap_bbps_get_topic_status() {
	$topic_id = bbp_get_topic_id();
	$default = get_option( '_bbps_default_status' );
	$status = get_post_meta( $topic_id, '_bbps_topic_status', true );
	//to do not hard code these if we let the users add their own satus
	if ($status)
		$switch = $status;
	else
		$switch = $default;
		
	switch( $switch ) {
		case 1:
			return "unresolved";
			break;
		case 2:
			return "resolved";
			break;
		case 3:
			return "not-support";
			break;
	}
}

remove_action('bbp_theme_before_topic_title', 'bbps_modify_title');

