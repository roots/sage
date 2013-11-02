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
		.bbp-reply-author img { border-radius: 50%; }
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

