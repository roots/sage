<?php while (have_posts()) : the_post(); ?>
  <?php roots_entry_before(); ?>
  <?php roots_entry_top(); ?>
  <?php the_content(); ?>
  <?php roots_entry_bottom(); ?>
  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
  <?php roots_entry_after(); ?>
<?php endwhile; ?>
