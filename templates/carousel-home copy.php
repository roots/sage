<div class="carousel-wrap">
  <div id="homeCarousel" class="carousel slide">
  <?php $i = 0 ?>
  <?php if( get_field('carousel_item') ) { ?>
  <div class="carousel-inner">
    <?php while( has_sub_field('carousel_item') ) { ?>
    <div class="item <?php if( $i == 0 ) { echo 'active'; } ?>">
  		<div class="background-image" style="background-image: url(<?php the_sub_field('background_image'); ?>); background-position: <?php the_sub_field('backghround-position'); ?>;">
  		  <h1><?php the_sub_field('headline'); ?></h1>
  		  <p class="lead"><?php the_sub_field('lead'); ?></p>
  		  <a href="<?php the_sub_field('call_to_action_link'); ?>"><?php the_sub_field('call_to_action'); ?></a>
      </div><!-- /.background-image -->
    </div><!-- /.item -->
    <?php $i++ ?>
	<?php } ?>
  </div><!-- /.carousel-inner -->
<?php } ?>

    <div class="carousel-control-wrapper">
      <a class="carousel-control left" href="#homeCarousel" data-slide="prev">&lsaquo;</a>
      <a class="carousel-control right" href="#homeCarousel" data-slide="next">&rsaquo;</a>
    </div>
    <ol class="carousel-indicators">
      <?php $i = 0 ?>
      <?php if( get_field('carousel_item') ) { ?>
        <?php while( has_sub_field('carousel_item') ) { ?>
          <li data-target="#homeCarousel" data-slide-to="<?php echo $i ;?>" class="<?php if( $i == 0 ) : echo 'active'; ?>"></li>
        <?php $i++ ?>
        <?php } ?>
      <?php } ?>
    </ol>
  </div><!-- /.carousel -->
</div><!-- /.carousel-wrap -->