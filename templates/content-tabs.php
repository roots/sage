<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header><?php get_template_part('templates/page', 'header'); ?></header>
    <div class="entry-content">
      <?php get_template_part('templates/content', 'tabbable'); ?>
    </div>
    <footer><?php get_template_part('templates/page', 'footer'); ?></footer>
  </article>
<?php endwhile; ?>