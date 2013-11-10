<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header><?php get_template_part('templates/page', 'header'); ?></header>
		<?php $queried_post_type = get_query_var('post_type'); ?>
    <div class="entry-content">
	    <div class="row">
	    	<?php if( get_field('show_image_column') == 1 ) { ?>
			    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			    	 <?php get_template_part('templates/content', 'images'); ?>
			    </div>
			    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 entry-content">
			      <?php the_content(); ?>
			    </div>
		    <?php } else { ?>
		    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 entry-content">
		      <?php if ( 'post' ==  $queried_post_type ){ ?>
		        <?php get_template_part('templates/content', 'calendar-icon'); ?>
		      <?php } ?>
		      <?php if ( has_post_thumbnail() && 'post' ==  $queried_post_type ){ ?>
		         <div class="pull-left wrap-featured-image">
		         	<?php the_post_thumbnail('thumbnail', array('class' => 'img-thumbnail')); ?>
		         </div>
		      <?php } ?>
		      <?php the_content(); ?>
		    	</div>
	     <?php } ?>
	    </div>
    </div>
    <footer><?php get_template_part('templates/page', 'footer'); ?></footer>
  </article>
<?php endwhile; ?>
