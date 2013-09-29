      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php get_template_part('templates/content', 'related'); ?>
      <?php get_template_part('templates/content', 'legal'); ?>
      <?php edit_post_link('edit', '<p>', '</p>'); ?>