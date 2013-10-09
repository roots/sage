<?php
$posttype = 'carousel';
$number_of_posts = 3;
$startframe = 0;
$args = 'post_type=' . $posttype . '&showposts=' . $number_of_posts;
$the_query = new WP_Query( $args );
$classes = '';?>
<div id="carousel-home" class="carousel slide carousel-fade" data-interval="8000">
  <?php $i = 0 ?>
  <div class="carousel-inner">
      <?php while ($the_query->have_posts()) : $the_query->the_post();?>
					<div <?php if( $i == $startframe ) : $classes = array('item','active', 'first'); elseif ( $i == 1 ) : $classes = array('item','second'); elseif ( $i == 2 ) : $classes = array('item','third'); elseif ( $i == 3 ) : $classes = array('item','forth',); endif; post_class($classes)?>>
					   <?php if ( has_post_thumbnail() ) { the_post_thumbnail('full', array('class' => 'background-image-carousel')); } ?>
						  <div class="carousel-caption<?php if(get_field('headline_type') == "plain") { ?> plain<?php } ?><?php if(get_field('headline_type') == "background") { ?> background<?php } ?><?php if(get_field('headline_type') == "block") { ?> block<?php } ?>">
                  <h1><?php the_title(); ?></h1>
                  <?php the_content(); ?>	    
                  <?php // get_template_part('templates/content', 'call-to-action'); ?>
              </div>
          </div> 
          <!-- Controls -->
      <?php $i++ ?>
      <?php endwhile; ?>
  </div>
  <a class="carousel-control left" href="#carousel-home" data-slide="prev"><span class="glyphicons-icon white chevron-left"></span></a>
  <a class="carousel-control right" href="#carousel-home" data-slide="next"><span class="glyphicons-icon white chevron-right"></span></a>
    <ol class="carousel-indicators">
      <li data-target="#carousel-home" data-slide-to="0" class="active"></li>
      <li data-target="#carousel-home" data-slide-to="1"></li>
      <li data-target="#carousel-home" data-slide-to="2"></li>
    </ol>
</div>
<div class="bottom-bar"></div>
<?php wp_reset_query();?>