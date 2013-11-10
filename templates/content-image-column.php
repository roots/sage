<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header><?php get_template_part('templates/page', 'header'); ?></header>
    <?php $queried_post_type = get_query_var('post_type'); ?>
    <div class="row">
    	<?php if( get_field('show_image_column') == 1 ) { ?>
		    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		    	 <?php get_template_part('templates/content', 'images'); ?>
		    </div>
		    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 entry-content">
		      <?php the_content(); ?>
		      <?php get_template_part('templates/content', 'tabbable'); ?>
		    </div>
	    <?php } ?>
    </div>
    <footer><?php get_template_part('templates/page', 'footer'); ?></footer>
  </article>
<?php endwhile; ?>
