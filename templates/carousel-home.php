<?php $my_query = new WP_Query( 'post_type=sequence' );?>

<div class="carousel-wrap">
  <div id="homeCarousel" class="carousel slide">
  <?php $i = 0 ?>
  <div class="carousel-inner">
  <?php if ( $my_query->have_posts() ) { 
       while ( $my_query->have_posts() ) { 
           $my_query->the_post(); ?>
          <div class="item <?php if( $i == 0 ) { echo 'active'; } ?>">
        		<div class="background-image" style="background-image: url(<?php the_field('background_image'); ?>); background-position: <?php the_field('background-position'); ?>; background-color: <?php the_field('background_color'); ?>;">
        		  <h1 style="color: #fff;"><?php the_field('headline'); ?></h1>
        		  <p class="lead" style="color: #fff;"><?php the_field('lead'); ?></p>
        		  <a href="<?php the_field('call_to_action_link'); ?>"><?php the_field('call_to_action'); ?></a>
            </div><!-- /.background-image -->
          </div><!-- /.item -->
          <?php $i++ ?>
      	<?php } ?>
    <?php } ?>
  </div><!-- /.carousel-inner -->

  <?php wp_reset_postdata(); ?>
    <div class="carousel-control-wrapper">
      <a class="carousel-control left" href="#homeCarousel" data-slide="prev">&lsaquo;</a>
      <a class="carousel-control right" href="#homeCarousel" data-slide="next">&rsaquo;</a>
    </div>
    <div class="carousel-indicators-wrap">
      <ol class="carousel-indicators">
      <?php $my_query = new WP_Query( 'post_type=sequence' );?>
      <?php $i = 0 ?>
      <?php if ( $my_query->have_posts() ) { 
         while ( $my_query->have_posts() ) { 
             $my_query->the_post(); ?>
            <li data-target="#homeCarousel" data-slide-to="<?php echo $i ;?>" class="<?php if( $i == 0 ) { echo 'active'; } ?>"></li>
          <?php $i++ ?>
          <?php } ?>
        <?php } ?>
      </ol>
    </div>
  </div><!-- /.carousel -->
</div><!-- /.carousel-wrap -->
<?php wp_reset_postdata(); ?>