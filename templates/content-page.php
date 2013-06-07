<?php while (have_posts()) : the_post(); ?>
  <?php do_action( 'shoestrap_before_the_content' ); ?>
  <?php the_content(); ?>
  <?php do_action( 'shoestrap_after_the_content' ); ?>
  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
  <div class="clearfix"></div>
<?php endwhile; ?>