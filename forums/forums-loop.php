<?php

/**
 * BuddyPress - Forums Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_forums_loop' ); ?>

<?php if ( bp_has_forum_topics( bp_ajax_querystring( 'forums' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="topic-count-top">

			<?php bp_forum_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="topic-pag-top">

			<?php bp_forum_pagination(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_forums_list' ); ?>

	<table class="forum">
		<thead>
			<tr>
				<th id="th-title"><?php _e( 'Topic', 'buddypress' ); ?></th>
				<th id="th-postcount"><?php _e( 'Posts', 'buddypress' ); ?></th>
				<th id="th-freshness"><?php _e( 'Freshness', 'buddypress' ); ?></th>

				<?php do_action( 'bp_directory_forums_extra_cell_head' ); ?>

			</tr>
		</thead>

		<tbody>

			<?php while ( bp_forum_topics() ) : bp_the_forum_topic(); ?>

			<tr class="<?php bp_the_topic_css_class(); ?>">
				<td class="td-title">
					<a class="topic-title" href="<?php bp_the_topic_permalink(); ?>" title="<?php bp_the_topic_title(); ?> - <?php _e( 'Permalink', 'buddypress' ); ?>">

						<?php bp_the_topic_title(); ?>

					</a>

					<p class="topic-meta">
						<span class="topic-by"><?php /* translators: "started by [poster] in [forum]" */ printf( __( 'Started by %1$s', 'buddypress' ), bp_get_the_topic_poster_avatar( 'height=20&width=20') . bp_get_the_topic_poster_name() ); ?></span>

						<?php if ( !bp_is_group_forum() ) : ?>

							<span class="topic-in">

								<?php
									$topic_in = '<a href="' . bp_get_the_topic_object_permalink() . '">' . bp_get_the_topic_object_avatar( 'type=thumb&width=20&height=20' ) . '</a>' .
													'<a href="' . bp_get_the_topic_object_permalink() . '" title="' . bp_get_the_topic_object_name() . '">' . bp_get_the_topic_object_name() .'</a>';

									/* translators: "started by [poster] in [forum]" */
									printf( __( 'in %1$s', 'buddypress' ), $topic_in );
								?>

							</span>

						<?php endif; ?>

					</p>
				</td>
				<td class="td-postcount">
					<?php bp_the_topic_total_posts(); ?>
				</td>
				<td class="td-freshness">
					<span class="time-since"><?php bp_the_topic_time_since_last_post(); ?></span>
					<p class="topic-meta">
						<span class="freshness-author">
							<a href="<?php bp_the_topic_permalink(); ?>"><?php bp_the_topic_last_poster_avatar( 'type=thumb&width=20&height=20' ); ?></a>
							<?php bp_the_topic_last_poster_name(); ?>
						</span>
					</p>
				</td>

				<?php do_action( 'bp_directory_forums_extra_cell' ); ?>

			</tr>

			<?php do_action( 'bp_directory_forums_extra_row' ); ?>

			<?php endwhile; ?>

		</tbody>
	</table>

	<?php do_action( 'bp_after_directory_forums_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="topic-count-bottom">
			<?php bp_forum_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="topic-pag-bottom">
			<?php bp_forum_pagination(); ?>
		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there were no forum topics found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_forums_loop' ); ?>
