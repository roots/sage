<?php while (have_posts()) : the_post(); ?>
	<article <?php post_class(); ?>>
		<?php do_action( 'shoestrap_single_top' ); ?>
		<?php shoestrap_title_section(); ?>
		<?php do_action( 'shoestrap_entry_meta' ); ?>
		<div class="entry-content">
			<?php do_action( 'shoestrap_single_pre_content' ); ?>
			<?php the_content(); ?>
			<?php echo shoestrap_clearfix(); ?>
			<?php do_action( 'shoestrap_single_after_content' ); ?>
		</div>
		<footer>
			<?php shoestrap_meta( 'cats' ); ?>
			<?php shoestrap_meta( 'tags' ); ?>
			<?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'shoestrap'), 'after' => '</p></nav>')); ?>
		</footer>
		<?php
		// The comments section loaded when appropriate
		if ( post_type_supports( 'post', 'comments' ) ):
			do_action( 'shoestrap_pre_comments' );
			if ( !has_action( 'shoestrap_comments_override' ) )
				comments_template('/templates/comments.php');
			else
				do_action( 'shoestrap_comments_override' );
			do_action( 'shoestrap_after_comments' );
		endif;
		?>
		<?php do_action( 'shoestrap_in_article_bottom' ); ?>
	</article>
<?php endwhile;