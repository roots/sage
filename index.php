<?php get_template_part('templates/page', 'header'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>

  <?php if(get_post_type() != 'post') : ?>
    <?php if(get_post_format()) get_template_part('templates/content', trailingslashit(get_post_type()) . get_post_format()); ?>
    <?php else get_template_part('templates/content', get_post_type()); ?>
  <?php else : ?>
    <?php get_template_part('templates/content', get_post_format()); ?>
  <?php endif; ?>

<?php endwhile; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>
