<?php

if ( !has_action( 'shoestrap_page_header_override' )
  get_template_part('templates/page', 'header');
else
  do_action( 'shoestrap_page_header_override' );
?>

<?php do_action( 'shoestrap_index_begin' ); ?>

<?php if (!have_posts()) : ?>
  <div class="alert">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php do_action( 'shoestrap_in_loop_start_action' ); ?>
  <?php
  if ( !has_action( 'shoestrap_article_content' ) )
    get_template_part('templates/content', get_post_format());
  else
    do_action( 'shoestrap_article_content' );
  ?>
<?php endwhile; ?>

<?php do_action( 'shoestrap_index_end' ); ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif;
