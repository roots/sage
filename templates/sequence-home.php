<?php $my_query = new WP_Query( 'post_type=sequence' );?>

<div id="sequence-theme">
  <div id="sequence" class="sequence">
    <?php $i = 0 ?>
    <ul>
    <div class="controls-wrapper">
      <a class="prev">&lsaquo;</a>
      <a class="next">&rsaquo;</a>
    </div>
    <?php if ( $my_query->have_posts() ) { 
       while ( $my_query->have_posts() ) { 
               $my_query->the_post(); ?>
					<li>
						<div class="info background-image" style="background-image: url(<?php the_field('background_image'); ?>); background-position: <?php the_field('background-position'); ?>; background-color: <?php the_field('background_color'); ?>;">
						<div class="container">
        		  <h1><?php the_field('headline'); ?></h1>
        		  <p class="lead"><?php the_field('lead'); ?></p>
        		  <a href="<?php the_field('call_to_action_link'); ?>"><?php the_field('call_to_action'); ?></a>
						</div>
					 </div>
					</li>
          <?php $i++ ?>
      	<?php } ?>
    <?php } ?>
    </ul>
  </div><!-- /#sequence -->
</div><!-- /#sequence-theme -->