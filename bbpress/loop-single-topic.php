<?php

/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<li class="list-group-item">
	<div class="support-status <?php echo shoestrap_bbps_get_topic_status(); ?>"></div>
	<ul id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

		<li class="col-md-1">
			<?php do_action( 'bbp_theme_before_topic_started_by' ); ?>
			<span class="bbp-topic-started-by-avatar-avatar"><?php echo bbp_get_topic_author_avatar(); ?></span>
			<?php do_action( 'bbp_theme_after_topic_started_by' ); ?>
		</li>

		<li class="bbp-topic-title col-md-5">
			<?php if ( bbp_is_user_home() ) : ?>
				<?php if ( bbp_is_favorites() ) : ?>
					<span class="bbp-topic-action">
						<?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>
						<?php bbp_user_favorites_link( array( 'before' => '', 'favorite' => '+', 'favorited' => '&times;' ) ); ?>
						<?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>
					</span>
				<?php elseif ( bbp_is_subscriptions() ) : ?>
					<span class="bbp-topic-action">
						<?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>
						<?php bbp_user_subscribe_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>
						<?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>
			<?php do_action( 'bbp_theme_before_topic_title' ); ?>
			<a class="bbp-topic-permalink lead" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>
			<!-- <hr /> -->
			<?php do_action( 'bbp_theme_after_topic_title' ); ?>
			<?php bbp_topic_pagination(); ?>
			<?php do_action( 'bbp_theme_before_topic_meta' ); ?>
			<p class="bbp-topic-meta">
				<?php if ( !bbp_is_single_forum() || ( bbp_get_topic_forum_id() !== bbp_get_forum_id() ) ) : ?>
					<?php do_action( 'bbp_theme_before_topic_started_in' ); ?>
					<span class="bbp-topic-started-in"><?php printf( __( 'in: <a href="%1$s">%2$s</a>', 'bbpress' ), bbp_get_forum_permalink( bbp_get_topic_forum_id() ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?></span>
					<?php do_action( 'bbp_theme_after_topic_started_in' ); ?>
				<?php endif; ?>
			</p>
			<?php do_action( 'bbp_theme_after_topic_meta' ); ?>
			<?php bbp_topic_row_actions(); ?>
		</li>

		<li class="bbp-topic-voice-count col-md-2 text-center"><?php bbp_topic_voice_count(); ?></li>

		<li class="bbp-topic-reply-count col-md-2 text-center"><?php bbp_show_lead_topic() ? bbp_topic_reply_count() : bbp_topic_post_count(); ?></li>

		<li class="bbp-topic-freshness col-md-2 text-right small">
			<?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>
			<?php bbp_topic_freshness_link(); ?>
			<?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>
			<!-- <hr /> -->
			<p class="bbp-topic-meta">
				<?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>
				<span class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'size' => 20, 'link_title' => '' ) ); ?></span>
				<?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>
			</p>
		</li>

	</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?> -->
</li>
