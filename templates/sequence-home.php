<?php
$posttype = 'sequence';
$number_of_posts = 3;
$startframe = 0;
$args = 'post_type=' . $posttype . '&showposts=' . $number_of_posts;
$the_query = new WP_Query( $args );
$secondary_color = get_field('secondary_color', 'options');
$classes = '';
?>
<div class="sequence-preloader">
  <svg class="preloading" xmlns="http://www.w3.org/2000/svg">
    <circle class="circle" cx="6" cy="6" r="6"></circle>
    <circle class="circle" cx="22" cy="6" r="6"></circle>
    <circle class="circle" cx="38" cy="6" r="6"></circle>
  </svg>
</div>
<div id="sequence-theme">
  <div id="sequence" class="sequence">
    <?php //  <a class="control next">&lsaquo;</a> ?>
    <?php //  <a class="control prev">&rsaquo;</a> ?>
    <?php $i = 0 ?>
    <ul style="background-color: <?php echo $secondary_color ?>;">
      <?php while ($the_query->have_posts()) : $the_query->the_post();?>
          <?php $backgroundImage = get_field('background_image'); ?>
					<li <?php if( $i == $startframe ) : $classes = array('animate-in','first-sequence'); elseif ( $i == 1 ) : $classes = array('second-sequence',); elseif ( $i == 2 ) : $classes = array('third-sequence',); endif; post_class($classes)?>>
						<div class="info" style="background-image: url(<?php echo $backgroundImage ?>); background-position: <?php the_field('background_position') ?>; background-color: <?php the_field('background_color') ?>;">
						  <div class="sequence-upper-image"></div>
  						    <div class="sequence-content">
              		  <h1 style="color: <?php the_field('headline_color'); ?>"><?php the_field('headline'); ?></h1>
              		  <p class="lead" style="color: <?php the_field('lead_color'); ?>"><?php the_field('lead'); ?></p>
              <?php if(get_field('internal_or_external_link') == "external") { ?>
              		  <a class="btn btn-sequence" href="<?php the_field('call_to_action_link'); ?>" target="_blank"><i class="icon-film"></i><?php the_field('call_to_action'); ?></a>
              <?php } ?>
              <?php if(get_field('internal_or_external_link') == "internal") { ?>
              		  <a class="btn btn-sequence" href="<?php the_field('call_to_action_link'); ?>"><i class="icon-circle-arrow-right"></i><?php the_field('call_to_action'); ?></a>
              <?php } ?>
  						    </div>
					 </div>
					</li>
          <?php $i++ ?>
      <?php endwhile; ?>
    </ul>
  </div><!-- /#sequence -->
</div><!-- /#sequence-theme -->
<div class="sequence-bar-bottom"></div>
<?php wp_reset_query();?>