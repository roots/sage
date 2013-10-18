<?php

/**
 * Replies Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_replies_loop' ); ?>

<ul id="topic-<?php bbp_topic_id(); ?>-replies" class="forums bbp-replies list-unstyled">

	<li class="bbp-header well well-sm">
		<div class="row">
			<div class="bbp-reply-author col-md-6"><?php  _e( 'Author',  'bbpress' ); ?></div><!-- .bbp-reply-author -->
			<div class="bbp-reply-content col-md-6">
				<?php if ( !bbp_show_lead_topic() ) : ?>
					<div class="pull-right">
						<?php shoestrap_bbp_user_subscribe_link( array( 'before' => '' ) ); ?>
						<?php shoestrap_bbp_user_favorites_link(); ?>
					</div>
				<?php else : ?>
					<?php _e( 'Replies', 'bbpress' ); ?>
				<?php endif; ?>
			</div><!-- .bbp-reply-content -->
		</div>
	</li><!-- .bbp-header -->

	<li class="bbp-body">
		<?php if ( bbp_thread_replies() ) : ?>
			<?php bbp_list_replies(); ?>
		<?php else : ?>
			<?php while ( bbp_replies() ) : bbp_the_reply(); ?>
				<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>
			<?php endwhile; ?>
		<?php endif; ?>
	</li><!-- .bbp-body -->

</ul><!-- #topic-<?php bbp_topic_id(); ?>-replies -->

<?php do_action( 'bbp_template_after_replies_loop' ); ?>
