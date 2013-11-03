<?php

/**
 * Topics Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_topics_loop' ); ?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics list-group">

	<li class="bbp-header list-group-item">

		<ul class="forum-titles row list-unstyled">
			<li class="bbp-topic-title col-md-5"><?php _e( 'Topic', 'bbpress' ); ?></li>
			<li class="bbp-topic-voice-count col-md-2 text-center"><?php _e( 'Voices', 'bbpress' ); ?></li>
			<li class="bbp-topic-reply-count col-md-2 text-center"><?php bbp_show_lead_topic() ? _e( 'Replies', 'bbpress' ) : _e( 'Posts', 'bbpress' ); ?></li>
			<li class="bbp-topic-freshness col-md-3 text-right"><?php _e( 'Freshness', 'bbpress' ); ?></li>
		</ul>

	</li>

	<li class="bbp-body list-group-item">
		<?php while ( bbp_topics() ) : bbp_the_topic(); ?>
			<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>
		<?php endwhile; ?>
	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->

<?php do_action( 'bbp_template_after_topics_loop' );