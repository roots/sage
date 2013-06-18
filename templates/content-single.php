
<?php
if ( !has_action( 'shoestrap_content_single_override' ) ) { 
  while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
      <header>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php get_template_part('templates/entry-meta'); ?>
      </header>
      <div class="entry-content">
        <?php do_action( 'shoestrap_before_the_content' ); ?>
        <?php the_content(); ?>
        <?php do_action( 'shoestrap_after_the_content' ); ?>
        <div class="clearfix"></div>
      </div>
      <footer>
        <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      </footer>
      <?php if ( post_type_supports( 'post', 'comments' ) ): ?>
      <?php comments_template('/templates/comments.php'); ?>
      <?php endif; ?>
    </article>
  <?php endwhile; 
} else { do_action( 'shoestrap_content_single_override' ); } ?>








