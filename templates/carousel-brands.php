<?php
$posttype = 'brand';
$number_of_posts = -1;
$startframe = 0;
$exclude = array('-4988','-4952','-4950','-4948','-4946','-4944','-4931','-4929');
$args = array(
  'post_type'     => $posttype,
  'post__not_in'  => $exclude,
  'posts_per_page' => $number_of_posts,
  'orderby' => 'rand',
);
$the_query = new WP_Query( $args );
$classes = 'item';
?>
<div class="container">
  <div id="carousel-brands" class="carousel slide" data-interval="">
    <div class="carousel-inner">
    <?php $i = 0 ?>
      <div class="item active">
        <div class="row">
        <ul>
          <?php while ($the_query->have_posts()) : $the_query->the_post();?>
          <?php if( $i == 4 ) : ?>
        </ul>
        </div>
      </div><!-- /.item -->
      <?php $i = 0 ?>
      <div class="item">
      <div class="row">
        <ul>
          <?php endif; ?>
          <?php
            $classes = array(
              'col-xs-12 col-sm-3 col-md-3 col-lg-3'
            );
          ?>
          <li <?php post_class($classes);?>><div class="brand-logo-wrap"><a title="<?php the_title();?>" href="<?php the_field('brand_website');?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail('full', array('class' => 'img-responsive')); } ?></a></div></li>

          <?php $i++ ?>
          <?php endwhile; ?>
        </ul>
      </div>
      </div>
    </div>

  </div>
    <div class="carousel-control-wrapper">
      <a class="carousel-control left" href="#carousel-brands" data-slide="prev"><span class=""></span></a>
      <a class="carousel-control right" href="#carousel-brands" data-slide="next"><span class=""></span></a>
    </div>
</div>
<?php wp_reset_query();?>