<?php function roots_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>">
			<header class="comment-author vcard">
				<?php echo get_avatar($comment,$size='32'); ?>
				<?php printf(__('<cite class="fn">%s</cite>', 'roots'), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_date('c') ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s', 'roots'), get_comment_date(),  get_comment_time()) ?></a></time>
				<?php edit_comment_link(__('(Edit)', 'roots'), '', '') ?>
			</header>

			<?php if ($comment->comment_approved == '0') : ?>
       			<div class="notice">
					<p class="bottom"><?php _e('Your comment is awaiting moderation.', 'roots') ?></p>
          		</div>
          		
			<?php endif; ?>
			
			<section class="comment">
				<?php comment_text() ?>
			</section>

			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			
		</article>
<?php } ?>

<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!', 'roots'));

	if ( post_password_required() ) { ?>
	<section id="comments">
		<div class="notice">
			<p class="bottom"><?php _e('This post is password protected. Enter the password to view comments.', 'roots'); ?></p>
		</div>
	</section>
	<?php
		return;
	}
?>
<?php // You can start editing here. ?>
<?php if ( have_comments() ) : ?>
	<section id="comments">
		<h3><?php comments_number(__('No Responses to', 'roots'), __('One Response to', 'roots'), __('% Responses to', 'roots') ); ?> &#8220;<?php the_title(); ?>&#8221;</h3>
		<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=roots_comments'); ?>
		<?php // wp_list_comments(); ?>
		</ol>
		<footer>
			<nav id="comments-nav">
				<div class="comments-previous"><?php previous_comments_link( __( '&larr; Older comments', 'roots' ) ); ?></div>
				<div class="comments-next"><?php next_comments_link( __( 'Newer comments &rarr;', 'roots' ) ); ?></div>
			</nav>
		</footer>
	</section>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( comments_open() ) : ?>
	<?php else : // comments are closed ?>
	<section id="comments">
		<div class="notice">
			<p class="bottom"><?php _e('Comments are closed.', 'roots') ?></p>
		</div>
	</section>
	<?php endif; ?>
<?php endif; ?>
<?php if ( comments_open() ) : ?>
<section id="respond">
	<h3><?php comment_form_title( __('Leave a Reply', 'roots'), __('Leave a Reply to %s', 'roots') ); ?></h3>
	<p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
	<p><?php printf( __('You must be <a href="%s">logged in</a> to post a comment.', 'roots'), wp_login_url( get_permalink() ) ); ?></p>
	<?php else : ?>
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		<?php if ( is_user_logged_in() ) : ?>
		<p><?php printf(__('Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'roots'), get_option('siteurl'), $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php __('Log out of this account', 'roots'); ?>"><?php _e('Log out &raquo;', 'roots'); ?></a></p>
		<?php else : ?>
		<p>
			<label for="author"><?php _e('Name', 'roots'); if ($req) _e(' (required)', 'roots'); ?></label>
			<input type="text" class="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?>>
		</p>
		<p>
			<label for="email"><?php _e('Email (will not be published)', 'roots'); if ($req) _e(' (required)', 'roots'); ?></label>
			<input type="email" class="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?>>
		</p>
		<p>
			<label for="url"><?php _e('Website', 'roots'); ?></label>
			<input type="url" class="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3">
		</p>
		<?php endif; ?>
		<p>
			<label for="comment"><?php _e('Comment', 'roots'); ?></label>
			<textarea name="comment" id="comment" tabindex="4"></textarea>
		</p>
		<p><input name="submit" class="button" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment', 'roots'); ?>"></p>
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>
	</form>
	<?php endif; // If registration required and not logged in ?>
</section>
<?php endif; // if you delete this the sky will fall on your head ?>