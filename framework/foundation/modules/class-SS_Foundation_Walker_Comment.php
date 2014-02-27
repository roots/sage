<?php
/**
 * Use Bootstrap's media object for listing comments
 *
 * @link http://getbootstrap.com/components/#media
 */
class SS_Foundation_Walker_Comment extends Walker_Comment {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<ul <?php comment_class( 'no-bullet comment-' . get_comment_ID() ); ?>>
		<?php
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;
		echo '</ul>';
	}

	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( ! empty( $args['callback'] ) ) {
			call_user_func( $args['callback'], $comment, $args, $depth );
			return;
		}

		extract( $args, EXTR_SKIP ); ?>

	<hr><li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-' . get_comment_ID() ); ?>>
		<?php include( ss_locate_template( 'templates/comment.php' ) ); ?>
	<?php
	}

	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( ! empty( $args['end-callback'] ) ) {
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			return;
		}
		echo "</div></li>";
	}
}

function shoestrap_get_avatar( $avatar, $type ) {
	if ( ! is_object( $type ) ) { return $avatar; }

	$avatar = str_replace( "class='avatar", "class='avatar left th", $avatar );
	return $avatar;
}
add_filter( 'get_avatar', 'shoestrap_get_avatar', 10, 2 );

/**
 * Display or retrieve edit comment link with formatting.
 *
 * @since 1.0.0
 *
 * @param string $link Optional. Anchor text.
 * @param string $before Optional. Display before edit link.
 * @param string $after Optional. Display after edit link.
 * @return string|null HTML content, if $echo is set to false.
 */
function ss_foundation_edit_comment_link( $link = null, $before = '', $after = '' ) {
	global $comment;

	if ( !current_user_can( 'edit_comment', $comment->comment_ID ) ) {
		return;
	}

	if ( null === $link ) {
		$link = __('Edit This');
	}

	$link = '<a class="comment-edit-link button tiny alert" href="' . get_edit_comment_link( $comment->comment_ID ) . '">' . $link . '</a>';
	echo $before . apply_filters( 'edit_comment_link', $link, $comment->comment_ID ) . $after;
}
