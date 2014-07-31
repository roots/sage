<?php
  if (post_password_required()) {
    return;
  }
?>

<section id="comments">
  <?php if (have_comments()) : ?>
    <h3><?php printf(_n('One Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'roots'), number_format_i18n(get_comments_number()), get_the_title()); ?></h3>

    <ul class="no-bullet">
      <?php wp_list_comments(array('walker' => new Roots_Walker_Comment)); ?>
    </ul>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
      <nav>
        <ul class="pager">
          <?php if (get_previous_comments_link()) : ?>
            <li class="previous"><?php previous_comments_link(__('&larr; Older comments', 'roots')); ?></li>
          <?php endif; ?>
          <?php if (get_next_comments_link()) : ?>
            <li class="next"><?php next_comments_link(__('Newer comments &rarr;', 'roots')); ?></li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <?php if (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
      <div class="alert-box warning">
        <?php _e('Comments are closed.', 'roots'); ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section><!-- /#comments -->

<section id="respond">
  <?php if (comments_open()) : ?>
    <h3><?php comment_form_title(__('Leave a Reply', 'roots'), __('Leave a Reply to %s', 'roots')); ?></h3>
    <p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
    <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
      <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'roots'), wp_login_url(get_permalink())); ?></p>
    <?php else : ?>
      <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" data-abide>
        <?php if (is_user_logged_in()) : ?>
          <h5 class="subheader">
            <?php printf(__('Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'roots'), get_option('siteurl'), $user_identity); ?>
            <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'roots'); ?>"><?php _e('Log out &raquo;', 'roots'); ?></a>
          </h5>
        <?php else : ?>
          <div class="row">
            <div class="medium-6 columns">
              <label for="author"><?php _e('Name', 'roots'); if ($req) _e(' <small>required</small>', 'roots'); ?></label>
              <input placeholder="<?php _e('John Smith', 'roots');?>" type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" <?php if ($req) echo 'aria-required="true" required pattern="[a-åA-Å][a-åA-Å ]+"'; ?>>
              <?php if ($req) echo '<small class="error">Name is required, and can only contain characters.</small>'; ?>
            </div>
            <div class="medium-6 columns">
              <label for="email"><?php _e('Email', 'roots'); if ($req) _e(' <small>required</small>', 'roots'); ?></label>
              <input placeholder="<?php _e('john@smith.com', 'roots');?>" type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" <?php if ($req) echo 'aria-required="true" required'; ?>>
              <?php if ($req) echo '<small class="error">An email address is required.</small>'; ?>
            </div>
          </div>
          <div class="row collapse">
            <label for="url"><?php _e('Website', 'roots'); ?></label>
            <input placeholder="<?php _e('http://johnsmith.com', 'roots');?>"  type="url" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22">
            <small class="error">Not a valid URL. (http://example.com)</small>
          </div>
        <?php endif; ?>
        <div class="row collapse">
          <label for="comment"><?php _e('Comment', 'roots'); ?></label>
          <textarea placeholder="<?php _e('Say, say say...', 'roots'); ?>" name="comment" id="comment" rows="5" aria-required="true"></textarea>
        </div>
        <input name="submit" class="button small" type="submit" id="submit" value="<?php _e('Submit Comment', 'roots'); ?>">
        <?php comment_id_fields(); ?>
        <?php do_action('comment_form', $post->ID); ?>
      </form>
    <?php endif; ?>
  <?php endif; ?>
</section><!-- /#respond -->
