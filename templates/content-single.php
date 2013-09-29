<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header><?php get_template_part('templates/page', 'header'); ?></header>
    <div class="entry-content">
      <?php $queried_post_type = get_query_var('post_type'); ?>
      <?php if ( has_post_thumbnail() && 'post' ==  $queried_post_type ){ ?>
           <div class="pull-left wrap-featured-image"><?php the_post_thumbnail('thumbnail', array('class' => 'img-thumbnail')); ?></div>
      <?php } ?>
      <?php the_content(); ?>
      <?php get_template_part('templates/content', 'tabbable'); ?>
      
    </div>
    <footer><?php get_template_part('templates/page', 'footer'); ?></footer>
  </article>
<?php endwhile; ?>
