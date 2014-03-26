<?php
/**
 * Use Bootstrap's media object for listing comments
 *
 * @link http://getbootstrap.com/components/#media
 */
class Shoestrap_Walker_Comment extends Walker_Comment {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<ul <?php comment_class( 'media list-unstyled comment-' . get_comment_ID() ); ?>>
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

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'media comment-' . get_comment_ID() ); ?>>
		<?php include( locate_template( 'templates/comment.php' ) ); ?>
	<?php
	}

	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( ! empty( $args['end-callback'] ) ) {
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			return;
		}
		echo "</div></li>\n";
	}
}

function shoestrap_get_avatar( $avatar, $type ) {
	if ( ! is_object( $type ) ) { return $avatar; }

	$avatar = str_replace( "class='avatar", "class='avatar pull-left media-object", $avatar );
	return $avatar;
}
add_filter( 'get_avatar', 'shoestrap_get_avatar', 10, 2 );
