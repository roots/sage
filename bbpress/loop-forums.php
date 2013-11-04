<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_forums_loop' ); ?>

<ul id="forums-list-<?php bbp_forum_id(); ?>" class="bbp-forums list-group">

	<li class="bbp-header list-group-item">

		<ul class="forum-titles row list-unstyled">
			<li class="bbp-forum-info col-md-5"><?php _e( 'Forum', 'bbpress' ); ?></li>
			<li class="bbp-forum-topic-count col-md-2 text-center"><?php _e( 'Topics', 'bbpress' ); ?></li>
			<li class="bbp-forum-reply-count col-md-2 text-center"><?php bbp_show_lead_topic() ? _e( 'Replies', 'bbpress' ) : _e( 'Posts', 'bbpress' ); ?></li>
			<li class="bbp-forum-freshness col-md-3 text-right"><?php _e( 'Freshness', 'bbpress' ); ?></li>
		</ul>

	</li><!-- .bbp-header -->

	<?php while ( bbp_forums() ) : bbp_the_forum(); ?>
		<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>
	<?php endwhile; ?>

</ul><!-- .forums-directory -->

<?php do_action( 'bbp_template_after_forums_loop' );