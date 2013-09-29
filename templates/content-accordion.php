<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header><?php get_template_part('templates/page', 'header'); ?></header>
    <div class="entry-content">
      <?php get_template_part('templates/content', 'panel-collapse'); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php get_template_part('templates/content', 'related'); ?>
    </footer>
  </article>
<?php endwhile; ?>

