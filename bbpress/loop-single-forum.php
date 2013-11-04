<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<li class="list-group-item">
	<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>

		<li class="bbp-forum-info col-md-5">
			<?php do_action( 'bbp_theme_before_forum_title' ); ?>
			<a class="bbp-forum-title lead" href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a>
			<?php do_action( 'bbp_theme_after_forum_title' ); ?>
			<?php do_action( 'bbp_theme_before_forum_description' ); ?>
			<div class="bbp-forum-content"><?php bbp_forum_content(); ?></div>
			<?php do_action( 'bbp_theme_after_forum_description' ); ?>
			<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>
			<?php bbp_list_forums(); ?>
			<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>
			<?php bbp_forum_row_actions(); ?>
		</li>

		<li class="bbp-forum-topic-count col-md-2 text-center"><?php bbp_forum_topic_count(); ?></li>

		<li class="bbp-forum-reply-count col-md-2 text-center"><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li>

		<li class="bbp-forum-freshness col-md-3 text-right">
			<?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>
			<?php bbp_forum_freshness_link(); ?>
			<?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>
			<p class="bbp-topic-meta">
				<?php do_action( 'bbp_theme_before_topic_author' ); ?>
				<span class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 14 ) ); ?></span>
				<?php do_action( 'bbp_theme_after_topic_author' ); ?>
			</p>
		</li>

	</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
</li>