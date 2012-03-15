<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
  <?php roots_post_before(); ?>
    <?php roots_post_inside_before(); ?>
      <div class="page-header">
      	<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
      </div>
      <?php the_content(); ?>
      <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
    <?php roots_post_inside_after(); ?>
  <?php roots_post_after(); ?>
<?php endwhile; /* End loop */ ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ($wp_query->max_num_pages > 1) { ?>
  <?php if(function_exists('wp_pagenavi')){ wp_pagenavi();}else{ ?>
  		<nav id="post-nav" class="pager">
    		<div class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></div>
    		<div class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></div>
  		</nav>
	<?php } ?>
<?php } ?>