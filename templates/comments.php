<?php

global $ss_framework;

	if (post_password_required()) {
		return;
	}

 if (have_comments()) : ?>
	<section id="comments">
		<h3><?php printf(_n('One Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'shoestrap'), number_format_i18n(get_comments_number()), get_the_title()); ?></h3>

		<ol class="media-list">
			<?php wp_list_comments(array('walker' => new Shoestrap_Walker_Comment)); ?>
		</ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
		<nav>
			<ul class="pager">
				<?php if (get_previous_comments_link()) : ?>
					<li class="previous"><?php previous_comments_link(__('&larr; Older comments', 'shoestrap')); ?></li>
				<?php endif; ?>
				<?php if (get_next_comments_link()) : ?>
					<li class="next"><?php next_comments_link(__('Newer comments &rarr;', 'shoestrap')); ?></li>
				<?php endif; ?>
			</ul>
		</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() && ! is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
			<?php echo $ss_framework->alert( 'warning', __( 'Comments are closed.', 'shoestrap' ) ); ?>
		<?php endif; ?>
	</section><!-- /#comments -->
<?php endif; ?>

<?php if ( ! have_comments() && ! comments_open() && ! is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
	<section id="comments">
		<?php echo $ss_framework->alert( 'warning', __( 'Comments are closed.', 'shoestrap' ) ); ?>
	</section><!-- /#comments -->
<?php endif; ?>

<?php if (comments_open()) : ?>
	<section id="respond">
		<h3><?php comment_form_title(__('Leave a Reply', 'shoestrap'), __('Leave a Reply to %s', 'shoestrap')); ?></h3>
		<p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
		<?php if (get_option('comment_registration') && ! is_user_logged_in()) : ?>
			<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'shoestrap'), wp_login_url(get_permalink())); ?></p>
		<?php else : ?>
			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
				<?php if (is_user_logged_in()) : ?>
					<p>
						<?php printf(__('Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'shoestrap'), get_option('siteurl'), $user_identity); ?>
						<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php __('Log out of this account', 'shoestrap'); ?>"><?php _e('Log out &raquo;', 'shoestrap'); ?></a>
					</p>
				<?php else : ?>
					<div class="form-group">
						<label for="author"><?php _e('Name', 'shoestrap'); if ($req) _e(' (required)', 'shoestrap'); ?></label>
						<input type="text" class="form-control" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" <?php if ($req) echo 'aria-required="true"'; ?>>
					</div>
					<div class="form-group">
						<label for="email"><?php _e('Email (will not be published)', 'shoestrap'); if ($req) _e(' (required)', 'shoestrap'); ?></label>
						<input type="email" class="form-control" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" <?php if ($req) echo 'aria-required="true"'; ?>>
					</div>
					<div class="form-group">
						<label for="url"><?php _e('Website', 'shoestrap'); ?></label>
						<input type="url" class="form-control" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22">
					</div>
				<?php endif; ?>
				<div class="form-group">
					<label for="comment"><?php _e('Comment', 'shoestrap'); ?></label>
					<textarea name="comment" id="comment" class="form-control" rows="5" aria-required="true"></textarea>
				</div>
				<p><input name="submit" class="btn btn-primary" type="submit" id="submit" value="<?php _e('Submit Comment', 'shoestrap'); ?>"></p>
				<?php comment_id_fields(); ?>
				<?php do_action('comment_form', $post->ID); ?>
			</form>
		<?php endif; ?>
	</section><!-- /#respond -->
<?php endif;
