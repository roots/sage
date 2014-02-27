<?php global $ss_framework; ?>
<div class="comment-heading">
	<span class="right"><?php ss_foundation_edit_comment_link( '<i class="el-icon-pencil"></i>', '', '' ); ?></span>
	<?php echo get_avatar( $comment, $size = '64'); ?>
	<h5 class="comment-heading"><?php echo get_comment_author_link(); ?></h5>
	<time datetime="<?php echo comment_date( 'c' ); ?>">
		<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s', 'shoestrap' ), get_comment_date(),  get_comment_time() ); ?></a>
	</time>
</div>
<?php echo $ss_framework->clearfix(); ?>
<div class="comment-body">

	<?php if ($comment->comment_approved == '0') : ?>
		<?php echo $ss_framework->alert( 'info', __( 'Your comment is awaiting moderation.', 'shoestrap' ) ); ?>
	<?php endif; ?>

	<?php comment_text(); ?>
	<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
