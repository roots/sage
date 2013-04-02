<?php
$posttype = 'brand';
$brands_per_slide = 5;
$number_of_posts = 15;
$startframe = 0;
$exclude = array('-4988','-4952','-4950','-4948','-4946','-4944','-4931','-4929');
$args = array(
  'post_type'     => $posttype,
  'post__not_in'  => $exclude,
  'posts_per_page' => $number_of_posts
);
$the_query = new WP_Query( $args );
$classes = 'item';
?>
<div class="container">
  <div id="brandsCarousel" class="carousel slide">
    <div class="carousel-inner">
    <?php $i = 0 ?>
      <div class="item active">
        <ul>
          <?php while ($the_query->have_posts()) : $the_query->the_post();?>
          <?php if( $i == 5 ) : ?>
        </ul>
      </div><!-- /.item -->
      <?php $i = 0 ?>
      <div class="item">
        <ul>
          <?php endif; ?>
          <li <?php post_class();?>><a class="brandpopover" data-toggle="popover" data-html="true" data-trigger="hover" data-placement="top" data-content="<?php the_field('brand_description');?>" title="<?php the_title();?>" href="<?php the_field('brand_website');?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail('full', array('class' => '')); } ?></a></li>
          <?php $i++ ?>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
    <div class="carousel-control-wrapper">
      <a class="carousel-control left" href="#brandsCarousel" data-slide="prev">&lsaquo;</a>
      <a class="carousel-control right" href="#brandsCarousel" data-slide="next">&rsaquo;</a>
    </div>
  </div>
</div>
<?php wp_reset_query();?>