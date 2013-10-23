<?php
$posttype = 'product';
$number_of_posts = 18;
$startframe = 0;
$args = array(
  'post_type'     => $posttype,
  'posts_per_page' => $number_of_posts,
);
$the_query = new WP_Query( $args );
$classes = 'item';
?>
<div class="container">
  <div id="carousel-products" class="carousel slide">
    <div class="carousel-inner">
    <?php $i = 0 ?>
      <div class="item active">
        <div class="row">
        <ul>
          <?php while ($the_query->have_posts()) : $the_query->the_post();?>
          <?php if( $i == 6 ) : ?>
        </ul>
        </div>
      </div><!-- /.item -->
      <?php $i = 0 ?>
      <div class="item">
      <div class="row">
      	<div class="fade-block left"></div>
        <ul>
          <?php endif; ?>
          <?php
            $classes = array(
              'col-xs-12 col-sm-6 col-md-3 col-lg-2'
            );
          ?>
          <li <?php post_class($classes);?>><div class="product-image-wrap"><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail('full', array('class' => 'img-responsive')); } ?></a></div></li>
          <?php $i++ ?>
          <?php endwhile; ?>
        </ul>
        <div class="fade-block right"></div>
      </div>
      </div>
    </div>

  </div>
    <div class="carousel-control-wrapper">
      <a class="carousel-control left" href="#carousel-products" data-slide="prev"><span class=""></span></a>
      <a class="carousel-control right" href="#carousel-products" data-slide="next"><span class=""></span></a>
    </div>
</div>
<?php wp_reset_query();?>