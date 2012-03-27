<?php function roots_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment; ?>
  <li <?php comment_class(); ?>>
    <article id="comment-<?php comment_ID(); ?>">
      <header class="comment-author vcard">
        <?php echo get_avatar($comment, $size = '32'); ?>
        <?php printf(__('<cite class="fn">%s</cite>', 'roots'), get_comment_author_link()); ?>
        <time datetime="<?php echo comment_date('c'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php printf(__('%1$s', 'roots'), get_comment_date(),  get_comment_time()); ?></a></time>
        <?php edit_comment_link(__('(Edit)', 'roots'), '', ''); ?>
      </header>

      <?php if ($comment->comment_approved == '0') { ?>
        <div class="alert alert-block fade in">
          <a class="close" data-dismiss="alert">&times;</a>
          <p><?php _e('Your comment is awaiting moderation.', 'roots'); ?></p>
        </div>
      <?php } ?>

      <section class="comment">
        <?php comment_text() ?>
      </section>

      <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>

    </article>
<?php } ?>

<?php if (post_password_required()) { ?>
  <section id="comments">
    <div class="alert alert-block fade in">
      <a class="close" data-dismiss="alert">&times;</a>
      <p><?php _e('This post is password protected. Enter the password to view comments.', 'roots'); ?></p>
    </div>
  </section><!-- /#comments -->
<?php
  return;
} ?>

<?php if (have_comments()) { ?>
  <section id="comments">
    <h3><?php printf(_n('One Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'roots'), number_format_i18n(get_comments_number()), get_the_title()); ?></h3>

    <ol class="commentlist">
      <?php wp_list_comments(array('callback' => 'roots_comment')); ?>
    </ol>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) { // are there comments to navigate through ?>
      <nav id="comments-nav" class="pager">
        <div class="previous"><?php previous_comments_link(__('&larr; Older comments', 'roots')); ?></div>
        <div class="next"><?php next_comments_link(__('Newer comments &rarr;', 'roots')); ?></div>
      </nav>

    <?php } // check for comment navigation ?>

    <?php if (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) { ?>
      <div class="alert alert-block fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <p><?php _e('Comments are closed.', 'roots'); ?></p>
      </div>
    <?php } ?>
  </section><!-- /#comments -->
<?php } ?>

<?php if (!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) { ?>
  <section id="comments">
    <div class="alert alert-block fade in">
      <a class="close" data-dismiss="alert">&times;</a>
      <p><?php _e('Comments are closed.', 'roots'); ?></p>
    </div>
  </section><!-- /#comments -->
<?php } ?>

<?php if (comments_open()) { ?>
  <section id="respond">
    <?php if (get_option('comment_registration') && !is_user_logged_in()) { ?>
      <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'roots'), wp_login_url(get_permalink())); ?></p>
    <?php } else { ?>
    <?php $roots_comment_args = array(
		'label_submit'         => __( 'Submit Comment', 'roots' ),
		'comment_notes_after'  => '' ); 
	comment_form( $roots_comment_args ); ?>
    <?php } // if registration required and not logged in ?>
  </section><!-- /#respond -->
<?php } ?>